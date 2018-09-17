<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_ri_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('poli/rk_pelayanan_ri_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'poli/rk_pelayanan_ri_v',
			'title' => 'Pelayanan Rawat Inap',
			'subtitle' => 'Pelayanan Rawat Inap',
			'master_menu' => 'home',
			'view' => 'pelayanan_ri',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

	function notif_pasien_baru(){
		$tanggal = date('d-m-Y');
		$data = $this->model->notif_pasien_baru($tanggal);
		echo json_encode($data);
	}

	function data_pasien_baru(){
		$tanggal = date('d-m-Y');
		$data = $this->model->data_pasien_baru($tanggal);
		echo json_encode($data);
	}

	function terima_pasien(){
		$id = $this->input->post('id');
		$this->db->query("UPDATE admum_rawat_inap SET STS_TERIMA = '1' WHERE ID = '$id'");
		echo '1';
	}

	function data_rawat_inap(){
		$keyword = $this->input->post('keyword');
		// $tanggal = date('d-m-Y');
		$tanggal = '13-09-2018';
		$data = $this->model->data_rawat_inap($keyword,$tanggal);
		echo json_encode($data);
	}

	function data_pasien_sudah(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pasien_sudah($keyword);
		echo json_encode($data);
	}

	function encode($input){
		return strtr(base64_encode($input), '+/=', '._-');
	}

	function decode($input){
		return base64_decode(strtr($input, '._-', '+/='));
	}

	function add_leading_zero($value, $threshold = 3) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function tindakan_ri($idx){
		$id = $this->decode($idx);

		$data = array(
			'page' => 'poli/rk_tindakan_ri_v',
			'title' => 'Pelayanan Rawat Inap',
			'subtitle' => 'Pelayanan Rawat Inap',
			'master_menu' => 'home',
			'view' => 'pelayanan_ri',
			'id' => $id,
			'dt' => $this->model->data_rawat_inap_id($id),
			'url_simpan' => base_url().'poli/rk_pelayanan_ri_c/simpan_tindakan',
			'url_ubah' => base_url().'poli/rk_pelayanan_ri_c/ubah_tindakan',
			'url_hapus' => base_url().'poli/rk_pelayanan_ri_c/hapus_tindakan',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

	// TINDAKAN

	function load_tindakan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_tindakan($keyword);
		echo json_encode($data);
	}

	function klik_tindakan(){
		$id = $this->input->post('id');
		$data = $this->model->klik_tindakan($id);
		echo json_encode($data);
	}

	function load_pelaksana(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_pelaksana($keyword);
		echo json_encode($data);
	}

	function klik_pelaksana(){
		$id_pegawai = $this->input->post('id');
		$data = $this->model->klik_pelaksana($id_pegawai);
		echo json_encode($data);
	}

	function get_hari_tindakan(){
		$id_tindakan = $this->input->post('id_tindakan');
		$hari_ke = '';
		//cek tindakan di hari 1
		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM rk_ri_tindakan WHERE ID_PELAYANAN = '$id_tindakan' AND HARI_KE = '1'";
		$qry_cek = $this->db->query($sql_cek);
		$total = $qry_cek->row()->TOTAL;
		if($total != '0'){ //jika tindakan hari 1 ada
			$sql = "SELECT ID,HARI_KE FROM rk_ri_tindakan WHERE ID_PELAYANAN = '$id_tindakan' ORDER BY HARI_KE DESC LIMIT 1";
			$qry = $this->db->query($sql);
			$row = $qry->row();
			$hari_ke = $row->HARI_KE + 1;
		}else{
			$hari_ke = '1';
		}

		echo json_encode($hari_ke);
	}

	function data_tindakan_ri(){
		$id_tindakan = $this->input->post('id_tindakan');
		$tanggal = date('d-m-Y');
		$data['hr'] = $this->model->get_hari_tindakan($id_tindakan);
		$data['dt'] = $this->model->data_tindakan_ri($id_tindakan,$tanggal);
		echo json_encode($data);
	}

	function data_tindakan_ri_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_tindakan_ri_id($id);
		echo json_encode($data);
	}

	function simpan_tindakan(){
		$id_pelayanan = $this->input->post('id_ri');
		$id_pasien = $this->input->post('id_pasien'); 
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$id_pelaksana = $this->input->post('id_user');
		$total = str_replace(',', '', $this->input->post('total_tindakan'));
		$id_setup_tindakan = $this->input->post('id_setup_tindakan');
		$hari_ke = '';

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM rk_ri_tindakan WHERE ID_PELAYANAN = '$id_pelayanan' AND HARI_KE = '1'";
		$qry_cek = $this->db->query($sql_cek);
		$total_cek = $qry_cek->row()->TOTAL;

		if($total_cek != '0'){
			$sql = "SELECT ID,HARI_KE FROM rk_ri_tindakan WHERE ID_PELAYANAN = '$id_pelayanan' ORDER BY HARI_KE DESC LIMIT 1";
			$qry = $this->db->query($sql);
			$row = $qry->row();
			$hari_ke = $row->HARI_KE + 1;
		}else{
			$hari_ke = '1';
		}

		$this->model->simpan_tindakan($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_pelaksana,$total,$hari_ke);
		$id_tindakan = $this->db->insert_id();
		$jumlah = $this->input->post('jumlah');
		$subtotal = $this->input->post('subtotal');

		foreach ($id_setup_tindakan as $key => $value) {
			$this->model->simpan_det_tindakan($id_tindakan,$value,$tanggal,$bulan,$tahun,$jumlah[$key],$subtotal[$key],$hari_ke);
		}

		// $this->session->set_flashdata('sukses','1');
		// redirect('poli/rk_pelayanan_ri_c/tindakan_ri/'.$id_pelayanan);
		echo '1';
	}

	function ubah_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_ubah');
		$id_setup_tindakan = $this->input->post('id_tindakan_ubah');
		$jumlah = $this->input->post('jumlah_ubah');
		$subtotal = str_replace(',', '', $this->input->post('subtotal_ubah'));

		$this->model->ubah_tindakan($id,$id_setup_tindakan,$jumlah,$subtotal);

		$this->session->set_flashdata('ubah','1');
		redirect('poli/rk_pelayanan_ri_c/tindakan_ri/'.$id_pelayanan);
	}

	function hapus_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_hapus');
		$this->model->hapus_tindakan($id);
		$this->session->set_flashdata('ubah','1');
		redirect('poli/rk_pelayanan_ri_c/tindakan_ri/'.$id_pelayanan);
	}

	// VISITE

	function load_visite(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_visite($keyword);
		echo json_encode($data);
	}

	function klik_visite(){
		$id = $this->input->post('id');
		$data = $this->model->klik_visite($id);
		echo json_encode($data);
	}

	function load_dokter(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_dokter($keyword);
		echo json_encode($data);
	}

	function klik_dokter(){
		$id = $this->input->post('id');
		$data = $this->model->klik_dokter($id);
		echo json_encode($data);
	}

	function data_visite(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_visite($id_pelayanan);
		echo json_encode($data);
	}

	function data_visite_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_visite_id($id,$id_pelayanan);
		echo json_encode($data);
	}

	function simpan_visite(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = $this->input->post('tanggal_visite');
		$bulan = date('n');
		$tahun = date('Y');
		$id_visite = $this->input->post('id_visite');
		$id_dokter = $this->input->post('id_dokter');

		$this->model->simpan_visite($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_visite,$id_dokter);

		echo '1';
	}

	function ubah_visite(){
		$id = $this->input->post('id_ubah_visite');
		$id_visite = $this->input->post('id_visite_ubah');
		$id_dokter = $this->input->post('id_dokter_ubah');
		$this->model->ubah_visite($id,$id_visite,$id_dokter);
		echo '1';
	}

	function hapus_visite(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$this->model->hapus_visite($id,$id_pelayanan);
		echo '1';
	}

	//GIZI

	function load_gizi(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_gizi($keyword);
		echo json_encode($data);
	}

	function klik_gizi(){
		$id = $this->input->post('id');
		$data = $this->model->klik_gizi($id);
		echo json_encode($data);
	}

	function data_gizi(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_gizi($id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function data_gizi_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_gizi_id($id,$id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function simpan_gizi(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$id_gizi = $this->input->post('id_gizi');

		$this->model->simpan_gizi($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$id_gizi);

		echo '1';
	}

	function ubah_gizi(){
		$id = $this->input->post('id_ubah_gizi');
		$id_gizi = $this->input->post('id_gizi_ubah');
		$this->model->ubah_gizi($id,$id_gizi);
		echo '1';
	}

	function hapus_gizi(){
		$id = $this->input->post('id');
		$this->model->hapus_gizi($id);
		echo '1';
	}

	//OKSIGEN

	function data_oksigen(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_oksigen($id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function data_oksigen_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_oksigen_id($id,$id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function simpan_oksigen(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$keterangan = addslashes($this->input->post('keterangan_oksigen'));
		$jumlah = str_replace(',', '', $this->input->post('jumlah_oksigen'));
		$tarif = str_replace(',', '', $this->input->post('tarif_oksigen'));
		$total = str_replace(',', '', $this->input->post('total_oksigen'));
		$pemakaian = $this->input->post('pemakaian_selama');

		$this->model->simpan_oksigen($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$keterangan,$jumlah,$tarif,$total,$pemakaian);

		echo '1';
	}

	function ubah_oksigen(){
		$id = $this->input->post('id_ubah_oksigen');
		$keterangan = $this->input->post('keterangan_oksigen_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_oksigen_ubah'));
		$tarif = str_replace(',', '', $this->input->post('tarif_oksigen_ubah'));
		$this->model->ubah_oksigen($id,$keterangan,$jumlah,$tarif);
		echo '1';
	}

	function hapus_oksigen(){
		$id = $this->input->post('id');
		$this->model->hapus_oksigen($id);
		echo '1';
	}

	//INFUS

	function data_infus(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_infus($id_pelayanan);
		echo json_encode($data);
	}

	function data_infus_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_infus_id($id,$id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function get_kode_infus(){
		$keterangan = 'SIP-INFUS-RI';
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
			$kode = "INF/RI.".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "INF/RI.".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_infus(){
	    $keterangan = 'SIP-INFUS-RI';
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

	function simpan_infus(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$kode = $this->input->post('kode_infus');
		$jumlah = $this->input->post('jumlah_infus');
		$tarif = str_replace(',', '', $this->input->post('tarif_infus'));
		$total = str_replace(',', '', $this->input->post('total_infus'));
		$pemakaian = $this->input->post('pemakaian_selama_infus');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan_infus($id_pelayanan,$id_pasien,$kode,$jumlah,$tarif,$total,$pemakaian,$tanggal,$bulan,$tahun);
		$this->insert_kode_infus();

		echo '1';
	}

	function ubah_infus(){
		$id = $this->input->post('id_ubah_infus');
		$jumlah = $this->input->post('jumlah_infus_ubah');
		$tarif = str_replace(',', '', $this->input->post('tarif_infus_ubah'));
		$total = $this->input->post('total_infus_ubah');
		$pemakaian = $this->input->post('pemakaian_selama_infus_ubah');

		$this->model->ubah_infus($id,$jumlah,$tarif,$total,$pemakaian);

		echo '1';
	}

	function hapus_infus(){
		$id = $this->input->post('id_hapus_infus');
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');

		$this->model->hapus_infus($id,$id_pelayanan,$id_pasien);

		echo '1';
	}

	//JASA PERAWAT

	function get_kode_jasa(){
		$keterangan = 'SIP-JASA-PERAWAT-RI';
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
			$kode = "JP/RI.".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "JP/RI.".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_jasa(){
	    $keterangan = 'SIP-JASA-PERAWAT-RI';
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

	function data_jasa(){
		$id_pelayanan = $this->input->post('id');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_jasa($id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function data_jasa_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_jasa_id($id,$id_pelayanan,$id_pasien);
		echo json_encode($data);
	}

	function load_jasa(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_jasa($keyword);
		echo json_encode($data);
	}

	function klik_jasa(){
		$id = $this->input->post('id');
		$data = $this->model->klik_jasa($id);
		echo json_encode($data);
	}

	function simpan_jasa(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$kode = $this->input->post('kode_jasa');
		$id_jasa_perawat = $this->input->post('id_jasa');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_jasa'));
		$total = str_replace(',', '', $this->input->post('total_jasa'));
		$perawatan_selama = $this->input->post('pemakaian_selama_jasa');
		$total_semua = str_replace(',', '', $this->input->post('total_semua_jasa'));
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan_jasa($id_pelayanan,$id_pasien,$kode,$id_jasa_perawat,$jumlah,$total,$perawatan_selama,$total_semua,$tanggal,$bulan,$tahun);
		$this->insert_kode_jasa();
		echo '1';
	}

	function hapus_jasa(){
		$id = $this->input->post('id_hapus_jasa');
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$this->model->hapus_jasa($id,$id_pelayanan,$id_pasien);
		echo '1';
	}

	// DIAGNOSA

	function load_kasus(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_kasus($keyword);
		echo json_encode($data);
	}

	function klik_kasus(){
		$id = $this->input->post('id');
		$data = $this->model->data_kasus_id($id);
		echo json_encode($data);
	}

	function load_spesialistik(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_spesialistik($keyword);
		echo json_encode($data);
	}

	function klik_spesialistik(){
		$id = $this->input->post('id');
		$data = $this->model->data_spesialistik_id($id);
		echo json_encode($data);
	}

	function data_diagnosa(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_diagnosa($id_pelayanan);
		echo json_encode($data);
	}

	function data_diagnosa_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_diagnosa_id($id,$id_pelayanan);
		echo json_encode($data);
	}

	function simpan_diagnosa(){
		$id_pelayanan = $this->input->post('id_ri');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$diagnosa = addslashes($this->input->post('diagnosa'));
		$tindakan = addslashes($this->input->post('tindakan_dg'));
		$hari_ke = '';

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM rk_ri_diagnosa WHERE ID_PELAYANAN = '$id_pelayanan' AND HARI_KE = '1'";
		$qry_cek = $this->db->query($sql_cek);
		$total_cek = $qry_cek->row()->TOTAL;

		if($total_cek != '0'){
			$sql = "SELECT ID,HARI_KE FROM rk_ri_diagnosa WHERE ID_PELAYANAN = '$id_pelayanan' ORDER BY HARI_KE DESC LIMIT 1";
			$qry = $this->db->query($sql);
			$row = $qry->row();
			$hari_ke = $row->HARI_KE + 1;
		}else{
			$hari_ke = '1';
		}

		$this->model->simpan_diagnosa($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$tindakan,$hari_ke);

		echo '1';
	}

	function ubah_diagnosa(){
		$id = $this->input->post('id_ubah_dg');
		$diagnosa = $this->input->post('diagnosa_ubah');
		$tindakan = $this->input->post('tindakan_dg_ubah');

		$this->model->ubah_diagnosa($id,$diagnosa,$tindakan);

		echo '1';
	}

	function hapus_diagnosa(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$this->model->hapus_diagnosa($id,$id_pelayanan);
		echo '1';
	}

	// LABORAT

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
		$id_pelayanan = $this->input->post('id_ri');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$jenis_laborat = $this->input->post('id_laborat');
		$total_tarif = str_replace(',', '', $this->input->post('total_tarif_pemeriksaan'));
		$cito = $this->input->post('cito');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$hari_ke = '';

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM rk_ri_laborat WHERE ID_PELAYANAN = '$id_pelayanan' AND HARI_KE = '1'";
		$qry_cek = $this->db->query($sql_cek);
		$total_cek = $qry_cek->row()->TOTAL;

		if($total_cek != '0'){
			$sql = "SELECT ID,HARI_KE FROM rk_ri_laborat WHERE ID_PELAYANAN = '$id_pelayanan' ORDER BY HARI_KE DESC LIMIT 1";
			$qry = $this->db->query($sql);
			$row = $qry->row();
			$hari_ke = $row->HARI_KE + 1;
		}else{
			$hari_ke = '1';
		}

		$pemeriksaan = $this->input->post('id_pemeriksaan');
		$subtotal = str_replace(',', '', $this->input->post('tarif_pemeriksaan'));

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$this->model->simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu,$hari_ke);
		$id_pemeriksaan_rj = $this->db->insert_id();
		/*$hasil = $this->input->post('hasil_periksa');
		$nilai_rujukan = $this->input->post('nilai_rujukan');*/

		foreach ($pemeriksaan as $key => $value) {
			$this->model->simpan_pemeriksaan_detail($id_pemeriksaan_rj,$value,$tanggal,$bulan,$tahun,$subtotal[$key],$waktu,$hari_ke);
		}

		$this->insert_kode_lab();

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

	function cetak_laborat($id,$id_pelayanan){
		$data1 = $this->model->data_rawat_inap_id($id_pelayanan);
		$data2 = $this->model->data_hasil_pemeriksaan($id);
		$data3 = $this->model->data_laborat_id($id);

		$data = array(
			'settitle' => 'Pelayanan Rawat Inap',
			'filename' => date('dmY').'_hasil_laborat',
			'view'	=> 'ri',
			'data1' => $data1,
			'data2' => $data2,
			'data3' => $data3,
		);

		$this->load->view('poli/pdf/rk_laporan_hasil_lab_pdf_v',$data);
	}

	//RESEP

	function load_resep(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_obat($keyword);
		echo json_encode($data);
	}

	function klik_resep(){
		$id = $this->input->post('id');
		$data = $this->model->klik_obat($id);
		echo json_encode($data);
	}

	function data_resep(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_resep($id_pelayanan);
		echo json_encode($data);
	}

	function data_resep_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_resep_id($id);
		echo json_encode($data);
	}

	function detail_resep(){
		$id_resep = $this->input->post('id');
		$data = $this->model->data_resep_det($id_resep);
		echo json_encode($data);
	}

	function get_kode_resep(){
		$keterangan = 'SIP-RESEP-RI';
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
			$kode = "RSP/RI.".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "RSP/RI.".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_resep(){
	    $keterangan = 'SIP-RESEP-RI';
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

	function simpan_resep(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$kode_resep = $this->input->post('kode_resep');
		$total = $this->input->post('grandtotal_resep_txt');
		$diminum = $this->input->post('diminum_selama');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$id_obat = $this->input->post('id_obat_resep');
		$harga = $this->input->post('harga_resep');
		$jumlah_beli = str_replace(',', '', $this->input->post('jumlah_resep'));
		$takaran = $this->input->post('takaran_resep');
		$aturan_umum = $this->input->post('aturan_minum');

		$this->model->simpan_resep($id_pelayanan,$id_pasien,$kode_resep,$total,$diminum,$tanggal,$bulan,$tahun);
		$id_resep = $this->db->insert_id();

		foreach ($id_obat as $key => $value) {
			$subtotal = $harga[$key] * $jumlah_beli[$key];
			$this->model->simpan_resep_det($id_resep,$value,$harga[$key],$jumlah_beli[$key],$subtotal,$takaran[$key],$aturan_umum[$key]);
		}

		$this->insert_kode_resep();

		echo '1';
	}

	function hapus_resep(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id');
		$this->model->hapus_resep($id,$id_pelayanan);
		$this->model->hapus_det_resep($id);
		echo '1';
	}

	// KONDISI AKHIR

	function load_ruang_icu(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_ruang_icu($keyword);
		echo json_encode($data);
	}

	function klik_ruang_icu(){
		$id = $this->input->post('id');
		$data = $this->model->klik_ruang_icu($id);
		echo json_encode($data);
	}

	function load_ruang_operasi(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_ruang_operasi($keyword);
		echo json_encode($data);
	}

	function klik_ruang_operasi(){
		$id = $this->input->post('id');
		$data = $this->model->klik_ruang_operasi($id); 
		echo json_encode($data);
	}

	function load_kamar_jenazah(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_kamar_jenazah($keyword);
		echo json_encode($data);
	}

	function klik_kamar_jenazah(){
		$id = $this->input->post('id');
		$data = $this->model->klik_kamar_jenazah($id);
		echo json_encode($data);
	}

	function load_lemari_jenazah(){
		$id_kamar = $this->input->post('id_kamar');
		$data = $this->model->load_lemari_jenazah($id_kamar);
		echo json_encode($data);
	}

	function klik_lemari_jenazah(){
		$id = $this->input->post('id');
		$data = $this->model->klik_lemari_jenazah($id);
		echo json_encode($data);
	}

	function simpan_ka(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$dirawat = $this->input->post('dirawat_selama');
		$tanggal_keluar = $this->input->post('tanggal_keluar');
		$kondisi_akhir = $this->input->post('kondisi_akhir');

		//ICU
		$id_ruang_icu = $this->input->post('id_ruang_icu');
		$tarif_icu = str_replace(',', '', $this->input->post('tarif_icu'));

		//OPERASI
		$id_ruang_operasi = $this->input->post('id_ruang_icu');
		$tarif = str_replace(',', '', $this->input->post('tarif_icu'));

		//MENINGGAL
		$id_kamar_jenazah = $this->input->post('id_kamar_jenazah');
		$id_lemari_jenazah = $this->input->post('id_lemari_jenazah');

		if($kondisi_akhir == 'ICU'){

			$this->model->simpan_icu($id_pelayanan,$id_pasien,$id_ruang_icu,$tarif_icu,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_icu SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_icu'");

		}else if($kondisi_akhir == 'Operasi'){

			$this->model->simpan_operasi($id_pelayanan,$id_pasien,$id_ruang_operasi,$tarif,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_operasi SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_operasi'");

		}else if($kondisi_akhir == 'Meninggal'){

			$this->model->simpan_meninggal($id_pelayanan,$id_pasien,$id_kamar_jenazah,$id_lemari_jenazah,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_lemari_jenazah SET STATUS_PAKAI = '1' WHERE ID = '$id_lemari_jenazah'");
			
		}

		$this->model->simpan_ka($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$dirawat,$kondisi_akhir);
		$this->db->query("UPDATE admum_rawat_inap SET STATUS_SUDAH = '1', TANGGAL_KELUAR = '$tanggal_keluar' WHERE ID = '$id_pelayanan'");

		echo '1';
	}

	//SURAT DOKTER

	function data_surat_dokter(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_surat_dokter($id_pelayanan);
		echo json_encode($data);
	}

	function data_surat_dokter_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_surat_dokter_id($id);
		echo json_encode($data);
	}

	function simpan_surat_dokter(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$waktu_istirahat = $this->input->post('waktu_sd');
		$mulai_tanggal = $this->input->post('mulai_tgl_sd');
		$sampai_tanggal = $this->input->post('sampai_tgl_sd');

		$this->model->simpan_surat_dokter($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$waktu_istirahat,$mulai_tanggal,$sampai_tanggal);
		
		echo '1';
	}

	function surat_dokter($id){
		$data1 = $this->model->data_surat_dokter_id($id);

		$data = array(
			'id_surat' => $id,
			'settitle' => 'Surat Dokter',
			'filename' => 'surat_dokter',
			'data1' => $data1,
		);

		$this->load->view('poli/pdf/rk_ri_surat_dokter_pdf_v',$data);
	}

	function hapus_surat_dokter(){
		$id = $this->input->post('id_hapus_sd');
		$this->model->hapus_surat_dokter($id);
		echo '1';
	}

}