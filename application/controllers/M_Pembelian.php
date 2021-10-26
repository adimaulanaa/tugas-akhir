<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Pembelian extends CI_Controller
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
        $this->load->model('m_pembelian_model');
        $this->load->library('datatables');
        $this->load->helper('random');
    }

    // Pembelian

    public function pembelian_start()
    {
        $ymd = date('ymd');
        $tgl_now = date('Y-m-d');
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->M_Pembelian_model->getNoFakturPembelian($ymd);
        $data = array(
            'no_faktur_pembelian' => $nofaktur,
            'tgl_pembelian' => $tgl_now,
            'id_user' => $id_user,
        );
        $this->db->insert('tabel_pembelian', $data);
        redirect('pembelian/pembelian_barang/' . $nofaktur, 'refresh');
    }

    public function pembelian()
    {
        $noresi = $this->uri->segment(3);
        $username = $this->session->userdata('ses_username');
        $data_faktur = $this->gudang_model->getDataPembelian($noresi, $username)->row();
        if ($data_faktur) {
            $data['tgl'] = date('d-M-Y');
            $data['faktur'] = $data_faktur;
            $data['supplier'] = $this->gudang_model->getSupplier();
            $this->load->view('template/header', $data);
            $this->load->view('gudang/pembelian');
            $this->load->view('template/footer');
            
        } else {
            $this->load->view('error404');
        }
    }

    public function get_detail_produk()
    {
        $idbarang = $this->input->post('idbarang');
        $data = $this->gudang_model->get_detail_produk($idbarang);
        echo json_encode($data);
    }

    public function add_list_pembelian()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $nm_barang = $this->input->post('nm_barang');
        $jumlah = $this->input->post('jumlah');
        $harga_beli = $this->input->post('harga_beli');
        $satuan = $this->input->post('satuan');
        $subtotal = (int) $harga_beli * (int) $jumlah;

        $produk = $this->gudang_model->getbarang($idbarang);

        if ($produk->num_rows() > 0) {
            $i = $produk->row_array();
            $input = array(
                'no_faktur_pembelian' => $nofaktur,
                'kd_barang' => $i['kd_barang'],
                'nm_barang' => $nm_barang,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'harga' => $harga_beli,
                'sub_total_beli' => $subtotal,
            );
            $data = $this->db->insert('tabel_rinci_pembelian', $input);
            echo json_encode($data);
        } else {
            echo "Produk tidak tersedia";
        }
    }

    public function data_list_pembelian()
    {
        $nofak = $this->uri->segment(3);
        $data = $this->gudang_model->data_list_pembelian($nofak);
        echo json_encode($data);
    }

    public function hapus_item_beli()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $data = $this->db->query("DELETE FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='$nofaktur' AND kd_barang='$idbarang'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_pembelian SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_pembelian='$nofaktur_e'");
        echo json_encode($data);
    }

    public function pembelian_selesai()
    {
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->input->post('faktur_beli');
        $total_pembelian = $this->input->post('tot_harga');
        $kd_supplier = "SUPP";
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $ket = "Pembelian " . $nofaktur;
        $user = $this->session->userdata('ses_username');
        $publish = "1";
        $data_faktur = $this->gudang_model->getPembelianSelesai($nofaktur, $id_user)->row();
        $list_produk = $this->gudang_model->getProdukDibeli($nofaktur)->result();

        if ($data_faktur && $list_produk) {
            foreach ($list_produk as $key) {
                $kd_barang_item = $key->kd_barang;
                $jumlah_item = $key->jumlah;
                $cek_stok = $this->gudang_model->getStokBeli($kd_barang_item);
                // $cek_porsi = $this->gudang_model->getPorsi($kd_barang_item);
                $i = $cek_stok->row_array();
                // $x = $cek_porsi->row_array();
                $stok_sekarang = $i['stok'];
                // $est_porsi = $x['estimasi_stok'];
                // $stok_porsi = (int) $jumlah_item * (int) $est_porsi;
                $stok_baru = (int) $stok_sekarang + (int) $jumlah_item;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,saldo,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$waktu','$jam','$stok_sekarang','0','$stok_baru','$ket','$user','$publish')");
            };
            $this->db->query("UPDATE tabel_pembelian SET total_pembelian='$total_pembelian', selesai='1', kd_supplier='$kd_supplier' WHERE no_faktur_pembelian='$nofaktur'");
            echo $this->session->set_flashdata('msg', 'Pembelian Sukses');
            redirect('/gudang/stok/', 'refresh');
        } else {
            echo $this->session->set_flashdata('error', 'Pembelian Gagal');
            redirect('gudang/pembelian/' . $nofaktur, 'refresh');
        }
    }

    // RETUR Barang Beli

    public function returbeli()
	{
		$this->load->view('template/header');
		$this->load->view('pembelian/returbeli');
		$this->load->view('template/footer');
    }
    
    public function get_data_fakturbeli()
	{
		$nofak = $this->input->post('nofak');
		$data['list'] = $this->m_pembelian_model->get_list($nofak);
		$data['faktur'] = $this->m_pembelian_model->detail_faktur($nofak);
		$hasil = $this->load->view('pembelian/list_returbeli', $data, true);
		$callback = array(
			'hasil' => $hasil,
		);
		echo json_encode($callback);
	}

    public function returbeli_item()
	{
		$nofak = $this->uri->segment(3);
		$kd_barang = base64_decode($this->uri->segment(4));
		$data_barang = $this->m_pembelian_model->getProdukRetur($nofak, $kd_barang)->row();
		if ($data_barang) {
			$data['produk'] = $data_barang;
			$this->load->view('template/header', $data);
			$this->load->view('pembelian/returbeli_item');
			$this->load->view('template/footer');
		} else {
			$this->load->view('error404');
		}
    }
    
    public function simpan_returbeli()
	{
		$nofak = $this->input->post('nofak');
		$kd_barang = $this->input->post('kd_barang');
		$jum_retur = $this->input->post('jum_retur');
		$ket = $this->input->post('ket');
		$tgl = date('Y-m-d');
		$data_barang = $this->m_pembelian_model->getProdukRetur($nofak, $kd_barang);
		$data_stok = $this->m_pembelian_model->getStokRetur($kd_barang);
		$i = $data_barang->row_array();
		$s = $data_stok->row_array();
		$nm_barang = $i['nm_barang'];
		$harga_item = $i['hrg_beli'];
		$stok_awal = $s['stok'];
		$stok_sekarang = (int) $stok_awal - (int) $jum_retur;
		$total_harga_retur = (int) $jum_retur * (int) $harga_item;
		$kd_toko = "SS001";
		$waktu = date('Y-m-d');
		$jam = date('H:i:s');
		$user = $this->session->userdata('ses_username');
		$publish = "0";
		$this->db->query("INSERT INTO tabel_returbeli (no_faktur_pembelian,kd_barang,nm_barang,jumlah,total_returbeli,ket,tgl) VALUES ('$nofak','$kd_barang','$nm_barang','$jum_retur','$total_harga_retur','$ket','$tgl')");
		$this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,masuk,saldo,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang','$waktu','$jam','$stok_awal','0','$jum_retur','$stok_sekarang','$ket','$user','$publish')");
		$this->db->query("UPDATE tabel_rinci_pembelian SET returbeli='$jum_retur' WHERE kd_barang='$kd_barang' AND no_faktur_pembelian='$nofak'");
		$this->db->query("UPDATE tabel_stok_toko SET stok='$stok_sekarang' WHERE kd_barang='$kd_barang'");
		echo $this->session->set_flashdata('msg', 'Retur Sukses');
		redirect('m_pembelian/returbeli/', 'refresh');
    }
    

    // CETAK FAKTUR
    public function carifaktur()
	{
		$this->load->view('template/header');
		$this->load->view('pembelian/carifaktur');
		$this->load->view('template/footer');
    }

    public function get_data_faktur()
	{
		$nofak = $this->input->post('nofak');
        $data['list'] = $this->m_pembelian_model->get_list($nofak);
        $data['toko'] = $this->m_pembelian_model->get_toko();
        $data['no'] = 1;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->m_pembelian_model->detail_faktur($nofak);
		$hasil = $this->load->view('pembelian/list_faktur', $data, true);
		$callback = array(
			'hasil' => $hasil,
		);
		echo json_encode($callback);
	}

	public function cetak_data_faktur()
	{
		// $nofak = $this->input->post('no_faktur_pembelian');
		$no_faktur_pembelian = $this->uri->segment(3);
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		// $data['faktur'] = $nofak;
        $data['list'] = $this->m_pembelian_model->get_list($no_faktur_pembelian);
        $data['toko'] = $this->m_pembelian_model->get_toko();
		$data['no'] = 1;
		$data['tgl'] = $tgl;
		$data['waktu'] = $waktu; 
		$data['total_item'] = 0;
		$data['subtotal'] = 0;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->m_pembelian_model->detail_faktur($no_faktur_pembelian);
		$this->load->view('pembelian/reprint_faktur', $data);
		
	
	}

	public function reprint_struk()
	{
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		$nofaktur = $this->uri->segment(3);
		$data_faktur = $this->m_pembelian_model->reprintStruk($nofaktur)->row();
		$produk = $this->m_pembelian_model->getProdukDijual($nofaktur);
		if ($data_faktur) {
			$data['toko'] = $this->m_pembelian_model->get_toko();
			$data['faktur'] = $data_faktur;
			$data['tgl'] = $tgl;
			$data['waktu'] = $waktu;
			$data['produk'] = $produk;
			$data['total_item'] = 0;
			$data['subtotal'] = 0;
			// $data['bayar'] = $bayar;
			// $data['kembali'] = $kembali;
			// $data['debet'] = $debet;
			$this->load->view('pembelian/reprint_faktur', $data);
			// redirect('cetak_struk');
			// $this->load->view('kasir/struk_transaksi', $data);
		} else {
			$this->load->view('error404');
		}
	}



    // PERCOBAAN PEMBELIAN
    public function beli_barang()
	{
		$noresi = $this->uri->segment(3);
		$username = $this->session->userdata('ses_username');
		$data_faktur = $this->m_pembelian_model->getDataPembelian($noresi, $username)->row();
		$list_barang = $this->m_pembelian_model->getListPembelian($noresi);
		$data['satuan'] = $this->m_pembelian_model->getSatuan();
		if ($data_faktur) {
			$data['tgl'] = date('Y-m-d');
			$data['faktur'] = $data_faktur;
			$data['list'] = $list_barang;
			// $data[''] = $satuan;
			$data['tot_item'] = 0;
			$data['tot_belanja'] = 0;
			$data['belanja'] = $this->m_pembelian_model->getTotalBelanja($noresi)->row();
			$this->load->view('template/header', $data);
			$this->load->view('pembelian/beli_barang', $data);
			$this->load->view('template/footer');
		} else {
			$this->load->view('error404');
		}
    }
    
    public function nomor_faktur()
	{
		$ymd = date('ymd');
		$tgl_now = date('Y-m-d');
		$waktu = date('H:i:s');
		$kodeawal = "SO001";
		$id_user = $this->session->userdata('ses_username');
		$max = $this->db->query("SELECT MAX(RIGHT(no_faktur_pembelian,3)) AS last FROM tabel_pembelian WHERE substr(no_faktur_pembelian,6,6)='$ymd'");
		$x = $max->row_array();
		$last = $x['last'];
		$cek = $this->db->query("SELECT * FROM tabel_pembelian WHERE substr(no_faktur_pembelian,-3)='$last' AND substr(no_faktur_pembelian,6,6)='$ymd'");
		$i = $cek->row_array();
		$user = $i['id_user'];
		$selesai = $i['selesai'];
		if ($user == $id_user && $selesai == '0') {
			$nofaktur = $kodeawal . $ymd . $last;
		} else {
			$nofaktur = $this->m_pembelian_model->getNoFaktur($ymd);
			$data = array(
				'no_faktur_pembelian' => $nofaktur,
				'tgl_pembelian' => $tgl_now,
				'waktu' => $waktu,
				'id_user' => $id_user,
				'selesai' => '0',
			);
			$this->db->insert('tabel_pembelian', $data);
		}
		redirect('m_pembelian/beli-barang/' . $nofaktur, 'refresh');
    }
    
    public function nomor_faktur_new()
	{
		$ymd = date('ymd');
		$tgl_now = date('Y-m-d');
		$waktu = date('H:i:s');
		$id_user = $this->session->userdata('ses_username');
		$nofaktur = $this->m_pembelian_model->getNoFaktur($ymd);
		$data = array(
			'no_faktur_pembelian' => $nofaktur,
			'tgl_pembelian' => $tgl_now,
			'waktu' => $waktu,
			'id_user' => $id_user,
			'selesai' => '0',
		);
		$this->db->insert('tabel_pembelian', $data);
		redirect('m_pembelian/beli-barang/' . $nofaktur, 'refresh');
    }
    
    function get_autocomplete()
	{
		if (isset($_GET['term'])) {
			$result = $this->m_pembelian_model->cari_nama($_GET['term']);
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
    
    public function cekbarang()
	{
		$nofaktur = urldecode($this->uri->segment(3));
		$idbarang = urldecode($this->uri->segment(4));
		$produk = $this->m_pembelian_model->getbarang($idbarang);
		$cek_sudah_ada = $this->m_pembelian_model->cek_sudah_ada($idbarang, $nofaktur);
		$cek_stok = $this->m_pembelian_model->cek_jumlah_stok($idbarang);
		$x = $produk->row_array();
		$hrg_jual = $x['hrg_jual'];
		$hrg_beli = $x['hrg_beli'];
		$jumlah = "1";
		// $satuan = "PCS";
		$diskonrp = "0";
		$diskonpersen = "0";
		// $subtotal = ($hrg_jual * $jumlah) - $diskonrp;
		$subtotal = ($hrg_beli * $jumlah) - $diskonrp;
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;

		if ($produk->num_rows() > 0) {
			$i = $cek_stok->row_array();
			$stok_sekarang = $i['stok'];
			if ($cek_sudah_ada->num_rows() > 0) {
				$s = $cek_sudah_ada->row_array();
				$jum_beli = $s['jumlah'];
				$jum_beli_sekarang = $jumlah + $jum_beli;
				$subtot_sekarang = ($hrg_beli * $jum_beli_sekarang) - $diskonrp;
				if ($jum_beli_sekarang > $stok_sekarang) {
					echo $this->session->set_flashdata('error', 'Stok barang tidak cukup');
					header("Location: " . $uri, TRUE, $http_response_code);
				} else {
					$this->db->query("UPDATE tabel_rinci_pembelian SET jumlah='$jum_beli_sekarang', sub_total_beli='$subtot_sekarang', satuan='$satuan', diskonrp='$diskonrp', diskonpersen='$diskonpersen' WHERE kd_barang='$idbarang' AND no_faktur_pembelian='$nofaktur'");
					header("Location: " . $uri, TRUE, $http_response_code);
				}
			} else {
				if ($subtotal < 0) {
					echo $this->session->set_flashdata('error', 'Error');
					header("Location: " . $uri, TRUE, $http_response_code);
				
				// ($stok_sekarang < $jumlah) {
				// 	echo $this->session->set_flashdata('error', 'Stok bahan tidak cukup');
				// 	header("Location: " . $uri, TRUE, $http_response_code);
				} else {
					$input = array(
						'no_faktur_pembelian' => $nofaktur,
						'kd_barang' => $x['kd_barang'],
						'nm_barang' => $x['nm_barang'],
						'jumlah' => $jumlah,
						'hrg_beli' => $x['hrg_beli'],
						'harga' => $hrg_jual,
						// 'satuan' => $x['satuan'],
						'diskonrp' => $diskonrp,
						'diskonpersen' => $diskonpersen,
						'sub_total_beli' => $subtotal,
					);
					$this->db->insert('tabel_rinci_pembelian', $input);
					header("Location: " . $uri, TRUE, $http_response_code);

					// if ($subtotal < 0) {
					// 	echo $this->session->set_flashdata('error', 'Error');
					// 	header("Location: " . $uri, TRUE, $http_response_code);
					// } else {
					// 	$input = array(
					// 		'no_faktur_pembelian' => $nofaktur,
					// 		'kd_barang' => $x['kd_barang'],
					// 		'nm_barang' => $x['nm_barang'],
					// 		'jumlah' => $jumlah,
					// 		'hrg_beli' => $x['hrg_beli'],
					// 		'harga' => $hrg_jual,
					// 		// 'satuan' => $x['satuan'],
					// 		'diskonrp' => $diskonrp,
					// 		'diskonpersen' => $diskonpersen,
					// 		'sub_total_beli' => $subtotal,
					// 	);
					// 	$this->db->insert('tabel_rinci_pembelian', $input);
					// 	header("Location: " . $uri, TRUE, $http_response_code);
					// }
				}
			}
		} else {
			echo $this->session->set_flashdata('error', 'Kode ' . $idbarang . ' tidak tersedia :(');
			header("Location: " . $uri, TRUE, $http_response_code);
		}
    }
    
    public function edit_jumlah_beli()
	{
		$idbarang = $this->input->post('kd_barang_e');
		$nofaktur = $this->input->post('nofak_e');
		$jumlah = $this->input->post('jml');
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;
		$cek_stok = $this->m_pembelian_model->cek_jumlah_stok($idbarang);
		$rinci = $this->m_pembelian_model->cek_sudah_ada($idbarang, $nofaktur);
		$i = $cek_stok->row_array();
		$x = $rinci->row_array();
		$stok_sekarang = $i['stok'];
		// $diskonrp = $jumlah * $x['harga'] * $x['diskonpersen'] / 100;
		$diskonrp = $jumlah * $x['hrg_beli'] * $x['diskonpersen'] / 100;
		// $subtot_sekarang = ($x['harga'] * $jumlah) - $diskonrp;
		$subtot_sekarang = ($x['hrg_beli'] * $jumlah) - $diskonrp;
		
		$this->db->query("UPDATE tabel_rinci_pembelian SET jumlah='$jumlah', sub_total_beli='$subtot_sekarang', diskonrp='$diskonrp' WHERE kd_barang='$idbarang' AND no_faktur_pembelian='$nofaktur'");
			header("Location: " . $uri, TRUE, $http_response_code);
	}
	
	public function edit_satuan_beli()
	{
		$idbarang = $this->input->post('kd_barang_e');
		$nofaktur = $this->input->post('nofak_e');
		$satuan = $this->input->post('satuan');
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;
		$satuan = $this->m_pembelian_model->getSatuan();
		// $cek_stok = $this->m_pembelian_model->cek_jumlah_stok($idbarang);
		// $rinci = $this->m_pembelian_model->cek_sudah_ada($idbarang, $nofaktur);
		$i = $satuan->row_array();
		// $x = $rinci->row_array();
		// $stok_sekarang = $i['stok'];
		// $diskonrp = $jumlah * $x['harga'] * $x['diskonpersen'] / 100;
		// $subtot_sekarang = ($x['harga'] * $jumlah) - $diskonrp;
		
		$this->db->query("UPDATE tabel_rinci_pembelian SET satuan='$satuan' WHERE kd_barang='$idbarang' AND no_faktur_pembelian='$nofaktur'");
			header("Location: " . $uri, TRUE, $http_response_code);
    }
    
    public function hapus_barang_beli()
	{
		$nofaktur = urldecode($this->uri->segment(3));
		$idbarang = urldecode($this->uri->segment(4));
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;
		$this->db->query("DELETE FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='$nofaktur' AND kd_barang='$idbarang'");
		header("Location: " . $uri, TRUE, $http_response_code);
    }
    
    public function edit_diskon_beli()
	{
		$idbarang = $this->input->post('kd_barang_d');
		$nofaktur = $this->input->post('nofak_d');
		$diskonpersen = $this->input->post('dis_d');
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;
		$rinci = $this->m_pembelian_model->cek_sudah_ada($idbarang, $nofaktur);
		$x = $rinci->row_array();
		// $diskonrp = $x['jumlah'] * $x['harga'] * $diskonpersen / 100;
		// $subtot_sekarang = ($x['harga'] * $x['jumlah']) - $diskonrp;
		$diskonrp = $x['jumlah'] * $x['hrg_beli'] * $diskonpersen / 100;
		$subtot_sekarang = ($x['hrg_beli'] * $x['jumlah']) - $diskonrp;

		if ($diskonpersen > 100) {
			echo $this->session->set_flashdata('error', 'Diskon tidak valid');
			header("Location: " . $uri, TRUE, $http_response_code);
		} else {
			$this->db->query("UPDATE tabel_rinci_pembelian SET sub_total_beli='$subtot_sekarang', diskonrp='$diskonrp', diskonpersen='$diskonpersen' WHERE kd_barang='$idbarang' AND no_faktur_pembelian='$nofaktur'");
			header("Location: " . $uri, TRUE, $http_response_code);
		}
	}

	public function hitung_diskon()
	{
		$nofaktur = $this->input->post('nofak_dis');
		$input_diskon = $this->input->post('diskon');
		$total_pembelian = $this->input->post('sum_belanja');
		$diskon = str_replace(".", "", $input_diskon);
		$ket_dis = $this->input->post('ket_dis');
		// $pembelian_sdiskon = $total_pembelian - $diskon;
		$ppn = 0.1;
		$hitung_ppn = $total_pembelian * $ppn;

		$pembelian_sdiskon = $total_pembelian + $hitung_ppn;
		$uri = base_url('m_pembelian/beli-barang/') . $nofaktur;
		if ($pembelian_sdiskon < 0) {
			echo $this->session->set_flashdata('error', 'Diskon tidak valid');
			header("Location: " . $uri, TRUE, $http_response_code);
		} else {
			$data = array(
				'total_pembelian' => $total_pembelian,
				'diskon' => $diskon,
				'total_pembelian_sdiskon' => $pembelian_sdiskon,
				'ket_diskon' => $ket_dis,
			);
			$this->db->where('no_faktur_pembelian', $nofaktur);
			$this->db->update('tabel_pembelian', $data);
			header("Location: " . $uri, TRUE, $http_response_code);
		}
    }
    
    public function pembelian_pending()
	{
		$id_user = $this->session->userdata('ses_username');
		$now = date('Y-m-d');
		$before = date('Y-m-d', strtotime('-30 days', strtotime($now)));
		$data['pending'] = $this->m_pembelian_model->transaksiPending($id_user, $now, $before);
		$data['no'] = 1;
		$this->load->view('template/header', $data);
		$this->load->view('pembelian/pembelian_pending');
		$this->load->view('template/footer');
    }
    
    public function hapus_faktur()
	{
		$nofaktur = urldecode($this->uri->segment(3));
		$this->db->query("DELETE FROM tabel_pembelian WHERE no_faktur_pembelian='$nofaktur'");
		$this->db->query("DELETE FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='$nofaktur'");
		echo $this->session->set_flashdata('msg', 'Faktur berhasil ' . $nofaktur . ' dihapus');
		redirect('m_pembelian/pembelian-pending/', 'refresh');
    }
    
    public function go_to_bayar()
	{
		$noresi = $this->input->post('nofak_bayar');
		$total_pembelian = $this->input->post('total_belanja');
		$diskon = $this->input->post('diskon_belanja');
		$ket_diskon = $this->input->post('diskon_ket');
		$total_sdiskon = $total_pembelian - $diskon;
		$data = array(
			'total_pembelian' => $total_pembelian,
			'diskon' => $diskon,
			'total_pembelian_sdiskon' => $total_sdiskon,
			'ket_diskon' => $ket_diskon,
		);
		$this->db->where('no_faktur_pembelian', $noresi);
		$this->db->update('tabel_pembelian', $data);
		$uri = base_url('m_pembelian/beli-barang-bayar/') . $noresi;
		header("Location: " . $uri, TRUE, $http_response_code);
    }
    
    public function beli_barang_bayar()
	{
		$noresi = $this->uri->segment(3);
		$username = $this->session->userdata('ses_username');
		$data_faktur = $this->m_pembelian_model->getDataPembelian($noresi, $username)->row();
		$list_barang = $this->m_pembelian_model->getListPembelian($noresi);
		if ($data_faktur) {
			$data['tgl'] = date('Y-m-d');
			$data['faktur'] = $data_faktur;
			$data['list'] = $list_barang;
			$data['tot_item'] = 0;
			$data['tot_belanja'] = 0;
			$data['belanja'] = $this->m_pembelian_model->getTotalBelanja($noresi)->row();
			$this->load->view('template/header', $data);
			$this->load->view('pembelian/beli_barang_bayar');
			$this->load->view('template/footer');
		} else {
			$this->load->view('error404');
		}
    }
    
    //pengurangan stok
    public function cetak_struk_beli()
	{
		$tgl = date('Y-m-d');
		$waktu = date('H:i:s');
		$kd_toko = "SS001";
		$debet = 0;
		$bayar = 0;
		$id_user = $this->session->userdata('ses_username');
		$nofaktur = $this->input->post('nofak_print');
		$diskon = $this->input->post('diskon_print');
		$total_pembelian = $this->input->post('sum_print');
		$bayar = $this->input->post('cash_print');
		$debet = $this->input->post('debet_print');
		$bank = $this->input->post('bank_print');
		$cash = $total_pembelian - $debet;
		$kembali = ($bayar + $debet) - $total_pembelian;
		$selesai = 1;
		$ket_ks = "Pembelian " . $nofaktur;
		$uri = base_url('m_pembelian/cetak_struk_beli/') . $nofaktur;
		$this->db->trans_start();
		$data_faktur = $this->m_pembelian_model->getPembelianSelesai($nofaktur, $id_user)->row();
		$list_produk = $this->m_pembelian_model->getProdukDibeli($nofaktur)->result();
		if ($data_faktur && $list_produk) {
			foreach ($list_produk as $key) {
				$kd_barang_item = $key->kd_barang;
				$jumlah_item = $key->jumlah;
				$validasi_stok = $this->m_pembelian_model->getStok($kd_barang_item);
				$i = $validasi_stok->row_array();
				$stok_sekarang = $i['stok'];
				
				$stok_porsi = $this->m_pembelian_model->getStokPorsi($kd_barang_item)->result();
				foreach ($stok_porsi as $key) {
					// $kd_bahan = $key->kode_bahan;
					$kd_barang_item = $key->kd_barang;
					$stok_bahan = $key->stok;
					$stok_baru = (int) $stok_sekarang + (int) $jumlah_item;
					$this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
					$this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,masuk,saldo,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$tgl','$waktu','$stok_bahan','0','$jumlah_item','$stok_baru','$ket_ks','$id_user','1')");
				}


				// if ($stok_sekarang < $jumlah_item) {
				// 	echo $this->session->set_flashdata('error', 'Stok ada yang kurang');
				// 	header("Location: " . $uri, TRUE, $http_response_code);
				// 	return false;
				// } else {
				// 	$stok_porsi = $this->m_pembelian_model->getStokPorsi($kd_barang_item)->result();
				// 	foreach ($stok_porsi as $key) {
				// 		// $kd_bahan = $key->kode_bahan;
				// 		$kd_barang_item = $key->kd_barang;
				// 		$stok_bahan = $key->stok;
				// 		$stok_baru = (int) $stok_sekarang + (int) $jumlah_item;
				// 		$this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
				// 		$this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,masuk,saldo,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$tgl','$waktu','$stok_bahan','$jumlah_item','0','$stok_baru','$ket_ks','$id_user','0')");
				// 	}
				// }
			};
			$update = array(
				'waktu' => $waktu,
				'cash' => $cash,
				'debet' => $debet,
				'ket' => $bank,
				'selesai' => $selesai,
			);
			$this->db->where('id_user', $id_user);
			$this->db->where('no_faktur_pembelian', $nofaktur);
			$this->db->update('tabel_pembelian', $update);
			$this->db->trans_complete();
			$data_cetak['toko'] = $this->m_pembelian_model->get_toko();
			$data_cetak['faktur'] = $data_faktur;
			$data_cetak['tgl'] = $tgl;
			$data_cetak['waktu'] = $waktu;
			$data_cetak['bayar'] = $bayar;
			$data_cetak['kembali'] = $kembali;
			$data_cetak['debet'] = $debet;
			$data_cetak['produk'] = $list_produk;
			$data_cetak['total_item'] = 0;
			$data_cetak['subtotal'] = 0;
			$this->load->view('pembelian/cetak_struk_beli', $data_cetak);
		} else {
			echo "Error retrieving information from server. <br><br>Halaman ini tidak bisa dimuat ulang, silahkan tutup halaman ini.";
		}
    }
    
    public function beli_barang_selesai()
	{
		$this->load->view('template/header');
		$this->load->view('pembelian/beli_barang_selesai');
		$this->load->view('template/footer');
	}



    //
}
