<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kategori_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/admum_setup_kategori_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/admum_setup_kategori_v',
			'title' => 'Setup Kategori Barang',
			'subtitle' => 'Setup Kategori Barang',
			'childtitle' => '',
			'master_menu' => 'master_setup',
			'view' => 'setup_kategori'
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function get_data_kategori(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_data_kategori($keyword);
		echo json_encode($data);
	}

	function data_kategori_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kategori_id($id);
		echo json_encode($data);
	}

	function cek_kategori(){
		$nama_kategori = $this->input->post('nama_kategori');
		$sql = "SELECT COUNT(*) AS TOTAL FROM log_kategori WHERE NAMA_KATEGORI = '$nama_kategori'";
		$qry = $this->db->query($sql)->row();
		$total = $qry->TOTAL;
		$msg = '';
		if($total != 0){
			$msg = 'Ada';
		}else{
			$msg = 'Tidak Ada';
		}

		echo json_encode($msg);
	}

	function simpan(){
		$nama_kategori = $this->input->post('nama_kategori');

		$this->model->simpan($nama_kategori);
		$this->insert_kode_satuan();

		$this->session->set_flashdata('sukses','1');
		redirect('finance/admum_setup_kategori_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_kategori = $this->input->post('nama_kategori_ubah');

		$this->model->ubah($id,$nama_kategori);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/admum_setup_kategori_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/admum_setup_kategori_c');
	}

}