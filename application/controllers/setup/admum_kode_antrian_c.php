<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_kode_antrian_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_kode_antrian_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$msg = "";
		if($this->input->post('kode_antrian')){
			$msg = 1;
			$kode = $this->input->post('kode_antrian');
			$untuk = $this->input->post('untuk');
			$antrian_max = $this->input->post('antrian_max');

			$this->model->simpan_antrian($kode, $untuk, $antrian_max);

		} else if($this->input->post('hapus')){
			$msg = 3;
			$id_hapus = $this->input->post('id_hapus');
			$this->model->hapusAntrian($id_hapus);
		}

		$data = array(
			'page' => 'setup/admum_kode_antrian_v',
			'title' => 'Setup Kode Antrian',
			'subtitle' => 'Setup Kode Antrian',
			'childtitle' => '',
			'master_menu' => 'antrian',
			'view' => 'kode_antrian',
			'msg' => $msg,
			'post_url' => base_url().'setup/admum_kode_antrian_c',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function data_antrian(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_antrian($keyword);
		echo json_encode($data);
	}

	function cek_kode_antrian(){
		$val = $this->input->post('val');
		$data = $this->model->cek_kode_antrian($val);
		echo json_encode(count($data));
	}

}