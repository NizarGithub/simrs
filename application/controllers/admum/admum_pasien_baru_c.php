<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_baru_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
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
			'title' => 'Pasien Baru',
			'subtitle' => 'Pasien Baru',
			'childtitle' => 'Umum',
			'master_menu' => 'pasien',
			'view' => 'tambah_pasien',
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

	function simpan(){
		$kode_pasien = $this->input->post('kode_pasien');
		$tanggal_daftar = date('d-m-Y');
		$nama = addslashes($this->input->post('nama'));
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = addslashes($this->input->post('alamat'));
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = addslashes($this->input->post('tempat_lahir'));
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$kelurahan = addslashes($this->input->post('kelurahan'));
		$kecamatan = addslashes($this->input->post('kecamatan'));
		$kota = addslashes($this->input->post('kota'));
		$provinsi = $this->input->post('provinsi');

		$this->model->simpan(
			$kode_pasien,
			$tanggal_daftar,
			$nama,
			$jenis_kelamin,
			$pendidikan,
			$agama,
			$alamat,
			$golongan_darah,
			$tempat_lahir,
			$tanggal_lahir,
			$umur,
			$kelurahan,
			$kecamatan,
			$kota,
			$provinsi);

		$this->insert_kode_pasien();

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

	function get_history_medik(){
		$id_pasien = $this->input->post('id_pasien');
		$data = array();
		$data['detail_RJ'] = $this->model->getDetailLayananRJ($id_pasien, '');
		$data['detail_IGD'] = $this->model->getDetailLayananIGD($id_pasien, '');

		$data['detail_RI'] = $this->model->getDetailLayananRI($id_pasien, '');
		$data['dataDetVisite_RI'] = $this->model->dataDetVisite_RI($id_pasien, '');
		$data['dataDetGizi_RI'] = $this->model->dataDetGizi_RI($id_pasien, '');
		$data['dataDetOksigen_RI'] = $this->model->dataDetOksigen_RI($id_pasien, '');
		$data['dataDetDiagnosa_RI'] = $this->model->dataDetDiagnosa_RI($id_pasien, '');
		$data['dataDetResep_RI'] = $this->model->dataDetResep_RI($id_pasien, '');

		

		echo json_encode($data);
	}

	function get_history_medik_by_search_rj(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_RJ'] = $this->model->getDetailLayananRJ($id_pasien, $tgl);
		echo json_encode($data);
	}

	function get_history_medik_by_search_igd(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_IGD'] = $this->model->getDetailLayananIGD($id_pasien, $tgl);
		echo json_encode($data);
	}

	function get_history_medik_by_search_ri(){
		$id_pasien = $this->input->post('id_pasien');
		$tgl = addslashes($this->input->post('tgl'));
		$data = array();
		$data['detail_RI'] = $this->model->getDetailLayananRI($id_pasien, $tgl);
		$data['dataDetVisite_RI'] = $this->model->dataDetVisite_RI($id_pasien, $tgl);
		$data['dataDetGizi_RI'] = $this->model->dataDetGizi_RI($id_pasien, $tgl);
		$data['dataDetOksigen_RI'] = $this->model->dataDetOksigen_RI($id_pasien, $tgl);
		$data['dataDetDiagnosa_RI'] = $this->model->dataDetDiagnosa_RI($id_pasien, $tgl);
		$data['dataDetResep_RI'] = $this->model->dataDetResep_RI($id_pasien, $tgl);

		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */