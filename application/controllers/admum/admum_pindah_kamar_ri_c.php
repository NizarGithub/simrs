<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pindah_kamar_ri_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_pindah_kamar_ri_v',
			'title' => 'Pindah Kamar Rawat Inap',
			'subtitle' => 'Pindah Kamar Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'pindah_kamar',
			'view' => 'pindah_kamar'
		);

		$this->load->view('admum/admum_home_v',$data);
	}


}