<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_ruang_operasi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_setup_ruang_operasi_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_setup_ruang_operasi_v',
			'title' => 'Setup Ruang Operasi',
			'subtitle' => 'Setup Ruang Operasi',
			'master_menu' => 'master_setup',
			'view' => 'ruang_operasi',
			'childtitle' => '',
			'url_simpan' => base_url().'admum/admum_setup_ruang_operasi_c/simpan',
			'url_ubah' => base_url().'admum/admum_setup_ruang_operasi_c/ubah',
			'url_hapus' => base_url().'admum/admum_setup_ruang_operasi_c/hapus',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$keterangan = 'SIP-OPERASI';
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

		//001
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "R-OPR-".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "R-OPR-".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'SIP-OPERASI';
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

	function data_ruang(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_ruangan($keyword);
		echo json_encode($data);
	}

	function data_ruang_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_ruangan_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode = $this->input->post('kode');
		$nama_ruang = $this->input->post('nama_ruang');
		$keterangan = $this->input->post('keterangan');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$status_pakai = '0';

		$this->model->simpan($kode,$nama_ruang,$keterangan,$tanggal,$bulan,$tahun,$status_pakai);
		$this->insert_kode();
		
		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_setup_ruang_operasi_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_ruang = $this->input->post('nama_ruang_ubah');
		$keterangan = $this->input->post('keterangan_ubah');

		$this->model->ubah($id,$nama_ruang,$keterangan);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_setup_ruang_operasi_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_setup_ruang_operasi_c');
	}

}