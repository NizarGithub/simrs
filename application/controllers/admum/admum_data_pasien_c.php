<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_data_pasien_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('admum/admum_data_pasien_m','model');
		$this->load->model('master_model_m','m_master');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_data_pasien_v',
			'title' => 'Data Pasien',
			'subtitle' => 'Data Pasien',
			'childtitle' => '',
			'master_menu' => 'pasien',
			'view' => 'data_pasien',
			'url_ubah' => base_url().'admum/admum_data_pasien_c/ubah',
			'url_hapus' => base_url().'admum/admum_data_pasien_c/hapus',
			'url_cetak' => base_url().'admum/admum_data_pasien_c/cetak_pasien',
		);

		$this->load->view('admum/admum_home_v',$data); 
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
		$keterangan = "User ".strtoupper($nama)." Departemen ".strtoupper($dep)." Divisi ".strtoupper($div)." telah melakukan ".strtoupper($aksi)." data";

		$this->m_master->simpan_log($id_pegawai,$tanggal,$waktu,$keterangan);
	}

	function pasien_rj()
	{
		$data = array(
			'page' => 'admum/admum_data_pasien_rj_v',
			'title' => 'Data Pasien Rawat Jalan',
			'subtitle' => 'Data Pasien Rawat Jalan',
			'childtitle' => '',
			'master_menu' => 'pasien',
			'view' => 'data_pasien',
			'url_ubah' => base_url().'admum/admum_data_pasien_c/ubah_rj',
			'url_hapus' => base_url().'admum/admum_data_pasien_c/hapus',
			'url_cetak' => base_url().'admum/admum_data_pasien_c/cetak_excel_pasien_rj',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function pasien_ri()
	{
		$data = array(
			'page' => 'admum/admum_data_pasien_ri_v',
			'title' => 'Data Pasien Rawat Inap',
			'subtitle' => 'Data Pasien Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'pasien',
			'view' => 'data_pasien',
			'url_ubah' => base_url().'admum/admum_data_pasien_c/ubah_ri',
			'url_hapus' => base_url().'admum/admum_data_pasien_c/hapus',
			'url_cetak' => base_url().'admum/admum_data_pasien_c/cetak_excel_pasien_ri',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function pasien_igd()
	{
		$data = array(
			'page' => 'admum/admum_data_pasien_igd_v',
			'title' => 'Data Pasien IGD',
			'subtitle' => 'Data Pasien IGD',
			'childtitle' => '',
			'master_menu' => 'pasien',
			'view' => 'data_pasien',
			'url_ubah' => base_url().'admum/admum_data_pasien_c/ubah_igd',
			'url_hapus' => base_url().'admum/admum_data_pasien_c/hapus',
			'url_cetak' => base_url().'admum/admum_data_pasien_c/cetak_excel_pasien_igd',
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function data_provinsi(){
		$id_kota_kab = $this->input->post('id_kota_kab');
		$data = $this->model->provinsi($id_kota_kab);
		echo json_encode($data);
	}

	function kirim_permintaan(){
		$this->simpan_log('Permintaan Hapus');
		$id_pegawai = $this->input->post('id_pegawai');
		$id_pasien = $this->input->post('id_pasien_kirim');
		$permintaan = 'Permintaan Hapus Data Pasien';
		$this->model->simpan_permintaan($id_pegawai,$id_pasien,$permintaan);

		$this->session->set_flashdata('kirim','1');
		redirect('admum/admum_data_pasien_c');
	}

	//PASIEN PER POLI
	function data_pasien_per_poli(){
		$keyword = $this->input->get('keyword');
		$now = date('d-m-Y');

		$data = $this->model->data_pasien_per_poli($keyword,$now);
		echo json_encode($data);
	}

	function data_pasien_per_poli_id(){
		$id = $this->input->post('id');
		$data['pl'] = $this->model->data_poli_id($id);
		$data['ps'] = $this->model->data_pasien_per_poli_id($id);
		echo json_encode($data);
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama = $this->input->post('nama');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = $this->input->post('alamat');
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$kelurahan = $this->input->post('kelurahan');
		$kecamatan = $this->input->post('kecamatan');
		$kota = $this->input->post('kota');
		$provinsi = "";
		$status = "Umum";

		$ubah_kota = $this->input->post('ubah_kota');
		if($ubah_kota){
			$provinsi = $this->input->post('provinsi_ubah');
		}else{
			$provinsi = $this->input->post('provinsi');
		}

		$this->model->ubah_pasien(
			$id,
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
			$provinsi,
			$status);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_data_pasien_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus_pasien($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_data_pasien_c');
	}
	//

	// PASIEN RJ
	function data_pasien_rj(){
		$keyword = $this->input->get('keyword');
		$now = date('d-m-Y');
		$data = $this->model->data_pasien_rj($keyword,$now);
		echo json_encode($data);
	}

	function ubah_rj(){
		$id = $this->input->post('id_ubah');
		$nama = $this->input->post('nama');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = $this->input->post('alamat');
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$kelurahan = $this->input->post('kelurahan');
		$kecamatan = $this->input->post('kecamatan');
		$kota = $this->input->post('kota');
		$provinsi = "";
		$status = "RJ";

		$ubah_kota = $this->input->post('ubah_kota');
		if($ubah_kota){
			$provinsi = $this->input->post('provinsi_ubah');
		}else{
			$provinsi = $this->input->post('provinsi');
		}

		$this->model->ubah_pasien(
			$id,
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
			$provinsi,
			$status);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_data_pasien_c/pasien_rj');
	}
	//

	// PASIEN RAWAT INAP
	function data_pasien_ri(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_pasien_ri($keyword);
		echo json_encode($data);
	}

	function ubah_ri(){
		$id = $this->input->post('id_ubah');
		$nama = $this->input->post('nama');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = $this->input->post('alamat');
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$kelurahan = $this->input->post('kelurahan');
		$kecamatan = $this->input->post('kecamatan');
		$kota = $this->input->post('kota');
		$provinsi = "";
		$status = "RI";

		$ubah_kota = $this->input->post('ubah_kota');
		if($ubah_kota){
			$provinsi = $this->input->post('provinsi_ubah');
		}else{
			$provinsi = $this->input->post('provinsi');
		}

		$this->model->ubah_pasien(
			$id,
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
			$provinsi,
			$status);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_data_pasien_c/pasien_ri');
	}
	//

	// PASIEN IGD
	function data_pasien_igd(){
		$id_klien = "";
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$pilih_umur = $this->input->get('pilih_umur');
		$status = "IGD";
		$data = $this->model->data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$status);
		echo json_encode($data);
	}

	function ubah_igd(){
		$id = $this->input->post('id_ubah');
		$nama = $this->input->post('nama');
		$jenis_kelamin = $this->input->post('jenis_kelamin');
		$pendidikan = $this->input->post('pendidikan');
		$agama = $this->input->post('agama');
		$alamat = $this->input->post('alamat');
		$golongan_darah = $this->input->post('golongan_darah');
		$tempat_lahir = $this->input->post('tempat_lahir');
		$tanggal_lahir = $this->input->post('tanggal_lahir');
		$umur = $this->input->post('umur');
		$kelurahan = $this->input->post('kelurahan');
		$kecamatan = $this->input->post('kecamatan');
		$kota = $this->input->post('kota');
		$provinsi = "";
		$status = "IGD";

		$ubah_kota = $this->input->post('ubah_kota');
		if($ubah_kota){
			$provinsi = $this->input->post('provinsi_ubah');
		}else{
			$provinsi = $this->input->post('provinsi');
		}

		$this->model->ubah_pasien(
			$id,
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
			$provinsi,
			$status);

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_data_pasien_c/pasien_ri');
	}
	//

	//CETAK PASIEN
	function cetak_pasien(){
		$cetak = $this->input->post('pilihan_cetak');
		if($cetak == 'Excel'){
			$this->cetak_excel();
		}else{
			$this->cetak_pdf();
		}
	}

	function cetak_excel(){
		$keyword = $this->input->post('cari_pasien');
		$now = date('d-m-Y');

		$data = array(
			'title' => 'Data Pasien',
			'subtitle' => 'Data Pasien',
			'dt' => $this->model->data_pasien_per_poli($keyword,$now),
			'filename' => date('dmY').'_laporan_pasien_per_poli'
		);

		$this->load->view('admum/excel/excel_data_pasien',$data); 
	}

	function cetak_pdf(){
		$keyword = $this->input->post('cari_pasien');
		$now = date('d-m-Y');

		$data = array(
			'title' => 'Data Pasien',
			'subtitle' => 'Data Pasien',
			'dt' => $this->model->data_pasien_per_poli($keyword,$now),
			'settitle' => 'Laporan Pasien Per Poli',
			'filename' => date('dmY').'_laporan_pasien_per_poli'
		);

		$this->load->view('admum/pdf/cetak_pasien_per_poli_pdf',$data);
	}

	function cetak_excel_pasien_rj(){
		$id_klien = "";
		$keyword = $this->input->post('cari_pasien');
		$urutkan = $this->input->post('urutkan');
		$pilih_umur = "";
		$status = "RJ";
		$now = str_replace('-','_',date('d-m-Y'));

		$data = array(
			'title' => 'Data Pasien Rawat Jalan',
			'subtitle' => 'Data Pasien Rawat Jalan',
			'dt' => $this->model->data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$status),
			'filename' => 'laporan_data_pasien_rj_'.$now,
		);

		$this->load->view('admum/excel/excel_data_pasien_rj',$data);
	}

	function cetak_excel_pasien_ri(){
		$id_klien = "";
		$keyword = $this->input->post('cari_pasien');
		$urutkan = $this->input->post('urutkan');
		$pilih_umur = "";
		$status = "RI";
		$now = str_replace('-','_',date('d-m-Y'));

		$data = array(
			'title' => 'Data Pasien Rawat Inap',
			'subtitle' => 'Data Pasien Rawat Inap',
			'dt' => $this->model->data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$status),
			'filename' => 'laporan_data_pasien_ri_'.$now,
		);

		$this->load->view('admum/excel/excel_data_pasien_ri',$data);
	}

	function cetak_excel_pasien_igd(){
		$id_klien = "";
		$keyword = $this->input->post('cari_pasien');
		$urutkan = $this->input->post('urutkan');
		$pilih_umur = "";
		$status = "IGD";
		$now = str_replace('-','_',date('d-m-Y'));

		$data = array(
			'title' => 'Data Pasien IGD',
			'subtitle' => 'Data Pasien IGD',
			'dt' => $this->model->data_pasien($id_klien,$keyword,$urutkan,$pilih_umur,$status),
			'filename' => 'laporan_data_pasien_ri_'.$now,
		);

		$this->load->view('admum/excel/excel_data_pasien_igd',$data);
	}

	function get_history_medik_rj(){
		$id_pasien = $this->input->post('id_pasien');
		$data['detail_RJ'] = $this->model->getDetailLayananRJ($id_pasien, '');

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