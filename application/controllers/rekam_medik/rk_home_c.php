<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_beranda_v',
			'title' => 'Rekam Medik',
			'subtitle' => 'Rekam Medik',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function next_antri(){
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$id_antrian   = $this->input->post('id_antrian');
		$tgl = date('d-m-Y');

		$sql = "INSERT INTO kepeg_antrian(ID_KODE,KODE,URUT,TGL) VALUES ('$id_antrian','$kode_antrian','$jml_antrian','$tgl')";
		$this->db->query($sql);

		echo json_encode(1);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */