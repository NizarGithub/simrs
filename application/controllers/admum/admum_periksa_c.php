<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_periksa_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('rekam_medik/rk_periksa_m','model');
	}

	function index()
	{
		$data = array(
			'page' => 'rekam_medik/rk_periksa_v',
			'title' => 'Periksa',
			'subtitle' => 'Periksa',
			'master_menu' => '',
			'view' => 'periksa',
			'data_pasien' => $this->model->data_pasien()
		);

		$this->load->view('rekam_medik/rk_home_v',$data);
	}

}