<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_laporan_bulanan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('lab/lab_laporan_bulanan_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'lab/lab_laporan_bulanan_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Laboratorium',
			'master_menu' => 'laporan',
			'view' => 'laporan',
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

	function load_laborat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_laborat($keyword);
		echo json_encode($data);
	}

	function klik_laborat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_laborat($id);
		echo json_encode($data);
	}

	function cetak(){
		$laporan = $this->input->post('laporan');
		if($laporan == 'pdf'){
			$this->cetak_pdf();
		}else{
			$this->cetak_excel();
		}
	}

	function cetak_pdf(){
		$filter = $this->input->post('filter');
		$id_lab = $this->input->post('id_lab');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$settitle = '';

		if($filter == 'Semua'){
			$settitle = 'Laporan Bulanan Semua Jenis Lab';
		}else{
			$settitle = 'Laporan Bulanan Per Jenis Lab';
		}

		$data = array(
			'settitle' => $settitle,
			'filename' => date('dmY').'_laporan_bulanan_laborat',
			'bulan' => $bulan,
			'tahun' => $tahun,
			'dt' => $this->model->get_jenis_lab_bulanan($bulan,$tahun,$filter,$id_lab)
		);

		$this->load->view('lab/pdf/cetak_laporan_bulanan_pdf',$data);
	}

	function cetak_excel(){

	}

}