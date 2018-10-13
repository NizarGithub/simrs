<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bantuan_dewe extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data = array(
			'page' => 'bantuan_dewe_v',
			'title' => 'Pendaftaran Rawat Jalan',
			'subtitle' => 'Pasien Baru',
			'childtitle' => 'Rawat Jalan',
			'master_menu' => 'pasien',
			'view' => 'pasien_rj'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function query_pasien(){
		$sql = "
			SELECT 
				ID,
				KODE_PASIEN,
				NAMA,
				JENIS_KELAMIN,
				TANGGAL_DAFTAR,
				TANGGAL_LAHIR,
				UMUR,
				IFNULL(UMUR_BULAN,0) AS UMUR_BULAN,
				NAMA_AYAH,
				NAMA_IBU,
				ALAMAT,
				SUBSTR(KODE_PASIEN,4,3) AS KODE,
				SUBSTR(TANGGAL_DAFTAR,4,2) AS BULAN
			FROM rk_pasien 
			WHERE ID BETWEEN 55001 AND 60000
			ORDER BY ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function load_data_pasien(){
		$data = $this->query_pasien();
		echo json_encode($data);
	}

	function query_ubah($id,$tanggal_daftar,$tanggal_lahir){
		$sql = "
			UPDATE rk_pasien SET 
				TANGGAL_DAFTAR = '$tanggal_daftar', 
				TANGGAL_LAHIR = '$tanggal_lahir'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function ubah(){
		$id = $this->input->post('id');
		$tanggal_daftar = $this->input->post('tanggal_daftar');
		$tanggal_lahir = $this->input->post('tanggal_lahir');

		foreach ($id as $key => $value) {
			$this->query_ubah($value,$tanggal_daftar[$key],$tanggal_lahir[$key]);
		}

		echo '1';
	}

}