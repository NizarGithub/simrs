<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan_brg_per_div_c extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
    $this->load->model('finance/laporan_brg_per_div_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index(){
		$data = array(
			'page' => 'finance/laporan_brg_per_div_v',
			'title' => 'Laporan Barang Per Divisi',
			'subtitle' => 'Laporan Barang Per Divisi',
			'master_menu' => 'laporan_barang',
			'view' => 'per_divisi',
		);

		$this->load->view('finance/finance_home_v',$data);
	}

  function data_peralatan(){
    $id_departemen = $this->input->post('id_departemen');
    $id_divisi = $this->input->post('id_divisi');
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $bulan = $this->input->post('bulan');
    $tahun = $this->input->post('tahun');

    $data = $this->model->data_peralatan($id_departemen,$id_divisi,$by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    echo json_encode($data);
  }

  function data_departemen(){
    $keyword = $this->input->get('keyword');
		$data = $this->model->data_departemen($keyword);
		echo json_encode($data);
	}

  function klik_departemen(){
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->model->klik_departemen($id_departemen);
		echo json_encode($data);
	}

  function data_divisi(){
    $keyword = $this->input->get('keyword');
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->model->data_divisi($id_departemen,$keyword);
		echo json_encode($data);
	}

	function klik_divisi(){
		$id_divisi = $this->input->post('id_divisi');
		$data = $this->model->klik_divisi($id_divisi);
		echo json_encode($data);
	}

  function cetak_pdf(){
    $id_departemen = $this->input->post('id_departemen');
    $id_divisi = $this->input->post('id_divisi');
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $bulan = $this->input->post('select_bulan');
    $tahun = $this->input->post('tahun');
    $settitle = "";
    $judul = "";

    $bulan_arr = array(
      1 =>  "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
      5 =>  "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
      9 =>  "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
    );

    $sql_dep = $this->db->query("SELECT ID,NAMA_DEP FROM kepeg_departemen WHERE ID = '$id_departemen'")->row();
    $nama_dep = $sql_dep->NAMA_DEP;

    if($id_divisi != ""){
      $sql_div = $this->db->query("SELECT ID,NAMA_DIV FROM kepeg_divisi WHERE ID = '$id_divisi'")->row();
      $nama_div = $sql_div->NAMA_DIV;
      $settitle = "Laporan Barang Dep. ".$nama_dep." Divisi ".$nama_div;
    }else{
      $settitle = "Laporan Barang Dep. ".$nama_dep;
    }

    if($by == 'Semua'){
      $judul = "Semua Barang";
    }else if($by == 'Tanggal'){
      $judul = "Tanggal : ".$tanggal_sekarang." s/d ".$tanggal_sampai;
    }else if($by == 'Bulan'){
      $judul = "Bulan : ".$bulan_arr[$bulan];
    }

		$data = $this->model->data_peralatan($id_departemen,$id_divisi,$by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    $array = array(
      'settitle' => $settitle,
      'filename' => date('dmY').'_laporan_barang',
      'dt' => $data,
      'judul' => $judul
    );

    $this->load->view('finance/pdf/lap_laporan_barang_pdf_v', $array);
  }

	function cetak_excel(){
    $id_departemen = $this->input->post('id_departemen');
    $id_divisi = $this->input->post('id_divisi');
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $bulan = $this->input->post('select_bulan');
    $tahun = $this->input->post('tahun');
    $settitle = "";
    $judul = "";

    $bulan_arr = array(
      1 =>  "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
      5 =>  "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
      9 =>  "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
    );

    $sql_dep = $this->db->query("SELECT ID,NAMA_DEP FROM kepeg_departemen WHERE ID = '$id_departemen'")->row();
    $nama_dep = $sql_dep->NAMA_DEP;

    if($id_divisi != ""){
      $sql_div = $this->db->query("SELECT ID,NAMA_DIV FROM kepeg_divisi WHERE ID = '$id_divisi'")->row();
      $nama_div = $sql_div->NAMA_DIV;
      $settitle = "Laporan Barang Dep. ".$nama_dep." Divisi ".$nama_div;
    }else{
      $settitle = "Laporan Barang Dep. ".$nama_dep;
    }

    if($by == 'Semua'){
      $judul = "Semua Barang";
    }else if($by == 'Tanggal'){
      $judul = "Tanggal : ".$tanggal_sekarang." s/d ".$tanggal_sampai;
    }else if($by == 'Bulan'){
      $judul = "Bulan : ".$bulan_arr[$bulan];
    }

    $data = $this->model->data_peralatan($id_departemen,$id_divisi,$by,$tanggal_sekarang,$tanggal_sampai,$bulan,$tahun);

    $array = array(
      'settitle' => $settitle,
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
