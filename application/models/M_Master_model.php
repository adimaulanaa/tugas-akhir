<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Master_model extends CI_Model
{
    public function getSupplier()
    {
        $this->db->order_by('kd_supplier');
        return $this->db->get('tabel_supplier');
    }

    public function cekKodeSupplier($kode)
    {
        $query = $this->db->query("SELECT kd_supplier FROM tabel_supplier WHERE kd_supplier='$kode'");
        return $query;
    }

    public function delete_supplier($kd_supplier)
    {
        $this->db->delete('tabel_supplier', array('kd_supplier' => $kd_supplier));
    }

    // Kategori
    public function getKategory()
    {
        $this->db->order_by('kd_kategori');
        return $this->db->get('tabel_kategori_barang');
    }

    public function cekKodeKategori($kode)
    {
        $query = $this->db->query("SELECT kd_kategori FROM tabel_kategori_barang WHERE kd_kategori='$kode'");
        return $query;
    }

    public function delete_kategori($kd_kategori)
    {
        // $this->db->delete('tabel_kategori_barang', array('kd_kategori' => $kd_kategori));
        $this->db->where('kd_kategori', $kd_kategori);
        $this->db->delete('tabel_kategori_barang');
    }

    // SATUAN
    public function getSatuan()
    {
        $this->db->order_by('nm_satuan');
        return $this->db->get('tabel_satuan_barang');
    }

    public function cekKodeSatuan($kode)
    {
        $query = $this->db->query("SELECT kd_satuan FROM tabel_satuan_barang WHERE kd_satuan='$kode'");
        return $query;
    }

    public function delete_satuan($kd_satuan)
    {
        $this->db->delete('tabel_satuan_barang', array('kd_satuan' => $kd_satuan));
        // $this->db->where('kd_satuan', $kd_satuan);
        // $this->db->delete('tabel_satuan_barang');
    }

    // Member
    public function getMember()
    {
        return $this->db->get('tabel_member');
    }

    public function cekUsermember($usermember)
    {
        return $this->db->query("SELECT kd_member FROM tabel_member WHERE kd_member='$usermember'");
    }

    // produk join table
    public function getProduk()
    {
        $this->load->library('datatables');
        $this->datatables->select('a.kd_barang,a.nm_barang,a.kd_satuan,a.kd_kategori,a.kd_supplier,a.hrg_jual,a.hrg_beli,a.kode_virtual,b.nm_kategori,c.nm_satuan,d.nm_supplier,a.estimasi_stok,a.modal_per_porsi');
        $this->datatables->from('tabel_barang AS a');
        $this->datatables->join('tabel_kategori_barang AS b', 'a.kd_kategori = b.kd_kategori', 'left');
        $this->datatables->join('tabel_satuan_barang AS c', 'a.kd_satuan = c.kd_satuan', 'left');
        $this->datatables->join('tabel_supplier AS d', 'a.kd_supplier = d.kd_supplier', 'left');
        $this->db->order_by('a.kd_kategori');
        $this->datatables->add_column('Aksi', '<a href="javascript:void(0);" class="edit_record" title="Edit data" data-kode="$1" data-nama="$2" data-satuan="$3" data-kategori="$4" data-supplier="$5" data-jual="$6" data-beli="$7" data-satuan="$8" data-porsi="$9"><i class="fa fa-pencil-square-o"></i></a> <a href="javascript:void(0);" class="hapus_record" title="Hapus data" data-kode="$1"><i class="fa fa-trash-o"></i></a>', 'kd_barang, nm_barang, kd_satuan, kd_kategori, kd_supplier, hrg_jual, hrg_beli, nm_satuan, estimasi_stok');
        return print_r($this->datatables->generate());
    }

    public function cekKodeBarang($kode)
    {
        return $this->db->query("SELECT kd_barang FROM tabel_barang WHERE kd_barang='$kode'");
    }


    // 
}
