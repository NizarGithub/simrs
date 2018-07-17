<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_asuransi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();  
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('asuransi/list_asuransi_m', 'model');
	} 

	function index($id)
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){
			$msg = 1;
			$no_polis 			 = $this->input->post('no_polis');
			$nama_pemegang_polis = $this->input->post('nama_pemegang_polis');
			$id_pasien			 = $this->input->post('id_pasien');
			$jml_klaim			 = str_replace(',', '', $this->input->post('jml_klaim'));

			$this->model->simpan_asuransi($id, $no_polis, $nama_pemegang_polis, $id_pasien, $jml_klaim);
		
		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_asuransi($id_hapus);
		}

		$dt_asuransi = $this->model->get_data_asuransi($id);
		$dt = $this->model->getDataPolis($id);
		$data = array(
			'page' => 'asuransi/list_asuransi_v',
			'title' => $dt_asuransi->NAMA_ASURANSI,
			'subtitle' => $dt_asuransi->NAMA_ASURANSI, 
			'master_menu' => 'asuransi',
			'view' => 'asuransi_'.$id,
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'asuransi/list_asuransi_c/index/'.$id,
		);

		$this->load->view('asuransi/asr_home_v',$data);
	}

	function get_data_asuransi(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_asr_by_id($id);
		echo json_encode($data);
	}

	function data_pasien(){
		$keyword = $this->input->post('keyword');
		$dt = $this->model->getDataPasien($keyword);
		echo json_encode($dt);		
	}

	function get_data_pasien_by_id(){
		$id_pasien = $this->input->post('id_pasien');
		$dt = $this->model->get_data_pasien_by_id($id_pasien);
		echo json_encode($dt);	
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */