<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_jenis_penyakit_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_jenis_penyakit_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_jenis_penyakit_v',
			'title' => 'Jenis Penyakit',
			'subtitle' => 'Jenis Penyakit',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'jenis_penyakit',
			'url_simpan' => base_url().'setup/admum_jenis_penyakit_c/simpan',
			'url_ubah' => base_url().'setup/admum_jenis_penyakit_c/ubah',
			'url_hapus' => base_url().'setup/admum_jenis_penyakit_c/hapus',
			'url_cetak' => base_url().'setup/admum_jenis_penyakit_c/cetak_excel',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_jenis_penyakit(''),
		);

		$this->load->view('setup/excel/excel_jenis_penyakit_xls',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode(){
		$keterangan = 'JENIS-PENYAKIT';
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

		//KDJP001.2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "KDJP".$no.".".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "KDJP".$no.".".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'JENIS-PENYAKIT';
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
		$uraian = addslashes($this->input->post('uraian'));
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan($kode,$uraian,$tanggal,$bulan,$tahun);

		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_jenis_penyakit_c');
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

	function ubah(){
		$id = $this->input->post('id_ubah');
		$uraian = addslashes($this->input->post('uraian_ubah'));
		$this->model->ubah($id,$uraian);

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_jenis_penyakit_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('setup/admum_jenis_penyakit_c');
	}

}