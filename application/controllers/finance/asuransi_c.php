<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asuransi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/asr_beranda_v',
			'title' => 'Asuransi',
			'subtitle' => 'Asuransi',
			'master_menu' => 'asuransi',
			'view' => 'asuransi'
		);

		$this->load->view('finance/finance_home_v',$data);
	}

}