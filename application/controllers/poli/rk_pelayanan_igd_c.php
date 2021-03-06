<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_igd_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('rekam_medik/rk_pelayanan_igd_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_pelayanan_igd_v',
			'title' => 'Pelayanan IGD',
			'subtitle' => 'Pelayanan IGD',
			'master_menu' => 'pelayanan',
			'view' => 'pelayanan_igd',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function data_pasien_belum(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pasien_belum($keyword);
		echo json_encode($data);
	}

	function data_pasien_sudah(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pasien_sudah($keyword);
		echo json_encode($data);
	}

	function tindakan_igd($id){
		$data = array(
			'page' => 'rekam_medik/rk_tindakan_igd_v',
			'title' => 'Pelayanan IGD',
			'subtitle' => 'Pelayanan IGD',
			'master_menu' => 'pelayanan',
			'view' => 'pelayanan_igd',
			'id' => $id,
			'dt' => $this->model->data_igd_id($id),
			'url_simpan' => base_url().'rekam_medik/rk_pelayanan_igd_c/simpan_tindakan',
			'url_ubah' => base_url().'rekam_medik/rk_pelayanan_igd_c/ubah_tindakan',
			'url_hapus' => base_url().'rekam_medik/rk_pelayanan_igd_c/hapus_tindakan',
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	//TINDAKAN

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

	function data_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_tindakan($id_pelayanan);
		echo json_encode($data);
	}

	function data_tindakan_id(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_tindakan_id($id,$id_pelayanan);
		echo json_encode($data);
	}

	function simpan_tindakan(){
		$id = $this->input->post('id_rj');
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$id_tindakan = $this->input->post('id_tindakan');
		$jumlah = $this->input->post('jumlah');
		$subtotal = $this->input->post('subtotal');

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$total = str_replace(',', '', $this->input->post('tot_tarif_tindakan'));

		$this->model->simpan_tindakan($id,$id_pasien,$tanggal,$bulan,$tahun,$waktu,$total);
		$id_tindakan_rj = $this->db->insert_id();

		foreach ($id_tindakan as $key => $value) {
			$this->model->simpan_det_tindakan($id_tindakan_rj,$value,$tanggal,$bulan,$tahun,$jumlah[$key],$subtotal[$key],$waktu);
		}

		$this->session->set_flashdata('sukses','1');
		redirect('rekam_medik/rk_pelayanan_igd_c/tindakan_igd/'.$id); 
	}

	function ubah_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_ubah');
		$tindakan = $this->input->post('id_tindakan_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$subtotal = str_replace(',', '', $this->input->post('subtotal_ubah'));
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$this->model->ubah_tindakan($id,$tindakan,$jumlah,$subtotal,$waktu);

		$this->session->set_flashdata('ubah','1');
		redirect('rekam_medik/rk_pelayanan_igd_c/tindakan_igd/'.$id_pelayanan); 
	}

	function hapus_tindakan(){
		$id = $this->input->post('id_hapus');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$this->model->hapus_tindakan($id);

		$this->session->set_flashdata('hapus','1');
		redirect('rekam_medik/rk_pelayanan_igd_c/tindakan_igd/'.$id_pelayanan);
	}

	//LABORAT

	function add_leading_zero($value, $threshold = 3) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode_lab(){
		$keterangan = 'SIP-LABORAT-IGD';
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
			$kode = "IGD2016".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "IGD2016".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_lab(){
	    $keterangan = 'SIP-LABORAT-IGD';
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

	function simpan_laborat(){
		$kode_lab = $this->input->post('kode_lab');
		$id_pelayanan = $this->input->post('id_rj');
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

		$this->model->simpan_laborat($kode_lab,$id_pelayanan,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu);
		$id_pemeriksaan_igd = $this->db->insert_id();
		$hasil = $this->input->post('hasil_periksa');
		$nilai_rujukan = $this->input->post('nilai_rujukan');

		foreach ($pemeriksaan as $key => $value) {
			$this->model->simpan_det_laborat($id_pemeriksaan_igd,$value,$hasil[$key],$nilai_rujukan[$key],$tanggal,$bulan,$tahun,$subtotal[$key],$waktu);
		}

		$this->insert_kode_lab();

		echo '1';
	}

	function data_laborat(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$data = $this->model->data_laborat($id_pelayanan);
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
		$data1 = $this->model->data_igd_id($id_pelayanan);
		$data2 = $this->model->data_hasil_pemeriksaan($id);
		$data3 = $this->model->data_laborat_id($id);

		$data = array(
			'settitle' => 'Pelayanan IGD',
			'filename' => 'hasil_laborat',
			'view'	=> 'igd',
			'data1' => $data1,
			'data2' => $data2,
			'data3' => $data3,
		);

		$this->load->view('rekam_medik/pdf/rk_laporan_hasil_lab_pdf_v',$data);
	}

	// DIAGNOSA

	function data_kasus(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_kasus($keyword);
		echo json_encode($data);
	}

	function data_kasus_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kasus_id($id);
		echo json_encode($data);
	}

	function data_spesialistik(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_spesialistik($keyword);
		echo json_encode($data);
	}

	function data_spesialistik_id(){
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
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$diagnosa = addslashes($this->input->post('diagnosa'));
		$tindakan = addslashes($this->input->post('tindakan_dg'));
		$kasus = $this->input->post('id_kasus');
		$spesialistik = $this->input->post('id_spesialistik');

		$this->model->simpan_diagnosa($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$tindakan,$kasus,$spesialistik);

		echo '1';
	}

	function ubah_diagnosa(){
		$id = $this->input->post('id_ubah_dg');
		$diagnosa = $this->input->post('diagnosa_ubah');
		$tindakan = $this->input->post('tindakan_dg_ubah');
		$kasus = $this->input->post('id_kasus_ubah');
		$spesialistik = $this->input->post('id_spesialistik_ubah');

		$this->model->ubah_diagnosa($id,$diagnosa,$tindakan,$kasus,$spesialistik);

		echo '1';
	}

	function hapus_diagnosa(){
		$id = $this->input->post('id');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$this->model->hapus_diagnosa($id,$id_pelayanan);
		echo '1';
	}

	//RESEP

	function get_kode_resep(){
		$keterangan = 'SIP-RESEP-IGD';
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
			$kode = "RSP".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "RSP".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_resep(){
	    $keterangan = 'SIP-RESEP-IGD';
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

	function load_obat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_obat($keyword);
		echo json_encode($data);
	}

	function klik_obat(){
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

	function simpan_resep(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$kode_resep = $this->input->post('kode_resep');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$id_obat = $this->input->post('id_obat_resep');
		$takaran = $this->input->post('takaran_resep');
		$aturan_umum = $this->input->post('aturan_minum');

		$this->model->simpan_resep($id_pelayanan,$id_pasien,$kode_resep,$tanggal,$bulan,$tahun);
		$id_resep = $this->db->insert_id();

		foreach ($id_obat as $key => $value) {
			$this->model->simpan_resep_det($id_resep,$value,$takaran[$key],$aturan_umum[$key]);
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

	function load_ruangan(){
		$keyword = $this->input->post('keyword');
		$kelas = $this->input->post('kelas');
		$data = $this->model->load_ruangan($keyword,$kelas);
		echo json_encode($data);
	}

	function klik_ruangan(){
		$id = $this->input->post('id');
		$data = $this->model->klik_ruangan($id);
		echo json_encode($data);
	}

	function load_bed(){
		$keyword = $this->input->post('keyword');
		$id_kamar = $this->input->post('id_kamar');
		$data = $this->model->load_bed($keyword,$id_kamar);
		echo json_encode($data);
	}

	function klik_bed(){
		$id = $this->input->post('id');
		$data = $this->model->klik_bed($id);
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

	function simpan_kondisi(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$kondisi_akhir = $this->input->post('kondisi_akhir');

		//RI
		$asal_rujukan = $this->input->post('asal_rujukan');
		$nama_penanggungjawab = $this->input->post('nama_pjawab');
		$telepon = $this->input->post('telepon');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$kelas = $this->input->post('kelas_kamar');
		$id_kamar = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed');

		//OPERASI
		$id_ruang_operasi = $this->input->post('id_ruang_opr');
		$tarif = str_replace(',', '', $this->input->post('tarif_operasi'));

		//ICU
		$id_ruang_icu = $this->input->post('id_ruang_icu');
		$tarif_icu = str_replace(',', '', $this->input->post('tarif_icu'));

		//MENINGGAL
		$id_kamar_jenazah = $this->input->post('id_kamar_jenazah');
		$id_lemari_jenazah = $this->input->post('lemari_jenazah');

		if($kondisi_akhir == 'Rawat Inap'){
			
			$this->model->simpan_rawat_inap($id_pasien,$tanggal,$bulan,$tahun,$asal_rujukan,$nama_penanggungjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed);
			$this->db->query("UPDATE admum_igd SET STATUS_PINDAH = '$kondisi_akhir' WHERE ID = '$id_pelayanan'");
			$this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '1' WHERE ID = '$id_bed'");

		}else if($kondisi_akhir == 'Operasi'){
			
			$this->model->simpan_operasi($id_pelayanan,$id_pasien,$id_ruang_operasi,$tarif,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_operasi SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_operasi'");

		}else if($kondisi_akhir == 'ICU'){

			$this->model->simpan_icu($id_pelayanan,$id_pasien,$id_ruang_icu,$tarif_icu,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_icu SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_icu'");

		}else if($kondisi_akhir == 'Meninggal'){

			$this->model->simpan_meninggal($id_pelayanan,$id_pasien,$id_kamar_jenazah,$id_lemari_jenazah,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_lemari_jenazah SET STATUS_PAKAI = '1' WHERE ID = '$id_lemari_jenazah'");

		}

		$this->model->simpan_kondisi($id_pelayanan,$id_pasien,$tanggal,$bulan,$tahun,$kondisi_akhir);
		$this->db->query("UPDATE admum_igd SET STATUS_SUDAH = '1' WHERE ID = '$id_pelayanan'");

		echo '1';
	}

}