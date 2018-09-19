<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_poli_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('setup/admum_poli_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_poli_v',
			'title' => 'Poli',
			'subtitle' => 'Poli',
			'master_menu' => 'poli',
			'childtitle' => '',
			'view' => 'poli',
			'url_simpan' => base_url().'setup/admum_poli_c/simpan',
			'url_ubah' => base_url().'setup/admum_poli_c/ubah',
			'url_hapus' => base_url().'setup/admum_poli_c/hapus',
			'url_cetak' => base_url().'setup/admum_poli_c/cetak',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function data_poli(){
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$pilih_jenis = $this->input->get('pilih_jenis');
		$cari = $this->input->post('cari');
		$data = $this->model->data_poli($keyword,$urutkan,$pilih_jenis,$cari);
		echo json_encode($data);
	}

	function data_poli_id(){
		$id = $this->input->post('id');
		$data['poli'] = $this->model->data_poli_id($id);
		$data['prwt'] = $this->model->data_poli_perawat($id);
		echo json_encode($data);
	}

	function get_departemen(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_departemen($keyword);
		echo json_encode($data);
	}

	function klik_departemen(){
		$id = $this->input->post('id');
		$data = $this->model->get_departemen_id($id);
		echo json_encode($data);
	}

	function get_divisi(){
		$id_dep = $this->input->get('id_dep');
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_divisi($id_dep,$keyword);
		echo json_encode($data);
	}

	function klik_divisi(){
		$id = $this->input->post('id');
		$data = $this->model->get_divisi_id($id);
		echo json_encode($data);
	}

	function data_peg_dokter(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_peg_dokter($keyword);
		echo json_encode($data);
	}

	function data_peg_dokter_id(){
		$id_pegawai = $this->input->post('id');
		$data = $this->model->data_peg_dokter_id($id_pegawai);
		echo json_encode($data);
	}

	function load_perawat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->load_perawat($keyword);
		echo json_encode($data);
	}

	function klik_perawat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_perawat($id);
		echo json_encode($data);
	}

	function data_poli_perawat(){
		$id_poli = $this->input->post('id');
		$data = $this->model->data_poli_perawat($id_poli);
		echo json_encode($data);
	}

	function simpan(){
		$id_dep = $this->input->post('id_departemen');
		$id_div = $this->input->post('id_divisi');
		$jenis = $this->input->post('jenis');
		$nama = $this->input->post('nama_poli');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$biaya = str_replace(',', '', $this->input->post('biaya'));
		$id_peg_dokter = $this->input->post('id_peg_dokter');
		$id_peg_perawat = $this->input->post('id_perawat');

		$this->model->simpan($id_dep,$id_div,$jenis,$nama,$status,$keterangan,$biaya,$id_peg_dokter);
		$id_poli = $this->db->insert_id();

		foreach ($id_peg_perawat as $key => $value) {
			$this->model->simpan_perawat($id_poli,$value);
		}

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_poli_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$id_dep = $this->input->post('id_departemen_ubah');
		$id_div = $this->input->post('id_divisi_ubah');
		$jenis_ubah = $this->input->post('cek_jenis_ubah');
		$jenis = '';
		$nama = $this->input->post('nama_poli_ubah');
		$status = $this->input->post('status_ubah');
		$keterangan = $this->input->post('keterangan_ubah');
		$biaya = str_replace(',', '', $this->input->post('biaya_ubah'));
		$id_peg_dokter = $this->input->post('id_peg_dokter_ubah');
		$id_peg_perawat = $this->input->post('id_perawat_ubah');

		if($jenis_ubah == '1'){
			$jenis = $this->input->post('jenis_ubah');
		}else{
			$jenis = $this->input->post('jenis_txt');
		}

		$this->model->ubah($id,$id_dep,$id_div,$jenis,$nama,$status,$keterangan,$biaya,$id_peg_dokter);

		foreach ($id_peg_perawat as $key => $value) {
			$this->model->simpan_perawat($id,$value);
		}

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_poli_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('setup/admum_poli_c');
	}

	function hapus_perawat(){
		$id = $this->input->post('id');
		$this->model->hapus_perawat($id);
		echo '1';
	}

	function cetak(){
		$cetak = $this->input->post('cetak');
		if($cetak == 'Excel'){
			$this->cetak_excel();
		}else{
			$this->cetak_pdf();
		}
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_nama_poli(),
			'filename' => date('dmY').'_data_poli'
		);

		$this->load->view('setup/excel/excel_data_poli_xls',$data);
	}

	function cetak_pdf(){
		$data = array(
			'dt' => $this->model->data_nama_poli(),
			'settitle' => 'Laporan Data Poli',
			'filename' => date('dmY').'_data_poli'
		);

		$this->load->view('setup/pdf/pdf_data_poli',$data);
	}

}