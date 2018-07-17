<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_obat_racik_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('apotek/ap_obat_racik_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'apotek/ap_obat_racik_v',
			'title' => 'Obat Racik',
			'subtitle' => 'Obat Racik',
			'master_menu' => 'obat_racik',
			'view' => 'obat_racik',
			'url_simpan' => base_url().'apotek/ap_obat_racik_c/simpan',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$id_klien = '1';
		$keterangan = 'SIP-OBAT-RACIK';
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
			$kode = $no."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE ID_KLIEN = '$id_klien' AND KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = $no."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_obat(){
		$id_klien = '1';
	    $keterangan = 'SIP-OBAT-RACIK';
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

	function data_apoteker(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_apoteker($keyword);
		echo json_encode($data);
	}

	function klik_apoteker(){
		$id_apoteker = $this->input->post('id');
		$data = $this->model->klik_apoteker($id_apoteker);
		echo json_encode($data);
	}

	function data_bahan_racik(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_bahan_racik($keyword);
		echo json_encode($data);
	}

	function klik_racikan(){
		$id = $this->input->post('id');
		$data = $this->model->klik_racikan($id);
		echo json_encode($data);
	}

	function simpan(){
		$sip = $this->input->post('sip');
		$id_apoteker = $this->input->post('id_apoteker');
		$id_gudang = $this->input->post('id_gudang');
		$id_nama_obat = $this->input->post('id_nama_obat');
		$id_satuan = $this->input->post('id_satuan');
		$jumlah_pakai = str_replace(',', '', $this->input->post('jumlah'));

		$this->model->simpan_apoteker($sip,$id_apoteker);
		$id_last = $this->db->insert_id();

		foreach ($id_nama_obat as $key => $value) {
			$this->model->simpan($id_last,$value,$id_satuan[$key],$jumlah_pakai[$key]);
			$this->model->update_stok($id_gudang[$key],$jumlah_pakai[$key]);
		}

		$this->insert_kode_obat();

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/ap_obat_racik_c');
	}

}