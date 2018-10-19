<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_rj_per_jk_c extends CI_Controller {

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
			'page' => 'rekam_medik/lap_rj_per_jk_v',
			'title' => 'Laporan Pasien Per Jenis Kelamin',
			'subtitle' => 'Laporan Pasien Per Jenis Kelamin',
			'master_menu' => 'rawat_jalan',
			'view' => 'per_jenis_kelamin'
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function query_data($id_jk,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun){
		$where_jk = "1 = 1";
		$where = "1 = 1";

		if($id_jk == 'Semua'){
			$where_jk = $where_jk;
		}else{
			$where_jk = $where_jk." AND a.ID = '$id_jk";
		}

		if($jenis_cetak == 'Harian'){
			$where = $where." AND STR_TO_DATE(TANGGAL,'%d-%m-%Y') BETWEEN STR_TO_DATE('$tanggal_awal','%d-%m-%Y') AND STR_TO_DATE('$tanggal_akhir','%d-%m-%Y')";
		}else{
			$where = $where." AND BULAN = '$bulan' AND TAHUN = '$tahun'";
		}

		$sql = "
			SELECT 
				a.ID,
				a.ID_JABATAN,
				a.NAMA,
				a.TELPON,
				b.NAMA AS JABATAN,
				COUNT(c.ID_PASIEN) AS JUMLAH_PASIEN
			FROM kepeg_pegawai a
			JOIN kepeg_kel_jabatan b ON a.ID_JABATAN = b.ID
			LEFT JOIN (
				SELECT
					ID,
					ID_PASIEN,
					ID_jk,
					TANGGAL,
					BULAN,
					TAHUN
				FROM admum_rawat_jalan
				WHERE $where
			) c ON c.ID_jk = a.ID
			WHERE $where_jk
			AND b.NAMA LIKE '%Dokter%'
			GROUP BY a.ID
			ORDER BY a.ID ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_data(){
		$id_jk = $this->input->post('id_jk');
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		$data = $this->query_data($id_jk,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

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
		$id_jk = $this->input->post('dokter');
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
			$judul = 'Bulan '.$bulan_arr[$bulan].' Tahun '.$tahun;
		}

		if($id_jk == 'Semua'){
			$settitle = 'Laporan Pasien Semua Dokter';
		}else{
			$settitle = 'Laporan Pasien Per Dokter';
		}

		$data = $this->query_data($id_jk,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => $settitle,
			'filename' => date('dmY').'_lap_per_jk'
		);

		$this->load->view('rekam_medik/pdf/lap_rj_per_jk_pdf_v',$array);
	}

	function cetak_excel(){
		$id_jk = $this->input->post('dokter');
		$jenis_cetak = $this->input->post('jenis_cetak');
		$tanggal_awal = $this->input->post('tanggal_awal');
		$tanggal_akhir = $this->input->post('tanggal_akhir');
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$judul = '';
		$settitle = '';

		$bulan_arr = array(
			1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
			5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
			9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
		);

		if($jenis_cetak == 'Harian'){
			$judul = 'Tanggal : '.$tanggal_awal.' s/d '.$tanggal_akhir;
		}else{
			$judul = 'Bulan '.$bulan_arr[$bulan].' Tahun '.$tahun;
		}

		if($id_jk == 'Semua'){
			$settitle = 'Laporan Pasien Semua Dokter';
		}else{
			$settitle = 'Laporan Pasien Per Dokter';
		}

		$data = $this->query_data($id_jk,$jenis_cetak,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);

		$array = array(
			'dt' => $data,
			'judul' => $judul,
			'settitle' => $settitle,
			'filename' => date('dmY').'_lap_per_jk'
		);

		$this->load->view('rekam_medik/excel/lap_rj_per_jk_xls_v',$array);
	}

}