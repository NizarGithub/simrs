<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_indeks_catatan_medis extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('rekam_medik/rk_indeks_catatan_medis_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_indeks_catatan_medis_v',
			'title' => 'Indeks Catatan Medis',
			'subtitle' => 'Indeks Catatan Medis',
			'master_menu' => 'indeks_catatan_medis',
			'view' => 'indeks_catatan_medis',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function data_rawat_jalan(){
		$tanggal = $this->input->post('tanggal');
		$data = $this->model->data_rawat_jalan($tanggal);
		echo json_encode($data);
	}

	function data_rawat_inap(){
		$tanggal = $this->input->post('tanggal');
		$data = $this->model->data_rawat_inap($tanggal);
		echo json_encode($data);
	}

	function data_igd(){
		$tanggal = $this->input->post('tanggal');
		$data = $this->model->data_igd($tanggal);
		echo json_encode($data);
	}

	function icd_rawat_jalan($id){
		$data = array(
			'page' => 'rekam_medik/rk_icd_rj_v',
			'title' => 'ICD',
			'subtitle' => 'ICD',
			'master_menu' => 'indeks_catatan_medis',
			'view' => 'indeks_catatan_medis',
			'dt' => $this->model->data_rawat_jalan_id($id),
			'id' => $id,
			'url_ubah' => base_url().'rekam_medik/rk_indeks_catatan_medis/ubah_tindakan',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	//TINDAKAN

	function load_tindakan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_tindakan($keyword);
		echo json_encode($data);
	}

	function klik_tindakan(){
		$id = $this->input->post('id');
		$data = $this->model->klik_tindakan($id);
		echo json_encode($data);
	}

	function data_tindakan(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_tindakan($id_pelayanan);
		echo json_encode($data);
	}

	function data_tindakan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_tindakan_id($id);
		echo json_encode($data);
	}

	function tindakan_id(){
		$id = $this->input->post('id');
		$data = $this->model->tindakan_id($id);
		echo json_encode($data);
	}

	function ubah_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_ubah');
		$tindakan = $this->input->post('id_tindakan_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$subtotal = str_replace(',', '', $this->input->post('subtotal_ubah'));

		$this->model->ubah_tindakan($id,$tindakan,$jumlah,$subtotal);

		$this->session->set_flashdata('ubah','1');
		redirect('rekam_medik/rk_indeks_catatan_medis/icd_rawat_jalan/'.$id_pelayanan);
	}

	//DIAGNOSA

	function data_kasus(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_kasus($keyword);
		echo json_encode($data);
	}

	function data_kasus_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kasus_id($id);
		echo json_encode($data);
	}

	function data_spesialistik(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_spesialistik($keyword);
		echo json_encode($data);
	}

	function data_spesialistik_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_spesialistik_id($id);
		echo json_encode($data);
	}

	function data_diagnosa(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_diagnosa($id_pelayanan);
		echo json_encode($data);
	}

	function data_diagnosa_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_diagnosa_id($id,$id_pelayanan);
		echo json_encode($data);
	}

	function ubah_diagnosa(){
		$id = $this->input->post('id_ubah_dg');
		$diagnosa = $this->input->post('diagnosa_ubah');
		$tindakan = $this->input->post('tindakan_dg_ubah');
		$kasus = $this->input->post('id_kasus_ubah');
		$spesialistik = $this->input->post('id_spesialistik_ubah');

		$this->model->ubah_diagnosa($id,$diagnosa,$tindakan,$kasus,$spesialistik);

		echo '1';
	}

}