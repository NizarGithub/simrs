<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jadwal_perawat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_jadwal_perawat_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('save')){
			$msg = 1;
			$perawat   = $this->input->post('id_perawat');
			$id_tim  	  = $this->input->post('id_tim');
			$hari  		  = $this->input->post('hari');
			$waktu_awal   = $this->input->post('waktu_awal');
			$waktu_akhir  = $this->input->post('waktu_akhir');

			$this->model->HapusAllJadwal();
			foreach ($perawat as $key => $id_perawat) {
				$this->model->simpanJadwal($id_perawat, $id_tim[$key], $hari[$key], $waktu_awal[$key], $waktu_akhir[$key]);
			}
		} 

		$dt = $this->model->getDataTim();
		$data = array(
			'page' => 'kepeg/setup_jadwal_perawat_v',
			'title' => 'Setup Jadwal Perawat',
			'subtitle' => 'Setup Jadwal Perawat',
			'master_menu' => 'pegawai_menu',
			'view' => 'jadwal_perawat',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/setup_jadwal_perawat_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_dep(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_dep_by_id($id);
		echo json_encode($data);
	}

	function data_kamar(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->get_data_kamar($keyword);
		echo json_encode($data);
	}

	function klik_kamar(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_kamar_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */