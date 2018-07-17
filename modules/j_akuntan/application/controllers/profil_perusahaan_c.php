<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil_perusahaan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../'); 
	    }
	    $this->load->model('profil_perusahaan_m','model');
	}

	function index()
	{
		$keyword = "";
		$msg = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;

			$nama_perusahaan   = addslashes($this->input->post('nama_perusahaan'));
			$alamat_perusahaan = addslashes($this->input->post('alamat_perusahaan'));
			$telepon   		   = addslashes($this->input->post('telepon'));
			$fax   			   = addslashes($this->input->post('fax'));
			$npwp   		   = addslashes($this->input->post('npwp'));
			$website   	       = addslashes($this->input->post('website'));
			$email   		   = addslashes($this->input->post('email'));
			$nama_bank   	   = addslashes($this->input->post('nama_bank'));
			$cabang_bank       = addslashes($this->input->post('cabang_bank'));
			$no_akun_bank      = addslashes($this->input->post('no_akun_bank'));
			$atas_nama         = addslashes($this->input->post('atas_nama'));


			$this->model->simpam_profil($id_klien, $nama_perusahaan, $alamat_perusahaan, $telepon, $fax, $npwp, $website, $email, $nama_bank, $cabang_bank, $no_akun_bank, $atas_nama);
		}

		$dt = $this->model->get_data_profil_usaha($id_klien);

		$data =  array(
			'page' => "profil_perusahaan_v", 
			'title' => "Profil Perusahaan", 
			'master' => "setting", 
			'view' => "profil_usaha", 
			'dt' => $dt, 
			'msg' => $msg, 
			'post_url' => 'profil_perusahaan_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cari_produk(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		
		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_data_produk($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_produk_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_produk_by_id($id);

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */