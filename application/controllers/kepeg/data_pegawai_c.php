<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_pegawai_c extends CI_Controller { 

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs'); 
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){ 
	        redirect(base_url());
	    }
		$this->load->model('kepeg/data_pegawai_m', 'model');
	} 

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){ 
			$msg = 1;


		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_jabatan     = $this->input->post('id_jabatan');
			$ed_nama_jab    = addslashes($this->input->post('ed_nama_jab'));
			$ed_uraian      = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_pegawai($id_hapus);
		}

		$dt = $this->model->get_data_pegawai();

		$data = array(
			'page' => 'kepeg/data_pegawai_v',
			'title' => 'Data Pegawai',
			'subtitle' => 'Data Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'data_peg',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/data_pegawai_c/',
			'get_departemen' => $this->model->get_departemen(),
			'get_jabatan' => $this->model->get_jabatan(),
			'get_pangkat' => $this->model->get_pangkat(),
			'get_pendidikan' => $this->model->get_pendidikan(),
			'get_gol_pajak' => $this->model->get_gol_pajak(),
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function grid()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){ 
			$msg = 1;


		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_jabatan     = $this->input->post('id_jabatan');
			$ed_nama_jab    = addslashes($this->input->post('ed_nama_jab'));
			$ed_uraian      = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_pegawai($id_hapus);
		}

		$dt = $this->model->get_data_pegawai();

		$data = array(
			'page' => 'kepeg/data_pegawai_grid_v',
			'title' => 'Data Pegawai',
			'subtitle' => 'Data Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'data_peg',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/data_pegawai_c/grid',
			'get_departemen' => $this->model->get_departemen(),
			'get_jabatan' => $this->model->get_jabatan(),
			'get_pangkat' => $this->model->get_pangkat(), 
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function ubah($id=""){

		$warning = "";
		$msg = "";		

		if($this->input->post('simpan')){
			$msg 		= 2;
			$nip        = $this->input->post('nip');
			$id_dep     = $this->input->post('id_dep');
			$id_div     = $this->input->post('id_div');
			$id_jabatan = $this->input->post('id_jabatan');
			$id_pangkat = $this->input->post('id_pangkat');
			$status     = $this->input->post('status');
			$nama       = addslashes($this->input->post('nama'));
			$kota_lahir = addslashes($this->input->post('kota_lahir'));
			$tgl_lahir  = addslashes($this->input->post('tgl_lahir'));
			$alamat     = addslashes($this->input->post('alamat'));
			$telpon     = addslashes($this->input->post('telpon'));
			$id_pendidikan     = addslashes($this->input->post('id_pendidikan'));
			$sk_pangkat     = addslashes($this->input->post('nomor_sk_pangkat'));
			$tgl_sk_pangkat     = addslashes($this->input->post('tgl_sk_pangkat'));
			$tgl_awal_pangkat     = addslashes($this->input->post('tgl_awal_pangkat'));
			$tgl_akhir_pangkat     = addslashes($this->input->post('tgl_selesai_pangkat'));
			$sts_jabatan     = addslashes($this->input->post('sts_jabatan'));
			$sk_jabatan     = addslashes($this->input->post('nomor_sk_jabatan'));
			$tgl_sk_jabatan     = addslashes($this->input->post('tgl_sk_jabatan'));
			$tgl_awal_jabatan     = addslashes($this->input->post('tgl_awal_jabatan'));
			$tgl_akhir_jabatan     = addslashes($this->input->post('tgl_selesai_jabatan'));
			$id_gol_pajak     = addslashes($this->input->post('id_gol_pajak'));	
			$temp_image = $this->input->post('temp_image');

			$this->model->ubah_data_pegawai(
				$id,
				$nip, 
				$id_dep, 
				$id_div,
				$id_jabatan, 
				$id_pangkat,
				$status, 
				$nama, 
				$kota_lahir, 
				$tgl_lahir, 
				$alamat, 
				$telpon, 
				$id_pendidikan, 
				$sk_pangkat, 
				$tgl_sk_pangkat, 
				$tgl_awal_pangkat, 
				$tgl_akhir_pangkat, 
				$sts_jabatan, 
				$sk_jabatan, 
				$tgl_sk_jabatan, 
				$tgl_awal_jabatan, 
				$tgl_akhir_jabatan, 
				$id_gol_pajak);

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
					$config['upload_path'] = './files/foto_pegawai/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '200000';
					$config['max_width']  = '10000';
					$config['max_height']  = '10000';
					$this->load->library('upload', $config);
					$this->upload->do_upload();
					$data = $this->upload->data();
					$name_array[] = $data['file_name'];

					$this->model->simpan_foto_user($nip, str_replace(' ', '_', $value['name'][$s]));
				}
		    }
		}

		$dt = $this->model->get_data_pegawai_by_id($id);

		$data = array(
			'page' => 'kepeg/ubah_data_pegawai_v',
			'title' => 'Ubah Data Pegawai',
			'subtitle' => 'Ubah Data Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'data_peg',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/data_pegawai_c/ubah/'.$id,
			'get_departemen' => $this->model->get_departemen(),
			'get_jabatan' => $this->model->get_jabatan(),
			'get_pangkat' => $this->model->get_pangkat(),
			'get_pendidikan' => $this->model->get_pendidikan(),
			'get_gol_pajak' => $this->model->get_gol_pajak(),
		);

		$this->load->view('kepeg/kepeg_master_home_v',$data);
	}

	function get_divisi(){
		$id_dep = $this->input->post('id_dep');
		$data = $this->model->get_divisi_by_id_dep($id_dep);
		echo json_encode($data);
	}

	function get_data_jabatan(){
		$id = $this->input->post('id');
		$data = $this->model->get_data_jab_by_id($id);
		echo json_encode($data);
	}

	function cek_nip(){
		$nip = addslashes($this->input->post('nip'));
		$id_peg = $this->input->post('id_peg');
		$data = $this->model->cek_nip($id_peg, $nip);

		echo json_encode(count($data));
	}

	function cari_peg_by_nama(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->cari_peg_by_nama($keyword);
		echo json_encode($data);
	}

	function cari_peg_by_jabatan(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->cari_peg_by_jabatan($keyword);
		echo json_encode($data);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */