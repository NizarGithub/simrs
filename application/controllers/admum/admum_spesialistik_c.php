<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_spesialistik_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_spesialistik_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_spesialistik_v',
			'title' => 'Setup Spesialistik',
			'subtitle' => 'Setup Spesialistik',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'spesialistik',
			'url_simpan' => base_url().'admum/admum_spesialistik_c/simpan',
			'url_ubah' => base_url().'admum/admum_spesialistik_c/ubah',
			'url_hapus' => base_url().'admum/admum_spesialistik_c/hapus',
			'url_cetak' => base_url().'admum/admum_spesialistik_c/cetak_excel',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_spesialistik(''),
		);

		$this->load->view('admum/excel/excel_spesialistik_xls',$data);
	}

	function data_spesialistik(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_spesialistik($keyword);
		echo json_encode($data);
	}

	function data_spesialistik_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_spesialistik_id($id);
		echo json_encode($data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$keterangan = 'SIP-SPESIALISTIK';
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
			$kode = "SPDG".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "SPDG".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'SIP-SPESIALISTIK';
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

	function simpan(){
		$kode = $this->input->post('kode');
		$spesialistik = addslashes($this->input->post('spesialistik'));

		$this->model->simpan($kode,$spesialistik);
		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_spesialistik_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$spesialistik = addslashes($this->input->post('spesialistik_ubah'));

		$this->model->ubah($id,$spesialistik);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_spesialistik_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_spesialistik_c');
	}

}