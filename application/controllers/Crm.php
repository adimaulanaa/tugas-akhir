<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crm extends CI_Controller {

	function __construct() {
		parent::__construct();
		//validasi jika user belum login
		if ($this->session->userdata('masuk') != TRUE) {
			$url = base_url();
			redirect($url);
		}
		$this->load->model('crm_model');
		$this->load->helper('random');
	}

	public function profile_member()   
    {
        $data['member'] = $this->crm_model->getMember();
        $this->load->view('template/header', $data, FALSE);
        $this->load->view('vcrm/member/profile_member');
        $this->load->view('template/footer');
    }

	function get_automember()
	{
		if (isset($_GET['term'])) {
			$result = $this->crm_model->cari_member($_GET['term']);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$arr_result[] = array(
						'label' => $row->nm_member,
						'kode' => $row->kd_member,
					);
				}
				echo json_encode($arr_result);
			}
		}
	}

	public function get_data_member()
	{
		$kd_member = $this->input->post('kd_member');
		$data['grandtotal'] = 0;
		$data['qty'] = 0;
		$data['member1'] = $this->crm_model->get($kd_member);
		$data['list'] = $this->crm_model->get_list($kd_member);
		$data['member'] = $this->crm_model->detail_member($kd_member);
		$hasil = $this->load->view('vcrm/member/list_pembelian', $data, true);
		$callback = array(
			'hasil' => $hasil,
		);
		echo json_encode($callback);
	}




}
