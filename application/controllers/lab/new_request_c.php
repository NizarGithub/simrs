<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_request_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('lab/new_request_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0; 
		

		if($this->input->post('simpan')){	
			// SIMPAN LAB PEMERIKSAAN 
			$msg = 1;
			$nomor_periksa = addslashes($this->input->post('nomor_periksa'));
			$kode_pasien = addslashes($this->input->post('kode_pasien'));
			$jml_biaya   = str_replace(',', '', $this->input->post('jml_biaya'));
			$sts = 0;

			$this->model->simpan_pemeriksaan($nomor_periksa, $kode_pasien, $jml_biaya, $sts);
			$id_pemeriksaan = $this->model->getIDPemeriksaan($nomor_periksa)->ID;

			// SIMPAN LAB PEMERIKSAAN DETAIL

			$id_lab    = $this->input->post('id_lab');
			$biaya     = $this->input->post('biaya');
			$jenis_lab = $this->input->post('jenis_lab');
			$catatan   = $this->input->post('catatan');

			foreach ($id_lab as $key => $val) {
				$this->model->simpan_pemeriksaan_detail($id_pemeriksaan, $kode_pasien, $val, $biaya[$key], $jenis_lab[$key], $catatan[$key]);
			}

			$bln = date('m');	
		    $thn = date('Y');
			$this->model->update_nomor_lab($bln, $thn);
		}

		$dt = ""; 
		$nomor = $this->getNomorLab();
		$nomor = str_pad($nomor, 4, '0', STR_PAD_LEFT);
		$nomor_lab = "LAB-".$nomor.".".date('m').".".date('y');

		$data = array(
			'page' => 'lab/new_request_v',
			'title' => 'Permintaan Baru',
			'subtitle' => 'Permintaan Baru',
			'master_menu' => 'laboratorium',
			'view' => 'new_request',
			'warning' => $warning,
			'dt' => $dt,
			'dt_jenis_lab' => $this->model->getJenisLab(),
			'msg' => $msg,
			'nomor' => $nomor_lab,
			'post_url' => 'lab/new_request_c',
		);

		$this->load->view('lab/lab_home_v',$data);
	}

	function ajax_rm(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->ajax_rm($keyword);
		echo json_encode($data);
	}

	function get_data_rm(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_rm_by_id($id);
		echo json_encode($data);
	}

	function get_data_lab(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_lab_by_id($id);
		echo json_encode($data);
	}

	function getNomorLab(){
		$bln = date('m');	
		$thn = date('Y');
		
		$cek_nomor = $this->model->cek_nomor_lab($bln, $thn);
		if(count($cek_nomor) == 0){
			$this->model->save_nomor_lab($bln, $thn);
		}

		$nomor = $this->model->get_nomor_lab($bln, $thn)->NOMOR;
		return $nomor;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */