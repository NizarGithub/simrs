<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_jenis_laborat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_setup_jenis_laborat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_setup_jenis_laborat_v',
			'title' => 'Setup Jenis Laborat',
			'subtitle' => 'Setup Jenis Laborat',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'jenis_laborat',
			'url_simpan' => base_url().'setup/admum_setup_jenis_laborat_c/simpan',
			'url_ubah' => base_url().'setup/admum_setup_jenis_laborat_c/ubah',
			'url_hapus' => base_url().'setup/admum_setup_jenis_laborat_c/hapus',
			'url_cetak' => base_url().'setup/admum_setup_jenis_laborat_c/cetak_excel',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_jenis_laborat(''),
		);

		$this->load->view('setup/excel/excel_jenis_laborat_xls',$data);
	}

	function data_jenis_laborat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_jenis_laborat($keyword);
		echo json_encode($data);
	}

	function data_jenis_laborat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_jenis_laborat_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$jenis_laborat = addslashes($this->input->post('jenis_laborat'));
		$this->model->simpan($jenis_laborat);

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_setup_jenis_laborat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$jenis_laborat = addslashes($this->input->post('jenis_laborat_ubah'));

		$this->model->ubah($id,$jenis_laborat);

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_setup_jenis_laborat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('setup/admum_setup_jenis_laborat_c');
	} 

}