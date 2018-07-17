<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_tunjangan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_tunjangan_m', 'model'); 
	}

	function index() 
	{

		$msg = 0;
		$warning = 0;
		$kode_tunj = ""; 
		$nama_tunj = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$kode_tunj = addslashes($this->input->post('kode_tunj'));
			$nama_tunj = addslashes($this->input->post('nama_tunj')); 
			$uraian   = addslashes($this->input->post('uraian'));

			$cek_kode = $this->model->cek_kode_tunj($kode_tunj);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_tunjangan($kode_tunj, $nama_tunj, $uraian);

				$kode_tunj = "";
				$nama_tunj = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_tunjangan = $this->input->post('id_tunjangan');
			$ed_nama_tunj   = addslashes($this->input->post('ed_nama_tunj'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_tunjangan($id_tunjangan, $ed_nama_tunj, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_tunjangan($id_hapus);
		}

		$dt = $this->model->get_data_tunjangan();

		$data = array(
			'page' => 'kepeg/setup_tunjangan_v',
			'title' => 'Setup Tunjangan',
			'subtitle' => 'Setup Tunjangan',
			'master_menu' => 'master_setup',
			'view' => 'set_tunjangan',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_tunj' => $kode_tunj,
			'nama_tunj' => $nama_tunj,
			'uraian' => $uraian,
			'post_url' => 'kepeg/setup_tunjangan_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_tunjangan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_tunjangan_by_id($id);
		echo json_encode($data);
	}

	function ubah_dep(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_tunjangan_by_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */