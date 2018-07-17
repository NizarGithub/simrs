<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_admin_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data = array(
			'page' => '',
			'title' => 'Home Admin',
			'subtitle' => 'Home Admin',
			'view' => 'home',
		);

		$this->load->view('admin/home_admin_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */