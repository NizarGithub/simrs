<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_asuransi_c extends CI_Controller {

	function __construct()
	{ 
		parent::__construct(); 
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
		$this->load->model('finance/data_asuransi_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;
		$kode_asr = "";
		$nama_asr = ""; 
		$uraian   = "";

		if($this->input->post('simpan')){
			
			$kode_asr = addslashes($this->input->post('kode_asr'));
			$nama_asr = addslashes($this->input->post('nama_asr')); 
			$uraian   = addslashes($this->input->post('uraian'));
			$temp_image   = addslashes($this->input->post('temp_image'));

			$cek_kode = $this->model->cek_kode_asr($kode_asr);
			if(count($cek_kode) > 0){
				$warning = 1;
			} else {
				$msg = 1;
				$warning = 0;
				$this->model->simpan_asuransi($kode_asr, $nama_asr, $uraian);

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
						$config['upload_path'] = './files/finance/';
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size']	= '200000';
						$config['max_width']  = '10000';
						$config['max_height']  = '10000';
						$this->load->library('upload', $config);
						$this->upload->do_upload();
						$data = $this->upload->data();
						$name_array[] = $data['file_name'];

						$this->model->simpanLogoAsuransi($kode_asr, str_replace(' ', '_', $value['name'][$s]));
					}
			    }

				$kode_asr = "";
				$nama_asr = ""; 
				$uraian   = "";
			}

		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_asuransi = $this->input->post('id_asuransi');
			$ed_kode_asr   = addslashes($this->input->post('ed_kode_asr'));
			$ed_nama_asr   = addslashes($this->input->post('ed_nama_asr'));
			$ed_uraian     = addslashes($this->input->post('ed_uraian'));
			$temp_image   = addslashes($this->input->post('temp_image_ed'));

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
					$config['upload_path'] = './files/finance/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '200000';
					$config['max_width']  = '10000';
					$config['max_height']  = '10000';
					$this->load->library('upload', $config);
					$this->upload->do_upload();
					$data = $this->upload->data();
					$name_array[] = $data['file_name'];

					$this->model->simpanLogoAsuransi($ed_kode_asr, str_replace(' ', '_', $value['name'][$s]));
				}
		    }

			$this->model->ubah_asuransi($id_asuransi, $ed_nama_asr, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_departemen($id_hapus);
		}

		$dt = $this->model->get_data_asuransi();

		$data = array(
			'page' => 'finance/data_asuransi_v',
			'title' => 'Setup Asuransi',
			'subtitle' => 'Setup Asuransi',
			'master_menu' => 'master_setup',
			'view' => 'data_asuransi',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'kode_asr' => $kode_asr,
			'nama_asr' => $nama_asr,
			'uraian' => $uraian,
			'post_url' => 'finance/data_asuransi_c',
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function get_data_asuransi(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_asr_by_id($id);
		echo json_encode($data);
	}

	function hapus(){
		$id_hapus   = $this->input->post('id_hapus');
		$this->model->hapus_departemen($id_hapus);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/data_asuransi_c');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */