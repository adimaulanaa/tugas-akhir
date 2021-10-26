<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mutasi_model extends CI_Model
{
    public function getSupplier()
    {
        $this->db->order_by('kd_supplier');
        return $this->db->get('tabel_supplier');
    }

    public function get_toko()
	{
		$query = $this->db->query("SELECT * FROM tabel_toko LIMIT 1");
		return $query->row();
    }


    public function getNoFakturMutasiMasuk($ymd)
    {
        $q = $this->db->query("SELECT MAX(RIGHT(no_faktur_mutasi,5)) AS id_max FROM tabel_mutasi_masuk WHERE substr(no_faktur_mutasi,6,6)='$ymd'");
        $kd = "";
        $kodeawal = "MM001";
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $k) {
                $tmp = ((int) $k->id_max) + 1;
                $kd = sprintf("%05s", $tmp);
            }
        } else {
            $kd = "00001";
        }
        return $kodeawal . $ymd . $kd;
    }

    public function getDataMutasi($noresi, $username)
    {
        $this->db->where('tabel_mutasi_masuk.no_faktur_mutasi', $noresi);
        $this->db->where('tabel_mutasi_masuk.id_user', $username);
        $this->db->where('tabel_mutasi_masuk.selesai', '0');
        return $this->db->get('tabel_mutasi_masuk');
    }

    public function get_detail_produk($idbarang)
    {
        $hsl = $this->db->query("SELECT tabel_stok_toko.stok, tabel_barang.nm_barang, tabel_satuan_barang.nm_satuan, tabel_barang.hrg_beli, tabel_barang.hrg_jual, tabel_kategori_barang.nm_kategori FROM tabel_barang LEFT JOIN tabel_stok_toko ON tabel_barang.kd_barang = tabel_stok_toko.kd_barang LEFT JOIN tabel_kategori_barang ON tabel_barang.kd_kategori = tabel_kategori_barang.kd_kategori LEFT JOIN tabel_satuan_barang ON tabel_barang.kd_satuan = tabel_satuan_barang.kd_satuan WHERE tabel_barang.kd_barang='$idbarang'");
        if ($hsl->num_rows() > 0) {
            foreach ($hsl->result() as $data) {
                $hasil = array(
                    'namaproduk' => $data->nm_barang,
                    'stok' => $data->stok,
                    'harga' => $data->hrg_jual,
                    'kategori' => $data->nm_kategori,
                    'harga_beli' => $data->hrg_beli,
                    'satuan' => $data->nm_satuan,
                );
            }
        }
        return $hasil;
    }

    public function getbarang($idbarang)
    {
        $this->db->where('kd_barang', $idbarang);
        return $this->db->get('tabel_barang');
    }

    public function data_list_mutasi($nofak)
    {
        return $this->db->select('tabel_rinci_mutasi_masuk.*')
            ->where('no_faktur_mutasi', $nofak)
            ->get('tabel_rinci_mutasi_masuk')
            ->result();
    }

    public function getMutasiSelesai($nofaktur, $id_user)
    {
        $this->db->where('no_faktur_mutasi', $nofaktur);
        $this->db->where('id_user', $id_user);
        return $this->db->get('tabel_mutasi_masuk');
    }

    public function getProdukDibeli($nofaktur)
    {
        $this->db->where('no_faktur_mutasi', $nofaktur);
        return $this->db->get('tabel_rinci_mutasi_masuk');
    }

    public function getStokBeli($kd_barang_item)
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko WHERE kd_barang='$kd_barang_item'");
    }

    public function get_list_masuk($no_faktur_mutasi)
	{
		return $this->db->select('tabel_rinci_mutasi_masuk.*')
			->where('tabel_rinci_mutasi_masuk.no_faktur_mutasi', $no_faktur_mutasi)
			->get('tabel_rinci_mutasi_masuk')
			->result();
    }

    public function detail_mutasi_masuk($no_faktur_internal)
	{
		return $this->db->select('tabel_mutasi_masuk.*')
			->where('tabel_mutasi_masuk.no_faktur_mutasi', $no_faktur_internal)
			->where('tabel_mutasi_masuk.selesai', '1')
			->get('tabel_mutasi_masuk')
			->row();
    }
    
    /// mutasi keluar

    public function getNoFakturMutasiKeluar($ymd)
    {
        $q = $this->db->query("SELECT MAX(RIGHT(no_faktur_mutasi,5)) AS id_max FROM tabel_mutasi_keluar WHERE substr(no_faktur_mutasi,6,6)='$ymd'");
        $kd = "";
        $kodeawal = "MK001";
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $k) {
                $tmp = ((int) $k->id_max) + 1;
                $kd = sprintf("%05s", $tmp);
            }
        } else {
            $kd = "00001";
        }
        return $kodeawal . $ymd . $kd;
    }

    public function getMutasiKeluarSelesai($nofaktur, $id_user)
    {
        $this->db->where('no_faktur_mutasi', $nofaktur);
        $this->db->where('id_user', $id_user);
        return $this->db->get('tabel_mutasi_keluar');
    }

    public function getProdukDibeliKeluar($nofaktur)
    {
        $this->db->where('no_faktur_mutasi', $nofaktur);
        return $this->db->get('tabel_rinci_mutasi_keluar');
    }

    public function getDataMutasiKeluar($noresi, $username)
    {
        $this->db->where('tabel_mutasi_keluar.no_faktur_mutasi', $noresi);
        $this->db->where('tabel_mutasi_keluar.id_user', $username);
        $this->db->where('tabel_mutasi_keluar.selesai', '0');
        return $this->db->get('tabel_mutasi_keluar');
    }

    public function data_list_mutasi_keluar($nofak)
    {
        return $this->db->select('tabel_rinci_mutasi_keluar.*')
            ->where('no_faktur_mutasi', $nofak)
            ->get('tabel_rinci_mutasi_keluar')
            ->result();
    }

    public function get_list_keluar($no_faktur_mutasi)
	{
		return $this->db->select('tabel_rinci_mutasi_keluar.*')
			->where('tabel_rinci_mutasi_keluar.no_faktur_mutasi', $no_faktur_mutasi)
			->get('tabel_rinci_mutasi_keluar')
			->result();
    }

    public function detail_mutasi_keluar($no_faktur_internal)
	{
		return $this->db->select('tabel_mutasi_keluar.*')
			->where('tabel_mutasi_keluar.no_faktur_mutasi', $no_faktur_internal)
			->where('tabel_mutasi_keluar.selesai', '1')
			->get('tabel_mutasi_keluar')
			->row();
    }
    
    public function get_list_mutasi_masuk($nofak)
	{
		
		return $this->db->select('tabel_rinci_mutasi_masuk.*')
			->where('tabel_rinci_mutasi_masuk.no_faktur_mutasi', $nofak)
			->get('tabel_rinci_mutasi_masuk')
			->result();
	}

    public function detail_masuk($nofak)
	{
		return $this->db->select('tabel_mutasi_masuk.*')
			->where('tabel_mutasi_masuk.no_faktur_mutasi', $nofak)
			->where('tabel_mutasi_masuk.selesai', '1')
			->get('tabel_mutasi_masuk')
			->row();
	}

}