<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kamar_jenazah_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_setup_kamar_jenazah_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_setup_kamar_jenazah_v',
			'title' => 'Setup Kamar Jenazah',
			'subtitle' => 'Setup Kamar Jenazah',
			'master_menu' => 'master_setup',
			'view' => 'setup_kamar_jenazah',
			'childtitle' => '',
			'url_simpan' => base_url().'admum/admum_setup_kamar_jenazah_c/simpan',
			'url_simpan_lemari' => base_url().'admum/admum_setup_kamar_jenazah_c/simpan_lemari',
			'url_ubah' => base_url().'admum/admum_setup_kamar_jenazah_c/ubah',
			'url_hapus' => base_url().'admum/admum_setup_kamar_jenazah_c/hapus',
			'url_hapus_lemari' => base_url().'admum/admum_setup_kamar_jenazah_c/hapus_lemari',
			'url_cetak' => base_url().'admum/admum_setup_kamar_jenazah_c/cetak_excel',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_kamar('','Default'),
		);

		$this->load->view('admum/excel/excel_kamar_jenazah_xls',$data);
	}


	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_kamar(){
		$id_klien = '1';
		$keterangan = 'KAMAR-JENAZAH';

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND ID_KLIEN = '$id_klien'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//KJZ-001
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "KJZ-".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "KJZ-".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_kamar(){
		$id_klien = '1';
	    $keterangan = 'KAMAR-JENAZAH';

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND ID_KLIEN = '$id_klien'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(ID_KLIEN,NEXT,KETERANGAN) VALUES ('$id_klien','1','$keterangan')");
		}else{
			$sql = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'");
		}
	}

	function data_kamar_jenazah(){
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$data = $this->model->data_kamar($keyword,$urutkan);
		echo json_encode($data);
	}

	function data_kamar_jenazah_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kamar_id($id);
		echo json_encode($data);
	}

	function data_lemari(){
		$id_kamar_jenazah = $this->input->post('id');
		$data['detail'] = $this->model->data_kamar_id($id_kamar_jenazah);
		$data['lemari'] = $this->model->data_lemari($id_kamar_jenazah);
		echo json_encode($data);
	}

	function data_lemari_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_lemari_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_kamar = $this->input->post('kode_kamar');
		$nama_kamar = $this->input->post('nama_kamar');
		$biaya = str_replace(',', '', $this->input->post('biaya'));
		$jumlah_lemari = $this->input->post('jumlah_bed');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan($kode_kamar,$nama_kamar,$biaya,$jumlah_lemari,$tanggal,$bulan,$tahun);
		$this->insert_kode_kamar();

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_setup_kamar_jenazah_c');
	}

	function simpan_lemari(){
		$id_kamar_jenazah = $this->input->post('id_kamar');
		$no = $this->input->post('no');
		$nomor_lemari = $this->input->post('nomor_lemari');
		$jumlah = $this->input->post('jumlah');

		foreach ($no as $key => $value) {
			$this->model->simpan_lemari($id_kamar_jenazah,$value,$nomor_lemari[$key],$jumlah[$key]);
		}

		$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id_kamar_jenazah' ORDER BY ID DESC LIMIT 1";
		$qry = $this->db->query($sql)->row();
		$id_last = $qry->ID;
		$this->db->query("UPDATE admum_lemari_jenazah SET STATUS_HAPUS = '1' WHERE ID = '$id_last'");

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_setup_kamar_jenazah_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_kamar = $this->input->post('nama_kamar_ubah');
		$biaya = str_replace(',', '', $this->input->post('biaya_ubah'));
		$jumlah_lemari = str_replace(',', '', $this->input->post('jumlah_lemari_ubah'));

		$this->model->ubah($id,$nama_kamar,$biaya,$jumlah_lemari);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_setup_kamar_jenazah_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		
		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total = $qry_cek->TOTAL;

		$msg = "";
		if($total == 0){
			$this->model->hapus($id);
			$msg = 'hapus';
		}else{
			$msg = 'tidak_hapus';
		}

		$this->session->set_flashdata($msg,'1');
		redirect('admum/admum_setup_kamar_jenazah_c');
	}

	function hapus_lemari(){
		$id = $this->input->post('id_hapus_lemari');
		$id_kamar_jenazah = $this->input->post('id_kamar_jenazah');
		$this->model->hapus_lemari($id);

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id_kamar_jenazah'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total = $qry_cek->TOTAL;

		if($total != 0){
			$sql = "SELECT * FROM admum_lemari_jenazah WHERE ID_KAMAR_JENAZAH = '$id_kamar_jenazah' ORDER BY ID DESC LIMIT 1";
			$qry = $this->db->query($sql)->row();
			$id_last = $qry->ID;
			$this->db->query("UPDATE admum_lemari_jenazah SET STATUS_HAPUS = '1' WHERE ID = '$id_last'");
		}else{

		}

		// $this->session->set_flashdata('hapus','1');
		// redirect('admum/admum_setup_kamar_jenazah_c');
		echo '1';
	}

}