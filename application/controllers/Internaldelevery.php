<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Internaldelevery extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //validasi jika user belum login
        if ($this->session->userdata('masuk') != TRUE) {
            $url = base_url();
            redirect($url);
        }
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model('internaldelevery_model');
        $this->load->library('datatables');
        $this->load->helper('random');
    }

    public function internal_start()
    {
        $ymd = date('ymd');
        $tgl_now = date('Y-m-d');
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->internaldelevery_model->getNoFakturInternal($ymd);
        $data = array(
            'no_faktur_internal' => $nofaktur,
            'tgl_internal' => $tgl_now,
            'id_user' => $id_user,
        );
        $this->db->insert('tabel_internal', $data);
        redirect('internaldelevery/internal/' . $nofaktur, 'refresh');
    }
    // Internaldelevery/internal
    public function internal()
    {
        // $data['supplier'] = $this->m_master_model->getSupplier();
        $noresi = $this->uri->segment(3);
        $username = $this->session->userdata('ses_username');
        $data_faktur = $this->internaldelevery_model->getDataInternal($noresi, $username)->row();
        if ($data_faktur) {
            $data['tgl'] = date('d-M-Y');
            $data['faktur'] = $data_faktur;
            $data['supplier'] = $this->internaldelevery_model->getSupplier();
            $this->load->view('template/header', $data);
            $this->load->view('gudang/internal', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->view('error404');
        }
    }

    public function get_detail_produk()
    {
        $idbarang = $this->input->post('idbarang');
        $data = $this->internaldelevery_model->get_detail_produk($idbarang);
        echo json_encode($data);
    }

    function get_autocomplete()
	{
		if (isset($_GET['term'])) {
			$result = $this->internaldelevery_model->cari_nama($_GET['term']);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$arr_result[] = array(
						'label' => $row->nm_barang,
						'kode' => $row->kd_barang,
					);
				}
				echo json_encode($arr_result);
			}
		}
	}
    

    public function add_list_internal()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $nm_barang = $this->input->post('nm_barang');
        $jumlah = $this->input->post('jumlah');
        $harga_beli = $this->input->post('harga_beli');
        $satuan = $this->input->post('satuan');
        $subtotal = (int) $harga_beli * (int) $jumlah;

        $produk = $this->internaldelevery_model->getbarang($idbarang);

        if ($produk->num_rows() > 0) {
            $i = $produk->row_array();
            $input = array(
                'no_faktur_internal' => $nofaktur,
                'kd_barang' => $i['kd_barang'],
                'nm_barang' => $nm_barang,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'harga' => $harga_beli,
                'sub_total_beli' => $subtotal,
            );
            $data = $this->db->insert('tabel_rinci_internal', $input);
            echo json_encode($data);
        } else {
            echo "Produk tidak tersedia";
        }
    }

    public function data_list_internal()
    {
        $nofak = $this->uri->segment(3);
        $data = $this->internaldelevery_model->data_list_internal($nofak);
        echo json_encode($data);
    }

    public function hapus_item_beli()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $data = $this->db->query("DELETE FROM tabel_rinci_internal WHERE no_faktur_internal='$nofaktur' AND kd_barang='$idbarang'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_internal SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_internal='$nofaktur_e'");
        echo json_encode($data);
    }

    public function internal_selesai()
    {
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->input->post('faktur_beli');
        $total_internal = $this->input->post('tot_harga');
        $kd_supplier = $this->input->post('supplier_e');
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $ket = "Internal " . $nofaktur;
        $user = $this->session->userdata('ses_username');
        $publish = "1";
        $data_faktur = $this->internaldelevery_model->getInternalSelesai($nofaktur, $id_user)->row();
        $list_produk = $this->internaldelevery_model->getProdukDibeli($nofaktur)->result();

        if ($data_faktur && $list_produk) {
            foreach ($list_produk as $key) {
                $kd_barang_item = $key->kd_barang;
                $jumlah_item = $key->jumlah;
                $cek_stok = $this->internaldelevery_model->getStokBeli($kd_barang_item);
                // $cek_porsi = $this->internaldelevery_model->getPorsi($kd_barang_item);
                $i = $cek_stok->row_array();
                // $x = $cek_porsi->row_array();
                $stok_sekarang = $i['stok'];
                // $est_porsi = $x['estimasi_stok'];
                // $stok_porsi = (int) $jumlah_item * (int) $est_porsi;
                $stok_baru = (int) $stok_sekarang - (int) $jumlah_item;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,saldo,masuk,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$waktu','$jam','$stok_sekarang','$jumlah_item','$stok_baru','0','$ket','$user','$publish')");
            };
            $this->db->query("UPDATE tabel_internal SET total_internal='$total_internal', selesai='1', kd_supplier='$kd_supplier' WHERE no_faktur_internal='$nofaktur'");
            echo $this->session->set_flashdata('msg', 'internal Sukses');
            redirect('/m_stok/stok/', 'refresh');
        } else {
            echo $this->session->set_flashdata('error', 'internal Gagal');
            redirect('internaldelevery/internal/' . $nofaktur, 'refresh');
        }
    }

    public function menu()
    {
        $data['bahan_utama'] = $this->internaldelevery_model->getBahanUtama();
        $data['bahan_tambahan'] = $this->internaldelevery_model->getBahanTambahan();
        $data['menu'] = $this->internaldelevery_model->getDataMenu();
        $data['paket'] = $this->internaldelevery_model->getDetailMenu();
        $data['no'] = 1;
        $this->load->view('template/header', $data);
        $this->load->view('gudang/menu');
        $this->load->view('template/footer');
    }

    public function simpan_data_menu()
    {
        $kode_menu = $this->input->post('kode_menu', TRUE);
        $nama_menu = $this->input->post('nama_menu', TRUE);
        $bahan_utama = $this->input->post('bahan_utama', TRUE);
        $bahan_tambahan = $this->input->post('bahan_tambahan', TRUE);
        $harga_jual = str_replace(".", "", $this->input->post('harga_jual', TRUE));
        $cek_kode = $this->internaldelevery_model->cekKodeMenu($kode_menu);
        if ($cek_kode->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Kode ' . $kode_menu . ' sudah terdaftar, silahkan pakai kode lain');
            redirect('gudang/menu', 'refresh');
        } else {
            $this->internaldelevery_model->save_menu($kode_menu, $nama_menu, $bahan_utama, $bahan_tambahan, $harga_jual);
        }
        echo $this->session->set_flashdata('msg', 'Menu ' . $nama_menu . ' berhasil diinput');
        redirect('gudang/menu', 'refresh');
    }

    public function get_bahan_by_menu()
    {
        $kode_menu = $this->input->post('kode_menu');
        $data = $this->internaldelevery_model->get_bahan_by_menu($kode_menu)->result();
        foreach ($data as $result) {
            $value[] = $result->kd_barang;
        }
        echo json_encode($value);
    }

    public function simpan_edit_menu()
    {
        $kode_menu = $this->input->post('kode_menu_e', TRUE);
        $nama_menu = $this->input->post('nama_menu_e', TRUE);
        $bahan_utama = $this->input->post('bahan_utama_e', TRUE);
        $bahan_tambahan = $this->input->post('bahan_tambahan_e', TRUE);
        $harga_jual = str_replace(".", "", $this->input->post('harga_jual_e', TRUE));
        $this->internaldelevery_model->save_edit_menu($kode_menu, $nama_menu, $bahan_utama, $bahan_tambahan, $harga_jual);
        echo $this->session->set_flashdata('msg', 'Menu berhasil diedit');
        redirect('gudang/menu', 'refresh');
    }

    public function hapus_menu()
    {
        $kode_menu = $this->input->post('kode_menu_h', TRUE);
        $this->internaldelevery_model->delete_menu($kode_menu);
        echo $this->session->set_flashdata('msg', 'Menu berhasil dihapus');
        redirect('gudang/menu', 'refresh');
    }

    public function bahan_rusak()
    {
        $this->load->view('template/header');
        $this->load->view('gudang/bahan_rusak');
        $this->load->view('template/footer');
    }

    public function get_detail_bahan()
    {
        $kd_bahan = $this->input->post('kd_bahan');
        $data = $this->internaldelevery_model->get_detail_bahan($kd_bahan);
        echo json_encode($data);
    }

    public function simpan_bahan_rusak()
    {
        $kd_bahan = $this->input->post('kd_bahan');
        $jum_rusak = $this->input->post('rusak');
        $ket = $this->input->post('ket');
        $cek_stok = $this->internaldelevery_model->cekStok($kd_bahan);
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $user = $this->session->userdata('ses_username');
        if ($cek_stok->num_rows() > 0) {
            $q = $cek_stok->row_array();
            $stok_sekarang = $q['stok'];
            if ($jum_rusak > $stok_sekarang) {
                echo $this->session->set_flashdata('error', 'Input jumlah barang rusak melebihi jumlah stok');
                redirect('gudang/bahan-rusak', 'refresh');
            } else {
                $stok_sekarang = (int) $stok_sekarang - (int) $jum_rusak;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_sekarang' WHERE kd_barang='$kd_bahan'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,masuk,saldo,keterangan,user,publish) VALUES ('$kd_toko','$kd_bahan','$waktu','$jam','$stok_sekarang','$jum_rusak','0','$stok_sekarang','$ket','$user','0')");
                echo $this->session->set_flashdata('msg', 'Entry sukses');
                redirect('gudang/bahan-rusak', 'refresh');
            }
        } else {
            echo $this->session->set_flashdata('error', 'Kode ' . $kd_bahan . ' tidak terdaftar');
            redirect('gudang/bahan-rusak', 'refresh');
        }
    }

    public function cetak_data_faktur()
	{
		// $nofak = $this->input->post('no_faktur_pembelian');
		$no_faktur_internal = $this->uri->segment(3);
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		// $data['faktur'] = $nofak;
        $data['list'] = $this->internaldelevery_model->get_list($no_faktur_internal);
        $data['toko'] = $this->internaldelevery_model->get_toko();
		$data['no'] = 1;
		$data['tgl'] = $tgl;
		$data['waktu'] = $waktu; 
		$data['total_item'] = 0;
		$data['subtotal'] = 0;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->internaldelevery_model->detail_faktur($no_faktur_internal);
		$this->load->view('pembelian/inreprint_faktur', $data);
		
	
	}



}

/* End of file Gudang.php */
/* Location: ./application/controllers/Gudang.php */