<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crm_model extends CI_Model
{

    public function getUser() {
		return $this->db->get('tabel_user');
	}

    // Member
    public function getMember()
    {
        $this->db->order_by('kd_member');
        return $this->db->get('tabel_member');
    }
	public function get($kd_member)
    {
        $this->db->like('nm_member', $kd_member);
        return $this->db->get('tabel_member')->result();
    }

    public function cari_member($nm_member)
	{
		$this->db->like('nm_member', $nm_member, 'both');
		$this->db->order_by('kd_member', 'ASC');
		$this->db->limit(20); // cek limit pencarian
		return $this->db->get('tabel_member')->result();
	}

    public function get_list($kd_member)
	{
		return $this->db->select('tabel_rinci_penjualan.*')
			->where('tabel_rinci_penjualan.kd_member', $kd_member)
			->get('tabel_rinci_penjualan')
			->result();
		
	}

    public function detail_member($kd_member)
	{
		return $this->db->select('tabel_penjualan.*')
			->where('tabel_penjualan.kd_member', $kd_member)
			->where('tabel_penjualan.selesai', '1')
			->get('tabel_penjualan')
			->row();
	}



}
