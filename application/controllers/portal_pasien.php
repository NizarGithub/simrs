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

	function getJmlAntrianPasien($id_kode_antrian,$status){
		$tgl = date('d-m-Y');

		$sql = "
			SELECT
				a.*,
				c.ID_KODE,
				c.KODE,
				c.URUT,
				c.TGL,
				d.ID_PEGAWAI,
				b.STS AS STATUS
			FROM kepeg_loket a
			LEFT JOIN kepeg_setup_antrian b ON b.ID = a.KODE_ANTRIAN
			LEFT JOIN kepeg_antrian c ON c.ID_KODE = a.KODE_ANTRIAN
			LEFT JOIN kepeg_loket_operator d ON d.ID_LOKET = a.ID
			WHERE c.TGL = '$tgl'
			AND c.ID_KODE = '$id_kode_antrian'
			AND b.STS = '$status'
		";

		return $this->db->query($sql)->result();
	}

	function get_antri_online(){
		$id_kode_antrian = $this->input->post('id_kode_antrian');
		$status = $this->input->post('status');
		$data = $this->getJmlAntrianPasien($id_kode_antrian,$status);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */