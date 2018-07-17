<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pengembalian_rj_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('rekam_medik/rk_pengembalian_rj_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_pengembalian_rj_v',
			'title' => 'Pengembalian Rawat Jalan',
			'subtitle' => 'Pengembalian Rawat Jalan',
			'master_menu' => 'pengembalian',
			'view' => 'pengembalian_rj',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function data_pasien_kembali(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pasien_kembali($keyword);
		echo json_encode($data);
	}

}