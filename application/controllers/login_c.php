<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user != "" || $id_user != null){
	        redirect('portal');
	    }
	}

	function index()
	{

		$msg = "";

		if($this->input->post('username')){
			$user = $this->input->post('username');
			$pass = md5(md5($this->input->post('password')));
			$tabel = "kepeg_pegawai";
			$uspa = array(
				'USERNAME'	=> $user,
				'PASSWORD'	=> $pass
			);
			$cek_uspa = $this->cek_uspa($tabel,$uspa);
			$jumlah = $cek_uspa->num_rows();
			
			if($jumlah != 0){
				$data = $cek_uspa->row();
				$sess_array = array(
					'id'		 => $data->ID,
					'username'	 => $data->USERNAME,
					'id_klien'	 => 13,
				);
				$this->session->set_userdata('masuk_rs', $sess_array);
				$session_data = $this->session->userdata('masuk_rs');
				redirect('portal');
			}else{
				$msg = 1;
			}
		}

		$data = array(
			'title' => 'Login',
			'subtitle' => 'Login',
			'msg' => $msg,
		);

		$this->load->view('login_v',$data);
	}

	function cek_uspa($tabel = '', $uspa = array()){
        $where = '';
        foreach($uspa as $key => $value){
            $where .= " AND $key = '$value'";
        }
        $data = $this->db->query("SELECT u.* FROM $tabel u WHERE 1=1 $where AND STS_AKUN = 1");

        return $data;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */