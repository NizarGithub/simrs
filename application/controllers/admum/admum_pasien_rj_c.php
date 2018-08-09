<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_rj_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_pasien_rj_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_pasien_rj_v',
			'title' => 'Pendaftaran Rawat Jalan',
			'subtitle' => 'Pasien Baru',
			'childtitle' => 'Rawat Jalan',
			'master_menu' => 'pasien',
			'view' => 'pasien_rj',
			'url_simpan' => base_url().'admum/admum_pasien_rj_c/simpan'
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

	function randomNumber($length) {
	    $result = '';

	    for($i = 0; $i < $length; $i++) {
	        $result .= mt_rand(0, 9);
	    }

	    return $result;
	}

	function get_barcode(){
		$barcode = $this->randomNumber(12);
		echo json_encode($barcode);
	}

	function simpan_antrian(){
		$tanggal = date('d-m-Y');
		$sql = "SELECT * FROM kepeg_antrian WHERE TGL = '$tanggal'";
		$qry = $this->db->query($sql);
		$urut = '';
		if($qry->num_rows() > 0){
			$data = $qry->row()->URUT;
			$urut = $data+1;
			$s = "UPDATE kepeg_antrian SET URUT = '$urut' WHERE TGL = '$tanggal'";
			$this->db->query($s);
		}else{
			$kode = 'A';
			$urut = '1';
			$s = "INSERT INTO kepeg_antrian(KODE,URUT,TGL) VALUES('$kode','$urut','$tanggal')";
			$this->db->query($s);
		}
	}

	function simpan(){
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

		$this->model->simpan_rj($id_pasien_new,$asal_rujukan,$hari,$tanggal,$bulan,$tahun,$id_poli,$pilihan);
		$this->model->simpan_antrian($tanggal,$waktu,$id_pasien_new,$barcode,$nomor_antrian);
		$this->simpan_antrian();

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_pasien_rj_c');
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

	function load_poli(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_poli($keyword);
		echo json_encode($data);
	}

	function klik_poli(){
		$id = $this->input->post('id');
		$data = $this->model->klik_poli($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */