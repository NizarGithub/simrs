<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_ambulance_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$this->load->model('admum/admum_ambulance_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_ambulance_v',
			'title' => 'Ambulance',
			'subtitle' => 'Ambulance',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'ambulance',
			'url_simpan' => base_url().'admum/admum_ambulance_c/simpan',
			'url_ubah' => base_url().'admum/admum_ambulance_c/ubah',
			'url_hapus' => base_url().'admum/admum_ambulance_c/hapus',
			'url_cetak' => base_url().'admum/admum_ambulance_c/cetak_excel',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_ambulance(''),
		);

		$this->load->view('admum/excel/excel_data_ambulance_xls',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$id_klien = '1';
		$keterangan = 'SIP-AMBULANCE';
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE ID_KLIEN = '$id_klien' 
			AND KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "AB.".$no."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE ID_KLIEN = '$id_klien' AND KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "AB.".$no."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
		$id_klien = '1';
	    $keterangan = 'SIP-AMBULANCE';
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE ID_KLIEN = '$id_klien'
			AND KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(ID_KLIEN,NEXT,KETERANGAN,TAHUN) VALUES ('$id_klien','1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE ID_KLIEN = '$id_klien' AND TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'");
		}
	}

	function data_pegawai(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pegawai($keyword);
		echo json_encode($data);
	}

	function klik_pegawai(){
		$id_apoteker = $this->input->post('id');
		$data = $this->model->klik_pegawai($id_apoteker);
		echo json_encode($data);
	}

	function klik_perawat(){
		$id_apoteker = $this->input->post('id');
		$data = $this->model->klik_perawat($id_apoteker);
		echo json_encode($data);
	}

	function simpan(){
		$kode = $this->input->post('kode');
		$nomor_plat = $this->input->post('nomor_plat');
		$id_sopir = $this->input->post('id_sopir');
		$id_perawat = $this->input->post('id_perawat');

		$this->model->simpan($kode,$nomor_plat,$id_sopir);
		$id_ambulance = $this->db->insert_id();

		foreach ($id_perawat as $key => $value) {
			$this->model->simpan_perawat($id_ambulance,$value);
		}

		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_ambulance_c');
	}

	function data_ambulance(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_ambulance($keyword);
		echo json_encode($data);
	}

	function data_ambulance_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_ambulance_id($id);
		echo json_encode($data);
	}

	function data_detail_perawat(){
		$id_ambulance = $this->input->post('id');
		$data['ambulance'] = $this->model->data_ambulance_id($id_ambulance);
		$data['perawat'] = $this->model->data_detail_perawat($id_ambulance);
		echo json_encode($data);
	}

	function data_perawat_id(){
		$id = $this->input->post('id_perawat');
		$data = $this->model->data_perawat_id($id);
		echo json_encode($data);
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nomor_plat = $this->input->post('nomor_plat_ubah');
		$id_sopir = "";
		$cek_sopir = $this->input->post('cek_sopir');
		$id_perawat = $this->input->post('id_ubah_perawat');
		$id_perawat_ubah = $this->input->post('id_prwt_ubah');

		if($cek_sopir){
			$id_sopir = $this->input->post('id_sopir_ubah');
		}else{
			$id_sopir = $this->input->post('id_sopir_txt');
		}

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM admum_ambulance_perawat WHERE ID_AMBULANCE = '$id'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total = $qry_cek->TOTAL;

		if($total == 0){
			foreach ($id_perawat as $key2 => $value2) {
				$this->model->simpan_perawat($id,$id_perawat_ubah[$key2]);
			}
		}else{
			$this->model->ubah($id,$nomor_plat,$id_sopir);

			foreach ($id_perawat as $key => $value) {
				$this->model->ubah_perawat($value,$id_perawat_ubah[$key]);
			}
		}

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_ambulance_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);
		$this->model->hapus_perawat($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_ambulance_c');
	}

	function hapus_perawat(){
		$id = $this->input->post('id_perawat_hapus');
		$sql = "DELETE FROM admum_ambulance_perawat WHERE ID = '$id'";
		$this->db->query($sql);

		echo '1';
	}

}