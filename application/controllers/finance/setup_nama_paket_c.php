<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_nama_paket_c extends CI_Controller { 

	function __construct()  
	{ 
		parent::__construct();
		$this->load->model('finance/setup_nama_paket_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/setup_nama_paket_v',
			'title' => 'Setup Nama Paket',
			'subtitle' => 'Setup Nama Paket',
			'master_menu' => 'master_setup',
			'view' => 'master_paket'
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function get_paket(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_paket($keyword);
		echo json_encode($data);
	}

	function get_paket_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_paket_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$nama_paket = $this->input->post('nama_paket');
		$hari = $this->input->post('hari');
		$this->model->simpan($nama_paket,$hari);

		$this->session->set_flashdata('sukses','1');
		redirect('finance/setup_nama_paket_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_paket = $this->input->post('nama_paket_ubah');
		$hari = $this->input->post('hari_ubah');
		$this->model->ubah($id,$nama_paket,$hari);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/setup_nama_paket_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/setup_nama_paket_c');
	}

	function decode($input){
		return base64_decode(strtr($input, '._-', '+/='));
	}

	function pengaturan_paket($idx){
		$id = $this->decode($idx);
		$dt = $this->model->get_paket_id($id);

		$data = array(
			'page' => 'finance/pengaturan_paket_v',
			'title' => 'Pengaturan Paket',
			'subtitle' => 'Paket : '.$dt->NAMA_PAKET,
			'master_menu' => 'master_setup',
			'view' => 'master_paket',
			'id_paket' => $id
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function get_kamar_paket(){
		$id_paket = $this->input->post('id_paket');
		$data = $this->model->get_kamar_paket($id_paket);
		echo json_encode($data);
	}

	function get_kamar_paket_id(){
		$id = $this->input->post('id');
		$data['row'] = $this->model->get_kamar_paket_id($id);
		$data['tdk'] = $this->model->get_tindakan_paket($id);
		echo json_encode($data);
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

	function get_tindakan_paket(){
		$id_kamar_paket = $this->input->post('id_kamar_paket');
		$data = $this->model->get_tindakan_paket_det($id_kamar_paket);
		echo json_encode($data);
	}

	function simpan_kamar(){
		$id_paket = $this->input->post('id_paket');
		$kelas = $this->input->post('kelas_kamar');
		$biaya_kamar_bersalin = str_replace(',', '', $this->input->post('biaya_kamar_bersalin'));
		$biaya_kamar_perawatan = str_replace(',', '', $this->input->post('biaya_kamar_perawatan'));
		$biaya_kamar_neo = str_replace(',', '', $this->input->post('biaya_kamar_neo'));
		$biaya_pelayanan = str_replace(',', '', $this->input->post('biaya_pelayanan'));
		$biaya_obat = str_replace(',', '', $this->input->post('biaya_obat'));
		$buku_paspor = str_replace(',', '', $this->input->post('buku_paspor'));
		$jasa_operator = str_replace(',', '', $this->input->post('jasa_operator'));
		$visite_dokter = str_replace(',', '', $this->input->post('biaya_visite'));
		$visite_prof = str_replace(',', '', $this->input->post('biaya_visite_prof'));
		$jasa_anastesi = str_replace(',', '', $this->input->post('jasa_anastesi'));
		$jasa_penata_anastesi = str_replace(',', '', $this->input->post('jasa_penata_anastesi'));
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');

		$this->model->simpan_kamar(
			$id_paket,
			$kelas,
			$biaya_kamar_bersalin,
			$biaya_kamar_perawatan,
			$biaya_kamar_neo,
			$biaya_pelayanan,
			$biaya_obat,
			$buku_paspor,
			$jasa_operator,
			$visite_dokter,
			$visite_prof,
			$jasa_anastesi,
			$jasa_penata_anastesi,
			$tanggal,
			$bulan,
			$tahun);

		$id_kamar_paket = $this->db->insert_id();
		$id_tindakan = $this->input->post('id_tindakan');
		if ($id_tindakan != "") {
			foreach ($id_tindakan as $key => $value) {
				$this->model->simpan_tindakan($id_kamar_paket,$value);
			}
		}

		echo '1';
	}

	function ubah_kamar(){
		$id = $this->input->post('id_ubah');
		$kelas = $this->input->post('kelas_kamar_ubah');
		$biaya_kamar_bersalin = str_replace(',', '', $this->input->post('biaya_kamar_bersalin_ubah'));
		$biaya_kamar_perawatan = str_replace(',', '', $this->input->post('biaya_kamar_perawatan_ubah'));
		$biaya_kamar_neo = str_replace(',', '', $this->input->post('biaya_kamar_neo_ubah'));
		$biaya_pelayanan = str_replace(',', '', $this->input->post('biaya_pelayanan_ubah'));
		$biaya_obat = str_replace(',', '', $this->input->post('biaya_obat_ubah'));
		$buku_paspor = str_replace(',', '', $this->input->post('buku_paspor_ubah'));
		$jasa_operator = str_replace(',', '', $this->input->post('jasa_operator_ubah'));
		$visite_dokter = str_replace(',', '', $this->input->post('biaya_visite_ubah'));
		$visite_prof = str_replace(',', '', $this->input->post('biaya_visite_prof_ubah'));
		$jasa_anastesi = str_replace(',', '', $this->input->post('jasa_anastesi_ubah'));
		$jasa_penata_anastesi = str_replace(',', '', $this->input->post('jasa_penata_anastesi_ubah'));

		$this->model->ubah_kamar($id,$kelas,$biaya_kamar_bersalin,$biaya_kamar_perawatan,$biaya_kamar_neo,$biaya_pelayanan,$biaya_obat,$buku_paspor,$jasa_operator,$visite_dokter,$visite_prof,$jasa_anastesi,$jasa_penata_anastesi);

		$id_kamar_paket = $this->input->post('id_kamar_paket');
		$id_tindakan = $this->input->post('id_tindakan');

		$this->model->hapus_tindakan($id);

		foreach ($id_tindakan as $key => $value) {
			$this->model->simpan_tindakan($id,$value);
		}

		echo '1';
	}

	function hapus_kamar(){
		$id = $this->input->post('id');

		$this->model->hapus_tindakan($id);
		$this->model->hapus_kamar($id);

		echo '1';
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */