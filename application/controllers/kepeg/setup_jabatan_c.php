<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_jabatan_c extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url()); 
	    }
		$this->load->model('kepeg/setup_jabatan_m', 'model');
	}

	function index()
	{ 

		$msg = 0;
		$warning = 0;
		$kode_jab = "";
		$nama_jab   = "";
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$kode_jab     = addslashes($this->input->post('kode_jab'));
			$nama_jab     = addslashes($this->input->post('nama_jab'));
			$uraian       = addslashes($this->input->post('uraian'));

			$cek_kode = $this->model->cek_kode_jabatan($kode_jab);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_jabatan($kode_jab, $nama_jab, $uraian);

				$kode_jab = "";
				$nama_jab   = "";
				$uraian   = "";
			}
 

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_jabatan     = $this->input->post('id_jabatan');
			$ed_nama_jab    = addslashes($this->input->post('ed_nama_jab'));
			$ed_uraian      = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_jabatan($id_hapus);
		}

		$dt = $this->model->get_data_jabatan();

		$data = array(
			'page' => 'kepeg/setup_jabatan_v',
			'title' => 'Setup Jabatan',
			'subtitle' => 'Setup Jabatan',
			'master_menu' => 'master_setup',
			'view' => 'setup_jabatan',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_jab' => $kode_jab,
			'nama_jab' => $nama_jab,
			'uraian' => $uraian,
			'post_url' => 'kepeg/setup_jabatan_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_jabatan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_jab_by_id($id);
		echo json_encode($data);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */