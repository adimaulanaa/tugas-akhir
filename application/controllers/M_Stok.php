<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stok extends CI_Controller
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
        $this->load->model('m_stok_model');
        $this->load->library('datatables');
        $this->load->helper('random');
    }

    public function stok()
    {
        $data['tgl'] = date_indo(date('Y-m-d'));
        $kat = $this->input->get('category');
        $sort = $this->input->get('sort_stok');
        $data['kategori'] = $this->m_stok_model->getKategory();
        if ($kat != "wow" && $sort) {
            if ($sort == "empty") {
                $data['stok'] = $this->m_stok_model->getStokEmpty($kat);
            } elseif ($sort == "more") {
                $data['stok'] = $this->m_stok_model->getStokMore($kat);
            } else {
                $data['stok'] = $this->m_stok_model->getStokSort($kat);
            }
        } elseif ($kat == "wow" && $sort == "all") {
            $data['stok'] = $this->m_stok_model->getStokAll();
        } elseif ($kat == "wow" && $sort == "more") {
            $data['stok'] = $this->m_stok_model->getStok();
        } elseif ($kat == "wow" && $sort == "empty") {
            $data['stok'] = $this->m_stok_model->getStokAllEmpty();
        } else {
            $data['stok'] = $this->m_stok_model->getStok();
        }
        $data['sort'] = $sort;
        $data['kat'] = $kat;
        $this->load->view('template/header', $data);
        $this->load->view('stok/stok_barang');
        $this->load->view('template/footer');
    }

    public function edit_stok()
    {
        $data['tgl'] = date_indo(date('Y-m-d'));
        $kat = $this->input->get('category');
        $sort = $this->input->get('sort_stok');
        $data['kategori'] = $this->m_stok_model->getKategory();
        if ($kat != "wow" && $sort) {
            if ($sort == "empty") {
                $data['stok'] = $this->m_stok_model->getStokEmpty($kat);
            } elseif ($sort == "more") {
                $data['stok'] = $this->m_stok_model->getStokMore($kat);
            } else {
                $data['stok'] = $this->m_stok_model->getStokSort($kat);
            }
        } elseif ($kat == "wow" && $sort == "all") {
            $data['stok'] = $this->m_stok_model->getStokAll();
        } elseif ($kat == "wow" && $sort == "more") {
            $data['stok'] = $this->m_stok_model->getStok();
        } elseif ($kat == "wow" && $sort == "empty") {
            $data['stok'] = $this->m_stok_model->getStokAllEmpty();
        } else {
            $data['stok'] = $this->m_stok_model->getStok();
        }
        $data['sort'] = $sort;
        $data['kat'] = $kat;
        $this->load->view('template/header', $data);
        $this->load->view('stok/edit_stok');
        $this->load->view('template/footer');
    }

    public function json_edit_stok()
    {
        if ($this->input->is_ajax_request()) {
            $this->m_stok_model->getStokMaudiEdit();
        } else {
            redirect('m_stok/edit-stok/', 'refresh');
        }
    }

    public function simpan_edit_stok()
    {
        $kode = $this->input->post('kd_barang');
        $nama = $this->input->post('nm_barang');
        $stok = $this->input->post('stok');
        $sebelumnya = $this->input->post('sebelumnya');
        $stok_min = $this->input->post('stok_min');
        $user = $this->session->userdata('ses_username');
        $harga = $this->input->post('harga');
        $sb = $sebelumnya;
        $op = $stok;
        if ($stok > $sebelumnya) {
            $masuk = $stok - $sebelumnya;
            $keluar = "0";
            $publish = $this->input->post('publish');
            $keterangan = "Penambahan Stockopname";
        } else {
            $masuk = "0";
            $keluar = $sebelumnya - $stok;
            $publish = "0";
            $keterangan = "Pengurangan Stockopname";
        }
        $data_stok = array(
            'stok' => $stok,
            'stok_min' => $stok_min,
            'tgl_perubahan' => date('d-m-Y H:i:s'),
            'ket' => $keterangan,
            'publish' => $publish,
        );
        $kartu_stok = array(
            'kode_toko' => "SS001",
            'kode_barang' => $kode,
            'waktu' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'sebelumnya' => $sebelumnya,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'saldo' => $stok,
            'keterangan' => $keterangan,
            'user' => $user,
            'publish' => $publish,
        );
        if($sb < $op){
            $akhir  = $op - $sb;
            $oket = "Stockopname Bertambah";
            $orugi = "0";
            $ototalrugi = "0";
        } else {
            $akhir  = $sb - $op;
            $oket = "Stockopname Berkurang";
            $orugi = $akhir;
            // $hitrugi = $akhir * "1000";
            $hitrugi = $akhir * $harga;
            $ototalrugi = $hitrugi;
        }
        $stockopname = array(
            'kode_barang' => $kode,
            'nm_barang' => $nama,
            'owaktu' => date('Y-m-d'),
            'ojam' => date('H:i:s'),
            'osebelumnya' => $sb,
            'ostockopname' => $op,
            'ostockakhir' => $stok,
            'oselisih' => $akhir,
            'orugi' => $orugi,
            'ototalrugi' => $ototalrugi,
            'oket' => $oket,
            'ouser' => $user,
        );
        $this->db->where('kd_barang', $kode);
        $this->db->update('tabel_stok_toko', $data_stok);
        $this->db->insert('tabel_kartu_stok', $kartu_stok);
        $this->db->insert('tabel_stockopname', $stockopname);
        // echo $this->session->set_flashdata('msg', 'Stok ' . $nama . ' berhasil diedit');
        

        redirect('m_stok/edit-stok/', 'refresh');
    }

    // edit stok
    public function ubah_harga()
    {
        $data['kategori'] = $this->m_stok_model->getKategory();
        $data['satuan'] = $this->m_stok_model->getSatuan();
        $data['supplier'] = $this->m_stok_model->getSupplier();
        $this->load->view('template/header', $data);
        $this->load->view('stok/ubah_harga');
        $this->load->view('template/footer');
    }

    public function json_produk()
    {
        if ($this->input->is_ajax_request()) {
            $this->m_stok_model->getUbahHarga();
        } else {
            redirect('m_stok/ubah_harga/', 'refresh');
        }
    }


    public function ubah_harga_edit()
    {
        $waktu = date('H:i:s');
        $kode = $this->input->post('kd_barang');
        $nama = $this->input->post('nm_barang');
        // $satuan = $this->input->post('kd_satuan');
        $kategori = $this->input->post('kd_kategori');
        $supplier = $this->input->post('kd_supplier');
        $hrg_jual = str_replace(".", "", $this->input->post('hrg_jual'));
        $hrg_modal = str_replace(".", "", $this->input->post('hrg_beli'));
        $sebelumjual = $this->input->post('sebelumjual');
        $sebelumbeli = $this->input->post('sebelumbeli');
        // $modal_per_porsi = $hrg_modal / $estimasi_stok;
        $this->db->trans_start();
        $data = array(
            'kd_barang' => $kode,
            'nm_barang' => $nama,
            // 'kd_satuan' => $satuan,
            'kd_kategori' => $kategori,
            'kd_supplier' => $supplier,
            'hrg_jual' => $hrg_jual,
            'hrg_beli' => $hrg_modal,
            // 'modal_per_porsi' => $modal_per_porsi,
        );
        $dataharga = array(
            'kd_barang' => $kode,
            'nm_barang' => $nama,
            'waktu' => date('Y-m-d'),
            'hrg_jual' => $hrg_jual,
            'hrg_beli' => $hrg_modal,
            'sebelum_jual' => $sebelumjual,
            'sebelum_beli' => $sebelumbeli,
            // 'modal_per_porsi' => $modal_per_porsi,
        );
        $this->db->where('kd_barang', $kode);
        $this->db->update('tabel_barang', $data);
        $this->db->insert('tabel_ganti_harga', $dataharga);
        // $this->db->insert('tabel_ganti_harga', $data);
        // $this->db->query("INSERT INTO tabel_ganti_harga (kd_barang, nm_barang, hrg_jual, hrg_beli) VALUES ('$kode', '$nama', '$hrg_jual', '$hrg_modal)");

        // $q_rinci_menu = $this->db->query("SELECT * FROM tabel_rinci_menu WHERE kode_bahan='$kode'");
        // foreach ($q_rinci_menu->result() as $key) {
        //     $menu = $key->kode_menu;
        //     $q_menu = $this->db->query("SELECT * FROM tabel_rinci_menu WHERE kode_bahan='$kode'");
        //     $jum = $this->db->query("SELECT SUM(a.modal_per_porsi) AS tot_mod, b.kode_menu FROM tabel_barang AS a JOIN tabel_rinci_menu AS b ON a.kd_barang=b.kode_bahan WHERE kode_menu='$menu'");
        //     $x = $jum->row_array();
        //     $harga_modal = $x['tot_mod'];
        //     $n_menu = $x['kode_menu'];
        //     $data = $this->db->query("UPDATE tabel_menu SET harga_modal='$harga_modal' WHERE kode_menu='$n_menu'");
        // };
        $this->db->trans_complete();
        echo $this->session->set_flashdata('msg', 'Produk ' . $kode . ' berhasil diedit');
        redirect('m_stok/ubah_harga/', 'refresh');
    }

    // BARANG MASUK

    public function perubahan_harga()
	{
		$tgl = date('Y-m-d');
		$data['no'] = 1;
		$data['tanggal'] = $tgl;
		$data['masuk'] = $this->m_stok_model->dataBarangMasuk($tgl);
		$this->load->view('template/header', $data);
		$this->load->view('stok/perubahanharga');
		$this->load->view('template/footer');
	}

	public function cetak_barang_masuk()
	{
		$tgl = date('Y-m-d');
		$data['toko'] = $this->kasir_model->get_toko();
		$data['no'] = 1;
		$data['tanggal'] = $tgl;
		$data['masuk'] = $this->kasir_model->dataBarangMasuk($tgl);
		$this->load->view('kasir/cetak_barang_masuk', $data);
	}

    public function repacking()
    {
        $data['tgl'] = date_indo(date('Y-m-d'));
        $kat = $this->input->get('category');
        $sort = $this->input->get('sort_stok');
        $data['kategori'] = $this->m_stok_model->getKategory();
        if ($kat != "wow" && $sort) {
            if ($sort == "empty") {
                $data['stok'] = $this->m_stok_model->getStokEmpty($kat);
            } elseif ($sort == "more") {
                $data['stok'] = $this->m_stok_model->getStokMore($kat);
            } else {
                $data['stok'] = $this->m_stok_model->getStokSort($kat);
            }
        } elseif ($kat == "wow" && $sort == "all") {
            $data['stok'] = $this->m_stok_model->getStokAll();
        } elseif ($kat == "wow" && $sort == "more") {
            $data['stok'] = $this->m_stok_model->getStok();
        } elseif ($kat == "wow" && $sort == "empty") {
            $data['stok'] = $this->m_stok_model->getStokAllEmpty();
        } else {
            $data['stok'] = $this->m_stok_model->getStok();
        }
        $data['sort'] = $sort;
        $data['kat'] = $kat;
        $this->load->view('template/header', $data);
        $this->load->view('stok/repacking');
        $this->load->view('template/footer');
    }

    public function simpan_repacking()
    {
        $kode = $this->input->post('kd_barang');
        $nama = $this->input->post('nm_barang');
        $stok = $this->input->post('stok');
        $sebelumnya = $this->input->post('sebelumnya');
        $stok_min = $this->input->post('stok_min');
        $user = $this->session->userdata('ses_username');
        $sb = $sebelumnya;
        $op = $stok;
        if ($stok > $sebelumnya) {
            $masuk = $stok - $sebelumnya;
            $keluar = "0";
            $publish = $this->input->post('publish');
            $keterangan = "Penambahan Stockopname";
        } else {
            $masuk = "0";
            $keluar = $sebelumnya - $stok;
            $publish = "0";
            $keterangan = "Pengurangan Stockopname";
        }
        $data_stok = array(
            'stok' => $stok,
            'stok_min' => $stok_min,
            'tgl_perubahan' => date('d-m-Y H:i:s'),
            'ket' => $keterangan,
            'publish' => $publish,
        );
        $kartu_stok = array(
            'kode_toko' => "SS001",
            'kode_barang' => $kode,
            'waktu' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'sebelumnya' => $sebelumnya,
            'masuk' => $masuk,
            'keluar' => $keluar,
            'saldo' => $stok,
            'keterangan' => $keterangan,
            'user' => $user,
            'publish' => $publish,
        );
        // if($sb < $op){
        //     $akhir  = $op - $sb;
        //     $oket = "Stockopname Bertambah";
        //     $orugi = "0";
        //     $ototalrugi = "0";
        // } else {
        //     $akhir  = $sb - $op;
        //     $oket = "Stockopname Berkurang";
        //     $orugi = $akhir;
        //     // $ototalrugi = "12000";
        // }
        // $stockopname = array(
        //     'kode_barang' => $kode,
        //     'nm_barang' => $nama,
        //     'owaktu' => date('Y-m-d'),
        //     'ojam' => date('H:i:s'),
        //     'osebelumnya' => $sb,
        //     'ostockopname' => $op,
        //     'ostockakhir' => $stok,
        //     'oselisih' => $akhir,
        //     'orugi' => $orugi,
        //     // 'ototalrugi' => $ototalrugi,
        //     'oket' => $oket,
        //     'ouser' => $user,
        // );
        $this->db->where('kd_barang', $kode);
        $this->db->update('tabel_stok_toko', $data_stok);
        $this->db->insert('tabel_kartu_stok', $kartu_stok);
        // $this->db->insert('tabel_stockopname', $stockopname);
        echo $this->session->set_flashdata('msg', 'Stok ' . $nama . ' berhasil diedit');
        redirect('m_stok/repacking/', 'refresh');
    }


    //
}
