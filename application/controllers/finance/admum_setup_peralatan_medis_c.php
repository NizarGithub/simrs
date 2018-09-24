<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_peralatan_medis_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/admum_setup_peralatan_medis_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/admum_setup_peralatan_medis_v',
			'title' => 'Peralatan Medis',
			'subtitle' => 'Peralatan Medis',
			'childtitle' => '',
			'master_menu' => 'master_setup',
			'view' => 'setup_nama_barang',
			'url_simpan' => base_url().'finance/admum_setup_peralatan_medis_c/simpan',
			'url_ubah' => base_url().'finance/admum_setup_peralatan_medis_c/ubah',
			'url_hapus' => base_url().'finance/admum_setup_peralatan_medis_c/hapus',
			'url_cetak' => base_url().'finance/admum_setup_peralatan_medis_c/cetak_excel',
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_peralatan(''),
		);

		$this->load->view('finance/excel/excel_peralatan_medis',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_peralatan(){
		$keterangan = "PERALATAN-MEDIS";
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//SUPBRG-001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,5);
			$kode = "PB".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,5);
			$kode = "PB".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
	    $keterangan = 'PERALATAN-MEDIS';
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function data_merk(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_merk($keyword);
		echo json_encode($data);
	}

	function klik_merk(){
		$id_merk = $this->input->post('id_merk');
		$data = $this->model->klik_merk($id_merk);
		echo json_encode($data);
	}

	function get_data_alat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_peralatan($keyword);
		echo json_encode($data);
	}

	function data_alat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_peralatan_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_alat = $this->input->post('kode_barang');
		$nama_alat = $this->input->post('nama_barang');
		$id_kategori = $this->input->post('id_kategori');

		$this->model->simpan($kode_alat,$nama_alat,$id_kategori);
		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('finance/admum_setup_peralatan_medis_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$barcode = $this->input->post('barcode_ubah');
		$nama_alat = $this->input->post('nama_barang_ubah');
		$id_merk_ubah = $this->input->post('id_merk_ubah');
		$jenis_alat = $this->input->post('jenis_alat_ubah');

		$id_merk = "";

		if($id_merk_ubah != ""){
			$id_merk = $id_merk_ubah;
		}else{
			$id_merk = $this->input->post('id_merk_lama');
		}

		$this->model->ubah($id,$barcode,$nama_alat,$id_merk,$jenis_alat);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/admum_setup_peralatan_medis_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/admum_setup_peralatan_medis_c');
	}

	function cek_barcode(){
		$barcode = $this->input->post('barcode');
		$data = $this->model->cek_barcode($barcode);
		echo json_encode($data);
	}

}