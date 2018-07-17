<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rk_periksa_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien(){
		$sql = "SELECT * FROM pasien ORDER BY ID DESC";
		$query = $this->db->query($sql);
		return $query->result();
	}

}