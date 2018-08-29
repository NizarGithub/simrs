<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_setup_tindakan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('setup/admum_setup_tindakan_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'setup/admum_setup_tindakan_v',
			'title' => 'Setup Tindakan',
			'subtitle' => 'Setup Tindakan',
			'childtitle' => '',
			'master_menu' => 'setup',
			'view' => 'tindakan',
			'url_simpan' => base_url().'setup/admum_setup_tindakan_c/simpan',
			'url_ubah' => base_url().'setup/admum_setup_tindakan_c/ubah',
			'url_hapus' => base_url().'setup/admum_setup_tindakan_c/hapus',
			'url_cetak' => base_url().'setup/admum_setup_tindakan_c/cetak_excel',
		);

		$this->load->view('setup/setup_home_v',$data);
	}

	function cetak_excel(){
		$data = array(
			'dt' => $this->model->data_tindakan(''),
		);

		$this->load->view('setup/excel/excel_tindakan_xls',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function get_kode(){
		$id_klien = '1';
		$keterangan = 'SIP-TINDAKAN';
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//001
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = $no;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = $no;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
		$id_klien = '1';
	    $keterangan = 'SIP-TINDAKAN';
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	} 

	function data_tindakan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_tindakan($keyword);
		echo json_encode($data);
	}

	function data_tindakan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_tindakan_id($id);
		echo json_encode($data);
	}

	function simpan(){
		$kode = $this->input->post('kode');
		$nama_tindakan = addslashes($this->input->post('nama_tindakan'));
		$tarif = str_replace(',', '', $this->input->post('tarif'));

		$this->model->simpan($kode,$nama_tindakan,$tarif);
		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('setup/admum_setup_tindakan_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$nama_tindakan = addslashes($this->input->post('nama_tindakan_ubah'));
		$tarif = str_replace(',', '', $this->input->post('tarif_ubah'));

		$this->model->ubah($id,$nama_tindakan,$tarif);

		$this->session->set_flashdata('ubah','1');
		redirect('setup/admum_setup_tindakan_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('setup/admum_setup_tindakan_c');
	}

}