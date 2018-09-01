<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pindah_kamar_ri_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('admum/admum_pindah_kamari_ri_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_pindah_kamar_ri_v',
			'title' => 'Pindah Kamar Rawat Inap',
			'subtitle' => 'Pindah Kamar Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'pindah_kamar',
			'view' => 'pindah_kamar'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function load_data_pasien(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_data_pasien($keyword);
		echo json_encode($data);
	}

	function klik_pasien(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien($id);
		echo json_encode($data);
	}

	function load_kamar(){
		$keyword = $this->input->post('keyword');
		$kelas = $this->input->post('kelas');
		$data = $this->model->load_kamar($keyword,$kelas);
		echo json_encode($data);
	}

	function klik_kamar(){
		$id = $this->input->post('id');
		$data = $this->model->klik_kamar($id);
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

	function simpan_log($aksi,$id_pasien){
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_pegawai = $sess_user['id'];
    	$sql = "SELECT
					a.ID,
					a.NAMA,
					b.NAMA_DEP,
					c.NAMA_DIV
				FROM kepeg_pegawai a
				LEFT JOIN kepeg_departemen b ON b.ID = a.ID_DEPARTEMEN
				LEFT JOIN kepeg_divisi c ON c.ID = a.ID_DIVISI
				WHERE a.ID = '$id_pegawai'
    	";
    	$qry = $this->db->query($sql);
    	$row = $qry->row();
    	$nama = $row->NAMA;
    	$dep = $row->NAMA_DEP;
    	$div = $row->NAMA_DIV;
		$tanggal = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$ket = '';
		if($aksi == 'pindah_kamar'){
			$ket = 'pindah kamar '.strtoupper('rawat inap');
		}else{
			$ket = strtoupper($aksi);
		}
		$keterangan = "User ".strtoupper($nama)." Departemen ".strtoupper($dep)." Divisi ".strtoupper($div)." telah melakukan ".$ket;

		$this->master_model_m->simpan_log2($id_pegawai,$id_pasien,$tanggal,$waktu,$keterangan);
	}

	function simpan(){
		$id_ri = $this->input->post('id_ri');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$nama = $this->input->post('nama');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$umur = $this->input->post('umur');
		$alamat = $this->input->post('alamat');
		$id_pasien = $this->input->post('id_pasien');
		$hubungan = $this->input->post('hubungan_pasien');
		$id_kamar_lama = $this->input->post('id_kamar_lama');
		$id_bed_lama = $this->input->post('id_bed_lama');
		$id_kamar_baru = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed');

		// $this->model->simpan($id_ri,$tanggal,$bulan,$tahun,$waktu,$nama,$jenis_kelamin,$umur,$alamat,$id_pasien,$hubungan,$id_kamar_lama,$id_kamar_baru,$id_bed);
		// $this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '0' WHERE ID = '$id_bed_lama'");
		// $this->db->query("UPDATE admum_bed_rawat_inap SET STATUS_PAKAI = '1' WHERE ID = '$id_bed'");

		// $this->simpan_log('pindah_kamar',$id_pasien);

		// $this->session->set_flashdata('sukses','1');
		// redirect('admum/admum_pindah_kamar_ri_c');

		echo '1';
	}

	function cetak_form(){
		$arrayName = array(
			'settitle' => 'Form Pindah Kamar Rawat Inap',
			'filename' => 'form_pindah_kamar_ri' 
		);

		$this->load->view('admum/pdf/form_pindah_kamar_pdf',$arrayName);
	}

}