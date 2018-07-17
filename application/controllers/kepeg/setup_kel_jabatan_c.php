<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_kel_jabatan_c extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_kel_jabatan_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_kel_jab = "";
		$nama_kel_jab   = "";
		$jenis   = "";

		if($this->input->post('simpan')){
			
			$kode_kel_jab     = addslashes($this->input->post('kode_kel_jab'));
			$nama_kel_jab     = addslashes($this->input->post('nama_kel_jab'));
			$jenis            = addslashes($this->input->post('jenis'));

			$cek_kode = $this->model->cek_kode_kel_jabatan($kode_kel_jab);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_kel_jabatan($kode_kel_jab, $nama_kel_jab, $jenis);

				$kode_kel_jab = "";
				$nama_kel_jab   = "";
				$jenis   = "";
			}
 

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_kel_jabatan       = $this->input->post('id_kel_jabatan');
			$ed_kode_kel_jab      = addslashes($this->input->post('ed_kode_kel_jab'));
			$ed_nama_kel_jab      = addslashes($this->input->post('ed_nama_kel_jab'));
			$ed_jenis             = addslashes($this->input->post('ed_jenis'));

			$this->model->ubah_jabatan($id_kel_jabatan, $ed_nama_kel_jab, $ed_jenis);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_kel_jabatan($id_hapus);
		}

		$dt = $this->model->get_data_kel_jabatan();

		$data = array(
			'page' => 'kepeg/setup_kel_jabatan_v',
			'title' => 'Setup Kelompok Jabatan',
			'subtitle' => 'Setup Kelompok Jabatan',
			'master_menu' => 'master_setup',
			'view' => 'kel_jab',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_kel_jab' => $kode_kel_jab,
			'nama_kel_jab' => $nama_kel_jab,
			'jenis' => $jenis,
			'post_url' => 'kepeg/setup_kel_jabatan_c',
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