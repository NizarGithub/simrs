<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permintaan_po_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('finance/permintaan_po_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/permintaan_po_v',
			'title' => 'Permintaan PO',
			'subtitle' => 'Permintaan PO',
			'childtitle' => '',
			'master_menu' => 'logistik',
			'view' => 'permintaan_po'
		);

		$this->load->view('finance/finance_home_v',$data);
	}
	
	function data_permintaan_barang(){
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->data_permintaan_barang($bulan,$tahun);
		echo json_encode($data);
	}

	function detail_barang_permintaan(){
		$id_permintaan = $this->input->post('id_permintaan');
		$data = $this->model->detail_barang_permintaan($id_permintaan);
		echo json_encode($data);
	}

	function data_permintaan_barang_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_permintaan_barang_id($id);
		echo json_encode($data);
	}

	function data_barang_diproses(){
		$id = $this->input->post('id');
		$data['row'] = $this->model->data_permintaan_barang_id($id);
		$data['res'] = $this->model->detail_barang_permintaan($id);
		echo json_encode($data);
	}

	function diproses(){
		$id = $this->input->post('id_proses');
		$tanggal = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$this->model->diproses($id,$tanggal,$waktu);

		$id_barang_gudang = $this->input->post('id_barang_gudang');
		$jumlah = $this->input->post('jumlah_permintaan');

		foreach ($id_barang_gudang as $key => $value) {
			$this->model->update_stok_barang($value,$jumlah[$key]);
		}

		// $this->session->set_flashdata('proses','1');
		// redirect('finance/permintaan_po_c');

		echo '1';
	}

	function dibatalkan(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		$id = $this->input->post('id_batal');
		$tanggal = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$this->model->dibatalkan($id,$tanggal,$waktu,$id_user);

		$this->session->set_flashdata('batal','1');
		redirect('finance/permintaan_po_c');
	}

	function decode($input){
		return base64_decode(strtr($input, '._-', '+/='));
	}

	function cetak($idx){
		$id = $this->decode($idx);
		$dt_row = $this->model->data_permintaan_barang_id($id);
		$dt_res = $this->model->detail_barang_permintaan($id);

		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		$sql = "SELECT ID,NAMA FROM kepeg_pegawai WHERE ID = '$id_user'";
		$qry = $this->db->query($sql);
		$pegawai = $qry->row()->NAMA;

		$data = array(
			'dt_row' => $dt_row,
			'dt_res' => $dt_res,
			'pegawai' => $pegawai,
			'settitle' => 'Laporan Permintaan Barang',
			'filename' => date('dmY').'_lap_permintaan_barang'
		);

		$this->load->view('finance/pdf/lap_permintaan_barang_pdf_v',$data);
	}

}