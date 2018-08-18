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
		$id_kode_antrian = $this->input->post('id_kode_antrian');
		$status = 'offline';
		$id_user = $this->input->post('id_user');
		$data = $this->master_model_m->getJmlAntrian($id_kode_antrian,$status,$id_user);
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
		$now = date('d-m-Y');
		$posisi = '1';

		$data = $this->model->data_pasien($keyword,$posisi,$now,$id_divisi,$level);
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
		$this->model->terima_pasien($id);
		echo '1';
	}

	function get_rekam_medik(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$data['rk'] = $this->model->get_rekam_medik($id_pasien,$tanggal);
		$data['ps'] = $this->model->data_pasien_id($id_pasien);
		echo json_encode($data);
	}

	function get_tindakan(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$sql = "
			SELECT
				TD.*
			FROM rk_tindakan_rj TD
			WHERE TD.ID_PASIEN = '$id_pasien'
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
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$data = $this->model->get_diagnosa($id_pasien);
		echo json_encode($data);
	}

	function get_resep(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$sql = "SELECT * FROM rk_resep_rj WHERE ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		$id_resep = '';
		$data = '';
		if($query->num_rows() > 0){
			$data = $query->row();
			$id_resep = $data->ID;
		}else{
			$id_resep = '';
		}

		$resep['ind'] = $data;
		$resep['det'] = $this->model->get_resep($id_resep);
		echo json_encode($resep);
	}

} 

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */