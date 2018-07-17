<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kategori_akun_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect('../../');
	    }
	    $this->load->model('daftar_kategori_akun_m','model');
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
			$nama_kat     = addslashes($this->input->post('nama_kat'));
			$deskripsi    = addslashes($this->input->post('deskripsi'));

			$this->model->simpan_kat($id_klien, $nama_kat, $deskripsi);

		} else if($this->input->post('id_hapus')){

			$msg = 2;
			$id   = $this->input->post('id_hapus');
			$this->model->hapus_kategori($id);

		} else if($this->input->post('edit')){
			$msg = 1;

			$id_kat   = $this->input->post('id_kat');
			$nama_kat_ed   = addslashes($this->input->post('nama_kat_ed'));
			$deskripsi_ed   = addslashes($this->input->post('deskripsi_ed'));

			$this->model->edit_kat($id_kat, $nama_kat_ed, $deskripsi_ed);
		}

		$dt = $this->model->get_data_kategori($keyword, $id_klien);

		$data =  array(
			'page' => "daftar_kategori_akun_v", 
			'title' => "Daftar Kategori Akun", 
			'msg' => "", 
			'master' => "master_data", 
			'view' => "daftar_kat_akun", 
			'dt' => $dt, 
			'msg' => $msg, 
			'kode_produk' => $kode_produk, 
			'post_url' => 'daftar_kategori_akun_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cari_kat(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		
		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_data_kategori($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_kat_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_kat_by_id($id);

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */