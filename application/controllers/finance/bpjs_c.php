<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bpjs_c extends CI_Controller { 

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
			'page' => 'asuransi/bpjs_beranda_v',
			'title' => 'BPJS',
			'subtitle' => 'BPJS',
			'master_menu' => 'bpjs',
			'view' => 'bpjs'
		);

		$this->load->view('asuransi/bpjs_home_v',$data);
	} 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */