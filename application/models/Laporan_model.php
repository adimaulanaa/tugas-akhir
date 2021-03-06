<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_model extends CI_Model
{

	public function get_toko()
	{
		$query = $this->db->query("SELECT * FROM tabel_toko LIMIT 1");
		return $query->row();
	}

	public function getDataRetur($tanggal)
	{
		$this->db->where('tgl', $tanggal);
		return $this->db->get('tabel_retur');
	}

	public function getDataReturFilter($tgl_awal, $tgl_akhir)
	{
		$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"');
		return $this->db->get('tabel_retur');
	}

	public function getDataUpdateFaktur($tanggal)
	{
		$this->db->where('tgl', $tanggal);
		return $this->db->get('tabel_update_faktur');
	}

	public function getDataUpdateFakturFilter($tgl_awal, $tgl_akhir)
	{
		$this->db->where('tgl BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"');
		return $this->db->get('tabel_update_faktur');
	}

	public function getDataPengeluaranRekap($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('*')
			->where('tgl BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->group_by('tgl')
			->get('tabel_biaya');
	}

	public function getDataPengeluaranRinci($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('*')
			->where('tgl BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->get('tabel_biaya');
	}
	
	public function getDataStockopnameBertambah($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('*')
			->where('owaktu BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			// ->where('status')
			->group_by('owaktu', 'status')
			->get('tabel_stockopname');
	}

	public function getDataStockopnameBerkurang($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('*')
			->where('owaktu BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('status = 0' )
			->group_by('owaktu', 'status')
			->get('tabel_stockopname');
	}

	public function getDataStockopnameSemua($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('*')
			->where('owaktu BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->get('tabel_stockopname');
	}

	public function getNilaiPersediaan()
	{
		return $this->db->select('a.kd_barang,a.nm_barang,a.hrg_jual,a.hrg_beli,b.stok,a.modal_per_porsi')
			->join('tabel_stok_toko AS b', 'a.kd_barang = b.kd_barang', 'LEFT')
			->where('b.stok > 0')
			->order_by('a.kd_barang')
			->get('tabel_barang AS a');
	}

	public function getDataPembelian($tanggal)
	{
		return $this->db->select('a.no_faktur_pembelian,a.tgl_pembelian,b.kd_barang,b.nm_barang,b.satuan,b.jumlah,b.harga,b.sub_total_beli')
			->join('tabel_rinci_pembelian AS b', 'a.no_faktur_pembelian = b.no_faktur_pembelian')
			->where('a.tgl_pembelian', $tanggal)
			->where('a.selesai', '1')
			->order_by('a.no_faktur_pembelian')
			->get('tabel_pembelian AS a');
	}

	public function getDataPembelianKc($tanggal)
	{
		return $this->db->select('a.no_faktur_internal,a.tgl_internal,b.kd_barang,b.nm_barang,b.satuan,b.jumlah,b.harga,b.sub_total_beli')
			->join('tabel_rinci_internal AS b', 'a.no_faktur_internal = b.no_faktur_internal')
			->where('a.tgl_internal', $tanggal)
			->where('a.selesai', '1')
			->order_by('a.no_faktur_internal')
			->get('tabel_internal AS a');
	}

	public function getDataPembelianFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.no_faktur_pembelian,a.tgl_pembelian,b.kd_barang,b.nm_barang,b.satuan,b.jumlah,b.harga,b.sub_total_beli')
			->join('tabel_rinci_pembelian AS b', 'a.no_faktur_pembelian = b.no_faktur_pembelian')
			->where('a.tgl_pembelian BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('a.selesai', '1')
			->get('tabel_pembelian AS a');
	}

	public function getDataPembelianKcFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.no_faktur_internal,a.tgl_internal,b.kd_barang,b.nm_barang,b.satuan,b.jumlah,b.harga,b.sub_total_beli')
			->join('tabel_rinci_internal AS b', 'a.no_faktur_internal = b.no_faktur_internal')
			->where('a.tgl_internal BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('a.selesai', '1')
			->get('tabel_internal AS a');
	}

	public function getDataPenjualanTransaksiFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_penjualan')
			->get('tabel_penjualan AS a');
	}

	public function getDataPembelianTransaksiFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_pembelian AS b', 'a.no_faktur_pembelian = b.no_faktur_pembelian')
			->where('a.tgl_pembelian BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_pembelian AS b WHERE a.no_faktur_pembelian=b.no_faktur_pembelian)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_pembelian')
			->get('tabel_pembelian AS a');
	}

	public function getDataPembelianTransaksi($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_pembelian AS b', 'a.no_faktur_pembelian = b.no_faktur_pembelian')
			->where('a.tgl_pembelian', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_pembelian AS b WHERE a.no_faktur_pembelian=b.no_faktur_pembelian)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_pembelian')
			->get('tabel_pembelian AS a');
	}

	public function getDataPenjualanTransaksi($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_penjualan')
			->get('tabel_penjualan AS a');
	}

	public function getDataPenjualanBarangFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.no_faktur_penjualan, b.kd_barang,b.nm_barang,b.nm_barang,b.hrg_beli,SUM(b.retur) AS jum_retur, SUM(b.jumlah) AS jum_item')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->group_by('b.kd_barang')
			->get('tabel_penjualan AS a');
	}

	public function getDataPenjualanBarang($tanggal)
	{
		return $this->db->select('a.no_faktur_penjualan, b.kd_barang,b.nm_barang,b.nm_barang,b.hrg_beli,SUM(b.retur) AS jum_retur, SUM(b.jumlah) AS jum_item')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->group_by('b.kd_barang')
			->get('tabel_penjualan AS a');
	}

	public function getDataProfit($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.no_faktur_penjualan, b.kd_barang,b.nm_barang,b.hrg_beli,b.harga,b.retur AS jum_retur, b.jumlah AS jum_item')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->order_by('b.kd_barang')
			->get('tabel_penjualan AS a');
	}

	public function getDiskonBarang($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('SUM(b.diskonrp) AS disk1')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->get('tabel_penjualan AS a');
	}

	public function getDiskonAkhir($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('SUM(diskon) AS diska')
			->where('a.tgl_penjualan BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->get('tabel_penjualan AS a');
	}

	public function getDataProfit1($tanggal)
	{
		return $this->db->select('a.no_faktur_penjualan, b.kd_barang,b.nm_barang,b.hrg_beli,b.harga,b.retur AS jum_retur, b.jumlah AS jum_item')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->order_by('b.kd_barang')
			->get('tabel_penjualan AS a');
	}

	public function getDiskonBarang1($tanggal)
	{
		return $this->db->select('SUM(b.diskonrp) AS disk1')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('a.tgl_penjualan', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->get('tabel_penjualan AS a');
	}

	public function getDiskonAkhir1($tanggal)
	{
		return $this->db->select('SUM(diskon) AS diska')
			->where('a.tgl_penjualan', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_penjualan AS b WHERE a.no_faktur_penjualan=b.no_faktur_penjualan)')
			->where('a.selesai', '1')
			->get('tabel_penjualan AS a');
	}

	public function getDataPengeluaranRinci1($tanggal)
	{
		return $this->db->select('*')
			->where('tgl', $tanggal)
			->get('tabel_biaya');
	}

	public function getDataRekap($tahun, $bulan)
	{
		return $this->db->select('a.tgl_penjualan, SUM(b.harga*b.jumlah) AS tot_jual, SUM(b.jumlah*b.hrg_beli) AS tot_modal, SUM(b.diskonrp) AS tot_diskon1')
			->join('tabel_rinci_penjualan AS b', 'a.no_faktur_penjualan = b.no_faktur_penjualan')
			->where('MONTH(a.tgl_penjualan)', $bulan)
			->where('YEAR(a.tgl_penjualan)', $tahun)
			->where('a.selesai', '1')
			->group_by('a.tgl_penjualan')
			->get('tabel_penjualan AS a');
	}

	public function getDiskon($tahun, $bulan)
	{
		return $this->db->select('SUM(diskon) AS tot_diskon2')
			->where('MONTH(a.tgl_penjualan)', $bulan)
			->where('YEAR(a.tgl_penjualan)', $tahun)
			->where('a.selesai', '1')
			->group_by('a.tgl_penjualan')
			->get('tabel_penjualan AS a');
	}

	public function getDataPengeluaranRekapitulasi($tahun, $bulan)
	{
		return $this->db->select('*')
			->where('MONTH(tgl)', $bulan)
			->where('YEAR(tgl)', $tahun)
			->group_by('tgl')
			->get('tabel_biaya');
	}


	public function getDatapembeliankc1TransaksiFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_internal AS b', 'a.no_faktur_internal = b.no_faktur_internal')
			->where('a.tgl_internal BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_internal AS b WHERE a.no_faktur_internal=b.no_faktur_internal)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_internal')
			->get('tabel_internal AS a');
	}

	public function getDataPembeliankc1Transaksi($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_internal AS b', 'a.no_faktur_internal = b.no_faktur_internal')
			->where('a.tgl_internal', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_internal AS b WHERE a.no_faktur_internal=b.no_faktur_internal)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_internal')
			->get('tabel_internal AS a');
	}

	public function getDataMutasiMasukFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_mutasi_masuk AS b', 'a.no_faktur_mutasi = b.no_faktur_mutasi')
			->where('a.tgl_mutasi BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_mutasi_masuk AS b WHERE a.no_faktur_mutasi=b.no_faktur_mutasi)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_mutasi')
			->get('tabel_mutasi_masuk AS a');
	}

	public function getDataMutasiMasuk($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_mutasi_masuk AS b', 'a.no_faktur_mutasi = b.no_faktur_mutasi')
			->where('a.tgl_mutasi', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_mutasi_masuk AS b WHERE a.no_faktur_mutasi=b.no_faktur_mutasi)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_mutasi')
			->get('tabel_mutasi_masuk AS a');
	}

	public function getDataMutasiKeluarFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_mutasi_keluar AS b', 'a.no_faktur_mutasi = b.no_faktur_mutasi')
			->where('a.tgl_mutasi BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_mutasi_keluar AS b WHERE a.no_faktur_mutasi=b.no_faktur_mutasi)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_mutasi')
			->get('tabel_mutasi_keluar AS a');
	}

	public function getDataMutasiKeluar($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_mutasi_keluar AS b', 'a.no_faktur_mutasi = b.no_faktur_mutasi')
			->where('a.tgl_mutasi', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_mutasi_keluar AS b WHERE a.no_faktur_mutasi=b.no_faktur_mutasi)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_mutasi')
			->get('tabel_mutasi_keluar AS a');
	}
	
	public function getDataMutasiPakaiFilter($tgl_awal, $tgl_akhir)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_pemakaian_toko AS b', 'a.no_faktur_pemakaiantoko = b.no_faktur_pemakaiantoko')
			->where('a.	tgl_pemakaian BETWEEN "' . date('Y-m-d', strtotime($tgl_awal)) . '" and "' . date('Y-m-d', strtotime($tgl_akhir)) . '"')
			->where('EXISTS (SELECT 1 FROM tabel_rinci_pemakaian_toko AS b WHERE a.no_faktur_pemakaiantoko=b.no_faktur_pemakaiantoko)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_pemakaiantoko')
			->get('tabel_pemakaian_toko AS a');
	}

	public function getDataMutasiPakai($tanggal)
	{
		return $this->db->select('a.*,b.*')
			->join('tabel_rinci_pemakaian_toko AS b', 'a.no_faktur_pemakaiantoko = b.no_faktur_pemakaiantoko')
			->where('a.tgl_pemakaian', $tanggal)
			->where('EXISTS (SELECT 1 FROM tabel_rinci_pemakaian_toko AS b WHERE a.no_faktur_pemakaiantoko=b.no_faktur_pemakaiantoko)')
			->where('a.selesai', '1')
			->group_by('a.no_faktur_pemakaiantoko')
			->get('tabel_pemakaian_toko AS a');		
	}

}

/* End of file Laporan_model.php */
/* Location: ./application/models/Laporan_model.php */