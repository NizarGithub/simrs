<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_rj_per_poli_c extends CI_Controller {

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
			'page' => 'rekam_medik/lap_rj_per_poli_v',
			'title' => 'Laporan Pasien Per Poli',
			'subtitle' => 'Laporan Pasien Per Poli',
			'master_menu' => 'rawat_jalan',
			'view' => 'per_poli'
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function query_data($id_poli,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun){
		$where_poli = "1 = 1";
		$where = "1 = 1";

		if($id_poli == 'Semua'){
			$where_poli = $where_poli;
		}else{
			$where_poli = $where_poli." AND a.ID = '$id_poli'";
		}

		if($jenis_cetak == 'Harian'){
			$where = $where." AND STR_TO_DATE(TANGGAL,'%d-%m-%Y') BETWEEN STR_TO_DATE('$tanggal_awal','%d-%m-%Y') AND STR_TO_DATE('$tanggal_akhir','%d-%m-%Y')";
		}else{
			$where = $where." AND BULAN = '$bulan' AND TAHUN = '$tahun'";
		}

		$sql = "
			SELECT
				a.ID,
				a.NAMA,
				a.`STATUS`,
				a.KETERANGAN,
				COUNT(b.ID_PASIEN) AS JUMLAH_PASIEN,
				b.TANGGAL,
				b.BULAN,
				b.TAHUN
			FROM admum_poli a
			LEFT JOIN (
				SELECT
					ID,
					ID_POLI,
					ID_PASIEN,
					TANGGAL,
					BULAN,
					TAHUN
				FROM admum_rawat_jalan
				WHERE $where
			) b ON b.ID_POLI = a.ID
			WHERE $where_poli
			GROUP BY a.ID
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_data(){
		$id_poli = $this->input->post('id_poli');
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		$data = $this->query_data($id_poli,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

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
		$id_poli = $this->input->post('poli');
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$settitle = '';
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

		if($id_poli == 'Semua'){
			$settitle = 'Laporan Pasien Semua Poli';
		}else{
			$settitle = 'Laporan Pasien Per Poli';
		}

		$data = $this->query_data($id_poli,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => $settitle,
			'filename' => date('dmY').'_lap_per_poli'
		);

		$this->load->view('rekam_medik/pdf/lap_rj_per_poli_pdf_v',$array);
	}

	function cetak_excel(){
		$id_poli = $this->input->post('id_poli');
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

		$data = $this->query_data($id_poli,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => 'Laporan Pasien Per Wilayah',
			'filename' => date('dmY').'_lap_per_wilayah'
		);

		$this->load->view('rekam_medik/excel/lap_rj_per_poli_xls_v',$array);
	}

}