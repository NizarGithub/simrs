<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_supplier_obat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/admum_setup_supplier_obat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'apotek/admum_setup_supplier_obat_v',
			'title' => 'Setup Supplier Obat',
			'subtitle' => 'Setup Supplier Obat',
			'master_menu' => 'obat',
			'view' => 'setup_supplier_obat',
			'childtitle' => '',
			'url_simpan' => base_url().'apotek/admum_setup_supplier_obat_c/simpan',
			'url_ubah' => base_url().'apotek/admum_setup_supplier_obat_c/ubah',
			'url_hapus' => base_url().'apotek/admum_setup_supplier_obat_c/hapus',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_supplier_obat(){
		$keterangan = 'SUPPLIER-OBAT';
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

		//SUP-001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "SUP-".$no."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "SUP-".$no."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_supplier(){
	    $keterangan = 'SUPPLIER-OBAT';
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

	function get_data_supplier(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_supplier($keyword);
		echo json_encode($data);
	}

	function data_supplier_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_supplier_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_supplier = $this->input->post('kode_supplier');
		$nama_supplier = $this->input->post('nama_supplier');
		$merk = $this->input->post('merk');
		$alamat = $this->input->post('alamat');
		$email = $this->input->post('email');
		$telepon = $this->input->post('telepon');
		$tanggal_daftar = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan($kode_supplier,$nama_supplier,$merk,$alamat,$email,$telepon,$tanggal_daftar,$bulan,$tahun);
		$this->insert_kode_supplier();

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/admum_setup_supplier_obat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_supplier = $this->input->post('nama_supplier_ubah');
		$merk = $this->input->post('merk_ubah');
		$alamat = $this->input->post('alamat_ubah');
		$email = $this->input->post('email_ubah');
		$telepon = $this->input->post('telepon_ubah');

		$this->model->ubah($id,$nama_supplier,$merk,$alamat,$email,$telepon);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/admum_setup_supplier_obat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('apotek/admum_setup_supplier_obat_c');
	}

}