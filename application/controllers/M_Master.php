<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Master extends CI_Controller
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
        $this->load->model('m_master_model');
        $this->load->library('datatables');
        $this->load->helper('random');
        // $this->load->helper('url');
    }

    public function supplier()
    {
        $data['supplier'] = $this->m_master_model->getSupplier();

        $data['tgl'] = date_indo(date('Y-m-d'));
        $kat = $this->input->get('category');
        $sup = $this->input->get('supplier');
        $sort = $this->input->get('sort_stok');
        $data['kategori'] = $this->m_master_model->getKategory();
        $data['supplier'] = $this->m_master_model->getSupplier();
        $data['sort'] = $sort;
        $data['kat'] = $kat;
        $data['sup'] = $sup;
        
        $this->load->view('template/header', $data);
        $this->load->view('master/supplier');
        $this->load->view('template/footer');
    }

    public function simpan_supplier()
    {
        $kode = $this->input->post('kd_supplier');
        $nama = $this->input->post('nm_supplier');
        $alamat = $this->input->post('alamat');
        $telp = $this->input->post('telp');
        $an = $this->input->post('an');
        $no_sales = $this->input->post('no_sales');
        $cek_kode = $this->m_master_model->cekKodeSupplier($kode);

        if ($cek_kode->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Kode ' . $kode . ' sudah terdaftar :(');
            redirect('m_master/supplier/', 'refresh');
        } else {
            $data = array(
                'kd_supplier' => $kode,
                'nm_supplier' => $nama,
                'almt_supplier' => $alamat,
                'tlp_supplier' => $telp,
                'atas_nama' => $an,
                'no_sales' => $no_sales,
            );
            $this->db->insert('tabel_supplier', $data);
            echo $this->session->set_flashdata('msg', 'Supplier ' . $nama . ' berhasil ditambah');
            redirect('m_master/supplier/', 'refresh');
        }
    }

    public function simpan_supplier_edit()
    {
        $kode = $this->input->post('kd_supplier');
        $nama = $this->input->post('nm_supplier');
        $alamat = $this->input->post('alamat');
        $telp = $this->input->post('telp');
        $atas = $this->input->post('an');
        $no_sales = $this->input->post('no_sales');
        $data = array(
            'nm_supplier' => $nama,
            'almt_supplier' => $alamat,
            'tlp_supplier' => $telp,
            'atas_nama' => $atas,
            'no_sales' => $no_sales,
        );
        $this->db->where('kd_supplier', $kode);
        $this->db->update('tabel_supplier', $data);
        echo $this->session->set_flashdata('msg', 'Kode ' . $kode . ' berhasil diedit');
        redirect('m_master/supplier/', 'refresh');
        // $this->load->view('master/supplier');
    }

    public function hapus_supplier()
    {
        $kd_supplier = $this->input->post('kd_supplier_h', TRUE);
        $this->m_master_model->delete_supplier($kd_supplier);
        echo $this->session->set_flashdata('msg', 'Supplier berhasil dihapus');
        redirect('m_master/supplier', 'refresh');
    }

    // KATEGORI

    public function kategori()
    {
        $data['kategori'] = $this->m_master_model->getKategory();
        // $this->load->view('header', $data);
        // $this->load->view('master/kategori');
        $this->load->view('template/header', $data);
        $this->load->view('master/kategori');
        $this->load->view('template/footer');
    }

    public function simpan_kategori()
    {
        $kode = $this->input->post('kd_kategori');
        $nama = $this->input->post('nm_kategori');
        $cek_kode = $this->m_master_model->cekKodeKategori($kode);

        if ($cek_kode->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Kode ' . $kode . ' sudah terdaftar :(');
            redirect('m_master/kategori/', 'refresh');
        } else {
            $data = array(
                'kd_kategori' => $kode,
                'nm_kategori' => $nama,
            );
            $this->db->insert('tabel_kategori_barang', $data);
            echo $this->session->set_flashdata('msg', 'Kategori ' . $nama . ' berhasil ditambah');
            redirect('m_master/kategori/', 'refresh');
        }
    }

    public function simpan_kategori_edit()
    {
        $kode = $this->input->post('kd_kategori');
        $nama = $this->input->post('nm_kategori');
        $data = array(
            'nm_kategori' => $nama,
        );
        $this->db->where('kd_kategori', $kode);
        $this->db->update('tabel_kategori_barang', $data);
        echo $this->session->set_flashdata('msg', 'Kategori ' . $kode . ' berhasil diedit');
        redirect('m_master/kategori/', 'refresh');
    }

    public function hapus_kategori()
    {
        $kd_kategori = $this->input->post('kd_kategori_h', TRUE);
        $this->m_master_model->delete_kategori($kd_kategori);
        echo $this->session->set_flashdata('msg', 'Kategori berhasil dihapus');
        redirect('m_master/kategori/', 'refresh');
    }

    // SATUAN
    public function satuan()
    {
        $data['satuan'] = $this->m_master_model->getSatuan();
        
        $this->load->view('template/header', $data);
        $this->load->view('master/satuan');
        $this->load->view('template/footer');
    }

    public function simpan_satuan()
    {
        $uri = base_url('master/satuan/');
        $kode = $this->input->post('kd_satuan');
        $nama = $this->input->post('nm_satuan');
        $cek_kode = $this->m_master_model->cekKodeSatuan($kode);

        if ($cek_kode->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Kode ' . $kode . ' sudah terdaftar :(');
            redirect('m_master/satuan/', 'refresh');
        } else {
            $data = array(
                'kd_satuan' => $kode,
                'nm_satuan' => $nama,
            );
            $this->db->insert('tabel_satuan_barang', $data);
            echo $this->session->set_flashdata('msg', 'Satuan ' . $nama . ' berhasil ditambah');
            redirect('m_master/satuan/', 'refresh');
        }
    }

    public function simpan_satuan_edit()
    {
        $kode = $this->input->post('kd_satuan');
        $nama = $this->input->post('nm_satuan');
        $data = array(
            'nm_satuan' => $nama,
        );
        $this->db->where('kd_satuan', $kode);
        $this->db->update('tabel_satuan_barang', $data);
        echo $this->session->set_flashdata('msg', 'Kode ' . $kode . ' berhasil diedit');
        redirect('m_master/satuan/', 'refresh');
    }

    public function hapus_satuan()
    {
        $data['satuan'] = $this->m_master_model->getSatuan();
        $kd_satuan = $this->input->post('kd_satuan_h', TRUE);
        $this->m_master_model->delete_satuan($kd_satuan);
        echo $this->session->set_flashdata('msg', 'Satuan berhasil dihapus');
        redirect('m_master/satuan/', 'refresh');
        // $this->load->view('master/satuan');
    }

    // Member
    public function member()
    {
        $data['member'] = $this->m_master_model->getMember();
        $this->load->view('template/header', $data, FALSE);
        $this->load->view('master/member');
        $this->load->view('template/footer');
    }

    public function simpan_member()
    {
        $usermember = $this->input->post('kd_member');
        $nama = $this->input->post('nm_member');
        $alamat = $this->input->post('almt_member');
        $tlp = $this->input->post('telp');
        $jumlah = $this->input->post('jum_point');
        $ket = $this->input->post('keterangan');
        $cek_member = $this->m_master_model->cekUsermember($usermember);

        if ($cek_member->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Usermember ' . $usermember . ' sudah terdaftar :(');
            redirect('m_master/member/', 'refresh');
        } else {
            $data = array(
                'kd_member' => $usermember,
                'nm_member' => $nama,
                'almt_member' => $alamat,
                'telp' => $tlp,
                'jum_point' => $jumlah,
                'keterangan' => $ket,

            );
            $this->db->insert('tabel_member', $data);
            echo $this->session->set_flashdata('msg', 'Member ' . $usermember . ' berhasil ditambah');
            redirect('m_master/member/', 'refresh');
        }
    }

    public function simpan_member_edit()
    {
        $usermember = $this->input->post('kd_member_e');
        $nama = $this->input->post('nm_member_e');
        $alamat = $this->input->post('almt_member_e');
        $tlp = $this->input->post('telp_e');
        $jumlah = $this->input->post('jum_point_e');
        $ket = $this->input->post('keterangan_e');
        $data = array(
            'kd_member' => $usermember,
            'nm_member' => $nama,
            'almt_member' => $alamat,
            'telp' => $tlp,
            'jum_point' => $jumlah,
            'keterangan' => $ket,

        );
        $this->db->where('kd_member', $usermember);
        $this->db->update('tabel_member', $data);

        echo $this->session->set_flashdata('msg', 'Data Member ' . $usermember . ' berhasil diedit');
        redirect('m_master/member/', 'refresh');
    }

    public function hapus_member()
    {
        $kd = urldecode($this->uri->segment(3));
        $this->db->where('kd_member', $kd);
        $this->db->delete('tabel_member');
        echo $this->session->set_flashdata('msg', 'Member ' . $kd . ' berhasil dihapus');
        redirect('m_master/member/', 'refresh');
    }

    // Barang
    public function barang()
    {
        $data['kategori'] = $this->m_master_model->getKategory();
        $data['satuan'] = $this->m_master_model->getSatuan();
        $data['supplier'] = $this->m_master_model->getSupplier();
        $this->load->view('template/header', $data);
        $this->load->view('master/barang');
        $this->load->view('template/footer');
    }

    public function json_produk()
    {
        if ($this->input->is_ajax_request()) {
            $this->m_master_model->getProduk();
        } else {
            redirect('master/barang/', 'refresh');
        }
    }

    public function simpan_barang()
    {
        // $uri = base_url('gudang/barang/');
        $kode = $this->input->post('kd_barang');
        $nama = $this->input->post('nm_barang');
        $satuan = $this->input->post('kd_satuan');
        $kategori = $this->input->post('kd_kategori');
        $supplier = $this->input->post('kd_supplier');
        $hrg_jual = str_replace(".", "", $this->input->post('hrg_jual'));
        $hrg_modal = str_replace(".", "", $this->input->post('hrg_beli'));
        // $estimasi_stok = $this->input->post('estimasi_stok');
        $stok_awal = "0";
        $stok_min = "1";
        $user = $this->session->userdata('ses_username');
        // $modal_per_porsi = $hrg_modal / $estimasi_stok;

        $cek_kode = $this->m_master_model->cekKodeBarang($kode);
        if ($cek_kode->num_rows() > 0) {
            echo $this->session->set_flashdata('error', 'Kode Barang ' . $kode . ' sudah terdaftar :(');
            // header("Location: " . $uri, TRUE, $http_response_code);
            redirect('m_master/barang/', 'refresh');
        } else {
            $data = array(
                'kd_barang' => $kode,
                'nm_barang' => $nama,
                'kd_satuan' => $satuan,
                'kd_kategori' => $kategori,
                'kd_supplier' => $supplier,
                'hrg_jual' => $hrg_jual,
                'hrg_beli' => $hrg_modal,
            );
            $data_stok = array(
                'kd_toko' => "SS001",
                'kd_barang' => $kode,
                'stok' => $stok_awal,
                'stok_min' => $stok_min,
                'tgl_perubahan' => date('d-m-Y H:i:s'),
                'ket' => "Barang Baru",
            );
            $this->db->insert('tabel_barang', $data);
            $this->db->insert('tabel_stok_toko', $data_stok);
            echo $this->session->set_flashdata('msg', 'Barang ' . $kode . ' berhasil ditambah');
            // header("Location: " . $uri, TRUE, $http_response_code);
            redirect('m_master/barang/', 'refresh');
        }
    }

    public function simpan_barang_edit()
    {
        $kode = $this->input->post('kd_barang');
        $nama = $this->input->post('nm_barang');
        $satuan = $this->input->post('kd_satuan');
        $kategori = $this->input->post('kd_kategori');
        $supplier = $this->input->post('kd_supplier');
        $hrg_jual = str_replace(".", "", $this->input->post('hrg_jual'));
        $hrg_modal = str_replace(".", "", $this->input->post('hrg_beli'));
        // $modal_per_porsi = $hrg_modal / $estimasi_stok;
        $this->db->trans_start();
        $data = array(
            'kd_barang' => $kode,
            'nm_barang' => $nama,
            'kd_satuan' => $satuan,
            'kd_kategori' => $kategori,
            'kd_supplier' => $supplier,
            'hrg_jual' => $hrg_jual,
            'hrg_beli' => $hrg_modal,
            // 'modal_per_porsi' => $modal_per_porsi,
        );
        $this->db->where('kd_barang', $kode);
        $this->db->update('tabel_barang', $data);

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
        echo $this->session->set_flashdata('msg', 'Barang ' . $kode . ' berhasil diedit');
        redirect('m_master/barang/', 'refresh');
    }

    public function hapus_barang()
    {
        $kode = urldecode($this->uri->segment(3));
        $this->db->query("DELETE FROM tabel_barang WHERE kd_barang='$kode'");
        $this->db->query("DELETE FROM tabel_stok_toko WHERE kd_barang='$kode'");
        echo $this->session->set_flashdata('msg', 'Produk ' . $kode . ' berhasil dihapus');
        redirect('m_master/barang/', 'refresh');
    }

    //
}
