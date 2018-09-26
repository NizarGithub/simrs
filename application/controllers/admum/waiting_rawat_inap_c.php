<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Waiting_rawat_inap_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('admum/waiting_rawat_inap_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/waiting_rawat_inap_v',
			'title' => 'Waiting Rawat Inap',
			'subtitle' => 'Waiting Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'waiting_rawat_inap',
			'view' => 'waiting_rawat_inap'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function get_data_pasien_poli(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_data_pasien_poli($keyword);
		echo json_encode($data);
	}

	function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien($id);
		echo json_encode($data);
	}

}