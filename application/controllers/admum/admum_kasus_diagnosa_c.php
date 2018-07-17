<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_kasus_diagnosa_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_kasus_diagnosa_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_kasus_diagnosa_v',
			'title' => 'Setup Kasus Diagnosa',
			'subtitle' => 'Setup Kasus Diagnosa',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'kasus',
			'url_simpan' => base_url().'admum/admum_kasus_diagnosa_c/simpan',
			'url_ubah' => base_url().'admum/admum_kasus_diagnosa_c/ubah',
			'url_hapus' => base_url().'admum/admum_kasus_diagnosa_c/hapus',
			'url_cetak' => base_url().'admum/admum_kasus_diagnosa_c/cetak_excel',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_kasus(''),
		);

		$this->load->view('admum/excel/excel_kasus_diagnosa_xls',$data);
	}

	function data_kasus(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_kasus($keyword);
		echo json_encode($data);
	}

	function data_kasus_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kasus_id($id);
		echo json_encode($data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$keterangan = 'SIP-KASUS-DIAGNOSA';
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
			$kode = "KDG".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "KDG".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'SIP-KASUS-DIAGNOSA';
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
		$nama_kasus = addslashes($this->input->post('nama_kasus'));

		$this->model->simpan($kode,$nama_kasus);
		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_kasus_diagnosa_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_kasus = addslashes($this->input->post('nama_kasus_ubah'));

		$this->model->ubah($id,$nama_kasus);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_kasus_diagnosa_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_kasus_diagnosa_c');
	}

}