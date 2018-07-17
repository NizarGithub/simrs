<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_home_c extends CI_Controller {

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
			'page' => 'admum/admum_beranda_v',
			'title' => 'Administrasi Umum',
			'subtitle' => 'Administrasi Umum',
			'childtitle' => '',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */