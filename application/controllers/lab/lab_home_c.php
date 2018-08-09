<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('lab/lab_home_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'lab/lab_beranda_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Laboratorium',
			'master_menu' => 'home',
			'view' => '',
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

	function data_pasien(){
		$now = date('d-m-Y');
		$keyword = $this->input->get('keyword');
		$posisi = '2';

		$data = $this->model->data_pasien($keyword,$posisi,$now);
		echo json_encode($data);
	}

	function terima_pasien(){
		$id = $this->input->post('id');
		$this->model->terima_pasien($id);
		echo '1';
	}

	function data_pasien_terima(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
        $user = $this->master_model_m->get_user_info($id_user);
        $level = $user->LEVEL;
    	$id_divisi = $sess_user['id_divisi']; //ID LABORAT
		$keyword = $this->input->get('keyword');
		$posisi = '2';
		$now = date('d-m-Y');

		$data = $this->model->data_pasien_terima($keyword,$posisi,$now,$id_divisi,$level);
		echo json_encode($data);
	}

	function data_pasien_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_pasien_id($id);
		echo json_encode($data);
	}

	function tindakan($id){
		$data = array(
			'page' => 'lab/lab_tindakan_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Tindakan Laborat',
			'master_menu' => 'home',
			'view' => '',
			'dt' => $this->model->data_rawat_jalan_id($id)
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

	function add_leading_zero($value, $threshold = 3) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode_lab(){
		$keterangan = 'SIP-LABORAT';
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "2016".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "2016".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_lab(){
	    $keterangan = 'SIP-LABORAT';
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function load_laborat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_laborat($keyword);
		echo json_encode($data);
	}

	function klik_laborat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_laborat($id);
		echo json_encode($data);
	}

}