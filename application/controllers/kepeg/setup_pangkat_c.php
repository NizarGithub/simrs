<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_pangkat_c extends CI_Controller {

	function __construct()
	{ 
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url()); 
	    }
		$this->load->model('kepeg/setup_pangkat_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$nama_pangkat = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$golongan   = $this->input->post('golongan');
			$ruang   = $this->input->post('ruang');
			$nama_pangkat = addslashes($this->input->post('nama_pangkat')); 

			$cek_kode = $this->model->cek_nama_pangkat($golongan, $ruang);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_pangkat($nama_pangkat, $golongan, $ruang);

				$nama_pangkat = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_pangkat = $this->input->post('id_pangkat');
			$ed_nama_pangkat     = addslashes($this->input->post('ed_nama_pangkat'));

			$this->model->ubah_pangkat($id_pangkat, $ed_nama_pangkat);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_pangkat($id_hapus);
		}

		$dt = $this->model->get_data_pangkat();

		$data = array(
			'page' => 'kepeg/setup_pangkat_v',
			'title' => 'Setup Pangkat',
			'subtitle' => 'Setup Pangkat',
			'master_menu' => 'master_setup',
			'view' => 'pangkat',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'nama_pangkat' => $nama_pangkat,
			'uraian' => $uraian,
			'post_url' => 'kepeg/setup_pangkat_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_pangkat(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_pang_by_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */