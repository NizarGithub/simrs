<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_result_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('lab/lab_result_m', 'model');
	}  

	function index()
	{

		$msg = 0;
		$warning = 0; 
		

		if($this->input->post('simpan')){	
			// SIMPAN LAB PEMERIKSAAN 
			$msg = 1;
			$nomor_periksa = addslashes($this->input->post('nomor_periksa'));

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_pemeriksaan($id_hapus);
		}

		$dt = $this->model->getRequestLab(); 

		$data = array(
			'page' => 'lab/lab_result_v',
			'title' => 'Hasil Laboratorium',
			'subtitle' => 'Hasil Laboratorium',
			'master_menu' => 'laboratorium',
			'view' => 'lab_result',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'lab/lab_result_c',
		);

		$this->load->view('lab/lab_home_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */