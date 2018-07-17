<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preview_gaji_c extends CI_Controller { 

	function __construct()
	{ 
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/preview_gaji_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$bln = date('m');
		$thn = date('Y');

		if($this->input->post('cari')){
			
			$bln = $this->input->post('bulan');
			$thn = $this->input->post('tahun');


		}
		
		$dt = $this->model->get_data_tunjangan();

		$data = array(
			'page' => 'kepeg/preview_gaji_v',
			'title' => 'Preview Gaji Pegawai',
			'subtitle' => 'Preview Gaji Pegawai',
			'master_menu' => 'gaji',
			'view' => 'gaji_pegawai',
			'warning' => $warning,
			'dt' => $dt,
			'bln' => $bln,
			'tahun_aktif' => $thn,
			'msg' => $msg,
			'post_url' => 'kepeg/preview_gaji_c', 
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */