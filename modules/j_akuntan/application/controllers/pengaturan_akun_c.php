<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengaturan_akun_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect('../../');
	    }
	    $this->load->model('pengaturan_akun_m','model');
	}

	function index()
	{
		$keyword = "";
		$alert = "";
		$msg = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id'];

		if($this->input->post('simpan')){

			$nama_lengkap   = addslashes($this->input->post('nama_lengkap'));
			$temp_image     = $this->input->post('temp_image');
			$is_ganti       = $this->input->post('is_ganti');

			$this->model->ubah_nama($id_klien, $nama_lengkap);

			if($temp_image != 1 && $is_ganti != 1){
		    	$msg = 1;
		    } else if($temp_image == 1 && $is_ganti != 1){
		    	$msg = 1;
		    }


			if($temp_image == 1){
	            $name_array = array();
				$count = count($_FILES['userfile']['size']);
				foreach($_FILES as $key=>$value)
				for($s=0; $s<=$count-1; $s++) {
					$_FILES['userfile']['name']    	= str_replace(' ', '_', $value['name'][$s]) ;
					$_FILES['userfile']['type']    	= $value['type'][$s];
					$_FILES['userfile']['tmp_name'] = $value['tmp_name'][$s];
					$_FILES['userfile']['error']    = $value['error'][$s];
					$_FILES['userfile']['size']    	= $value['size'][$s];  
					$config['upload_path'] = './../../files/foto_pegawai/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '200000';
					$config['max_width']  = '10000';
					$config['max_height']  = '10000';
					$this->load->library('upload', $config);
					$this->upload->do_upload();
					$data = $this->upload->data();
					$name_array[] = $data['file_name'];

					$this->model->edit_ava_user($id_klien, str_replace(' ', '_', $value['name'][$s]) );
				}
		    }

		    if($is_ganti == 1){

		    	$data_user = $this->model->get_data_akun($id_klien);
		    	$pass_lama  = $this->input->post('pass_lama');
		    	$pass_baru1 = $this->input->post('pass_baru1');
		    	$pass_baru2 = $this->input->post('pass_baru2');

		    	if($data_user->PASSWORD != md5(md5($pass_lama))) {
		    		$alert = 1;
		    	} else {
		    		if($pass_baru1 != $pass_baru2){
		    			$alert = 2;
		    		} else {
		    			$this->model->ganti_password($id_klien, $pass_baru1);
		    			$msg = 1;
		    		}
		    	}
		    }

		    
		}

		$dt = $this->model->get_data_akun($id_klien);

		$data =  array(
			'page' => "pengaturan_akun_v", 
			'title' => "Pengaturan Akun", 
			'master' => "setting", 
			'view' => "pengaturan_akun", 
			'dt' => $dt, 
			'msg' => $msg, 
			'alert' => $alert, 
			'post_url' => 'pengaturan_akun_c', 
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