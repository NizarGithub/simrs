<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_golongan_pajak_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();  
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id']; 
		if($id_user == "" || $id_user == null){
	        redirect(base_url()); 
	    }
		$this->load->model('kepeg/setup_golongan_pajak_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_golongan = ""; 
		$nama_golongan   = "";
		$nilai_ptkp   = "";

		if($this->input->post('simpan')){
			
			$kode_golongan   = $this->input->post('kode_golongan');
			$nama_golongan   = $this->input->post('nama_golongan');
			$nilai_ptkp      = $this->input->post('nilai_ptkp'); 

			$cek_kode = $this->model->cek_golongan($kode_golongan);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_golongan(addslashes($kode_golongan), addslashes($nama_golongan), $nilai_ptkp);

				$kode_golongan = ""; 
				$nama_golongan = "";
				$nilai_ptkp    = "";
			} 

		} else if($this->input->post('ubah')){ 

			$msg = 2;
			$id_gol            = $this->input->post('id_gol');
			$ed_kode_golongan  = $this->input->post('ed_kode_golongan');
			$ed_nama_golongan  = addslashes($this->input->post('ed_nama_golongan'));
			$ed_nilai_ptkp     = str_replace(',', '', $this->input->post('ed_nilai_ptkp'));

			$this->model->ubah_golongan($id_gol, $ed_kode_golongan, $ed_nama_golongan, $ed_nilai_ptkp);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_golongan_pajak($id_hapus);
		}

		$dt = $this->model->get_data_golongan_pajak();

		$data = array(
			'page' => 'kepeg/setup_golongan_pajak_v',
			'title' => 'Setup Golongan Pajak',
			'subtitle' => 'Setup Golongan Pajak',
			'master_menu' => 'master_setup',
			'view' => 'gol_pajak',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_golongan' => $kode_golongan,
			'nama_golongan' => $nama_golongan,
			'nilai_ptkp' => $nilai_ptkp,
			'post_url' => 'kepeg/setup_golongan_pajak_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_golongan_pajak(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_gol_by_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */