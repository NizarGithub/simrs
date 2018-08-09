<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_pengguna_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('setting/login_pengguna_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;
		$id_pegawai = "";

		if($this->input->post('simpan')){
			
			$id_pegawai     = addslashes($this->input->post('id_pegawai')); 
			$status         = addslashes($this->input->post('status')); 
			$username       = addslashes($this->input->post('username')); 
			$pass1          = addslashes($this->input->post('pass1')); 
			$pass2          = addslashes($this->input->post('pass2')); 
			//$pass_now       = addslashes($this->input->post('pass_now')); 
			$new_pass1      = addslashes($this->input->post('new_pass1')); 
			$new_pass2      = addslashes($this->input->post('new_pass2'));

			$sts_pass_awal  = addslashes($this->input->post('sts_pass_awal')); 
			$sts_pass_edit  = addslashes($this->input->post('sts_pass_edit'));
			$level       	= addslashes($this->input->post('level'));

			if($sts_pass_awal == 0){
				if($pass1 == $pass2){
					$this->model->simpan_login_user($id_pegawai, $status, $username, $level);
					$this->model->simpan_password_user($id_pegawai, $pass2);
					$msg = 1;
				} else {
					$warning = 1;
				}
			} else {
				
				if($sts_pass_edit == 1){
					if($new_pass1 == $new_pass2){
						$msg = 1;
						$this->model->simpan_login_user($id_pegawai, $status, $username, $level);
						$this->model->simpan_password_user($id_pegawai, $new_pass2);						
					} else {
						$warning = 1;
					}
				} else {
					$msg = 1;
					$this->model->simpan_login_user($id_pegawai, $status, $username, $level);
				}
			}

		} 

		$dt = "";

		$data = array(
			'page' => 'setting/login_pengguna_v',
			'title' => 'Login Pengguna',
			'subtitle' => 'Login Pengguna',
			'master_menu' => 'user_setting',
			'view' => 'login_user',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'id_pegawai' => $id_pegawai,
			'post_url' => 'setting/login_pengguna_c',
		);

		$this->load->view('setting/setting_master_home_v',$data);
	}

	function get_data_jabatan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_jab_by_id($id);
		echo json_encode($data);
	}

	function get_pegawai(){
		$where = "1=1";
		$keyword = $this->input->post('keyword');
		if($keyword != "" || $keyword != null){
			$where = $where." AND (NIP LIKE '%$keyword%' OR NAMA LIKE '%$keyword%' OR USERNAME LIKE '%$keyword%')";
		}

		$sql = "
		SELECT * FROM kepeg_pegawai WHERE $where
		ORDER BY ID ASC
		";

		$dt = $this->db->query($sql)->result();

		echo json_encode($dt);
	}

	function get_data_pegawai(){ 
		$id = $this->input->post('id');

		$sql = "
		SELECT * FROM kepeg_pegawai WHERE ID = $id
		";

		$dt = $this->db->query($sql)->row();

		echo json_encode($dt);
	}

	function cek_username(){
		$id_peg = $this->input->post('id_peg');
		$username = addslashes($this->input->post('username'));

		$data = $this->model->cek_username($id_peg, $username);

		echo json_encode(count($data));
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */