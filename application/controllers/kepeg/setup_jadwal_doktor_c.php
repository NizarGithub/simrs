<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jadwal_doktor_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_jadwal_doktor_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){
			$msg = 1;
			$id_pegawai  = $this->input->post('id_pegawai');
			$id_poli     = $this->input->post('id_poli');
			$hari        = $this->input->post('hari');
			$waktu_awal  = $this->input->post('waktu_awal');
			$waktu_akhir = $this->input->post('waktu_akhir');

			$this->model->hapus_jadwal_all($id_pegawai);
			foreach ($id_poli as $key => $val) {
				$this->model->simpan_jadwal($id_pegawai, $val, $hari[$key], $waktu_awal[$key], $waktu_akhir[$key]);
			}

		} 

		$dt = $this->model->getListDoktor();

		$data = array(
			'page' => 'kepeg/setup_jadwal_doktor_v',
			'title' => 'Atur Jadwal Doktor',
			'subtitle' => 'Atur Jadwal Doktor',
			'master_menu' => 'pegawai_menu',
			'view' => 'jadwal_dokter',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'dt_poli' => $this->model->get_poli(),
			'post_url' => 'kepeg/setup_jadwal_doktor_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_doktor(){
		$where = "1=1";
		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (NIP LIKE '%$keyword%' OR NAMA LIKE '%$keyword%' OR USERNAME LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM kepeg_pegawai WHERE $where AND STATUS LIKE '%DOKTER%'
		ORDER BY ID ASC
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_jadwal_doktor(){
		$id_doktor = $this->input->post('id_doktor');
		$hari 	   = $this->input->post('hari');

		$sql = "
		SELECT a.*, b.NAMA AS POLI FROM kepeg_jadwal_dokter a
		JOIN admum_poli b ON a.ID_POLI = b.ID
		WHERE a.ID_DOKTER = $id_doktor AND a.HARI = '$hari'
		ORDER BY a.ID ASC
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */