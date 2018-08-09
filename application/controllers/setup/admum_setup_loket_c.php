<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_loket_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_setup_loket_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$msg = "";
		if($this->input->post('nama_loket')){
			$msg = 1;
			$nama_loket   = $this->input->post('nama_loket');
			$kode_antrian = $this->input->post('kode_antrian');
			$akses        = $this->input->post('akses');
			$id_operator  = $this->input->post('id_perawat');

			$this->model->simpan_loket($nama_loket, $kode_antrian);

			$id_loket = $this->model->getIDLoket()->ID;

			foreach ($id_operator as $key => $opr) {
				$this->model->simpanOperator($id_loket, $opr);
			}

			foreach ($akses as $key => $aks) {
				$this->model->simpanAkses($id_loket, $aks);
			}

			$this->session->set_flashdata('sukses','1');


		} else if($this->input->post('hapus')){
			$msg = 3;
			$id_hapus = $this->input->post('id_hapus');
			$this->model->hapusLoket($id_hapus);
		}

		$data = array(
			'page' => 'setup/admum_setup_loket_v',
			'title' => 'Setup Loket',
			'subtitle' => 'Setup Loket',
			'childtitle' => '',
			'master_menu' => 'antrian',
			'view' => 'setup_loket',
			'msg' => $msg,
			'dt' => $this->model->getDataLoket(),
			'dt_kode_antrian' => $this->model->getKodeAntrian(),
			'post_url' => base_url().'setup/admum_setup_loket_c',
			'url_simpan' => base_url().'setup/admum_setup_loket_c',
			'url_ubah' => base_url().'setup/admum_setup_loket_c',
			'url_hapus' => base_url().'setup/admum_setup_loket_c',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function edit($id){

		if($this->input->post('nama_loket')){
			$msg = 1;
			$nama_loket   = $this->input->post('nama_loket');
			$kode_antrian = $this->input->post('kode_antrian');
			$akses        = $this->input->post('akses');
			$id_operator  = $this->input->post('id_perawat');

			$this->model->UpdateLoket($id, $nama_loket, $kode_antrian);
			$id_loket = $id;

			$this->model->deleteAllOperator($id_loket);
			foreach ($id_operator as $key => $opr) {
				$this->model->simpanOperator($id_loket, $opr);
			}

			$this->model->deleteAllAkses($id_loket);
			foreach ($akses as $key => $aks) {
				$this->model->simpanAkses($id_loket, $aks);
			}

			$this->session->set_flashdata('ubah','1');
		    redirect('setup/admum_setup_loket_c');
		}

		$data = array(
			'page' => 'setup/admum_setup_loket_edit_v',
			'title' => 'Setup Loket',
			'subtitle' => 'Setup Loket',
			'childtitle' => '',
			'master_menu' => 'antrian',
			'view' => 'setup_loket',
			'dt' => $this->model->data_loket_id($id),
			'dt_kode_antrian' => $this->model->getKodeAntrian(),
			'dtOperator' => $this->model->data_detail_operator($id),
			'dtAkses' => $this->model->getAksesMenu($id),
			'url_simpan' => base_url().'setup/admum_setup_loket_c/edit/'.$id,
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function data_loket_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_loket_id($id);
		echo json_encode($data);
	}

	function data_detail_operator(){
		$id_loket = $this->input->post('id');
		$data['operator'] = $this->model->data_detail_operator($id_loket);
		echo json_encode($data);
	}

}