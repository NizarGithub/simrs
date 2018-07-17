<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_pegawai_c extends CI_Controller {
 
	function __construct()
	{ 
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id']; 
		if($id_user == "" || $id_user == null){ 
	        redirect(base_url());
	    }
		$this->load->model('kepeg/add_pegawai_m', 'model');
	}

	function index()
	{

		$msg = 0;
		$warning = 0;

		if($this->input->post('simpan')){
			$msg = 1;
			$id_dep     = $this->input->post('id_dep');
			$id_div     = $this->input->post('id_div');
			$nip        = $this->input->post('nip');
			$nama       = addslashes($this->input->post('nama'));
			$kota_lahir = addslashes($this->input->post('kota_lahir'));
			$tgl_lahir  = addslashes($this->input->post('tgl_lahir'));
			$alamat     = addslashes($this->input->post('alamat'));
			$telpon     = addslashes($this->input->post('telpon'));
			$status     = $this->input->post('status');
			$id_jabatan = $this->input->post('id_jabatan');
			$id_pangkat = $this->input->post('id_pangkat');
			$temp_image = $this->input->post('temp_image');

			$id_pendidikan       = $this->input->post('id_pendidikan');
			$nomor_sk_pangkat    = $this->input->post('nomor_sk_pangkat');
			$tgl_sk_pangkat      = $this->input->post('tgl_sk_pangkat');
			$tgl_awal_pangkat    = $this->input->post('tgl_awal_pangkat');
			$tgl_selesai_pangkat = $this->input->post('tgl_selesai_pangkat');
			$sts_jabatan         = $this->input->post('sts_jabatan');
			$nomor_sk_jabatan    = $this->input->post('nomor_sk_jabatan');
			$tgl_sk_jabatan      = $this->input->post('tgl_sk_jabatan');
			$tgl_awal_jabatan    = $this->input->post('tgl_awal_jabatan');
			$tgl_selesai_jabatan = $this->input->post('tgl_selesai_jabatan');
			$id_gol_pajak        = $this->input->post('id_gol_pajak');

			$this->model->simpan_pegawai($id_dep, $id_div, $nip, $nama, $kota_lahir, $tgl_lahir, $alamat, $telpon, $status, $id_jabatan, $id_pangkat,
										 $id_pendidikan, $nomor_sk_pangkat, $tgl_sk_pangkat, $tgl_awal_pangkat, $tgl_selesai_pangkat, $sts_jabatan, $nomor_sk_jabatan, $tgl_sk_jabatan,
										 $tgl_awal_jabatan, $tgl_selesai_jabatan, $id_gol_pajak);

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


		} else if($this->input->post('ubah')){

			$msg = 2;
			$id_jabatan     = $this->input->post('id_jabatan');
			$ed_nama_jab    = addslashes($this->input->post('ed_nama_jab'));
			$ed_uraian      = addslashes($this->input->post('ed_uraian'));

			$this->model->ubah_jabatan($id_jabatan, $ed_nama_jab, $ed_uraian);

		} else if($this->input->post('id_hapus')){

			$msg = 3;
			$id_hapus   = $this->input->post('id_hapus');
			$this->model->hapus_jabatan($id_hapus);
		}

		$dt = $this->model->get_data_jabatan();

		$data = array(
			'page' => 'kepeg/add_pegawai_v',
			'title' => 'Tambah Pegawai',
			'subtitle' => 'Tambah Pegawai',
			'master_menu' => 'pegawai_menu',
			'view' => 'add_peg',
			'warning' => $warning,
			'dt' => $dt,
			'msg' => $msg,
			'post_url' => 'kepeg/add_pegawai_c',
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

	function get_jabatan2(){
		$jenis = $this->input->post('jenis');
		$data = $this->model->get_data_jab_by_jenis($jenis);
		echo json_encode($data);
	}

	function cek_nip(){
		$nip = addslashes($this->input->post('nip'));
		$data = $this->model->cek_nip($nip);

		echo json_encode(count($data));
	}

	function get_pangkat_min(){
		$id_pendidikan = $this->input->post('id_pendidikan');
		$data = $this->model->get_pangkat_by_pendidikan($id_pendidikan);

		echo json_encode($data);
	}

	function get_gol_pajak(){
		$id_gol = $this->input->post('id_gol');
		$data = $this->model->get_gol_pajak_by_id($id_gol);

		echo json_encode($data);
	}

	function getDataPendidikan(){
		$data = $this->model->get_pendidikan();
		echo json_encode($data);
	}

	function getDataPangkat(){
		$data = $this->model->get_pangkat();
		echo json_encode($data);
	}

	function getDataDepartemen(){
		$data = $this->model->get_departemen();
		echo json_encode($data);
	}

	function getDataGolonganPajak(){
		$data = $this->model->get_gol_pajak();
		echo json_encode($data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */