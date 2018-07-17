<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_absen_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('absensi/data_absen_m', 'model');

        error_reporting(0);
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$alert = 0;
		
		$bln = date('m');
		$thn = date('Y');

		if($this->input->post('cari')){

			$bln = $this->input->post('bulan');
			$thn = $this->input->post('tahun');
		
		} else if($this->input->post('alasan')){
			
			$msg = 1;

			$bln = $this->input->post('bln_aktif_ed');
			$thn = $this->input->post('thn_aktif_ed');

			$id_edit 		 = $this->input->post('id_edit');
			$ed_alasan       = $this->input->post('ed_alasan');
			$ed_ket_alasan   = $this->input->post('ed_ket_alasan');
			$ed_denda        = $this->input->post('ed_denda');

			$this->model->simpan_alasan($id_edit, $ed_alasan, $ed_ket_alasan, $ed_denda);

		}

		$dt = $this->model->get_data_absensi($bln, $thn);

		$jam_masuk = $this->model->get_jam_masuk();
		$denda_only = $this->model->get_jam_denda_only();

		$data = array(
			'page' => 'absensi/data_absen_v',
			'title' => 'Data Absensi',
			'subtitle' => 'Data Absensi',
			'master_menu' => 'absen',
			'view' => 'data_absen',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'tahun_aktif' => $thn,
			'bln' => $bln,
			'post_url' => 'absensi/data_absen_c',
			'jam_masuk' => $jam_masuk,
			'denda_only' => $denda_only,
		);

		$this->load->view('absensi/absensi_master_home_v',$data);
	}

	function get_nol($val){
		if($val == 1){
			$val = '01';
		} else if($val == 2){
			$val = '02';
		} else if($val == 3){
			$val = '03';
		} else if($val == 4){
			$val = '04';
		} else if($val == 5){
			$val = '05';
		} else if($val == 6){
			$val = '06';
		} else if($val == 7){
			$val = '07';
		} else if($val == 8){
			$val = '08';
		} else if($val == 9){
			$val = '09';
		}

		return $val;
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */