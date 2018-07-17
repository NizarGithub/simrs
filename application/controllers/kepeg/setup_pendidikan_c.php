<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_pendidikan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url()); 
	    }
		$this->load->model('kepeg/setup_pendidikan_m', 'model'); 
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_golongan = ""; 
		$nama_golongan   = "";
		$nilai_ptkp   = "";

		if($this->input->post('simpan')){
			
			$kode_pendidikan   = $this->input->post('kode_pendidikan');
			$jenjang  		   = $this->input->post('jenjang');
			$bidang     	   = $this->input->post('bidang'); 
			$nama_pendidikan   = $this->input->post('nama_pendidikan'); 
			$pangkat_min       = $this->input->post('pangkat_min'); 
			$pangkat_max       = $this->input->post('pangkat_max'); 

			$cek_kode = $this->model->cek_pendidikan($kode_pendidikan);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_pendidikan(addslashes($kode_pendidikan), $jenjang, $bidang, addslashes($nama_pendidikan), $pangkat_min, $pangkat_max);

				$kode_pendidikan = ""; 
				$jenjang = "";
				$bidang    = "";
				$nama_pendidikan    = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_pen              = $this->input->post('id_pen');
			$ed_jenjang          = $this->input->post('ed_jenjang');
			$ed_bidang  	     = $this->input->post('ed_bidang');
			$ed_nama_pendidikan  = addslashes($this->input->post('ed_nama_pendidikan'));

			$this->model->ubah_pendidikan($id_pen, $ed_jenjang, $ed_bidang, $ed_nama_pendidikan);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_pendidikan($id_hapus);
		}

		// OPTIONAL

		if($this->input->post('add_jen')){
			$msg = 1;
			$add_nama_jenjang = addslashes($this->input->post('add_nama_jenjang'));
			$this->model->add_jenjang($add_nama_jenjang);

		} else if($this->input->post('hapus_jen')){
			$msg = 3;
			$id_jenjang = $this->input->post('jenjang_sel');
			$this->model->hapus_jenjang($id_jenjang);

		} else if($this->input->post('add_bid')){
			$msg = 1;
			$add_nama_bidang = addslashes($this->input->post('add_nama_bidang'));
			$this->model->add_bidang($add_nama_bidang);

		} else if($this->input->post('hapus_bid')){
			$msg = 3;
			$id_bidang = $this->input->post('bidang_sel');
			$this->model->hapus_bidang($id_bidang);
		}

		$dt = $this->model->get_data_pendidikan();

		$data = array(
			'page' => 'kepeg/setup_pendidikan_v',
			'title' => 'Setup Pendidikan',
			'subtitle' => 'Setup Pendidikan',
			'master_menu' => 'master_setup',
			'view' => 'pendidikan',
			'warning' => $warning,
			'dt' => $dt,
			'dt_jenjang' => $this->model->get_data_jenjang(),
			'dt_bidang' => $this->model->get_data_bidang(),
			'dt_pangkat_min' => $this->model->get_pangkat(),
			'msg' => $msg,
			'kode_golongan' => $kode_golongan,
			'nama_golongan' => $nama_golongan,
			'nilai_ptkp' => $nilai_ptkp,
			'post_url' => 'kepeg/setup_pendidikan_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_pendidikan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_pend_by_id($id);
		echo json_encode($data);
	}

	function get_pangkat_max(){
		$id_pangkat_min = $this->input->post('id_pangkat_min');
		$data = $this->model->get_pangkat_max($id_pangkat_min);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */