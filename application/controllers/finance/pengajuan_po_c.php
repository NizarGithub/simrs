<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan_po_c extends CI_Controller { 

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
			'page' => 'finance/pengajuan_po_v',
			'title' => 'Pengajuan PO',
			'subtitle' => 'Pengajuan PO',
			'master_menu' => 'logistik',
			'view' => 'pengajuan_po'
		);

		$this->load->view('finance/finance_home_v',$data);
	} 

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */