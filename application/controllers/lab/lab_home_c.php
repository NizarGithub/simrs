<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lab_home_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('lab/lab_home_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'lab/lab_beranda_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Laboratorium',
			'master_menu' => 'home',
			'view' => '',
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

	function kode_pasien(){
		$keterangan = 'PASIEN-BARU';
		$tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND BULAN = '$bulan' 
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//PA-001/IX/28/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "PA-".$no."/".$this->romanic_number($bulan)."/".$tanggal."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND BULAN = '$bulan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "PA-".$no."/".$this->romanic_number($bulan)."/".$tanggal."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_pasien(){
	    $keterangan = 'PASIEN-BARU';
		$bulan = date('n');
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor
			WHERE BULAN = '$bulan' 
			AND TAHUN = '$tahun'
			AND KETERANGAN = '$keterangan'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,BULAN,TAHUN) VALUES ('1','$keterangan','$bulan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE BULAN = '$bulan' AND TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function data_provinsi(){
		$id_kota_kab = $this->input->post('id_kota_kab');
		$data = $this->model->provinsi($id_kota_kab);
		echo json_encode($data);
	}

	function load_data_pasien(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_data_pasien($keyword);
		echo json_encode($data);
	}

	function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien($id);
		echo json_encode($data);
	}

	function get_notif_pasien(){
		$now = date('d-m-Y');
		$keyword = $this->input->get('keyword');
		$posisi = '2';

		$data = $this->model->data_pasien($keyword,$posisi,$now);
		echo json_encode($data);
	}

	function terima_pasien(){
		$id = $this->input->post('id');
		$this->model->terima_pasien($id);
		echo '1';
	}

	function data_pasien_terima(){
		$keyword = $this->input->get('keyword');
		$posisi = '2';
		$now = $this->input->get('now');
        $level = $this->input->get('level');
        $hasil_cek = $this->input->get('hasil_cek');

        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

		$data = $this->model->data_pasien_terima($keyword,$posisi,$now,$level,$hasil_cek,$tanggal_awal,$tanggal_akhir,$bulan,$tahun);
		echo json_encode($data);
	}

	function data_pasien(){
		$now = date('d-m-Y');
		$keyword = $this->input->get('keyword');
		$posisi = '2';

		$data = $this->model->data_pasien($keyword,$posisi,$now);
		echo json_encode($data);
	}

	function data_pasien_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_pasien_id($id);
		echo json_encode($data);
	}

	function get_pasien_dr_poli(){
		$tanggal = $this->input->get('tanggal');
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_pasien_dr_poli($tanggal,$keyword);
		echo json_encode($data);
	}

	function get_pasien_poli_id(){
		$id_rj = $this->input->post('id');
		$data['ind'] = $this->model->get_pasien_poli_id($id_rj);
		$data['det'] = $this->model->get_detail_lab_poli($id_rj);
		echo json_encode($data);
	}

	function ubah_status_lihat(){
		$tanggal = date('d-m-Y');
		$this->model->ubah_status_lihat($tanggal);
		echo '1';
	}

	function ubah_laborat_detail(){
		$id_rj = $this->input->post('id_rj_det');
		$id = $this->input->post('id_detail');
		$hasil = $this->input->post('hasil');
		$nilai_rujukan = $this->input->post('nilai_rujukan');
		foreach ($hasil as $key => $value) {
			if($value != "" || $nilai_rujukan[$key] != ""){
				$this->model->ubah_laborat_detail($id[$key],$value,$nilai_rujukan[$key]);
			}
		}

		$this->db->query("UPDATE rk_laborat_rj SET STATUS_PENANGANAN = '1' WHERE ID = '$id_rj'");
		echo '1';
	}

	function encode($input){
		return strtr(base64_encode($input), '+/=', '._-');
	}

	function decode($input){
		return base64_decode(strtr($input, '._-', '+/='));
	}

	function tindakan($id){
		$idx = $this->decode(base64_decode($id));

		$data = array(
			'page' => 'lab/lab_tindakan_v',
			'title' => 'Laboratorium',
			'subtitle' => 'Tindakan Laborat',
			'master_menu' => 'home',
			'view' => '',
			'id' => $idx,
			'dt' => $this->model->data_rawat_jalan_id($idx)
		);

		$this->load->view('lab/lab_home_v',$data); 
	}

	function add_leading_zero($value, $threshold = 3) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode_lab(){
		$keterangan = 'SIP-LABORAT';
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//001/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "2016".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "2016".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_lab(){
	    $keterangan = 'SIP-LABORAT';
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function update_ke_lab(){
		$id_pasien = $this->input->post('id_pasien');
		$this->model->update_ke_lab($id_pasien);
		echo '1';
	}

	function data_laborat(){
		$id = $this->input->post('id');
		$data = $this->model->data_laborat($id);
		echo json_encode($data);
	}

	function data_laborat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_laborat_id($id);
		echo json_encode($data);
	}

	function data_hasil_pemeriksaan(){
		$id_pemeriksaan = $this->input->post('id');
		$data = $this->model->data_hasil_pemeriksaan($id_pemeriksaan);
		echo json_encode($data);
	}

	function hapus_laborat(){
		$id = $this->input->post('id_hapus_lab');

		$this->model->hapus_laborat($id);
		$this->model->hapus_laborat_detail($id);

		echo '1';
	}

	function cetak_laborat($id_pelayanan){
		$id_pelayananx = $this->decode(base64_decode($id_pelayanan));

		$data1 = $this->model->data_rawat_jalan_id($id_pelayananx);
		$data2 = $this->model->data_hasil_pemeriksaan($id_pelayananx);
		$data3 = $this->model->data_laborat_id($id_pelayananx);

		$data = array(
			'settitle' => 'Pelayanan Rawat Jalan',
			'filename' => 'hasil_laborat',
			'view'	=> 'rj',
			'data1' => $data1,
			'data2' => $data2,
			'data3' => $data3,
		);

		$this->load->view('lab/pdf/rk_laporan_hasil_lab_pdf_v',$data);
	}

	function load_laborat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_laborat($keyword);
		echo json_encode($data);
	}

	function klik_laborat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_laborat($id);
		echo json_encode($data);
	}

	function load_pemeriksaan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_pemeriksaan($keyword);
		echo json_encode($data);
	}

	function klik_pemeriksaan(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pemeriksaan($id);
		echo json_encode($data);
	}

	function simpan_pemeriksaan(){
		$kode_lab = $this->input->post('kode_lab');
		$id_pelayanan = $this->input->post('id_rj');
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$jenis_laborat = $this->input->post('id_laborat');
		$total_tarif = str_replace(',', '', $this->input->post('total_tarif_pemeriksaan'));
		$cito = $this->input->post('cito');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$pemeriksaan = $this->input->post('id_pemeriksaan');
		$subtotal = str_replace(',', '', $this->input->post('tarif_pemeriksaan'));

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$tipe = 'Dari Admission';

		$this->model->simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu,$tipe);
		$id_pemeriksaan_rj = $this->db->insert_id();
		$hasil = $this->input->post('hasil_periksa');
		$nilai_rujukan = $this->input->post('nilai_rujukan');

		foreach ($pemeriksaan as $key => $value) {
			$this->model->simpan_pemeriksaan_detail($id_pemeriksaan_rj,$value,$hasil[$key],$nilai_rujukan[$key],$tanggal,$bulan,$tahun,$subtotal[$key],$waktu);
		}

		$this->insert_kode_lab();

		echo '1';
	}

	function simpan_rj(){
		$id_pasien_new = $this->input->post('id_pasien');
		$asal_rujukan = $this->input->post('asal_rujukan');
		$h = date('l');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$pilihan = $this->input->post('pilihan');
		$id_poli = $this->input->post('id_poli');
		$hari = "";

		//NOMOR ANTRIAN
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$barcode = $this->input->post('barcode');
		$nomor_antrian = $this->input->post('jumlah_antrian');

		if($h == 'Monday'){
			$hari = "Senin";
		}else if($h == 'Tuesday'){
			$hari = "Selasa";
		}else if($h == 'Wednesday'){
			$hari = "Rabu";
		}else if($h == 'Thursday'){
			$hari = "Kamis";
		}else if($h == 'Friday'){
			$hari = "Jumat";
		}else if($h == 'Saturday'){
			$hari = "Sabtu";
		}else if($h == 'Sunday'){
			$hari = "Minggu";
		}

		$this->model->simpan_rj($id_pasien_new,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$waktu,$id_poli,$pilihan);

		echo '1';
	}

}