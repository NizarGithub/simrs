<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Waiting_rawat_inap_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('admum/waiting_rawat_inap_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/waiting_rawat_inap_v',
			'title' => 'Waiting Rawat Inap',
			'subtitle' => 'Waiting Rawat Inap',
			'childtitle' => '',
			'master_menu' => 'waiting_rawat_inap',
			'view' => 'waiting_rawat_inap'
		);

		$this->load->view('admum/admum_home_v',$data);
	}

	function get_data_pasien_poli(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_data_pasien_poli($keyword);
		echo json_encode($data);
	}

	function klik_pasien_poli(){
		$id = $this->input->post('id');
		$data = $this->model->klik_pasien_poli($id);
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

	function get_biaya_adm(){
		$sistem_bayar = $this->input->post('sistem_bayar');
		$data = $this->master_model_m->get_biaya_adm($sistem_bayar);
		echo json_encode($data);
	}

	function ubah(){
		$id = $this->input->post('id_ri');
		$pjawab = $this->input->post('nama_pjawab');
		$telepon = $this->input->post('telepon');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$kelas = $this->input->post('kelas_kamar');
		$id_kamar = $this->input->post('id_ruangan');
		$id_bed = $this->input->post('id_bed');
		$biaya_kamar = str_replace(',', '', $this->input->post('biaya'));
		$biaya_charge = str_replace(',', '', $this->input->post('biaya_charge_kamar'));
		$biaya_reg = str_replace(',', '', $this->input->post('biaya_adm'));

		$this->model->update_rawat_inap($id,$pjawab,$telepon,$sistem_bayar,$kelas,$id_kamar,$id_bed,$biaya_kamar,$biaya_charge,$biaya_reg);
		$this->model->update_stt_pakai($id_bed);

		$id_asuransi = $this->input->post('id_kerjasama');
		$no_polis = $this->input->post('nomor_polis');
		$no_peserta = $this->input->post('nomor_peserta');
		$nama = $this->input->post('nama');
		$status_pasien = $this->input->post('status_pasien');

		if($sistem_bayar == '2'){
			$this->model->simpan_asuransi($id,$id_asuransi,$no_polis,$no_peserta,$nama,$status_pasien);
		}

		$this->session->set_flashdata('sukses','1');
		redirect('admum/waiting_rawat_inap_c');
	}

}