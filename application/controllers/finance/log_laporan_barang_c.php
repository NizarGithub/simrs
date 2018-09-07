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
    $data = $this->model->data_peralatan();
    echo json_encode($data);
  }
  function range_tanggal(){
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $data = $this->model->range_tanggal($tanggal_sekarang, $tanggal_sampai);
    echo json_encode($data);
  }
  function range_bulan(){
    $bulan = $this->input->post('select_bulan');
    $data = $this->model->range_bulan($bulan);
    echo json_encode($data);
  }
  function data_departemen(){
		$data = $this->model->data_departemen();
		echo json_encode($data);
	}
  function klik_departemen(){
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->model->klik_departemen($id_departemen);
		echo json_encode($data);
	}
  function data_divisi(){
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->model->data_divisi($id_departemen);
		echo json_encode($data);
	}
	function klik_divisi(){
		$id_divisi = $this->input->post('id_divisi');
		$data = $this->model->klik_divisi($id_divisi);
		echo json_encode($data);
	}
  function search_divisi(){
    $id_divisi = $this->input->post('id_divisi');
    $data = $this->model->search_divisi($id_divisi);
    echo json_encode($data);
  }
  function semua_pdf(){
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $select_bulan = $this->input->post('select_bulan');
    $id_departemen = $this->input->post('id_departemen');
    $id_divisi = $this->input->post('id_divisi');

    $data = $this->model->semua_pdf(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $select_bulan,
      $id_departemen,
      $id_divisi
    );

		$data_row = $this->model->semua_pdf_row(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $select_bulan,
      $id_departemen,
      $id_divisi
    );

    $array = array(
      'settitle' => 'Laporan Barang',
      'filename' => date('dmY').'_laporan_barang',
      'data' => $data,
			'data_row' => $data_row,
			'by' => $by,
			'tanggal_sekarang' => $tanggal_sekarang,
			'tanggal_sampai' => $tanggal_sampai,
			'bulan' => $select_bulan,
			'id_divisi' => $id_divisi
    );
    $this->load->view('finance/semua_pdf', $array);
  }
	function semua_excel(){
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $select_bulan = $this->input->post('select_bulan');
    $id_departemen = $this->input->post('id_departemen');
    $id_divisi = $this->input->post('id_divisi');

    $data = $this->model->semua_excel(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $select_bulan,
      $id_departemen,
      $id_divisi
    );

		$data_row = $this->model->semua_excel_row(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $select_bulan,
      $id_departemen,
      $id_divisi
    );

    $array = array(
      'settitle' => 'Laporan Barang',
      'filename' => date('dmY').'_laporan_barang',
      'data' => $data,
			'data_row' => $data_row,
			'by' => $by,
			'tanggal_sekarang' => $tanggal_sekarang,
			'tanggal_sampai' => $tanggal_sampai,
			'bulan' => $select_bulan,
			'id_divisi' => $id_divisi
    );
    $this->load->view('finance/semua_excel', $array);
  }
  function cetak(){
    $cetak = $this->input->post('print');
    if ($cetak == 'excel') {
      $this->semua_excel();
    }else {
      $this->semua_pdf();
    }
  }
}
