<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pelanggan_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id']; 
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    }
	    $this->load->model('pelanggan_m','model'); 
	} 

	function index()
	{
		$keyword = "";
		$msg = "";
		$nama_pelanggan = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;
			$nama_pelanggan  = addslashes($this->input->post('nama_pelanggan'));
			$npwp 			 = addslashes($this->input->post('npwp'));
			$alamat_tagih    = addslashes($this->input->post('alamat_tagih'));
			$alamat_kirim    = addslashes($this->input->post('alamat_kirim'));
			$no_telp   	     = $this->input->post('no_telp');
			$no_hp   		 = $this->input->post('no_hp');
			$email   		 = $this->input->post('email');
			$tipe   		 = $this->input->post('tipe');
			$nama_usaha   	 = addslashes($this->input->post('nama_usaha'));
			$tdp   	 	     = addslashes($this->input->post('tdp'));
			$siup   	     = addslashes($this->input->post('siup'));

			if($tipe == "Perorangan"){
				$nama_usaha = "";
				$tdp = "";
				$siup = "";
			}

			$this->model->simpan_pelanggan($id_klien, $nama_pelanggan, $npwp, $alamat_tagih, $alamat_kirim, $no_telp, $no_hp, $email, $tipe, $nama_usaha, $tdp, $siup);

		} else if($this->input->post('id_hapus')){

			$msg = 2;
			$id   = $this->input->post('id_hapus');
			$this->model->hapus_pelanggan($id);

		} else if($this->input->post('edit')){
			$msg = 1;

			$id_pelanggan    	  = $this->input->post('id_pelanggan');
			$nama_pelanggan_ed    = addslashes($this->input->post('nama_pelanggan_ed'));
			$npwp_ed    		  = addslashes($this->input->post('npwp_ed'));
			$alamat_tagih_ed      = addslashes($this->input->post('alamat_tagih_ed'));
			$alamat_kirim_ed      = addslashes($this->input->post('alamat_kirim_ed'));
			$no_telp_ed    		  = addslashes($this->input->post('no_telp_ed'));
			$no_hp_ed    		  = addslashes($this->input->post('no_hp_ed'));
			$email_ed    		  = addslashes($this->input->post('email_ed'));
			$tipe_ed    		  = addslashes($this->input->post('tipe_ed'));
			$nama_usaha_ed    	  = addslashes($this->input->post('nama_usaha_ed'));
			$tdp_ed    	          = addslashes($this->input->post('tdp_ed'));
			$siup_ed    	      = addslashes($this->input->post('siup_ed'));

			if($tipe_ed == "Perorangan"){
				$nama_usaha_ed = "";
				$tdp_ed = "";
				$siup_ed = "";
			}

			$nama_pelanggan       = addslashes($this->input->post('nama_pelanggan_ed'));

			$this->model->edit_pelanggan($id_pelanggan, $nama_pelanggan_ed, $npwp_ed, $alamat_tagih_ed, $alamat_kirim_ed, $no_telp_ed, $no_hp_ed, $email_ed, $tipe_ed, $nama_usaha_ed, $tdp_ed, $siup_ed);
		}

		$dt = $this->model->get_data_pelanggan($keyword, $id_klien);

		$data =  array(
			'page' => "pelanggan_v", 
			'title' => "Daftar Pelanggan", 
			'msg' => "", 
			'master' => "master_data", 
			'view' => "daftar_pelanggan", 
			'dt' => $dt, 
			'msg' => $msg, 
			'nama_pelanggan' => $nama_pelanggan, 
			'post_url' => 'pelanggan_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

	function cari_pelanggan(){
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];
		
		$keyword = $this->input->get('keyword');
		$dt = $this->model->get_data_pelanggan($keyword, $id_klien);

		echo json_encode($dt);
	}

	function cari_pelanggan_by_id(){
		$id = $this->input->get('id');
		$dt = $this->model->cari_pelanggan_by_id($id);

		echo json_encode($dt);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */