<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{

		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];

		$sql_menu = "   
			SELECT a.* FROM kepeg_menu_1 a
			JOIN kepeg_hak_akses b ON a.ID = b.ID_MENU
			WHERE b.ID_PEGAWAI = '$id_user' AND b.KET = 'MENU_PORTAL'
			ORDER BY a.URUT ASC
		";

		$dt_menu = $this->db->query($sql_menu)->result();

		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
			'dt_menu' => $dt_menu,
		);

		$this->load->view('portal_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */