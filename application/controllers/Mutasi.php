<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi extends CI_Controller
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
        $this->load->model('Mutasi_model');
        $this->load->library('datatables');
        $this->load->helper('random');
    }

    public function mutasi_masuk_start()
    {
        $ymd = date('ymd');
        $tgl_now = date('Y-m-d');
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->Mutasi_model->getNoFakturMutasiMasuk($ymd);
        $data = array(
            'no_faktur_mutasi' => $nofaktur,
            'tgl_mutasi' => $tgl_now,
            'id_user' => $id_user,
        );
        $this->db->insert('tabel_mutasi_masuk', $data);
        redirect('mutasi/mutasi_masuk/' . $nofaktur, 'refresh');
    }

    public function mutasi_masuk()
    {
        $noresi = $this->uri->segment(3);
        $username = $this->session->userdata('ses_username');
        $data_faktur = $this->Mutasi_model->getDataMutasi($noresi, $username)->row();
        if ($data_faktur) {
            $data['tgl'] = date('d-M-Y');
            $data['faktur'] = $data_faktur;
            $data['supplier'] = $this->Mutasi_model->getSupplier();
            $this->load->view('template/header', $data);
            $this->load->view('pembelian/mutasi_masuk', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->view('error404');
        }
    }

    public function get_detail_produk()
    {
        $idbarang = $this->input->post('idbarang');
        $data = $this->Mutasi_model->get_detail_produk($idbarang);
        echo json_encode($data);
    }

    public function add_list_mutasi()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $nm_barang = $this->input->post('nm_barang');
        $jumlah = $this->input->post('jumlah');
        $harga_beli = $this->input->post('harga_beli');
        $satuan = $this->input->post('satuan');
        $subtotal = (int) $harga_beli * (int) $jumlah;
        $tgl = date('Y-m-d');
        $produk = $this->Mutasi_model->getbarang($idbarang);

        if ($produk->num_rows() > 0) {
            $i = $produk->row_array();
            $input = array(
                'no_faktur_mutasi' => $nofaktur,
                'kd_barang' => $i['kd_barang'],
                'nm_barang' => $nm_barang,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'harga' => $harga_beli,
                'sub_total_beli' => $subtotal,
                'tgl_mutasi_masuk' => $tgl,
            );
            $data = $this->db->insert('tabel_rinci_mutasi_masuk', $input);
            echo json_encode($data);
        } else {
            echo "Produk tidak tersedia";
        }
    }

    public function data_list_mutasi()
    {
        $nofak = $this->uri->segment(3);
        $data = $this->Mutasi_model->data_list_mutasi($nofak);
        echo json_encode($data);
    }

    public function hapus_item_beli()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $data = $this->db->query("DELETE FROM tabel_rinci_mutasi_masuk WHERE no_faktur_mutasi='$nofaktur' AND kd_barang='$idbarang'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_mutasi_masuk SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_mutasi='$nofaktur_e'");
        echo json_encode($data);
    }

    public function mutasi_selesai()
    {
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->input->post('faktur_beli');
        $total_mutasi = $this->input->post('tot_harga');
        $kd_supplier = $this->input->post('supplier_e');
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $ket = "Mutasi Masuk " . $nofaktur;
        $user = $this->session->userdata('ses_username');
        $publish = "1";
        $data_faktur = $this->Mutasi_model->getMutasiSelesai($nofaktur, $id_user)->row();
        $list_produk = $this->Mutasi_model->getProdukDibeli($nofaktur)->result();

        if ($data_faktur && $list_produk) {
            foreach ($list_produk as $key) {
                $kd_barang_item = $key->kd_barang;
                $jumlah_item = $key->jumlah;
                $cek_stok = $this->Mutasi_model->getStokBeli($kd_barang_item);
                $i = $cek_stok->row_array();
                $stok_sekarang = $i['stok'];
                $stok_baru = (int) $stok_sekarang + (int) $jumlah_item;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,saldo,masuk,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$waktu','$jam','$stok_sekarang','0','$stok_baru','$jumlah_item','$ket','$user','$publish')");
            };
            $this->db->query("UPDATE tabel_mutasi_masuk SET total_mutasi='$total_mutasi', selesai='1', kd_supplier='$kd_supplier' WHERE no_faktur_mutasi='$nofaktur'");
            echo $this->session->set_flashdata('msg', 'Mutasi Sukses');
            redirect('/m_stok/stok/', 'refresh');
        } else {
            echo $this->session->set_flashdata('error', 'Mutasi Gagal');
            redirect('mutasi/mutasi_masuk/' . $nofaktur, 'refresh');
        }
    }

    public function cetak_mutasi_masuk()
	{
		// $nofak = $this->input->post('no_faktur_pembelian');
		$no_faktur_mutasi = $this->uri->segment(3);
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		// $data['faktur'] = $nofak;
        $data['list'] = $this->Mutasi_model->get_list_masuk($no_faktur_mutasi);
        $data['toko'] = $this->Mutasi_model->get_toko();
		$data['no'] = 1;
		$data['tgl'] = $tgl;
		$data['waktu'] = $waktu; 
		$data['total_item'] = 0;
		$data['subtotal'] = 0;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->Mutasi_model->detail_mutasi_masuk($no_faktur_mutasi);
		$this->load->view('pembelian/inprint_mutasi_masuk', $data);
		
	
	}


    /// mutasi keluar

    public function mutasi_keluar_start()
    {
        $ymd = date('ymd');
        $tgl_now = date('Y-m-d');
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->Mutasi_model->getNoFakturMutasiKeluar($ymd);
        $data = array(
            'no_faktur_mutasi' => $nofaktur,
            'tgl_mutasi' => $tgl_now,
            'id_user' => $id_user,
        );
        $this->db->insert('tabel_mutasi_keluar', $data);
        redirect('mutasi/mutasi_keluar/' . $nofaktur, 'refresh');
    }

    public function mutasi_keluar()
    {
        $noresi = $this->uri->segment(3);
        $username = $this->session->userdata('ses_username');
        $data_faktur = $this->Mutasi_model->getDataMutasiKeluar($noresi, $username)->row();
        if ($data_faktur) {
            $data['tgl'] = date('d-M-Y');
            $data['faktur'] = $data_faktur;
            $data['supplier'] = $this->Mutasi_model->getSupplier();
            $this->load->view('template/header', $data);
            $this->load->view('kasir/mutasi_keluar', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->view('error404');
        }
    }

    public function add_list_mutasi_keluar()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $nm_barang = $this->input->post('nm_barang');
        $jumlah = $this->input->post('jumlah');
        $harga_beli = $this->input->post('harga_beli');
        $satuan = $this->input->post('satuan');
        $subtotal = (int) $harga_beli * (int) $jumlah;

        $produk = $this->Mutasi_model->getbarang($idbarang);
        $tgl = date('Y-m-d');
        if ($produk->num_rows() > 0) {
            $i = $produk->row_array();
            $input = array(
                'no_faktur_mutasi' => $nofaktur,
                'kd_barang' => $i['kd_barang'],
                'nm_barang' => $nm_barang,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'harga' => $harga_beli,
                'sub_total_beli' => $subtotal,
                'tgl_mutasi_keluar' => $tgl,
            );
            $data = $this->db->insert('tabel_rinci_mutasi_keluar', $input);
            echo json_encode($data);
        } else {
            echo "Produk tidak tersedia";
        }
    }

    public function data_list_mutasi_keluar()
    {
        $nofak = $this->uri->segment(3);
        $data = $this->Mutasi_model->data_list_mutasi_keluar($nofak);
        echo json_encode($data);
    }

    public function hapus_item_beli_keluar()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $data = $this->db->query("DELETE FROM tabel_rinci_mutasi_keluar WHERE no_faktur_mutasi='$nofaktur' AND kd_barang='$idbarang'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli_keluar()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_mutasi_keluar SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_mutasi='$nofaktur_e'");
        echo json_encode($data);
    }
    
    public function mutasi_keluar_selesai()
    {
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->input->post('faktur_beli');
        $total_mutasi = $this->input->post('tot_harga');
        $kd_supplier = $this->input->post('supplier_e');
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $ket = "Mutasi Keluar " . $nofaktur;
        $user = $this->session->userdata('ses_username');
        $publish = "1";
        $data_faktur = $this->Mutasi_model->getMutasiKeluarSelesai($nofaktur, $id_user)->row();
        $list_produk = $this->Mutasi_model->getProdukDibeliKeluar($nofaktur)->result();

        if ($data_faktur && $list_produk) {
            foreach ($list_produk as $key) {
                $kd_barang_item = $key->kd_barang;
                $jumlah_item = $key->jumlah;
                $cek_stok = $this->Mutasi_model->getStokBeli($kd_barang_item);
                $i = $cek_stok->row_array();
                $stok_sekarang = $i['stok'];
                $stok_baru = (int) $stok_sekarang - (int) $jumlah_item;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,saldo,masuk,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$waktu','$jam','$stok_sekarang','$jumlah_item','$stok_baru','0','$ket','$user','$publish')");
            };
            $this->db->query("UPDATE tabel_mutasi_keluar SET total_mutasi='$total_mutasi', selesai='1', kd_supplier='$kd_supplier' WHERE no_faktur_mutasi='$nofaktur'");
            echo $this->session->set_flashdata('msg', 'Mutasi Sukses');
            redirect('/m_stok/stok/', 'refresh');
        } else {
            echo $this->session->set_flashdata('error', 'Mutasi Gagal');
            redirect('mutasi/mutasi_keluar/' . $nofaktur, 'refresh');
        }
    }

    public function cetak_mutasi_keluar()
	{
		// $nofak = $this->input->post('no_faktur_pembelian');
		$no_faktur_mutasi = $this->uri->segment(3);
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		// $data['faktur'] = $nofak;
        $data['list'] = $this->Mutasi_model->get_list_keluar($no_faktur_mutasi);
        $data['toko'] = $this->Mutasi_model->get_toko();
		$data['no'] = 1;
		$data['tgl'] = $tgl;
		$data['waktu'] = $waktu; 
		$data['total_item'] = 0;
		$data['subtotal'] = 0;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->Mutasi_model->detail_mutasi_keluar($no_faktur_mutasi);
		$this->load->view('pembelian/inprint_mutasi_masuk', $data);
	}

    public function update_mutasi_masuk()
	{
		$this->load->view('template/header');
		$this->load->view('kasir/update_mutasi_masuk');
		$this->load->view('template/footer');
	}

    public function get_update_mutasi_masuk()
	{
		$nofak = $this->input->post('nofak');
		$data['mutasi'] = $this->Mutasi_model->detail_masuk($nofak);        
		$data['list'] = $this->Mutasi_model->get_list_mutasi_masuk($nofak);
		$hasil = $this->load->view('kasir/list_update_mutasi_masuk', $data, true);
		$callback = array(
			'hasil' => $hasil,
		);
		echo json_encode($callback);
	}
}