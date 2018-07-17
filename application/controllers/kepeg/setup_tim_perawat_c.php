<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_tim_perawat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_tim_perawat_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('nama_tim')){
			$msg = 1;
			$nama_tim   = $this->input->post('nama_tim');
			$id_ketua   = $this->input->post('id_ketua');
			$id_anggota = $this->input->post('id_anggota');
			$id_kamar   = $this->input->post('id_kamar');

			$this->model->simpanTim($nama_tim, $id_ketua);
			$id_tim = $this->model->getIDTim()->ID;

			foreach ($id_anggota as $key => $anggota) {
				$this->model->simpanAnggotaTim($id_tim, $anggota);
			}

			foreach ($id_kamar as $key => $kamar) {
				$this->model->simpanKamar($id_tim, $kamar);
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_departemen = $this->input->post('id_departemen');
			$ed_nama_dep   = addslashes($this->input->post('ed_nama_dep'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_departemen($id_departemen, $ed_nama_dep, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapusTim($id_hapus);
		}

		$dt = $this->model->getDataTim();
		$data = array(
			'page' => 'kepeg/setup_tim_perawat_v',
			'title' => 'Setup Tim Perawat',
			'subtitle' => 'Setup Tim Perawat',
			'master_menu' => 'master_setup',
			'view' => 'setup_tim_perawat',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/setup_tim_perawat_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function ubah($id_tim){
		$msg = 0;
		$warning = 0;

		if($this->input->post('nama_tim')){
			$msg = 1;
			$nama_tim   = $this->input->post('nama_tim');
			$id_ketua   = $this->input->post('id_ketua');
			$id_anggota = $this->input->post('id_anggota');
			$id_kamar   = $this->input->post('id_kamar');

			$this->model->UbahTim($id_tim, $nama_tim, $id_ketua);

			$this->model->HapusAnggotaTim($id_tim);
			foreach ($id_anggota as $key => $anggota) {
				$this->model->simpanAnggotaTim($id_tim, $anggota);
			}

			$this->model->HapusKamarTim($id_tim);
			foreach ($id_kamar as $key => $kamar) {
				$this->model->simpanKamar($id_tim, $kamar);
			}

		}

		$dt = $this->model->getDataTimbyID($id_tim);
		$dt_anggota = $this->model->getAnggotaTim($id_tim);
		$dt_kamar = $this->model->getKamarTim($id_tim);
		$data = array(
			'page' => 'kepeg/setup_tim_perawat_ubah_v',
			'title' => 'Ubah Tim Perawat',
			'subtitle' => 'Ubah Tim Perawat',
			'master_menu' => 'master_setup',
			'view' => 'setup_tim_perawat',
			'warning' => $warning,
			'dt' => $dt,
			'dt_anggota' => $dt_anggota,
			'dt_kamar' => $dt_kamar,
			'msg' => $msg,
			'id_tim' => $id_tim,
			'post_url' => 'kepeg/setup_tim_perawat_c/ubah/'.$id_tim,
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_dep(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_dep_by_id($id);
		echo json_encode($data);
	}

	function data_kamar(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->get_data_kamar($keyword);
		echo json_encode($data);
	}

	function klik_kamar(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_kamar_id($id);
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */