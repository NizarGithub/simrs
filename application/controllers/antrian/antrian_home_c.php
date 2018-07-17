<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antrian_home_c extends CI_Controller { 

	function __construct()  
	{ 
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }

	    $this->load->model("antrian/antrian_home_m", "model");
	} 

	function index()
	{
		$data = array(
			'page' => '',
			'title' => 'Pengaturan',
			'subtitle' => 'Pengaturan',
			'master_menu' => 'home',
			'view' => 'home',
			'msg' => '',
			'dtAntrian' => $this->model->getDataAntrian(),
			'dtAntrian_last' => $this->model->getDataAntrian_last(),
		);

		$this->load->view('antrian/antrian_home_v',$data);
	} 

	function getAntrian(){
		$data = $this->model->getDataAntrian();
		echo json_encode($data);
	}

	function getAntrianLast(){
		$data = $this->model->getDataAntrian_last();
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */