<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_gaji_pegawai_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url()); 
	    }
		$this->load->model('kepeg/lap_gaji_pegawai_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$bln = date('m');
		$thn = date('Y');

		if($this->input->post('cetak')){		
			
			$format = $this->input->post('format');

			if($format == "excel"){
				$this->cetak_excel();
			} else if($format == "pdf"){

			}
		}
		

		$data = array(
			'page' => 'kepeg/lap_gaji_pegawai_v',
			'title' => 'Laporan Gaji Pegawai',
			'subtitle' => 'Laporan Gaji Pegawai',
			'master_menu' => 'laporan',
			'view' => 'lap_gaji_peg',
			'warning' => $warning,
			'bln' => $bln,
			'tahun_aktif' => $thn,
			'msg' => $msg,
			'post_url' => 'kepeg/lap_gaji_pegawai_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function cetak_excel(){
		$bulan  = $this->input->post('bulan');
		$tahun  = $this->input->post('tahun');

		$bln_txt = $this->datetostr($bulan);
		$cek_thr = $this->model->cek_thr($bulan, $tahun);
		$sts_thr = 0;

		if(count($cek_thr) > 0){
			$sts_thr = 1;
		} 

		$data = array(
			'dt_setup_gaji' => $this->model->dt_setup_gaji(),
			'dtAllPegawai' => $this->model->get_all_pegawai($bulan, $tahun),
			'bulan' => $bulan,
			'tahun' => $tahun,
			'sts_thr' => $sts_thr,
			'title' => 'LAPORAN GAJI PEGAWAI '.strtoupper($bln_txt).' <br> TAHUN '.$tahun,
			'image'	=> base_url().'picture/jtech-logo.png',
		);

		$this->load->view('kepeg/xls/laporan_gaji_xls',$data);
	}

	function datetostr($var){
		if($var == "01"){
	 		$var = "Januari";
		} else if($var == "02"){
	 		$var = "Februari";
		} else if($var == "03"){
	 		$var = "Maret";
		} else if($var == "04"){
	 		$var = "April";
		} else if($var == "05"){
	 		$var = "Mei";
		} else if($var == "06"){
	 		$var = "Juni";
		} else if($var == "07"){
	 		$var = "Juli";
		} else if($var == "08"){
	 		$var = "Agustus";
		} else if($var == "09"){
	 		$var = "September";
		} else if($var == "10"){
	 		$var = "Oktober";
		} else if($var == "11"){
	 		$var = "November";
		} else if($var == "12"){
	 		$var = "Desember";
		}
	   	return $var;
	}


}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */