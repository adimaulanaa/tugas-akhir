<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PemakaianToko_model extends CI_Model
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

    public function getNoFakturPakaiMasuk($ymd)
    {
        $q = $this->db->query("SELECT MAX(RIGHT(no_faktur_pemakaiantoko,5)) AS id_max FROM tabel_pemakaian_toko WHERE substr(no_faktur_pemakaiantoko,6,6)='$ymd'");
        $kd = "";
        $kodeawal = "PT001";
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

    public function getDataPemakaianToko($noresi, $username)
    {
        $this->db->where('tabel_pemakaian_toko.no_faktur_pemakaiantoko', $noresi);
        $this->db->where('tabel_pemakaian_toko.id_user', $username);
        $this->db->where('tabel_pemakaian_toko.selesai', '0');
        return $this->db->get('tabel_pemakaian_toko');
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

    public function data_list_pemakaiantoko($nofak)
    {
        return $this->db->select('tabel_rinci_pemakaian_toko.*')
            ->where('no_faktur_pemakaiantoko', $nofak)
            ->get('tabel_rinci_pemakaian_toko')
            ->result();
    }

    public function getPakaiSelesai($nofaktur, $id_user)
    {
        $this->db->where('no_faktur_pemakaiantoko', $nofaktur);
        $this->db->where('id_user', $id_user);
        return $this->db->get('tabel_pemakaian_toko');
    }

    public function getProdukDibeliPakai($nofaktur)
    {
        $this->db->where('no_faktur_pemakaiantoko', $nofaktur);
        return $this->db->get('tabel_rinci_pemakaian_toko');
    }

    public function getStokBeli($kd_barang_item)
    {
        return $this->db->query("SELECT * FROM tabel_stok_toko WHERE kd_barang='$kd_barang_item'");
    }

    public function get_list_pakai($no_faktur_pemakaiantoko)
	{
		return $this->db->select('tabel_rinci_pemakaian_toko.*')
			->where('tabel_rinci_pemakaian_toko.no_faktur_pemakaiantoko', $no_faktur_pemakaiantoko)
			->get('tabel_rinci_pemakaian_toko')
			->result();
    }

    public function detail_pakai($no_faktur_pemakaiantoko)
	{
		return $this->db->select('tabel_pemakaian_toko.*')
			->where('tabel_pemakaian_toko.no_faktur_pemakaiantoko', $no_faktur_pemakaiantoko)
			->where('tabel_pemakaian_toko.selesai', '1')
			->get('tabel_pemakaian_toko')
			->row();
    }

}