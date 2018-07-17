<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produk_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    } 
	    $this->load->model('produk_m','model');
	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$kode_produk = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;
			$kode_produk   = addslashes($this->input->post('kode_produk'));
			$nama_produk   = addslashes($this->input->post('nama_produk'));
			$satuan        = addslashes($this->input->post('satuan'));
			$deskripsi     = addslashes($this->input->post('deskripsi'));

			$this->model->simpan_produk($id_klien, $kode_produk, $nama_produk, $satuan, $deskripsi);

		} else if($this->input->post('id_hapus')){

			$msg = 2;
			$id   = $this->input->post('id_hapus');
			$this->model->hapus_produk($id);

		} else if($this->input->post('edit')){
			$msg = 1;

			$id_produk   = $this->input->post('id_produk');
			$kode_produk_ed   = addslashes($this->input->post('kode_produk_ed'));
			$nama_produk_ed   = addslashes($this->input->post('nama_produk_ed'));
			$satuan_ed        = addslashes($this->input->post('satuan_ed'));
			$deskripsi_ed     = addslashes($this->input->post('deskripsi_ed'));
			$kode_produk      = addslashes($this->input->post('kode_produk_ed'));

			$this->model->edit_produk($id_produk, $kode_produk_ed, $nama_produk_ed, $satuan_ed, $deskripsi_ed);
		}

		$dt = $this->model->get_data_produk($keyword, $id_klien);

		$data =  array(
			'page' => "produk_v", 
			'title' => "Daftar Produk", 
			'msg' => "", 
			'master' => "master_data", 
			'view' => "daftar_produk", 
			'dt' => $dt, 
			'msg' => $msg, 
			'kode_produk' => $kode_produk, 
			'post_url' => 'produk_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cari_produk(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		
		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_data_produk($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_produk_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_produk_by_id($id);

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */