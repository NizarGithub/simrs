<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_distribusi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_pasien_baru_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_distribusi_v',
			'title' => 'Distribusi',
			'subtitle' => 'Distribusi',
			'master_menu' => 'distribusi',
			'view' => 'distribusi',
			'url_simpan' => base_url().'admum/admum_pasien_baru_c/simpan'
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

}