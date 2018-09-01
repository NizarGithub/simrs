<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('admum/admum_home_m','model');
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

	//PASIEN UMUM
	function data_pasien(){
		$id_klien = "";
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$pilih_umur = $this->input->get('pilih_umur');
		$pilih_status = $this->input->get('pilih_status');
		$now = $this->input->get('now');

		$data = $this->model->data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$pilih_status,$now);
		echo json_encode($data);
	}

	function data_pasien_id(){
		$id_klien = "";
		$id = $this->input->post('id');
		$data = $this->model->data_pasien_id($id,$id_klien);
		echo json_encode($data);
	}

	function get_history_medik(){
		$id_pasien = $this->input->post('id_pasien');
		$data = array();
		$data['detail_RJ'] = $this->model->getDetailLayananRJ($id_pasien, '');
		$data['detail_IGD'] = $this->model->getDetailLayananIGD($id_pasien, '');

		$data['detail_RI'] = $this->model->getDetailLayananRI($id_pasien, '');
		$data['dataDetVisite_RI'] = $this->model->dataDetVisite_RI($id_pasien, '');
		$data['dataDetGizi_RI'] = $this->model->dataDetGizi_RI($id_pasien, '');
		$data['dataDetOksigen_RI'] = $this->model->dataDetOksigen_RI($id_pasien, '');
		$data['dataDetDiagnosa_RI'] = $this->model->dataDetDiagnosa_RI($id_pasien, '');
		$data['dataDetResep_RI'] = $this->model->dataDetResep_RI($id_pasien, '');

		echo json_encode($data);
	}

	function get_history_medik_by_search_rj(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_RJ'] = $this->model->getDetailLayananRJ($id_pasien, $tgl);
		echo json_encode($data);
	}

	function get_history_medik_by_search_igd(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_IGD'] = $this->model->getDetailLayananIGD($id_pasien, $tgl);
		echo json_encode($data);
	}

	function get_history_medik_by_search_ri(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_RI'] = $this->model->getDetailLayananRI($id_pasien, $tgl);
		$data['dataDetVisite_RI'] = $this->model->dataDetVisite_RI($id_pasien, $tgl);
		$data['dataDetGizi_RI'] = $this->model->dataDetGizi_RI($id_pasien, $tgl);
		$data['dataDetOksigen_RI'] = $this->model->dataDetOksigen_RI($id_pasien, $tgl);
		$data['dataDetDiagnosa_RI'] = $this->model->dataDetDiagnosa_RI($id_pasien, $tgl);
		$data['dataDetResep_RI'] = $this->model->dataDetResep_RI($id_pasien, $tgl);

		echo json_encode($data);
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */