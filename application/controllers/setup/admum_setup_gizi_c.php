<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_gizi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_setup_gizi_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_setup_gizi_v',
			'title' => 'Setup Gizi',
			'subtitle' => 'Setup Gizi',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'gizi',
			'url_simpan' => base_url().'setup/admum_setup_gizi_c/simpan',
			'url_ubah' => base_url().'setup/admum_setup_gizi_c/ubah',
			'url_hapus' => base_url().'setup/admum_setup_gizi_c/hapus',
			'url_cetak' => base_url().'setup/admum_setup_gizi_c/cetak_excel',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_gizi(''),
		);

		$this->load->view('setup/excel/excel_data_gizi_xls',$data);
	}


	function data_gizi(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_gizi($keyword);
		echo json_encode($data);
	}

	function data_gizi_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_gizi_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode = addslashes(strtoupper($this->input->post('kode')));
		$gizi = addslashes($this->input->post('gizi'));
		$tarif = str_replace(',', '', $this->input->post('tarif'));

		$this->model->simpan($kode,$gizi,$tarif);

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_setup_gizi_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$kode = addslashes(strtoupper($this->input->post('kode_ubah')));
		$gizi = addslashes($this->input->post('gizi_ubah'));
		$tarif = str_replace(',', '', $this->input->post('tarif_ubah'));

		$this->model->ubah($id,$kode,$gizi,$tarif);

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_setup_gizi_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('setup/admum_setup_gizi_c');
	}

}