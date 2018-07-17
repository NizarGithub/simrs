<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_departemen_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_departemen_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_dep = "";
		$nama_dep = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$kode_dep = addslashes($this->input->post('kode_dep'));
			$nama_dep = addslashes($this->input->post('nama_dep')); 
			$uraian   = addslashes($this->input->post('uraian'));

			$cek_kode = $this->model->cek_kode_dep($kode_dep);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_departemen($kode_dep, $nama_dep, $uraian);

				$kode_dep = "";
				$nama_dep = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_departemen = $this->input->post('id_departemen');
			$ed_nama_dep   = addslashes($this->input->post('ed_nama_dep'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_departemen($id_departemen, $ed_nama_dep, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_departemen($id_hapus);
		}

		$dt = $this->model->get_data_departemen();

		$data = array(
			'page' => 'kepeg/setup_departemen_v',
			'title' => 'Setup Departemen',
			'subtitle' => 'Setup Departemen',
			'master_menu' => 'master_setup',
			'view' => 'setup_dep',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_dep' => $kode_dep,
			'nama_dep' => $nama_dep,
			'uraian' => $uraian,
			'post_url' => 'kepeg/setup_departemen_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_dep(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_dep_by_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */