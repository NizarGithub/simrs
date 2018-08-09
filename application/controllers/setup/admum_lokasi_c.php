<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_lokasi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_lokasi_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_lokasi_v', 
			'title' => 'Setup Default Lokasi',
			'subtitle' => 'Setup Default Lokasi',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'lokasi',
			'url_simpan' => base_url().'setup/admum_lokasi_c/simpan',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function data_provinsi(){
		$id_kota_kab = $this->input->post('id_kota_kab');
		$data = $this->model->provinsi($id_kota_kab);
		echo json_encode($data);
	}

	function simpan(){
		$id_kota_kab = $this->input->post('id_kota');
		$id_provinsi = $this->input->post('id_provinsi');

		$this->db->query("TRUNCATE TABLE admum_lokasi");
		$this->model->simpan($id_kota_kab,$id_provinsi);

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_lokasi_c');
	}

}