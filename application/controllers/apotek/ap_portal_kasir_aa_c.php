<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_portal_kasir_aa_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index(){
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
      'page' => 'apotek/ap_beranda_aa_v',
      'master_menu' => 'kasir_aa',
			'view' => 'kasir_aa',
      'subtitle' => ''
		);

		$this->load->view('apotek/ap_portal_kasir_aa_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
