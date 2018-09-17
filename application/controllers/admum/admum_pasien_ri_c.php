<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_pasien_ri_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
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
			'title' => 'Rawat Inap',
			'subtitle' => 'Pendaftaran Rawat Inap',
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
		if($aksi == 'ri'){
			$ket = 'pendaftaran '.strtoupper('rawat inap');
		}else{
			$ket = strtoupper($aksi);
		}
		$keterangan = "User ".strtoupper($nama)." Departemen ".strtoupper($dep)." Divisi ".strtoupper($div)." telah melakukan ".$ket;

		$this->master_model_m->simpan_log2($id_pegawai,$id_pasien,$tanggal,$waktu,$keterangan);
	}

	function simpan(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$bulan = date('n');
		$tahun = date('Y');
		$nama_pjawab = $this->input->post('nama_pjawab');
		$telepon = $this->input->post('telepon');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$asal_rujukan = $this->input->post('rujukan_dari');
		$id_dokter = $this->input->post('id_dokter');
		$kelas = $this->input->post('kelas_kamar');
		$id_kamar = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed');
		$biaya_kamar = str_replace(',', '', $this->input->post('biaya'));
		$biaya_adm = str_replace(',', '', $this->input->post('biaya_adm'));

		$this->model->simpan_ri($id_pasien,$tanggal_masuk,$waktu,$bulan,$tahun,$nama_pjawab,$telepon,$sistem_bayar,$asal_rujukan,$id_dokter,$id_asuransi,$kelas,$id_kamar,$id_bed,$biaya_kamar,$biaya_adm);
		$this->model->update_stt_pakai($id_bed);
		$id_ri = $this->db->insert_id();
		$id_asuransi = $this->input->post('id_kerjasama');
		$no_polis = $this->input->post('nomor_polis');
		$no_peserta = $this->input->post('nomor_peserta');
		$nama = $this->input->post('nama');
		$status_pasien = $this->input->post('status_pasien');

		if($sistem_bayar == '2'){
			$this->model->simpan_asuransi($id_ri,$id_asuransi,$no_polis,$no_peserta,$nama,$status_pasien);
		}

		$this->simpan_log('ri',$id_pasien);

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_pasien_ri_c');
	}

	function data_provinsi(){
		$id_kota_kab = $this->input->post('id_kota_kab');
		$data = $this->model->provinsi($id_kota_kab);
		echo json_encode($data);
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

	function load_dokter(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_dokter($keyword);
		echo json_encode($data);
	}

	function klik_dokter(){
		$id = $this->input->post('id');
		$data = $this->model->klik_dokter($id);
		echo json_encode($data);
	}

	function load_asuransi(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_asuransi($keyword);
		echo json_encode($data);
	}

	function klik_asuransi(){
		$id = $this->input->post('id');
		$data = $this->model->klik_asuransi($id);
		echo json_encode($data);
	}

	function get_biaya_adm(){
		$sistem_bayar = $this->input->post('sistem_bayar');
		$data = $this->master_model_m->get_biaya_adm($sistem_bayar);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */