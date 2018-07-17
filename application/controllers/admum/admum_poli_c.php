<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_poli_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('admum/admum_poli_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'admum/admum_poli_v',
			'title' => 'Poli',
			'subtitle' => 'Poli',
			'master_menu' => 'poli',
			'childtitle' => '',
			'view' => 'poli',
			'url_simpan' => base_url().'admum/admum_poli_c/simpan',
			'url_ubah' => base_url().'admum/admum_poli_c/ubah',
			'url_hapus' => base_url().'admum/admum_poli_c/hapus',
			'url_cetak' => base_url().'admum/admum_poli_c/cetak_excel',
		);

		$this->load->view('admum/admum_home_v',$data);
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
		$nama = $this->input->post('nama_poli');
		$initial_poli = $this->input->post('inisial_poli');
		$jenis = $this->input->post('jenis');
		$text_layar = strtoupper($nama);
		$id_peg_dokter = $this->input->post('id_peg_dokter');
		$id_peg_perawat = $this->input->post('id_perawat');

		$this->model->simpan($nama,$initial_poli,$jenis,$text_layar,$id_peg_dokter);
		$id_poli = $this->db->insert_id();

		foreach ($id_peg_perawat as $key => $value) {
			$this->model->simpan_perawat($id_poli,$value);
		}

		$this->session->set_flashdata('sukses','1');
		redirect('admum/admum_poli_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama = $this->input->post('nama_poli_ubah');
		$initial_poli = $this->input->post('inisial_poli_ubah');
		$jenis = $this->input->post('jenis_ubah');
		$text_layar = strtoupper($nama);
		$id_peg_dokter = $this->input->post('id_peg_dokter_ubah');
		$id_peg_perawat = $this->input->post('id_perawat_ubah');

		$this->model->ubah($id,$nama,$initial_poli,$jenis,$text_layar,$id_peg_dokter);

		foreach ($id_peg_perawat as $key => $value) {
			$this->model->simpan_perawat($id,$value);
		}

		$this->session->set_flashdata('ubah','1');
		redirect('admum/admum_poli_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('admum/admum_poli_c');
	}

	function hapus_perawat(){
		$id = $this->input->post('id');
		$this->model->hapus_perawat($id);
		echo '1';
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_poli('','','',''),
		);

		$this->load->view('admum/excel/excel_data_poli_xls',$data);
	}

}