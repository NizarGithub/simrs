<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_awal_barang_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->helper('url');
		$this->load->library('excel_reader2');
		$this->load->model('finance/stok_awal_barang_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/stok_awal_barang_v',
			'title' => 'Penyesuaian Stok Awal Barang',
			'subtitle' => 'Penyesuaian Stok Awal Barang',
			'childtitle' => '',
			'master_menu' => 'pengadaan_barang',
			'view' => 'stok_awal_barang'
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function data_barang(){
		$data = $this->model->data_barang();
		echo json_encode($data);
	}

	function export_excel(){
		$data = array(
			'dt' => $this->model->data_barang(),
			'settitle' => 'Laporan Stok Awal Barang',
			'filename' => date('dmY').'_lap_stok_awal_barang'
		);

		$this->load->view('finance/xls/lap_stok_awal_barang_xls_v',$data);
	}

	function import_excel(){
		// file yang tadinya di upload, di simpan di temporary file PHP, file tersebut yang kita ambil
		// dan baca dengan PHP Excel Class
		$data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
		$hasildata = $data->rowcount($sheet_index=0);

		// print_r($hasildata);
		// die();

		for ($i=2; $i<=$hasildata; $i++){
		  	$id_gudang = $data->val($i,2); 
		  	$stok = $data->val($i,9);
		  	$keterangan = $data->val($i,12);
		 
		 	$this->model->ubah($id_gudang,$stok,$keterangan);
		}

		if($hasildata){
			$this->session->set_flashdata('sukses','1');
		}else{
			$this->session->set_flashdata('gagal','1');
		}

		redirect('finance/stok_awal_barang_c');
	}

}