<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transfer_data_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('../../');
	    }
	    $this->load->model('transfer_data_m','model');
	}

	function index()
	{
		$keyword = "";
		$alert = "";
		$msg = "";
		$sess_user = $this->session->userdata('masuk_rs');
		$id_klien = $sess_user['id_klien'];

		if($this->input->post('simpan')){
			$msg = 1;
		    $temp_image     = $this->input->post('temp_image');
		    $nama_laporan   = addslashes($this->input->post('nama_laporan'));

		    $this->model->ganti_judul_laporan($id_klien, $nama_laporan);

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
					$config['upload_path'] = './files/laporan/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '200000';
					$config['max_width']  = '10000';
					$config['max_height']  = '10000';
					$this->load->library('upload', $config);
					$this->upload->do_upload();
					$data = $this->upload->data();
					$name_array[] = $data['file_name'];

					$this->model->edit_laporan_header($id_klien, str_replace(' ', '_', $value['name'][$s]) );
				}
		    }
		}

		$dt = $this->model->get_data_laporan($id_klien);

		$data =  array(
			'page' => "transfer_data_v",  
			'title' => "Transfer Data Akuntansi", 
			'master' => "", 
			'view' => "", 
			'dt' => $dt, 
			'msg' => $msg, 
			'alert' => $alert, 
			'post_url' => 'transfer_data_c', 
		);
		
		$this->load->view('beranda_v', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */