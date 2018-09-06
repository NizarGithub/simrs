<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_kamar_rawat_inap_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('setup/admum_setup_kamar_rawat_inap_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index(){
		$data = array(
			'page' => 'setup/admum_setup_kamar_rawat_inap_v',
			'title' => 'Setup Kamar Rawat Inap',
			'subtitle' => 'Setup Kamar Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'setup_kamar',
			'view' => 'setup_kamar_rawat_inap',
			'url_simpan' => base_url().'setup/admum_setup_kamar_rawat_inap_c/simpan',
			'url_simpan_bed' => base_url().'setup/admum_setup_kamar_rawat_inap_c/simpan_bed',
			'url_ubah' => base_url().'setup/admum_setup_kamar_rawat_inap_c/ubah',
			'url_hapus' => base_url().'setup/admum_setup_kamar_rawat_inap_c/hapus',
			'url_hapus_bed' => base_url().'setup/admum_setup_kamar_rawat_inap_c/hapus_bed',
			'url_cetak' => base_url().'setup/admum_setup_kamar_rawat_inap_c/cetak',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function cetak(){
		if($this->input->post('excel')){
			$this->cetak_excel();
		}else{
			$this->cetak_pdf();
		}
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_kamar('','Default','',''),
			'filename' => date('dmY').'_laporanDataKamarRawatInap'
		);

		$this->load->view('setup/excel/excel_kamar_rawat_inap_xls',$data);
	}

	function cetak_pdf(){
		$data = array(
			'dt' => $this->model->data_kamar('','Default','',''),
			'settitle' => 'Daftar Tarif Kamar dan Visite Dokter',
			'filename' => date('dmY').'_laporanDataKamarRawatInap'
		);

		$this->load->view('setup/pdf/laporan_kamar_rawat_inap_pdf',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function kode_kamar(){
		$id_klien = '1';
		$keterangan = 'RAWAT-INAP';

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND ID_KLIEN = '$id_klien'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//KRI-001
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "KRI-".$no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "KRI-".$no;
		}

		echo json_encode($kode);
	}

	function insert_kode_kamar(){
		$id_klien = '1';
	    $keterangan = 'RAWAT-INAP';

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND ID_KLIEN = '$id_klien'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(ID_KLIEN,NEXT,KETERANGAN) VALUES ('$id_klien','1','$keterangan')");
		}else{
			$sql = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'");
		}
	}

	function data_kamar(){
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$cari_berdasarkan = $this->input->get('cari_berdasarkan');
		$pilih_kelas = $this->input->get('pilih_kelas');

		$data = $this->model->data_kamar($keyword,$urutkan,$cari_berdasarkan,$pilih_kelas);

		echo json_encode($data);
	}

	function data_kamar_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_kamar_id($id);
		echo json_encode($data);
	}

	function cek_nomor_kamar(){
		$nomor = $this->input->post('nomor');
		$data = $this->model->cek_nomor_kamar($nomor);
		echo json_encode($data);
	}

	function data_bed(){
		$id_kamar_rawat_inap = $this->input->post('id');
		$data['detail'] = $this->model->data_kamar_id($id_kamar_rawat_inap);
		$data['bed'] = $this->model->data_bed($id_kamar_rawat_inap);
		echo json_encode($data);
	}

	function data_bed_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_bed_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_kamar = $this->input->post('kode_kamar');
		$kelas = $this->input->post('kelas_kamar');
		$biaya = str_replace(',', '', $this->input->post('biaya'));
		$visite_dokter = $this->input->post('visite');
		$biaya_visite = str_replace(',', '', $this->input->post('tarif'));
		$jasa_sarana = str_replace(',', '', $this->input->post('jasa_sarana'));
		$peruntukan_kamar = $this->input->post('peruntukan_kamar');
		$jumlah_bed = str_replace(',', '', $this->input->post('jumlah_bed'));
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan($kode_kamar,$kelas,$biaya,$visite_dokter,$biaya_visite,$jasa_sarana,$peruntukan_kamar,$jumlah_bed,$tanggal,$bulan,$tahun);
		// $this->insert_kode_kamar();

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_setup_kamar_rawat_inap_c');
	}

	function simpan_bed(){
		$id_kamar_rawat_inap = $this->input->post('id_kamar');
		$no = $this->input->post('no');
		$nomor_bed = $this->input->post('nomor_bed');
		$jumlah = $this->input->post('jumlah');

		foreach ($no as $key => $value) {
			$this->model->simpan_bed($id_kamar_rawat_inap,$value,$nomor_bed[$key],$jumlah[$key]);
		}

		$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID_KAMAR_RAWAT_INAP = '$id_kamar_rawat_inap' ORDER BY ID DESC LIMIT 1";
		$qry = $this->db->query($sql)->row();
		$id_last = $qry->ID;
		$this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_HAPUS = '1' WHERE ID = '$id_last'");

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_setup_kamar_rawat_inap_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$kode_kamar = $this->input->post('kode_kamar_ubah');
		$kelas = $this->input->post('kelas_kamar_ubah');
		$biaya = str_replace(',', '', $this->input->post('biaya_ubah'));
		$visite_dokter = $this->input->post('visite_ubah');
		$biaya_visite = str_replace(',', '', $this->input->post('tarif_ubah'));
		$jasa_sarana = str_replace(',', '', $this->input->post('jasa_sarana_ubah'));
		$peruntukan_kamar = $this->input->post('peruntukan_kamar_ubah');
		$jumlah_bed = str_replace(',', '', $this->input->post('jumlah_bed_ubah'));

		$this->model->ubah($id,$kode_kamar,$kelas,$biaya,$visite_dokter,$biaya_visite,$jasa_sarana,$peruntukan_kamar,$jumlah_bed);

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_setup_kamar_rawat_inap_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM admum_bed_rawat_inap WHERE ID_KAMAR_RAWAT_INAP = '$id'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total = $qry_cek->TOTAL;

		$msg = "";
		if($total == 0){
			$this->model->hapus($id);
			$msg = 'hapus';
		}else{
			$msg = 'tidak_hapus';
		}

		$this->session->set_flashdata($msg,'1');
		redirect('setup/admum_setup_kamar_rawat_inap_c');
	}

	function hapus_bed(){
		$id = $this->input->post('id_hapus_bed');
		$id_kamar_rawat_inap = $this->input->post('id_kamar_rawat_inap');
		$this->model->hapus_bed($id);

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM admum_bed_rawat_inap WHERE ID_KAMAR_RAWAT_INAP = '$id_kamar_rawat_inap'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total = $qry_cek->TOTAL;

		if($total != 0){
			$sql = "SELECT * FROM admum_bed_rawat_inap WHERE ID_KAMAR_RAWAT_INAP = '$id_kamar_rawat_inap' ORDER BY ID DESC LIMIT 1";
			$qry = $this->db->query($sql)->row();
			$id_last = $qry->ID;
			$this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_HAPUS = '1' WHERE ID = '$id_last'");
		}else{

		}

		// $this->session->set_flashdata('hapus','1');
		// redirect('setup/admum_setup_kamar_rawat_inap_c');
		echo '1';
	}

}