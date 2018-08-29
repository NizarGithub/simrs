<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poli_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('poli/poli_home_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$sess_user = $this->session->userdata('masuk_rs');
		$nama_div = $sess_user['divisi'];
		$data = array(
			'page' => 'poli/poli_beranda_v',
			'title' => 'Poli',
			'subtitle' => $nama_div,
			'childtitle' => '',
			'master_menu' => 'home',
			'view' => 'home',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

	function get_antrian_offline(){
		$id_user = $this->input->post('id_user');
		$akses = 'poli';
		$status = 'offline';
		$data['cek'] = $this->master_model_m->cek_user_info($id_user,$akses,$status);
		$data['data'] = $this->master_model_m->getLoket($id_user, $akses, $status);
		echo json_encode($data);
	}

	function get_nomor_offline(){
		$data = $this->model->get_antrian_pasien();
		echo json_encode($data);
	}

	function get_antrian_online(){
		$id_user = $this->input->post('id_user');
		$akses = 'poli';
		$status = 'online';
		$data['cek'] = $this->master_model_m->cek_user_info($id_user,$akses,$status);
		$data['data'] = $this->master_model_m->getLoket($id_user, $akses, $status);
		echo json_encode($data);
	}

	function get_nomor_online(){
		$id_kode_antrian = $this->input->post('id_kode_antrian');
		$status = 'online';
		$id_user = $this->input->post('id_user');
		$data = $this->master_model_m->getJmlAntrian($id_kode_antrian,$status,$id_user);
		echo json_encode($data);
	}

	function next_antri(){
		$id_antrian   = $this->input->post('id_antrian');
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$tgl = date('d-m-Y');
		$status = $this->input->post('status');

		$sql = "SELECT COUNT(*) AS TOTAL FROM kepeg_antrian WHERE TGL = '$tgl' AND ID_KODE = '$id_antrian' AND STS = '$status'";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		if($total != 0){
			$s = "SELECT * FROM kepeg_antrian WHERE TGL = '$tgl' AND ID_KODE = '$id_antrian' AND STS = '$status'";
			$q = $this->db->query($s);
			$r = $q->row();
			$urut = $r->URUT+1;
			$this->master_model_m->ubahAntrian($urut,$tgl,$status);
		}else{
			$this->master_model_m->simpanAntrian($id_antrian,$kode_antrian,$jml_antrian,$tgl,$status);
		}

		echo json_encode('1');
	}

	function notif_pasien_baru(){
        $level = $this->input->get('level');
    	$id_divisi = $this->input->get('id_divisi'); //ID POLI
		$keyword = $this->input->get('keyword');
		$posisi = '1';
		$now = date('d-m-Y');

		$data = $this->model->data_pasien_baru($level,$id_divisi,$posisi,$now);
		echo json_encode($data);
	}

	function data_pasien_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_pasien_id($id);
		echo json_encode($data);
	}

	function data_pasien_terima(){
		$keyword = $this->input->get('keyword');
		$posisi = '1';
		$now = $this->input->get('now');
    	$id_divisi = $this->input->get('id_divisi');
		$poli = $this->input->get('poli');
        $level = $this->input->get('level');
        $hasil_cek = $this->input->get('hasil_cek');

        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

		$data = $this->model->data_pasien_terima($keyword,$posisi,$now,$id_divisi,$poli,$level,$hasil_cek,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);
		echo json_encode($data);
	}

	function terima_pasien(){
		$id = $this->input->post('id');
		$tanggal = date('d-m-Y');
		$this->model->terima_pasien($id);
		$this->model->ubah_stt_panggil($id,$tanggal);
		echo '1';
	}

	function ubah_jenis_pasien(){
		$id_pasien = $this->input->post('id_pasien');
		$this->model->ubah_jenis_pasien($id_pasien);
		echo '1';
	}

	function get_rekam_medik(){
		$id_rj = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = $this->input->post('tanggal');
		$data['rk'] = $this->model->get_rekam_medik($id_rj,$tanggal);
		$data['ps'] = $this->model->data_pasien_id($id_pasien);
		echo json_encode($data);
	}

	function get_tindakan(){
		$id_rj = $this->input->post('id_rj');
		$tanggal = $this->input->post('tanggal');
		$sql = "
			SELECT
				TD.*
			FROM rk_tindakan_rj TD
			WHERE TD.ID_PELAYANAN = '$id_rj'
			AND TD.TANGGAL = '$tanggal'
		";
		$query = $this->db->query($sql);
		$id_tindakan = '';
		if($query->num_rows() > 0){
			$data = $query->row();
			$id_tindakan = $data->ID;
		}else{
			$id_tindakan = '';
		}

		$tindakan = $this->model->get_tindakan_det($id_tindakan);
		echo json_encode($tindakan);
	}

	function get_diagnosa(){
		$id_rj = $this->input->post('id_rj');
		$tanggal = $this->input->post('tanggal');
		$data = $this->model->get_diagnosa($id_rj,$tanggal);
		echo json_encode($data);
	}

	function get_resep(){
		$id_rj = $this->input->post('id_rj');
		$tanggal = $this->input->post('tanggal');
		$sql = "SELECT * FROM rk_resep_rj WHERE ID_PELAYANAN = '$id_rj' AND TANGGAL = '$tanggal'";
		$query = $this->db->query($sql);
		$data = $query->result();	
		echo json_encode($data);
	}

	function get_resep_det(){
		$id_resep = $this->input->post('id_resep');
		$data = $this->model->get_resep($id_resep);
		echo json_encode($data);
	}

	function get_lab(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = $this->input->post('tanggal');
		$data = $this->model->get_lab($id_pasien,$tanggal);
		echo json_encode($data);
	}

	function get_lab_det(){
		$id_lab = $this->input->post('id_lab');
		$data = $this->model->get_lab_det($id_lab);
		echo json_encode($data);
	}

	function panggil_pasien(){
		$id_rj = $this->input->post('id');
		$tanggal = date('d-m-Y');
		$data = $this->model->panggil_pasien($id_rj);
		$this->model->ubah_stt_panggil($id_rj,$tanggal);
		echo json_encode($data);
	}

	function closing_antrian(){
		$sql = "UPDATE kepeg_antrian SET STATUS_CLOSING = '1' WHERE STATUS_CLOSING = '0'";
		$this->db->query($sql);
		$sql1 = "UPDATE rk_antrian_pasien SET STATUS_CLOSING = '1' WHERE STATUS_CLOSING = '0'";
		$this->db->query($sql1);
		echo '1';
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */