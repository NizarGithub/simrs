<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kode_akun_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect('../../');
	    }
	    $this->load->model('daftar_kode_akun_m','model');
	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$nomor_akun = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;
			$nama_akun  = addslashes($this->input->post('nama_akun'));
			$nomor_akun = addslashes($this->input->post('nomor_akun'));
			$deskripsi  = addslashes($this->input->post('deskripsi'));
			$kategori   = $this->input->post('kategori');

			$this->model->simpan_akun($id_klien, $nama_akun, $nomor_akun, $deskripsi, $kategori);
		} else if($this->input->post('id_hapus')){
			$msg = 2;
			$id   = $this->input->post('id_hapus');
			$this->model->hapus_akun($id);

		} else if($this->input->post('edit')){
			$msg = 1;
			$nama_akun_ed  = addslashes($this->input->post('nama_akun_ed'));
			$id_akun_ed    = $this->input->post('id_akun_ed');
			$nomor_akun_ed = addslashes($this->input->post('nomor_akun_ed'));
			$deskripsi_ed  = addslashes($this->input->post('deskripsi_ed'));
			$kategori_ed   = $this->input->post('kategori_ed');

			$nomor_akun = addslashes($this->input->post('nomor_akun_ed'));

			$this->model->edit_akun($id_akun_ed, $nama_akun_ed, $nomor_akun_ed, $deskripsi_ed, $kategori_ed);
		}

		$dt = $this->model->get_no_akun($keyword, $id_klien);

		$data =  array(
			'page' => "daftar_kode_akun_v", 
			'title' => "Daftar Kode Akuntansi", 
			'msg' => "", 
			'master' => "master_data", 
			'view' => "daftar_akun", 
			'dt' => $dt, 
			'msg' => $msg, 
			'nomor_akun' => $nomor_akun, 
			'post_url' => 'daftar_kode_akun_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cari_kode(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_no_akun($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_kode_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_kode_by_id($id);

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */