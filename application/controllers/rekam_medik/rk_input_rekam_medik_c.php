<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_input_rekam_medik_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('rekam_medik/rk_input_rekam_medik_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_input_rekam_medik_v',
			'title' => 'Input Rekam Medik',
			'subtitle' => 'Input Rekam Medik',
			'master_menu' => 'input_rekam_medik',
			'view' => 'input_rekam_medik', 
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode(){
		$keterangan = 'INPUT-REKAM-MEDIK';

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//280196
		if($total == 0){
			$no = $this->add_leading_zero(1,2);
			$kode = "28".$no."96";
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,2);
			$kode = "28".$no."96";
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'INPUT-REKAM-MEDIK';
	    $bulan = date('n');
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,BULAN,TAHUN) VALUES ('1','$keterangan','$bulan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function load_data_pasien(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_data_pasien($keyword);
		echo json_encode($data);
	}

	function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien($id);
		echo json_encode($data);
	}

	function load_data_poli(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_data_poli($keyword);
		echo json_encode($data);
	}

	function klik_poli(){
		$id = $this->input->post('id');
		$data = $this->model->klik_poli($id);
		echo json_encode($data);
	}

	function simpan(){
		$no_rekam_medik = $this->input->post('no_rekam_medik');
		$id_pasien = $this->input->post('id_pasien');
		$sakit = $this->input->post('sakit');
		$id_jenis_penyakit = $this->input->post('id_jenis_penyakit');
		$tingkatan = $this->input->post('tingkatan');
		$tanggal = $this->input->post('tanggal');
		$status_operasi = $this->input->post('pilihan');
		$nama_wali = $this->input->post('nama_wali');

		$this->model->simpan($no_rekam_medik,$id_pasien,$sakit,$id_jenis_penyakit,$tingkatan,$tanggal,$status_operasi,$nama_wali);

		echo '1';
	}

	function data_jenis_penyakit(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_jenis_penyakit($keyword);
		echo json_encode($data);
	}

	function data_jenis_penyakit_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_jenis_penyakit_id($id);
		echo json_encode($data);
	}

	function get_data_rekam_medik(){
		$data = $this->model->get_data_rekam_medik();
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */