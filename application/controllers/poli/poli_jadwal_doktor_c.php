<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poli_jadwal_doktor_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('poli/poli_jadwal_doktor_m', 'model');
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
			'page' => 'poli/poli_jadwal_doktor_v',
			'title' => 'Atur Jadwal Doktor',
			'subtitle' => 'Atur Jadwal Doktor',
			'master_menu' => 'pegawai_menu',
			'view' => 'jadwal_dokter',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'dt_poli' => $this->model->get_poli(),
			'post_url' => 'poli/poli_jadwal_doktor_c',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

	function get_doktor(){
		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_dokter($keyword);
		echo json_encode($dt);
	}

	function get_jadwal_doktor(){
		$id_doktor = $this->input->post('id_doktor');
		$hari = $this->input->post('hari');

		$dt = $this->model-get_jadwal_dokter($id_dokter,$hari);

		echo json_encode($dt);
	}

	function get_data_pegawai(){ 
		$id = $this->input->post('id');

		$sql = "
		SELECT * FROM kepeg_pegawai WHERE ID = $id
		";

		$dt = $this->db->query($sql)->row();

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */