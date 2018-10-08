<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_laporan_barang_c extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
    $this->load->model('finance/log_laporan_barang_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index(){
		$data = array(
			'page' => 'finance/log_laporan_barang_v',
			'title' => 'Laporan Barang',
			'subtitle' => 'Laporan Barang',
			'master_menu' => 'laporan_barang',
			'view' => 'laporan_barang',
		);

		$this->load->view('finance/finance_home_v',$data);
	}

  function data_peralatan(){
    $by = $this->input->get('by');
    $tanggal_sekarang = $this->input->get('tanggal_sekarang');
    $tanggal_sampai = $this->input->get('tanggal_sampai');
    $bulan = $this->input->get('bulan');
    $tahun = $this->input->get('tahun');

    $data = $this->model->data_peralatan($by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    echo json_encode($data);
  }

  function cetak_pdf(){
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $bulan = $this->input->post('select_bulan');
    $tahun = $this->input->post('tahun');
    $judul = "";

    $bulan_arr = array(
      1 =>  "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
      5 =>  "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
      9 =>  "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
    );

    if($by == 'Semua'){
      $judul = "Semua Barang";
    }else if($by == 'Tanggal'){
      $judul = "Tanggal : ".$tanggal_sekarang." s/d ".$tanggal_sampai;
    }else if($by == 'Bulan'){
      $judul = "Bulan : ".$bulan_arr[$bulan];
    }

		$data = $this->model->data_peralatan($by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    $array = array(
      'settitle' => 'Laporan Barang',
      'filename' => date('dmY').'_laporan_barang',
      'dt' => $data,
      'judul' => $judul
    );

    $this->load->view('finance/pdf/lap_laporan_barang_pdf_v', $array);
  }

	function cetak_excel(){
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $bulan = $this->input->post('select_bulan');
    $tahun = $this->input->post('tahun');
    $judul = "";

    $bulan_arr = array(
      1 =>  "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
      5 =>  "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
      9 =>  "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
    );

    if($by == 'Semua'){
      $judul = "Semua Barang";
    }else if($by == 'Tanggal'){
      $judul = "Tanggal : ".$tanggal_sekarang." s/d ".$tanggal_sampai;
    }else if($by == 'Bulan'){
      $judul = "Bulan : ".$bulan_arr[$bulan];
    }

    $data = $this->model->data_peralatan($by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    $array = array(
      'settitle' => 'Laporan Barang',
      'filename' => date('dmY').'_laporan_barang',
      'dt' => $data,
      'judul' => $judul
    );
    
    $this->load->view('finance/xls/lap_laporan_barang_xls_v', $array);
  }

  function cetak(){
    $cetak = $this->input->post('print');
    if ($cetak == 'excel') {
      $this->cetak_excel();
    }else {
      $this->cetak_pdf();
    }
  }

}
