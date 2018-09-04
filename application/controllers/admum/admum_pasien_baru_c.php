<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_baru_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('admum/admum_pasien_baru_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    } 
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_pasien_baru_v', 
			'title' => 'Daftar Pasien Baru',
			'subtitle' => 'Daftar Pasien Baru',
			'childtitle' => 'Umum',
			'master_menu' => 'pasien',
			'view' => 'pasien',
			'url_simpan' => base_url().'admum/admum_pasien_baru_c/simpan'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function romanic_number($integer, $upcase = true) { 
	    $table = array(
	    	'M'		=>1000, 
	    	'CM'	=>900, 
	    	'D'		=>500, 
	    	'CD'	=>400, 
	    	'C'		=>100, 
	    	'XC'	=>90, 
	    	'L'		=>50, 
	    	'XL'	=>40, 
	    	'X'		=>10, 
	    	'IX'	=>9, 
	    	'V'		=>5, 
	    	'IV'	=>4, 
	    	'I'		=>1
	    ); 
	    
	    $return = ''; 
	    while($integer > 0) 
	    { 
	        foreach($table as $rom=>$arb) 
	        { 
	            if($integer >= $arb) 
	            { 
	                $integer -= $arb; 
	                $return .= $rom; 
	                break; 
	            } 
	        } 
	    } 

	    return $return; 
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

	function next_antri(){
		$kode_antrian = $this->input->post('kode_antrian');
		$jml_antrian  = $this->input->post('jml_antrian');
		$id_antrian   = $this->input->post('id_antrian');

		$this->model->simpanAntrian($kode_antrian, $jml_antrian, $id_antrian);

		echo json_encode(1);
	}

	function simpan_log($aksi){
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
		if($aksi == 'daftar'){
			$ket = 'pendaftaran '.strtoupper('pasien baru');
		}else{
			$ket = strtoupper($aksi);
		}
		$keterangan = "User ".strtoupper($nama)." Departemen ".strtoupper($dep)." Divisi ".strtoupper($div)." telah melakukan ".$ket;

		$this->master_model_m->simpan_log($id_pegawai,$tanggal,$waktu,$keterangan);
	}

	function simpan(){
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$kode_pasien = $this->input->post('kode_pasien');
		$tanggal_daftar = date('d-m-Y');
		$waktu_daftar = $format->format('H:i:s');
		$nama = addslashes($this->input->post('nama'));
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = addslashes($this->input->post('alamat'));
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = addslashes($this->input->post('tempat_lahir'));
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$umur_bulan = $this->input->post('umur_bulan');
		$nama_ayah = addslashes($this->input->post('nama_ayah'));
		$nama_ibu = addslashes($this->input->post('nama_ibu'));
		$telepon = $this->input->post('telepon');
		$kelurahan = addslashes($this->input->post('kelurahan'));
		$kecamatan = addslashes($this->input->post('kecamatan'));
		$kota = addslashes($this->input->post('kota'));
		$provinsi = addslashes($this->input->post('provinsi'));

		$this->model->simpan(
			$kode_pasien,
			$tanggal_daftar,
			$waktu_daftar,
			$nama,
			$jenis_kelamin,
			$pendidikan,
			$agama,
			$alamat,
			$golongan_darah,
			$tempat_lahir,
			$tanggal_lahir,
			$umur,
			$umur_bulan,
			$nama_ayah,
			$nama_ibu,
			$telepon,
			$kelurahan,
			$kecamatan,
			$kota,
			$provinsi
		);

		$this->insert_kode_pasien();
		$this->simpan_log('daftar');

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_pasien_baru_c');
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */