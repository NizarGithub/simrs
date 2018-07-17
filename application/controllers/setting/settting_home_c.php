<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settting_home_c extends CI_Controller { 

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
			'page' => 'setting/setting_home_v',
			'title' => 'Pengaturan',
			'subtitle' => 'Pengaturan',
			'master_menu' => 'home',
			'view' => 'home',
			'msg' => '',
		);

		$this->load->view('setting/setting_master_home_v',$data);
	} 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */