<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_home_c extends CI_Controller {

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
			'page' => 'apotek/ap_beranda_v',
			'title' => 'Apotek',
			'subtitle' => 'Apotek',
			'master_menu' => '',
			'view' => '',
			'url_simpan' => base_url().'apotek/log_obat_c/simpan',
			'url_ubah' => base_url().'apotek/log_obat_c/ubah',
			'url_hapus' => base_url().'apotek/log_obat_c/hapus',
		);

		$this->load->view('apotek/ap_home_v',$data); 
	}

}