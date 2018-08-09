<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_pelayanan_rj_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('poli/rk_pelayanan_rj_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'poli/rk_pelayanan_rj_v',
			'title' => 'Pelayanan Rawat Jalan',
			'subtitle' => 'Pelayanan Rawat Jalan',
			'master_menu' => 'pelayanan',
			'view' => 'pelayanan_rj',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

	function data_rawat_jalan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_rawat_jalan($keyword);
		echo json_encode($data);
	}

	//TINDAKAN
	function encode($input){
		return strtr(base64_encode($input), '+/=', '._-');
	}

	function decode($input){
		return base64_decode(strtr($input, '._-', '+/='));
	}

	function tindakan_rj($id){
		$idx = $this->decode($id);

		$data = array(
			'page' => 'poli/rk_tindakan_rj_v',
			'title' => 'Pelayanan Rawat Jalan',
			'subtitle' => 'Pelayanan Rawat Jalan',
			'master_menu' => 'pelayanan',
			'view' => 'pelayanan_rj',
			'dt' => $this->model->data_rawat_jalan_id($idx),
			'id' => $idx,
			'url_simpan' => base_url().'poli/rk_pelayanan_rj_c/simpan_tindakan',
			'url_ubah' => base_url().'poli/rk_pelayanan_rj_c/ubah_tindakan',
			'url_hapus' => base_url().'poli/rk_pelayanan_rj_c/hapus_tindakan',
		);

		$this->load->view('poli/poli_home_v',$data);
	}

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
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_tindakan($id_pelayanan);
		echo json_encode($data);
	}

	function data_tindakan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_tindakan_id($id);
		echo json_encode($data);
	}

	function tindakan_id(){
		$id = $this->input->post('id');
		$data = $this->model->tindakan_id($id);
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

		$this->model->simpan($id,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$waktu,$total);
		$id_tindakan_rj = $this->db->insert_id();

		foreach ($id_tindakan as $key => $value) {
			$this->model->simpan_detail($id_tindakan_rj,$value,$tanggal,$bulan,$tahun,$jumlah[$key],$subtotal[$key],$waktu);
		}

		$this->session->set_flashdata('sukses','1');
		redirect('poli/rk_pelayanan_rj_c/tindakan_rj/'.$id); 
	}

	function ubah_tindakan(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_ubah');
		$tindakan = $this->input->post('id_tindakan_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$subtotal = str_replace(',', '', $this->input->post('subtotal_ubah'));

		$this->model->ubah_tindakan($id,$tindakan,$jumlah,$subtotal);

		$this->session->set_flashdata('ubah','1');
		redirect('poli/rk_pelayanan_rj_c/tindakan_rj/'.$id_pelayanan);
	}

	function hapus_tindakan(){
		$ket = $this->input->post('ket_hapus');
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id_hapus');

		$this->model->hapus_tindakan($id);
		
		$this->session->set_flashdata('hapus','1');
		redirect('poli/rk_pelayanan_rj_c/tindakan_rj/'.$id_pelayanan);
	}

	//LABORAT

	function update_ke_lab(){
		$id_pasien = $this->input->post('id_pasien');
		$this->model->update_ke_lab($id_pasien);
		echo '1';
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

		$this->model->simpan_pemeriksaan($kode_lab,$id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$jenis_laborat,$total_tarif,$cito,$tanggal,$bulan,$tahun,$waktu);
		$id_pemeriksaan_rj = $this->db->insert_id();
		$hasil = $this->input->post('hasil_periksa');
		$nilai_rujukan = $this->input->post('nilai_rujukan');

		foreach ($pemeriksaan as $key => $value) {
			$this->model->simpan_pemeriksaan_detail($id_pemeriksaan_rj,$value,$hasil[$key],$nilai_rujukan[$key],$tanggal,$bulan,$tahun,$subtotal[$key],$waktu);
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
		$data1 = $this->model->data_rawat_jalan_id($id_pelayanan);
		$data2 = $this->model->data_hasil_pemeriksaan($id);
		$data3 = $this->model->data_laborat_id($id);

		$data = array(
			'settitle' => 'Pelayanan Rawat Jalan',
			'filename' => 'hasil_laborat',
			'view'	=> 'rj',
			'data1' => $data1,
			'data2' => $data2,
			'data3' => $data3,
		);

		$this->load->view('poli/pdf/rk_laporan_hasil_lab_pdf_v',$data);
	}

	// DIAGNOSA

	function data_penyakit(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_penyakit($keyword);
		echo json_encode($data);
	}

	function data_penyakit_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_penyakit_id($id);
		echo json_encode($data);
	}

	function simpan_spesialistik(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$diagnosa = addslashes($this->input->post('diagnosa'));
		$tindakan = addslashes($this->input->post('tindakan_dg'));
		$id_penyakit = $this->input->post('id_penyakit');

		$this->model->simpan_diagnosa($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$diagnosa,$tindakan,$id_penyakit);

		echo '1';
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

	// RESEP

	function load_resep(){
		$keyword = $this->input->post('keyword');
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

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode_resep(){
		$keterangan = 'SIP-RESEP';
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
	    $keterangan = 'SIP-RESEP';
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
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$alergi = $this->input->post('alergi');
		$kode_resep = $this->input->post('kode_resep');
		$diminum_selama = $this->input->post('diminum_selama');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$total = str_replace(',', '', $this->input->post('grandtotal_resep'));

		$id_obat = $this->input->post('id_obat_resep');
		$harga = $this->input->post('harga_obat');
		$jumlah = $this->input->post('jumlah_obat');
		$takaran = $this->input->post('takaran_resep');
		$aturan_umum = $this->input->post('aturan_minum');

		$this->model->simpan_resep($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$alergi,$kode_resep,$diminum_selama,$tanggal,$bulan,$tahun,$total);
		$id_resep = $this->db->insert_id();

		foreach ($id_obat as $key => $value) {
			$subtotal = $harga[$key] * $jumlah[$key];
			$this->model->simpan_resep_det($id_resep,$value,$harga[$key],$jumlah[$key],$subtotal,$takaran[$key],$aturan_umum[$key]);
			$this->model->ubah_stok_obat($value,$jumlah[$key]);
		}

		$this->insert_kode_resep();

		echo '1';
	}

	function detail_resep(){
		$id_resep = $this->input->post('id');
		$data = $this->model->data_resep_det($id_resep);
		echo json_encode($data);
	}

	function hapus_resep(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id = $this->input->post('id');
		$this->model->hapus_resep($id,$id_pelayanan);
		$this->model->hapus_det_resep($id);
		echo '1';
	}

	//KONDISI AKHIR

	function cek_kondisi_akhir(){
		$id_pelayanan = $this->input->post('id_pelayanan');
		$id_poli = $this->input->post('id_poli');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');

		$data = $this->model->cek_kondisi_akhir($id_pelayanan,$id_poli,$id_pasien,$tanggal);

		echo json_encode($data);
	}

	function simpan_kondisi(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$kondisi_akhir = $this->input->post('kondisi_akhir');

		//RAWAT INAP
		$asal_rujukan = $this->input->post('asal_rujukan');
		$nama_penanggungjawab = $this->input->post('nama_pjawab');
		$telepon = $this->input->post('telepon');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$kelas = $this->input->post('kelas_kamar');
		$id_kamar = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed'); 

		//ICU
		$id_ruang_icu = $this->input->post('id_ruang_icu');
		$tarif_icu = str_replace(',', '', $this->input->post('tarif_icu'));

		//OPERASI
		$id_ruang_operasi = $this->input->post('id_ruang_opr');
		$tarif = str_replace(',', '', $this->input->post('tarif_operasi'));

		//MENINGGAL
		$id_kamar_jenazah = $this->input->post('id_kamar_jenazah');
		$id_lemari_jenazah = $this->input->post('id_lemari_jenazah');

		if($kondisi_akhir == 'Rawat Inap'){

			$this->model->simpan_rawat_inap($id_pasien,$tanggal,$bulan,$tahun,$asal_rujukan,$nama_penanggungjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed);
			$this->db->query("UPDATE admum_rawat_jalan SET STATUS_PINDAH = '$kondisi_akhir' WHERE ID = '$id_pelayanan'");
			$this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '1' WHERE ID = '$id_bed'");
		
		}else if($kondisi_akhir == 'Operasi'){

			$this->model->simpan_operasi($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_ruang_operasi,$tarif,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_operasi SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_operasi'");

		}else if($kondisi_akhir == 'Meninggal'){

			$this->model->simpan_meninggal($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_kamar_jenazah,$id_lemari_jenazah,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_lemari_jenazah SET STATUS_PAKAI = '1' WHERE ID = '$id_lemari_jenazah'");

		}else if($kondisi_akhir == 'ICU'){

			$this->model->simpan_icu($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$id_ruang_icu,$tarif_icu,$tanggal,$bulan,$tahun);
			$this->db->query("UPDATE admum_setup_ruang_icu SET STATUS_PAKAI = '1' WHERE ID = '$id_ruang_icu'");
		}

		$this->model->simpan_kondisi($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$kondisi_akhir);
		$this->db->query("UPDATE admum_rawat_jalan SET STATUS_SUDAH = '1' WHERE ID = '$id_pelayanan'");

		echo '1';
	}

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

	//ICU

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

	//OPERASI

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

	//MENINGGAL

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

	//PASIEN SUDAH

	function data_pasien_sudah(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_pasien_sudah($keyword);
		echo json_encode($data);
	}

	//SURAT DOKTER

	function data_surat_dokter(){
		$id_pelayanan = $this->input->post('id');
		$data = $this->model->data_surat_dokter($id_pelayanan);
		echo json_encode($data);
	}

	function data_surat_dokter_id(){
		$id_pasien = $this->input->post('id_pasien');
		$data = $this->model->data_surat_dokter_id($id_pasien);
		echo json_encode($data);
	}

	function cek_surat_dokter(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');

		$sql = "SELECT COUNT(*) AS TOTAL FROM rk_surat_dokter_rj WHERE ID_PASIEN = '$id_pasien' AND TANGGAL = '$tanggal'";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;

		echo json_encode($total);
	}

	function simpan_surat_dokter(){
		$id_pelayanan = $this->input->post('id_rj');
		$id_poli = $this->input->post('id_poli');
		$id_peg_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$waktu_istirahat = $this->input->post('waktu_sd');
		$mulai_tanggal = $this->input->post('mulai_tgl_sd');
		$sampai_tanggal = $this->input->post('sampai_tgl_sd');

		$this->model->simpan_surat_dokter($id_pelayanan,$id_poli,$id_peg_dokter,$id_pasien,$tanggal,$bulan,$tahun,$waktu_istirahat,$mulai_tanggal,$sampai_tanggal);
		
		echo '1';
	}

	function surat_dokter($id_pasien){
		$data1 = $this->model->data_surat_dokter_id($id_pasien);

		$data = array(
			'settitle' => 'Surat Dokter',
			'filename' => 'surat_dokter',
			'data1' => $data1,
		);

		$this->load->view('poli/pdf/rk_surat_dokter_rj_pdf_v',$data);
	}

	function surat_dokter_ada(){
		$id_pasien = $this->input->post('id_pasien');
		$data1 = $this->model->data_surat_dokter_id($id_pasien);

		$data = array(
			'settitle' => 'Surat Dokter',
			'filename' => 'surat_dokter',
			'data1' => $data1,
		);

		$this->load->view('poli/pdf/rk_surat_dokter_rj_pdf_v',$data);
	}

	function hapus_surat_dokter(){
		$id = $this->input->post('id_hapus_sd');
		$this->model->hapus_surat_dokter($id);
		echo '1';
	}

}