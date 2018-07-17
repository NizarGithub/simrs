<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jadwal_thr_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_jadwal_thr_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$thn = date('Y');

		if($this->input->post('simpan')){
			$msg     = 1;			
			$thn   = $this->input->post('tahun');
			$tgl_thr = $this->input->post('tgl_thr'); 

			$this->model->simpanTHR($thn, $tgl_thr);
		} 

		$dt = $this->model->get_setup_thr($thn);
		$dt2 = $this->model->get_setup_thrAll();

		$data = array(
			'page' => 'kepeg/setup_jadwal_thr_v',
			'title' => 'Setup Jadwal THR',
			'subtitle' => 'Setup Jadwal THR',
			'master_menu' => 'master_setup',
			'view' => 'setup_thr',
			'dt' => $dt,
			'dt2' => $dt2,
			'thn' => $thn,
			'msg' => $msg,
			'post_url' => 'kepeg/setup_jadwal_thr_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_tgl(){
		$tahun = $this->input->post('tahun');
		$data = $this->model->get_setup_thr($tahun);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */