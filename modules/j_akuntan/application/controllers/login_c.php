<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_c extends CI_Controller {

	function __construct()
	{
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user != "" || $id_user != null){
	        redirect('../../');
	    }
	}

	function index()
	{
		$msg = 0;
		if($this->input->post('username')){
			$user = $this->input->post('username');
			$pass = md5(md5($this->input->post('password')));
			$tabel = "ak_user";
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
					'id_klien' => $data->ID_KLIEN,
				);
				$this->session->set_userdata('masuk_rs', $sess_array);
				$session_data = $this->session->userdata('masuk_rs');
				redirect('beranda_c');
			}else{
				$msg = 1;
			}
		}

		$data = array(
			'page' => '',
			'act' => '',
			'msg' => '',
			'act2' => '',
			'msg'  => $msg,
		);
		$this->load->view('login_v', $data);
	}

	function cek_uspa($tabel = '', $uspa = array()){
        $where = '';
        foreach($uspa as $key => $value){
            $where .= " AND $key = '$value'";
        }
        $data = $this->db->query("SELECT u.* FROM $tabel u WHERE 1=1 $where");

        return $data;
    }

	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */