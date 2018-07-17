<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_nilai_gaji_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('kepeg/setup_nilai_gaji_m', 'model'); 
	}

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){
			
			$kode_tunj = addslashes($this->input->post('kode_tunj'));


		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_tunjangan = $this->input->post('id_tunjangan');
			$ed_nama_tunj   = addslashes($this->input->post('ed_nama_tunj'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_tunjangan($id_tunjangan, $ed_nama_tunj, $ed_uraian);

		}

		$dt = $this->model->get_data_tunjangan();

		$data = array(
			'page' => 'kepeg/setup_nilai_gaji_v',
			'title' => 'Setup Nilai Tunjangan',
			'subtitle' => 'Setup Nilai Tunjangan',
			'master_menu' => 'master_setup',
			'view' => 'set_gaji_nilai',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'get_jabatan' => $this->model->get_jabatan(), 
			'post_url' => 'kepeg/setup_nilai_gaji_c', 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_data_tunjangan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_tunjangan_by_id($id);
		echo json_encode($data);
	}

	function get_pegawai(){
		$where = "1=1";
		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (a.NIP LIKE '%$keyword%' OR a.NAMA LIKE '%$keyword%' OR b.NAMA LIKE '%$keyword%')";
		}

		$sql = "
		SELECT a.*, b.NAMA AS JABATAN FROM kepeg_pegawai a 
		LEFT JOIN kepeg_jabatan b ON a.ID_JABATAN = b.ID
		WHERE $where
		ORDER BY a.ID ASC
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_jab_nilai(){
		$id_jabatan = $this->input->post('id_jabatan');
		$data = $this->model->get_data_tunjangan_by_jabatan($id_jabatan);
		echo json_encode($data);
	}

	function get_peg_nilai(){
		$id_pegawai = $this->input->post('id_pegawai');
		$id_jabatan = $this->input->post('id_jabatan');

		$data = "";

		$cek_data1 = $this->model->cek_data_tunj_peg($id_pegawai);

		$data1 = $this->model->get_data_tunjangan_by_pegawai($id_pegawai);
		$data2 = $this->model->get_data_tunjangan_by_jabatan($id_jabatan);

		if(count($cek_data1) > 0){
			$data = $data1;
		} else if(count($cek_data1) == 0) {
			$data = $data2;
		}
		
		echo json_encode($data);
	}

	function save_nilai_jab(){
		$id_jabatan = $this->input->post('id_jabatan');
		$id_gaji    = $this->input->post('id_gaji');
		$nilai      = $this->input->post('nilai');

		$this->model->delete_gaji_all($id_jabatan);

		foreach ($id_gaji as $key => $val) {
			$this->model->simpan_nilai_jab($id_jabatan, $val, $nilai[$key]);
		}

		echo json_encode('OK');
	}

	function save_nilai_peg(){
		$id_pegawai = $this->input->post('id_pegawai');
		$id_gaji    = $this->input->post('id_gaji_peg'); 
		$nilai      = $this->input->post('nilai_peg');

		$this->model->delete_gaji_all_peg($id_pegawai);

		foreach ($id_gaji as $key => $val) {
			$this->model->simpan_nilai_peg($id_pegawai, $val, $nilai[$key]);
		}

		echo json_encode('OK');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */