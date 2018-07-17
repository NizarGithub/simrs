<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hak_akses_c extends CI_Controller { 

	function __construct() 
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');  
		$id_user = $sess_user['id']; 
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }  
		$this->load->model('setting/hak_akses_m', 'model'); 
	} 
   
	function index() 
	{  

		$msg = 0;
		$warning = 0;
		$id_pegawai = "";

		$dt_pegawai = "";

		if($this->input->post('id_pegawai')){
			
			$id_pegawai     = $this->input->post('id_pegawai');
			$dt_pegawai 	= $this->model->get_data_pegawai($id_pegawai);

		} else if($this->input->post('simpan')){

			$msg = 1;
			$id_pegawai      = $this->input->post('id_pegawai2');
			$menu_portal     = $this->input->post('menu_portal');
			$ch_menu2        = $this->input->post('ch_menu2');
			$ch_menu3        = $this->input->post('ch_menu3');

			$this->model->hapus_all_akses($id_pegawai);

			foreach ($menu_portal as $key => $m_portal) {
				$this->model->simpan_hak_akses_menu_portal($id_pegawai, $m_portal, 'MENU_PORTAL');
			}

			foreach ($ch_menu2 as $key => $m2) {
				$this->model->simpan_hak_akses_menu_portal($id_pegawai, $m2, 'MENU_2');
			}

			foreach ($ch_menu3 as $key => $m3) {
				$this->model->simpan_hak_akses_menu_portal($id_pegawai, $m3, 'MENU_3');
			}


			$dt_pegawai 	= $this->model->get_data_pegawai($id_pegawai);
		}

		

		$data = array(
			'page' => 'setting/hak_akses_v',
			'title' => 'Hak Akses',
			'subtitle' => 'Hak Akses',
			'master_menu' => 'user_setting', 
			'view' => 'hak_akses',
			'warning' => $warning,
			'dt_pegawai' => $dt_pegawai,
			'id_pegawai' => $id_pegawai,
			'msg' => $msg,
			'post_url' => 'setting/hak_akses_c',
			'get_menu_1' => $this->model->get_data_menu_1($id_pegawai),
		);

		$this->load->view('setting/setting_master_home_v',$data);
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */