<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_gaji_pegawai_pph_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/lap_gaji_pegawai_pph_m', 'model'); 
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
			'page' => 'kepeg/lap_gaji_pegawai_pph_v',
			'title' => 'Laporan Gaji Pegawai + Pajak',
			'subtitle' => 'Laporan Gaji Pegawai + Pajak',
			'master_menu' => 'laporan',
			'view' => 'lap_gaji_peg_pph',
			'warning' => $warning,
			'bln' => $bln,
			'tahun_aktif' => $thn,
			'msg' => $msg,
			'post_url' => 'kepeg/lap_gaji_pegawai_pph_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function cetak_excel(){
		$tahun  = $this->input->post('tahun');

		$data = array(
			'dt_PKPRange' => $this->model->dt_PKPRange(),
			'dtAllPegawai' => $this->model->get_all_pegawai($tahun),
			'tahun' => $tahun,
			'title' => 'LAPORAN GAJI PEGAWAI <br> TAHUN '.$tahun,
			'image'	=> base_url().'picture/jtech-logo.png',
		);

		$this->load->view('kepeg/xls/laporan_gaji_pegawai_pajak_xls',$data);
	}

}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */