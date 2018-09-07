<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_satuan_barang_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/admum_satuan_barang_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/admum_satuan_barang_v',
			'title' => 'Setup Satuan Barang',
			'subtitle' => 'Setup Satuan Barang',
			'childtitle' => '',
			'master_menu' => 'master_setup',
			'view' => 'satuan_barang',
			'url_simpan' => base_url().'finance/admum_satuan_barang_c/simpan',
			'url_ubah' => base_url().'finance/admum_satuan_barang_c/ubah',
			'url_hapus' => base_url().'finance/admum_satuan_barang_c/hapus',
			'url_cetak' => base_url().'finance/admum_satuan_barang_c/cetak_excel',
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_kode_satuan(''),
		);

		$this->load->view('finance/excel/excel_satuan_barang_xls',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_satuan(){
		$keterangan = 'SATUAN-BARANG';
		$tanggal = date('d');
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

		//OBT-001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "BRG-".$no."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "BRG-".$no."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_satuan(){
	    $keterangan = 'SATUAN-BARANG';
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

	function get_data_satuan(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_kode_satuan($keyword);
		echo json_encode($data);
	}

	function data_satuan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kode_satuan_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_satuan = $this->input->post('kode_satuan');
		$nama_satuan = $this->input->post('nama_satuan');

		$this->model->simpan($kode_satuan,$nama_satuan);
		$this->insert_kode_satuan();

		$this->session->set_flashdata('sukses','1');
		redirect('finance/admum_satuan_barang_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$kode_satuan = $this->input->post('kode_satuan_ubah');
		$nama_satuan = $this->input->post('nama_satuan_ubah');

		$this->model->ubah($id,$kode_satuan,$nama_satuan);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/admum_satuan_barang_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/admum_satuan_barang_c');
	}

}