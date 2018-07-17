<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_range_pkp_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_range_pkp_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		if($this->input->post('simpan')){	
			$msg = 1;		
			$prosen_pkp  = $this->input->post('prosen_pkp');
			$nilai_awal  = $this->input->post('nilai_awal'); 
			$nilai_akhir = $this->input->post('nilai_akhir');

			$this->model->deleteAllPKP();
			foreach ($prosen_pkp as $key => $val) {
				if($val != "" && $nilai_awal[$key] != "" && $nilai_akhir[$key] != ""){
					$this->model->simpanPKP($val, $nilai_awal[$key], $nilai_akhir[$key]);
				}
			}
		} 

		$dt = $this->model->getRangePKP();

		$data = array(
			'page' => 'kepeg/setup_range_pkp_v',
			'title' => 'Setup Range PKP (Prosen PPH 21)',
			'subtitle' => 'Setup Range PKP (Prosen PPH 21)',
			'master_menu' => 'master_setup',
			'view' => 'setup_pkp',
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/setup_range_pkp_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */