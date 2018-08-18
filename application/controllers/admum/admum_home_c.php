<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_beranda_v',
			'title' => 'Administrasi Umum',
			'subtitle' => 'Administrasi Umum',
			'childtitle' => '',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function get_antrian_offline(){
		$id_user = $this->input->post('id_user');
		$akses = 'admum';
		$status = 'offline';
		$data['cek'] = $this->master_model_m->cek_user_info($id_user,$akses,$status);
		$data['data'] = $this->master_model_m->getLoket($id_user, $akses, $status);
		echo json_encode($data);
	}

	function get_nomor_offline(){
		$id_kode_antrian = $this->input->post('id_kode_antrian');
		$status = 'offline';
		$id_user = $this->input->post('id_user');
		$data = $this->master_model_m->getJmlAntrian($id_kode_antrian,$status,$id_user);
		echo json_encode($data);
	}

	function get_antrian_online(){
		$id_user = $this->input->post('id_user');
		$akses = 'admum';
		$status = 'online';
		$data['cek'] = $this->master_model_m->cek_user_info($id_user,$akses,$status);
		$data['data'] = $this->master_model_m->getLoket($id_user, $akses, $status);
		echo json_encode($data);
	}

	function get_nomor_online(){
		$id_kode_antrian = $this->input->post('id_kode_antrian');
		$status = 'online';
		$id_user = $this->input->post('id_user');
		$data = $this->master_model_m->getJmlAntrian($id_kode_antrian,$status,$id_user);
		echo json_encode($data);
	}

	function next_antri(){
		$id_antrian   = $this->input->post('id_antrian');
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$tgl = date('d-m-Y');
		$status = $this->input->post('status');

		$sql = "SELECT COUNT(*) AS TOTAL FROM kepeg_antrian WHERE TGL = '$tgl' AND ID_KODE = '$id_antrian' AND STS = '$status'";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		if($total != 0){
			$s = "SELECT * FROM kepeg_antrian WHERE TGL = '$tgl' AND ID_KODE = '$id_antrian' AND STS = '$status'";
			$q = $this->db->query($s);
			$r = $q->row();
			$urut = $r->URUT+1;
			$this->master_model_m->ubahAntrian($urut,$tgl,$status);
		}else{
			$this->master_model_m->simpanAntrian($id_antrian,$kode_antrian,$jml_antrian,$tgl,$status);
		}

		echo json_encode('1');
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */