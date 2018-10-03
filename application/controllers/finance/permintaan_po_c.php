<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_po_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/permintaan_po_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/permintaan_po_v',
			'title' => 'Permintaan PO',
			'subtitle' => 'Permintaan PO',
			'childtitle' => '',
			'master_menu' => 'logistik',
			'view' => 'permintaan_po'
		);

		$this->load->view('finance/finance_home_v',$data);
	}
	

}