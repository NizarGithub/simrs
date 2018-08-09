<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_not_found extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data = array(
			'title' => 'Coming Soon',
			'subtitle' => 'Coming Soon'
		);

		$this->load->view('page_not_found_v',$data);
	}

}