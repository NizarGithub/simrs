<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal_pasien extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->library("Uploader");
		$this->load->library("excel_reader2");
	}

	function index()
	{
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
			'master_menu' => 'dashboard',
			'subtitle' => 'Dashboard',
			'page' => 'beranda_pasien_v'
		);

		$this->load->view('portal_pasien_v',$data);
	}

	function import(){
		$excel = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
		$hasildata = $excel->rowcount($sheet_index=0);
		// default nilai 
		$sql = "";
		for ($i=8; $i<=$hasildata; $i++){
			$isi_a = $excel->val($i,8);
			$isi_b = $excel->val($i,9);
			$isi_c = $excel->val($i,10);
			$isi_d = $excel->val($i,11);
			$isi_e = $excel->val($i,12);
			$id_offline = $excel->val($i,15);
			$this->model->update_offline($tahun_offline,$isi_a,$isi_b,$isi_c,$isi_d,$isi_e,$id_offline);
		}
	}

	function get_dokter(){
		$data = $this->master_model_m->get_dokter();
		echo json_encode($data);
	}

	function get_tracking_pasien(){
		$tanggal = date('d-m-Y');
		$data = $this->master_model_m->get_tracking_pasien($tanggal);
		echo json_encode($data);
	}

	function get_antrian(){
		$akses = 'pasien';
		$data = $this->master_model_m->get_akses_antrian($akses);

		echo json_encode($data);
	}

	function get_total_pasien(){
		$tanggal = date('d-m-Y');
		$data['all'] = $this->master_model_m->get_total_all_pasien($tanggal);
		$data['poli'] = $this->master_model_m->get_total_pasien_poli($tanggal);
		$data['lab'] = $this->master_model_m->get_total_pasien_lab($tanggal);

		echo json_encode($data);
	}

	function getJmlAntrianPasien(){

		$sql = "
			SELECT
			a.ID,
			a.TANGGAL,
			a.WAKTU,
			a.ID_PASIEN,
			a.ID_PELAYANAN,
			a.BARCODE,
			a.WAKTU,
			a.ID_LOKET,
			b.NAMA_LOKET,
			a.KODE_ANTRIAN,
			a.NOMOR_ANTRIAN,
			a.STATUS_PANGGIL
		FROM rk_antrian_pasien a
		JOIN kepeg_loket b ON b.ID = a.ID_LOKET
		WHERE a.STATUS_PANGGIL = '1'
		AND a.STATUS_CLOSING = '0'
		ORDER BY a.NOMOR_ANTRIAN DESC
		LIMIT 1
		";

		return $this->db->query($sql)->result();
	}

	function get_antrian_off(){
		$data = $this->getJmlAntrianPasien();
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */