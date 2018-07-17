<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_laporan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('apotek/ap_laporan_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'apotek/ap_laporan_v',
			'title' => 'Laporan',
			'subtitle' => 'Laporan',
			'master_menu' => 'laporan',
			'view' => 'laporan',
			'url_cetak_so' => base_url().'apotek/ap_laporan_c/cetakStokObat',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	//LAPORAN PENJUALAN

	function data_laporan(){
		$pilihan = $this->input->post('pilihan');
		$bulan = $this->input->post('bulan');
		$now = date('Y-m-d');
		$data = $this->model->data_laporan($pilihan,$bulan,$now);
		echo json_encode($data);
	}

	function cetak($id){
		$m = date('n');
		$y = date('Y');

		$bulan = array(
			1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
			5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
			9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
		);

		$bln = $bulan[intval($m)];

		$data = array(
			'title' => 'Laporan Penjualan',
			'settitle' => 'Laporan Penjualan',
			'dt' => $this->model->data_trx_obat($id),
			'filename' => 'LapPenjualan_'.$bln.'_'.$y,
			'bulan' => $bln,
			'tahun' => $y,
		);

		$this->load->view('apotek/pdf/laporan_penjualan_obat_pdf_v',$data);
	}

	//STOK OBAT

	function get_data_obat(){
		$keyword = $this->input->post('keyword');
		$urutkan = $this->input->post('urutkan');
		$urutkan_stok = $this->input->post('urutkan_stok');
		$data = $this->model->data_obat($keyword,$urutkan,$urutkan_stok);
		echo json_encode($data);
	}

	function cetakStokObat(){
		$keyword = $this->input->post('cari_obat');
		$urutkan = $this->input->post('urutkan');
		$urutkan_stok = $this->input->post('urutkan_stok');

		$m = date('n');
		$y = date('Y');

		$bulan = array(
			1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
			5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
			9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
		);

		$bln = $bulan[intval($m)];

		$data = array(
			'title' => 'Laporan Sisa Stok Obat',
			'settitle' => 'Laporan Sisa Stok Obat',
			'dt' => $this->model->data_obat($keyword,$urutkan,$urutkan_stok),
			'filename' => 'LapSisaStok_'.$bln.'_'.$y,
			'bulan' => $bln,
			'tahun' => $y,
		);

		$this->load->view('apotek/pdf/laporan_stok_obat_pdf_v',$data);
	}

}