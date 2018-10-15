<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_permintaan_po_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('rekam_medik/rk_permintaan_po_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$sess_lock = $this->session->userdata('lock');
    	$id_user = $sess_user['id'];
    	$id_lock = $sess_lock['id_user'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_permintaan_po_v', 
			'title' => 'Permintaan PO (Pengadaan)',
			'subtitle' => 'Permintaan PO (Pengadaan)',
			'childtitle' => 'Umum',
			'master_menu' => 'permintaan_po',
			'view' => 'permintaan_po'
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

	function data_permintaan_barang(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_departemen = $sess_user['id_departemen'];
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->data_permintaan_barang($id_departemen,$bulan,$tahun);
		echo json_encode($data);
	}

	function detail_barang_permintaan(){
		$id_permintaan = $this->input->post('id_permintaan');
		$data = $this->model->detail_barang_permintaan($id_permintaan);
		echo json_encode($data);
	}

	function data_permintaan_barang_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_permintaan_barang_id($id);
		echo json_encode($data);
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

	function get_kode_po(){
		$keterangan = "PO-RM";
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

		//PO/ADM/001/X/2018
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "PO/RM/".$no."/".$this->romanic_number($bulan)."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND BULAN = '$bulan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "PO/RM/".$no."/".$this->romanic_number($bulan)."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
		$keterangan = "PO-RM";
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

	function data_peralatan(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_peralatan($keyword);
		echo json_encode($data);
	}

	function data_peralatan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_peralatan_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode_po = $this->input->post('kode_po');
		$id_dep = $this->input->post('id_departemen');
		$id_div = $this->input->post('id_divisi');
		$id_pegawai = $this->input->post('id_pegawai');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$total_barang = str_replace(',', '', $this->input->post('total_barang'));

		$this->model->simpan($kode_po,$id_dep,$id_div,$id_pegawai,$tanggal,$bulan,$tahun,$waktu,$total_barang);
		$id_permintaan = $this->db->insert_id();
		$id_barang = $this->input->post('id_barang');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));

		foreach ($id_barang as $key => $value) {
			$this->model->simpan_det($id_permintaan,$value,$jumlah[$key],$tanggal,$bulan,$tahun,$waktu);
		}

		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('rekam_medik/rk_permintaan_po_c');
	}

	function dibatalkan(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		$id = $this->input->post('id_batal');
		$tanggal = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$this->model->dibatalkan($id,$tanggal,$waktu,$id_user);

		$this->session->set_flashdata('batal','1');
		redirect('rekam_medik/rk_permintaan_po_c');
	}

}