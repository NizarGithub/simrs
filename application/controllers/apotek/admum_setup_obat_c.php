<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_obat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/admum_setup_obat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'apotek/admum_setup_obat_v',
			'title' => 'Setup Obat',
			'subtitle' => 'Setup Obat',
			'master_menu' => 'obat',
			'view' => 'setup_nama_obat',
			'childtitle' => '',
			'url_simpan' => base_url().'apotek/admum_setup_obat_c/simpan',
			'url_ubah' => base_url().'apotek/admum_setup_obat_c/ubah',
			'url_hapus' => base_url().'apotek/admum_setup_obat_c/hapus',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_obat(){
		$keterangan = 'NAMA-OBAT';
		$tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND BULAN = '$bulan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//K001-04/10/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "K".$no."-".$tanggal."/".$bulan."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND BULAN = '$bulan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "K".$no."-".$tanggal."/".$bulan."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_obat(){
	    $keterangan = 'NAMA-OBAT';
	    $tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND BULAN = '$bulan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,BULAN,TAHUN) VALUES ('1','$keterangan','$bulan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE BULAN = '$bulan' AND TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
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

	function data_jenis_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_jenis_obat($keyword);
		echo json_encode($data);
	}

	function klik_jenis(){
		$id_jenis = $this->input->post('id_jenis');
		$data = $this->model->klik_jenis($id_jenis);
		echo json_encode($data);
	}

	function data_satuan(){
		$data = $this->model->data_satuan();
		echo json_encode($data);
	}

	function klik_satuan(){
		$id_satuan = $this->input->post('id_satuan');
		$data = $this->model->klik_satuan($id_satuan);
		echo json_encode($data);
	}

	function get_data_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_obat($keyword);
		echo json_encode($data);
	}

	function data_obat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_obat_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_obat = $this->input->post('kode_obat');
		$barcode = $this->input->post('barcode');
		$nama_obat = $this->input->post('nama_obat');
		$merk = $this->input->post('id_merk');

		$this->model->simpan($kode_obat,$barcode,$nama_obat,$merk);
		$this->insert_kode_obat();

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/admum_setup_obat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$barcode = $this->input->post('barcode_ubah');
		$nama_obat = $this->input->post('nama_obat_ubah');
		$id_merk_ubah = $this->input->post('id_merk_ubah');

		$id_merk = "";

		if($id_merk_ubah != ""){
			$id_merk = $id_merk_ubah;
		}else{
			$id_merk = $this->input->post('id_merk_lama');
		}

		$this->model->ubah($id,$barcode,$nama_obat,$id_merk);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/admum_setup_obat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('apotek/admum_setup_obat_c');
	}

	function cek_barcode(){
		$barcode = $this->input->post('barcode');
		$data = $this->model->cek_barcode($barcode);
		echo json_encode($data);
	}

}