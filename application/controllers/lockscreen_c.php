<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lockscreen_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	}

	function index()
	{
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];

		$sess_array = array(
			'id_user'		 => $id_user,
		);

		$this->session->set_userdata('lock', $sess_array);
		$session_data = $this->session->userdata('lock');

		$data = array(
			'title' => 'Lockscreen',
			'subtitle' => 'Lockscreen'
		);

		$this->load->view('lockscreen_v',$data);
	}

	function unlock(){
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
    	$level = $sess_user['level'];
		$pass = md5(md5($this->input->post('password')));

		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
			WHERE a.ID = '$id_user' AND a.PASSWORD = '$pass'
		";
		$qry = $this->db->query($sql);
		$jumlah = $qry->num_rows();

		if($jumlah != 0){
			$this->session->unset_userdata('lock');

			if($level == 'Admission'){
				redirect('admum/admum_pasien_baru_c');
			}else if($level == 'Poli'){
				redirect('poli/poli_home_c');
			}else if($level == 'Laborat'){
				redirect('lab/lab_home_c');
			}else if($level == 'Farmasi'){
				redirect('apotek/ap_home_c');
			}else if($level == 'Rekam Medik'){
				redirect('rekam_medik/rk_home_c');
			}else if($level == 'Kasir AA'){
				redirect('apotek/ap_portal_kasir_aa_c');
			}else if($level == 'Kasir Rajal'){
				redirect('apotek/ap_kasir_rajal_c');
			}else if($level == 'Kasir Ranap'){
				redirect('finance/kasir_ranap_c');
			}else if($level == 'Finance'){
				redirect('finance/finance_home_c');
			}else if($level == 'Perawat'){
				redirect('poli/rk_pelayanan_ri_c');
			}else if($level == 'Super Admin'){
				redirect('portal');
			}
		}else{
			$this->session->set_flashdata('gagal_buka','1');
			redirect('lockscreen_c');
		}
	}

}