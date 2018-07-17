<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kenaikan_pangkat_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/kenaikan_pangkat_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$bln = date('m');
		$thn = date('Y');

		if($this->input->post('simpan')){
			$msg = 1;
			$id_pegawai 	   = $this->input->post('id_pegawai');
			$id_pangkat_skrg   = $this->input->post('id_pangkat_skrg');
			$id_pangkat_baru   = $this->input->post('id_pangkat_baru');
			$no_sk 			   = $this->input->post('no_sk');
			$tgl_sk 		   = $this->input->post('tgl_sk');
			$tgl_awal_pangkat  = $this->input->post('tgl_awal_pangkat');
			$tgl_akhir_pangkat = $this->input->post('tgl_akhir_pangkat');

			$dtPeg = $this->model->get_peg_detail($id_pegawai);
			$this->model->simpanHistory($id_pegawai, $id_pangkat_skrg, $dtPeg->SK_PANGKAT, $dtPeg->TGL_SK_PANGKAT, $dtPeg->TGL_AWAL_PANGKAT, $dtPeg->TGL_AKHIR_PANGKAT);		
			$this->model->update_pangkat($id_pegawai, $id_pangkat_baru, $no_sk, $tgl_sk, $tgl_awal_pangkat, $tgl_akhir_pangkat);
			
		}
		
		$dt = "";

		$data = array(
			'page' => 'kepeg/kenaikan_pangkat_v',
			'title' => 'Kenaikan Pangkat Pegawai',
			'subtitle' => 'Kenaikan Pangkat Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'naik_pangkat',
			'warning' => $warning,
			'dt' => $dt,
			'bln' => $bln,
			'tahun_aktif' => $thn,
			'msg' => $msg,
			'post_url' => 'kepeg/kenaikan_pangkat_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_detail_gaji(){
		$id_pegawai = $this->input->post('id_pegawai');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->get_gaji_pegawai_detail($id_pegawai, $bulan, $tahun);

		echo json_encode($data);
	}

	function peg_detail(){
		$id_peg = $this->input->post('id_peg');
		$data = $this->model->get_peg_detail($id_peg);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */