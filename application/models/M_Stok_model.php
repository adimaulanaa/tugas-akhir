<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stok_model extends CI_Model
{
    public function getKategory()
    {
        $this->db->order_by('kd_kategori');
        return $this->db->get('tabel_kategori_barang');
    }
    public function getSatuan()
    {
        $this->db->order_by('nm_satuan');
        return $this->db->get('tabel_satuan_barang');
    }

    public function getSupplier()
    {
        $this->db->order_by('kd_supplier');
        return $this->db->get('tabel_supplier');
    }

    public function getStok()
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_stok_toko.stok>0 ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokAll()
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokAllEmpty()
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_stok_toko.stok=0 ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokSort($kat)
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_kategori_barang.kd_kategori='" . $kat . "' ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokEmpty($kat)
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_stok_toko.stok=0 AND tabel_kategori_barang.kd_kategori='" . $kat . "' ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokMore($kat)
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_stok_toko.stok>0 AND tabel_kategori_barang.kd_kategori='" . $kat . "' ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokMin()
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko,tabel_barang,tabel_kategori_barang,tabel_satuan_barang WHERE tabel_stok_toko.kd_barang=tabel_barang.kd_barang AND tabel_barang.kd_kategori=tabel_kategori_barang.kd_kategori AND tabel_barang.kd_satuan=tabel_satuan_barang.kd_satuan AND tabel_stok_toko.stok<tabel_stok_toko.stok_min ORDER BY tabel_barang.kd_barang ASC");
    }

    public function getStokMaudiEdit()
    {
        $this->load->library('datatables');
        $this->datatables->select('a.kd_barang,a.nm_barang,a.hrg_beli,a.kd_kategori,b.nm_kategori,e.stok,e.stok_min');
        $this->datatables->from('tabel_barang AS a');
        $this->datatables->join('tabel_kategori_barang AS b', 'a.kd_kategori = b.kd_kategori', 'left');
        $this->datatables->join('tabel_stok_toko AS e', 'a.kd_barang = e.kd_barang', 'left');
        $this->datatables->add_column('Aksi', '<a href="javascript:void(0);" class="edit_record" title="Edit data" data-kode="$1" data-nama="$2" data-harga="$3" data-kategori="$4" data-stok="$5" data-stok_min="$6"><i class="fa fa-pencil-square-o"></i></a>', 'kd_barang, nm_barang, hrg_beli, kd_kategori, stok, stok_min');
        return print_r($this->datatables->generate());
    }

    public function getUbahHarga()
    {
        $this->load->library('datatables');
        $this->datatables->select('a.kd_barang,a.nm_barang,a.kd_satuan,a.kd_kategori,a.kd_supplier,a.hrg_jual,a.hrg_beli,a.kode_virtual,b.nm_kategori,c.nm_satuan,d.nm_supplier,a.estimasi_stok,a.modal_per_porsi');
        $this->datatables->from('tabel_barang AS a');
        $this->datatables->join('tabel_kategori_barang AS b', 'a.kd_kategori = b.kd_kategori', 'left');
        $this->datatables->join('tabel_satuan_barang AS c', 'a.kd_satuan = c.kd_satuan', 'left');
        $this->datatables->join('tabel_supplier AS d', 'a.kd_supplier = d.kd_supplier', 'left');
        $this->db->order_by('a.kd_kategori');
        $this->datatables->add_column('Aksi', '<a href="javascript:void(0);" class="edit_record" title="Edit data" data-kode="$1" data-nama="$2"  data-kategori="$4" data-supplier="$5" data-jual="$6" data-beli="$7" data-satuan="$8" data-porsi="$9"><i class="fa fa-pencil-square-o"></i></a> ', 'kd_barang, nm_barang, kd_satuan, kd_kategori, kd_supplier, hrg_jual, hrg_beli, nm_satuan, estimasi_stok');
        return print_r($this->datatables->generate());
    }

    public function dataBarangMasuk($tgl)
	{
		$this->db->join('tabel_barang AS b', 'a.kd_barang = b.kd_barang', 'left');
		$this->db->join('tabel_ganti_harga AS c', 'a.kd_barang = c.kd_barang', 'left');
		$this->db->where('a.waktu', $tgl);
		// $this->db->where('a.publish', '1');
		return $this->db->get('tabel_ganti_harga AS a');
	}


    //
}
