<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_rj_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/lap_rj_v',
			'title' => 'Laporan Pasien Harian & Bulanan',
			'subtitle' => 'Laporan Pasien Harian & Bulanan',
			'master_menu' => 'rawat_jalan',
			'view' => 'per_hari_bulan'
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

}