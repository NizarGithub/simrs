<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('rekam_medik/rk_input_rekam_medik_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_beranda_v',
			'title' => 'Rekam Medik',
			'subtitle' => 'Rekam Medik',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function notif_pasien_baru(){
		$now = date('d-m-Y');
		$data = $this->model->get_notif_pasien_baru($now);
		echo json_encode($data);
	}

	function get_data_rm(){
		$keyword = $this->input->get('keyword');
		$now = date('d-m-Y');

		$data = $this->model->get_data_rm($keyword,$now);
		echo json_encode($data);
	}

	function klik_approve(){
		$id = $this->input->post('id');
		$sts = $this->input->post('sts');
		if($sts == '1'){
			$this->model->ubah_sts_approve_rj($id);
		}else{
			$this->model->ubah_sts_approve_ri($id);
		}
		echo '1';
	}

	function dilihat(){
		$this->db->query("UPDATE admum_rawat_jalan SET STS_LIHAT = '1' WHERE STS_LIHAT = '0'");
		echo '1';
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */