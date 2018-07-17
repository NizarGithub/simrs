<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_home_c extends CI_Controller {

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
			'page' => 'lab/lab_beranda_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Laboratorium',
			'master_menu' => 'home',
			'view' => '',
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

}