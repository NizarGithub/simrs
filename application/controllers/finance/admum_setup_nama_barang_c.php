<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_nama_barang_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/admum_setup_nama_barang_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/admum_setup_nama_barang_v',
			'title' => 'Setup Nama Barang',
			'subtitle' => 'Setup Nama Barang',
			'childtitle' => '',
			'master_menu' => 'master_setup',
			'view' => 'setup_nama_barang',
			'url_simpan' => base_url().'finance/admum_setup_nama_barang_c/simpan',
			'url_ubah' => base_url().'finance/admum_setup_nama_barang_c/ubah',
			'url_hapus' => base_url().'finance/admum_setup_nama_barang_c/hapus',
			'url_cetak' => base_url().'finance/admum_setup_nama_barang_c/cetak_excel'
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
		redirect('finance/admum_setup_nama_barang_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_alat = $this->input->post('nama_barang_ubah');
		$id_kategori = '';
		$checkbox2 = $this->input->post('checkbox2');

		if($checkbox2){
			$id_kategori = $this->input->post('id_kategori_ubah');
		}else{
			$id_kategori = $this->input->post('id_kat_lama');
		}

		$this->model->ubah($id,$nama_alat,$id_kategori);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/admum_setup_nama_barang_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/admum_setup_nama_barang_c');
	}

	function cek_barcode(){
		$barcode = $this->input->post('barcode');
		$data = $this->model->cek_barcode($barcode);
		echo json_encode($data);
	}

}