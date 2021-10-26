<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Pembelian_model extends CI_Model
{

    public function getNoFakturPembelian($ymd)
    {
        $ymd = date('ymd');
        $q = $this->db->query("SELECT MAX(RIGHT(no_faktur_pembelian,5)) AS id_max FROM tabel_pembelian WHERE substr(no_faktur_pembelian,6,6)='$ymd'");
        $kd = "";

        $kodeawal = "SS001";
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

    // RETUR

    public function get_list($no_faktur_pembelian)
	{
		return $this->db->select('tabel_rinci_pembelian.*')
			->where('tabel_rinci_pembelian.no_faktur_pembelian', $no_faktur_pembelian)
			->get('tabel_rinci_pembelian')
			->result();
    }
    
    public function detail_faktur($no_faktur_pembelian)
	{
		return $this->db->select('tabel_pembelian.*')
			->where('tabel_pembelian.no_faktur_pembelian', $no_faktur_pembelian)
			->where('tabel_pembelian.selesai', '1')
			->get('tabel_pembelian')
			->row();
    }
    
    public function getProdukRetur($nofak, $kd_barang)
	{
		return $this->db->select('tabel_rinci_pembelian.*')
			->where('tabel_rinci_pembelian.no_faktur_pembelian', $nofak)
			->where('tabel_rinci_pembelian.kd_barang', $kd_barang)
			->get('tabel_rinci_pembelian');
	}

	public function getStokRetur($kd_barang)
	{
		return $this->db->query("SELECT * FROM tabel_stok_toko WHERE kd_barang='$kd_barang'");
    }
    
    public function get_toko()
	{
		$query = $this->db->query("SELECT * FROM tabel_toko LIMIT 1");
		return $query->row();
    }
	
	// reprint faktur
	public function reprintStruk($nofaktur)
	{
		$this->db->where('no_faktur_penjualan', $nofaktur);
		return $this->db->get('tabel_penjualan');
	}

	public function getProdukDijual($nofaktur)
	{
		$this->db->where('no_faktur_penjualan', $nofaktur);
		return $this->db->get('tabel_rinci_penjualan');
	}

	
    // percobaan beli
    public function getNoFaktur($ymd)
	{
		$q = $this->db->query("SELECT MAX(RIGHT(no_faktur_pembelian,3)) AS id_max FROM tabel_pembelian WHERE substr(no_faktur_pembelian,6,6)='$ymd'");
		$kd = "";
		$kodeawal = "SO001";
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $k) {
				$tmp = ((int) $k->id_max) + 1;
				$kd = sprintf("%03s", $tmp);
			}
		} else {
			$kd = "001";
		}
		return $kodeawal . $ymd . $kd;
    }
    
    public function getDataPembelian($noresi, $username)
	{
		$this->db->where('no_faktur_pembelian', $noresi);
		$this->db->where('id_user', $username);
		$this->db->where('selesai', '0');
		return $this->db->get('tabel_pembelian');
    }

    public function getListPembelian($noresi)
	{
		return $this->db->query("SELECT * FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='$noresi' ORDER BY id");
	}
	
	public function getSatuan() {
		$this->db->order_by('nm_satuan');
		return $this->db->get('tabel_satuan_barang');
	}
    
    public function getTotalBelanja($noresi)
	{
		return $this->db->query("SELECT SUM(sub_total_beli) AS tot_bel FROM tabel_rinci_pembelian WHERE no_faktur_pembelian='$noresi'");
    }
    
    public function cari_nama($nm_barang)
	{
		$this->db->like('nm_barang', $nm_barang, 'both');
		$this->db->order_by('kd_barang', 'ASC');
		$this->db->limit(20);
		return $this->db->get('tabel_barang')->result();
    }
    
    public function getbarang($idbarang)
	{
		$this->db->where('kd_barang', $idbarang);
		return $this->db->get('tabel_barang');
    }
    
    public function cek_sudah_ada($idbarang, $nofaktur)
	{
		return $this->db->query("SELECT * FROM tabel_rinci_pembelian WHERE kd_barang='$idbarang' AND no_faktur_pembelian='$nofaktur'");
	}

	public function cek_jumlah_stok($idbarang)
	{
		return $this->db->query("SELECT MIN(tabel_stok_toko.stok) AS stok FROM tabel_stok_toko WHERE tabel_stok_toko.kd_barang='$idbarang'");
    }
    
    public function transaksiPending($id_user, $now, $before)
	{
		return $this->db->query("SELECT * FROM tabel_pembelian WHERE selesai='0' AND id_user='$id_user' AND tgl_pembelian BETWEEN '" . $before . "' AND  '" . $now . "' ORDER BY no_faktur_pembelian DESC");
    }
    
    public function getPembelianSelesai($nofaktur, $id_user)
	{
		$this->db->where('no_faktur_pembelian', $nofaktur);
		$this->db->where('id_user', $id_user);
		return $this->db->get('tabel_pembelian');
    }
    
    public function getProdukDibeli($nofaktur)
	{
		$this->db->where('no_faktur_pembelian', $nofaktur);
		return $this->db->get('tabel_rinci_pembelian');
	}
    
    public function getStok($kd_barang_item)
	{
		return $this->db->query("SELECT MIN(tabel_stok_toko.stok) AS stok FROM tabel_stok_toko WHERE tabel_stok_toko.kd_barang='$kd_barang_item'");
	}

	public function getStokPorsi($kd_barang_item)
	{
		return $this->db->query("SELECT * FROM tabel_stok_toko WHERE tabel_stok_toko.kd_barang='$kd_barang_item'");
	}

    //

}
