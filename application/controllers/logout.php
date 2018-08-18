<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index(){
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_login = $sess_user['id'];
    	$tanggal = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$sql_log = "UPDATE kepeg_pegawai SET STS_LOGIN = '0',DATE_LOG = '$tanggal',TIME_LOG = '$waktu' WHERE ID = '$id_login'";
		$this->db->query($sql_log);

		$this->session->unset_userdata('masuk_rs');
		$this->session->sess_destroy();
		redirect('login_c');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */