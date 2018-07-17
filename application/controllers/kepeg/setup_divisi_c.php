<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_divisi_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_divisi_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$id_dep = "";
		$nama_dep = "";
		$kode_div = "";
		$nama_div = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$id_dep     = addslashes($this->input->post('id_dep'));
			$nama_dep   = addslashes($this->input->post('dep'));
			$kode_div   = addslashes($this->input->post('kode_div')); 
			$nama_div   = addslashes($this->input->post('nama_div'));
			$uraian     = addslashes($this->input->post('uraian'));

			$cek_kode = $this->model->cek_kode_divisi($kode_div);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_divisi($id_dep, $kode_div, $nama_div, $uraian);

				$id_dep = "";
				$nama_dep = "";
				$kode_div = "";
				$nama_div = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_divisi     = $this->input->post('id_divisi');
			$id_departemen     = $this->input->post('ed_id_dep');
			$ed_nama_div   = addslashes($this->input->post('ed_nama_div'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_divisi($id_divisi, $id_departemen, $ed_nama_div, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_divisi($id_hapus);
		}

		$dt = $this->model->get_data_departemen();

		$data = array(
			'page' => 'kepeg/setup_divisi_v',
			'title' => 'Setup Divisi',
			'subtitle' => 'Setup Divisi',
			'master_menu' => 'master_setup',
			'view' => 'setup_div',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'id_dep' => $id_dep,
			'nama_dep' => $nama_dep,
			'kode_div' => $kode_div,
			'nama_div' => $nama_div,
			'uraian' => $uraian,
			'post_url' => 'kepeg/setup_divisi_c',
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_departemen(){
		$where = "1=1";
		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (KODE LIKE '%$keyword%' OR NAMA_DEP LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM kepeg_departemen WHERE $where AND STS = 0
		ORDER BY ID ASC
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_data_divisi(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_div_by_id($id);
		echo json_encode($data);
	}

	function get_data_dep(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_dep_by_id($id);
		echo json_encode($data);
	}



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */