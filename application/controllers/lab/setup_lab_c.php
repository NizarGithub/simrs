<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_lab_c extends CI_Controller {

	function __construct()
	{ 
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('lab/setup_lab_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_lab = "";
		$jenis_lab = ""; 
		$biaya = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){			
			$kode_lab = addslashes($this->input->post('kode_lab'));
			$jenis_lab = addslashes($this->input->post('jenis_lab')); 
			$biaya = addslashes($this->input->post('biaya')); 
			$uraian   = addslashes($this->input->post('uraian'));

			$cek_kode = $this->model->cek_kode_lap($kode_lab);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_lab($kode_lab, $jenis_lab, $biaya, $uraian);

				$kode_lab = "";
				$jenis_lab = ""; 
				$biaya = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){
			$msg = 2;
			$id_lab 	    = $this->input->post('id_lab');
			$ed_jenis_lab   = addslashes($this->input->post('ed_jenis_lab'));
			$ed_biaya       = str_replace(',', '', $this->input->post('ed_biaya'));
			$ed_uraian      = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_lab($id_lab, $ed_jenis_lab, $ed_biaya, $ed_uraian);

		} else if($this->input->post('id_hapus')){
			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_lab($id_hapus);
		}

		$dt = $this->model->get_data_laboratorium();

		$data = array(
			'page' => 'lab/setup_lab_v',
			'title' => 'Setup Lab',
			'subtitle' => 'Setup Lab',
			'master_menu' => 'master_data',
			'view' => 'setup_lab',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_lab' => $kode_lab,
			'jenis_lab' => $jenis_lab,
			'biaya' => $biaya,
			'uraian' => $uraian,
			'post_url' => 'lab/setup_lab_c',
		);

		$this->load->view('lab/lab_home_v',$data);
	}

	function get_data_lab(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_lab_by_id($id);
		echo json_encode($data);
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */