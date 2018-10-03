<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_permintaan_po_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('admum/admum_permintaan_po_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$sess_lock = $this->session->userdata('lock');
    	$id_user = $sess_user['id'];
    	$id_lock = $sess_lock['id_user'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_permintaan_po_v', 
			'title' => 'Permintaan PO',
			'subtitle' => 'Permintaan PO',
			'childtitle' => 'Umum',
			'master_menu' => 'permintaan_po',
			'view' => 'permintaan_po'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function get_kode_po(){
		
	}

}