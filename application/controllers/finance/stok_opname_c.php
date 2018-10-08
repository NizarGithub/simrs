<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_opname_c extends CI_Controller { 

	function __construct()  
	{ 
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/stok_opname_v',
			'title' => 'Stok Opname',
			'subtitle' => 'Stok Opname',
			'master_menu' => 'logistik',
			'view' => 'stok_opname'
		);

		$this->load->view('finance/finance_home_v',$data);
	} 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */