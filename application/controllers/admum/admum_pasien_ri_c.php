<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_ri_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_pasien_ri_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{

		

		$data = array(
			'page' => 'admum/admum_pasien_ri_v',
			'title' => 'Pendaftaran Rawat Inap',
			'subtitle' => 'Pasien Baru',
			'childtitle' => 'Rawat Inap',
			'master_menu' => 'pasien',
			'view' => 'pasien_ri',
			'url_simpan' => base_url().'admum/admum_pasien_ri_c/simpan' 
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
		$id_pasien_new = "";
		$baru = $this->input->post('baru');

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

		$tanggal_masuk = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$asal_rujukan = $this->input->post('asal_rujukan');
		$nama_pjawab = $this->input->post('nama_pjawab');
		$telepon = $this->input->post('telepon');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$kelas = $this->input->post('kelas_kamar');
		$id_kamar = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed');

		if($baru){
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

			$id_pasien_new = $this->db->insert_id();

			$this->model->simpan_ri($id_pasien_new,$tanggal_masuk,$bulan,$tahun,$asal_rujukan,$nama_pjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed);
			$this->model->update_stt_pakai($id_bed);

			$this->insert_kode_pasien();
		}else{
			$id_pasien_new = $this->input->post('id_pasien');

			$this->model->simpan_ri($id_pasien_new,$tanggal_masuk,$bulan,$tahun,$asal_rujukan,$nama_pjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed);
			$this->model->update_stt_pakai($id_bed);

		}

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_pasien_ri_c');
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */