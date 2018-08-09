<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_jenis_obat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/admum_setup_jenis_obat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'apotek/admum_setup_jenis_obat_v',
			'title' => 'Setup Jenis Obat',
			'subtitle' => 'Setup Jenis Obat',
			'master_menu' => 'obat',
			'view' => 'setup_jenis_obat',
			'childtitle' => '',
			'url_simpan' => base_url().'apotek/admum_setup_jenis_obat_c/simpan',
			'url_ubah' => base_url().'apotek/admum_setup_jenis_obat_c/ubah',
			'url_hapus' => base_url().'apotek/admum_setup_jenis_obat_c/hapus',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	function get_data_jenis(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_jenis($keyword);
		echo json_encode($data);
	}

	function data_jenis_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_jenis_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$nama_jenis = $this->input->post('nama_jenis');
		$this->model->simpan($nama_jenis);

		$this->data_jenis_obat();

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/admum_setup_jenis_obat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_jenis = $this->input->post('nama_jenis_ubah');

		$this->model->ubah($id,$nama_jenis);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/admum_setup_jenis_obat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/admum_setup_jenis_obat_c');
	}

}