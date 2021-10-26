<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PemakaianToko extends CI_Controller
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
        $this->load->model('PemakaianToko_model');
        $this->load->library('datatables');
        $this->load->helper('random');
    }

    public function pakai_barang_start()
    {
        $ymd = date('ymd');
        $tgl_now = date('Y-m-d');
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->PemakaianToko_model->getNoFakturPakaiMasuk($ymd);
        $data = array(
            'no_faktur_pemakaiantoko' => $nofaktur,
            'tgl_pemakaian' => $tgl_now,
            'id_user' => $id_user,
        );
        $this->db->insert('tabel_pemakaian_toko', $data);
        redirect('pemakaiantoko/pemakaiantoko_masuk/' . $nofaktur, 'refresh');
    }

    public function pemakaiantoko_masuk()
    {
        $noresi = $this->uri->segment(3);
        $username = $this->session->userdata('ses_username');
        $data_faktur = $this->PemakaianToko_model->getDataPemakaianToko($noresi, $username)->row();
        if ($data_faktur) {
            $data['tgl'] = date('d-M-Y');
            $data['faktur'] = $data_faktur;
            $data['supplier'] = $this->PemakaianToko_model->getSupplier();
            $this->load->view('template/header', $data);
            $this->load->view('pembelian/pemakaian_barang', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->view('error404');
        }
    }

    public function get_detail_produk()
    {
        $idbarang = $this->input->post('idbarang');
        $data = $this->PemakaianToko_model->get_detail_produk($idbarang);
        echo json_encode($data);
    }

    public function add_list_pemakaiantoko()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $nm_barang = $this->input->post('nm_barang');
        $jumlah = $this->input->post('jumlah');
        $harga_beli = $this->input->post('harga_beli');
        $satuan = $this->input->post('satuan');
        $subtotal = (int) $harga_beli * (int) $jumlah;
        $tgl = date('Y-m-d');
        $produk = $this->PemakaianToko_model->getbarang($idbarang);

        if ($produk->num_rows() > 0) {
            $i = $produk->row_array();
            $input = array(
                'no_faktur_pemakaiantoko' => $nofaktur,
                'kd_barang' => $i['kd_barang'],
                'nm_barang' => $nm_barang,
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'harga' => $harga_beli,
                'sub_total_beli' => $subtotal,
                'tgl_pemakaian_toko' => $tgl,
            );
            $data = $this->db->insert('tabel_rinci_pemakaian_toko', $input);
            echo json_encode($data);
        } else {
            echo "Produk tidak tersedia";
        }
    }

    public function data_list_pemakaiantoko()
    {
        $nofak = $this->uri->segment(3);
        $data = $this->PemakaianToko_model->data_list_pemakaiantoko($nofak);
        echo json_encode($data);
    }

    public function hapus_item_beli()
    {
        $nofaktur = $this->input->post('nofaktur');
        $idbarang = $this->input->post('idbarang');
        $data = $this->db->query("DELETE FROM tabel_rinci_pemakaian_toko WHERE no_faktur_pemakaiantoko='$nofaktur' AND kd_barang='$idbarang'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli1()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_pemakaian_toko SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_pemakaiantoko='$nofaktur_e'");
        echo json_encode($data);
    }

    public function simpan_edit_jumlah_beli()
    {
        $nofaktur_e = $this->input->post('nofaktur_e');
        $idbarang_e = $this->input->post('idbarang_e');
        $jumlah_e = $this->input->post('jumlah_e');
        $harga_e = $this->input->post('harga_e');
        $subtot_sekarang = (int) $jumlah_e * (int) $harga_e;
        $data = $this->db->query("UPDATE tabel_rinci_pemakaian_toko SET jumlah='$jumlah_e', sub_total_beli='$subtot_sekarang' WHERE kd_barang='$idbarang_e' AND no_faktur_pemakaiantoko='$nofaktur_e'");
        echo json_encode($data);
    }

    public function pemakaiantoko_selesai()
    {
        $id_user = $this->session->userdata('ses_username');
        $nofaktur = $this->input->post('faktur_beli');
        $total_pakai = $this->input->post('tot_harga');
        $kd_supplier = $this->input->post('supplier_e');
        $kd_toko = "SS001";
        $waktu = date('Y-m-d');
        $jam = date('H:i:s');
        $ket = "Pemakaian Toko " . $nofaktur;
        $user = $this->session->userdata('ses_username');
        $publish = "1";
        $data_faktur = $this->PemakaianToko_model->getPakaiSelesai($nofaktur, $id_user)->row();
        $list_produk = $this->PemakaianToko_model->getProdukDibeliPakai($nofaktur)->result();

        if ($data_faktur && $list_produk) {
            foreach ($list_produk as $key) {
                $kd_barang_item = $key->kd_barang;
                $jumlah_item = $key->jumlah;
                $cek_stok = $this->PemakaianToko_model->getStokBeli($kd_barang_item);
                $i = $cek_stok->row_array();
                $stok_sekarang = $i['stok'];
                $stok_baru = (int) $stok_sekarang - (int) $jumlah_item;
                $this->db->query("UPDATE tabel_stok_toko SET stok='$stok_baru' WHERE kd_barang='$kd_barang_item'");
                $this->db->query("INSERT INTO tabel_kartu_stok (kode_toko,kode_barang,waktu,jam,sebelumnya,keluar,saldo,masuk,keterangan,user,publish) VALUES ('$kd_toko','$kd_barang_item','$waktu','$jam','$stok_sekarang','$jumlah_item','$stok_baru','0','$ket','$user','$publish')");
            };
            $this->db->query("UPDATE tabel_pemakaian_toko SET 	total_pemakaiantoko='$total_pakai', selesai='1', kd_supplier='$kd_supplier' WHERE no_faktur_pemakaiantoko='$nofaktur'");
            // echo $this->session->set_flashdata('msg', 'Mutasi Sukses');
            redirect('/m_stok/stok/', 'refresh');
        } else {
            // echo $this->session->set_flashdata('error', 'Mutasi Gagal');
            redirect('pemakaiantoko/pemakaiantoko_masuk/' . $nofaktur, 'refresh');
        }
    }

    
    public function cetak_pemakaiantoko()
	{
		// $nofak = $this->input->post('no_faktur_pembelian');
		$no_faktur_pemakaiantoko = $this->uri->segment(3);
		$tgl = date('d-m-Y');
		$waktu = date('H:i:s');
		// $data['faktur'] = $nofak;
        $data['list'] = $this->PemakaianToko_model->get_list_pakai($no_faktur_pemakaiantoko);
        $data['toko'] = $this->PemakaianToko_model->get_toko();
		$data['no'] = 1;
		$data['tgl'] = $tgl;
		$data['waktu'] = $waktu; 
		$data['total_item'] = 0;
		$data['subtotal'] = 0;
        // $data['tot'] = 0;
        $data['tot_harga'] = 0;
		$data['faktur'] = $this->PemakaianToko_model->detail_pakai($no_faktur_pemakaiantoko);
		$this->load->view('pembelian/inprint_pemakaiantoko', $data);
	}

    
}