<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_home_c extends CI_Controller {

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
			'page' => 'logistik/log_beranda_v',
			'title' => 'Logistik',
			'subtitle' => 'Logistik',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('logistik/log_home_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */