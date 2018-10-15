<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_rj_kunj_baru_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/lap_rj_kunj_baru_v',
			'title' => 'Laporan Kunjungan Pasien Baru',
			'subtitle' => 'Laporan Kunjungan Pasien Baru',
			'master_menu' => 'rawat_jalan',
			'view' => 'kunjungan_baru',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function query_data($jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun){
		$where = "1 = 1";

		if($jenis_cetak == 'Harian'){
			$where = $where." AND STR_TO_DATE(a.TANGGAL_DAFTAR,'%d-%m-%Y') BETWEEN STR_TO_DATE('$tanggal_awal','%d-%m-%Y') AND STR_TO_DATE('$tanggal_akhir','%d-%m-%Y')";
		}else{
			if($bulan < 10){
				$bulan = '0'.$bulan;
			}
			
			$where = $where." AND SUBSTR(a.TANGGAL_DAFTAR,4,2) = '$bulan' AND SUBSTR(a.TANGGAL_DAFTAR,7) = '$tahun'";
		}

		$sql = "
			SELECT
				a.ID,
				a.TANGGAL_DAFTAR,
				STR_TO_DATE(a.TANGGAL_DAFTAR,'%d-%m-%Y') AS TANGGAL_DAFTAR_BALIK,
				SUBSTR(a.TANGGAL_DAFTAR,4,2) AS BULAN,
				SUBSTR(a.TANGGAL_DAFTAR,7) AS TAHUN,
				a.KODE_PASIEN,
				a.NAMA,
				a.JENIS_KELAMIN,
				a.JENIS_PASIEN
			FROM rk_pasien a
			WHERE $where 
			AND a.JENIS_PASIEN = 'Baru'
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_data(){
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		$data = $this->query_data($jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		echo json_encode($data);
	}

	function cetak(){
		$jenis_laporan = $this->input->post('jenis_laporan');
		if($jenis_laporan == 'Pdf'){
			$this->cetak_pdf();
		}else{
			$this->cetak_excel();
		}
	}

	function cetak_pdf(){
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$judul = '';

		$bulan_arr = array(
			1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
			5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
			9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
		);

		if($jenis_cetak == 'Harian'){
			$judul = 'Tanggal : '.$tanggal_awal.' s/d '.$tanggal_akhir;
		}else{
			$judul = 'Bulan : '.$bulan_arr[$bulan].' Tahun '.$tahun;
		}

		$data = $this->query_data($jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => 'Laporan Kunjungan Pasien Baru',
			'filename' => date('dmY').'_lap_kunj_pasien_baru'
		);

		$this->load->view('rekam_medik/pdf/lap_rj_kunj_baru_pdf_v',$array);
	}

	function cetak_excel(){
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$judul = '';

		$bulan_arr = array(
			1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
			5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
			9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
		);

		if($jenis_cetak == 'Harian'){
			$judul = 'Tanggal : '.$tanggal_awal.' s/d '.$tanggal_akhir;
		}else{
			$judul = 'Bulan : '.$bulan_arr[$bulan].' Tahun '.$tahun;
		}

		$data = $this->query_data($jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => 'Laporan Kunjungan Pasien Baru',
			'filename' => date('dmY').'_lap_kunj_pasien_baru'
		);

		$this->load->view('rekam_medik/excel/lap_rj_kunj_baru_xls_v',$array);
	}

}