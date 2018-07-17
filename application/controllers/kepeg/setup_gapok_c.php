<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_gapok_c extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_gapok_m', 'model'); 
	}
 
	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_kel_jab = "";
		$nama_kel_jab   = "";
		$jenis   = "";

		if($this->input->post('simpan')){
			$msg = 1;
			$id_pangkat = $this->input->post('id_pangkat');
			$gapok      = str_replace(',', '', $this->input->post('gapok'));
			$thr        = str_replace(',', '', $this->input->post('thr'));
			$this->model->simpan_gapok($id_pangkat, $gapok, $thr);
		}

		$dt = $this->model->get_data_gaji_pokok();

		$data = array(
			'page' => 'kepeg/setup_gapok_v',
			'title' => 'Setup Gaji Pokok',
			'subtitle' => 'Setup Gaji Pokok',
			'master_menu' => 'master_setup',
			'view' => 'gapok',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_kel_jab' => $kode_kel_jab,
			'nama_kel_jab' => $nama_kel_jab,
			'jenis' => $jenis,
			'get_pangkat' => $this->model->get_pangkat(),
			'post_url' => 'kepeg/setup_gapok_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_jabatan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_jab_by_id($id);
		echo json_encode($data);
	}

	function get_gapok_by_pangkat(){
		$id_pangkat = $this->input->post('id_pangkat');
		$data = $this->model->get_gapok_by_pangkat($id_pangkat);
		echo json_encode($data);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */