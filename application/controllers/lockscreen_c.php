<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lockscreen_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        
	    }
	}

	function index()
	{
		$data = array(
			'title' => 'Login',
			'subtitle' => 'Login'
		);

		$this->load->view('lockscreen_v',$data);
	}

}