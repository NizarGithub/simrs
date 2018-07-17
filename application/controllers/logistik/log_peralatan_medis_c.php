<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_peralatan_medis_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('logistik/log_peralatan_medis_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'logistik/log_peralatan_medis_v',
			'title' => 'Peralatan Medis',
			'subtitle' => 'Peralatan Medis',
			'master_menu' => 'peralatan_medis',
			'view' => 'peralatan_medis',
			'url_simpan' => base_url().'logistik/log_peralatan_medis_c/simpan',
			'url_ubah' => base_url().'logistik/log_peralatan_medis_c/ubah',
			'url_hapus' => base_url().'logistik/log_peralatan_medis_c/hapus',
		);

		$this->load->view('logistik/log_home_v',$data);
	}

	function load_nama_alat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->load_nama_alat($keyword);
		echo json_encode($data);
	}

	function klik_nama_alat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_nama_alat($id);
		echo json_encode($data);
	}

	function data_satuan(){
		$data = $this->model->data_satuan();
		echo json_encode($data);
	}

	function klik_satuan(){
		$id_satuan = $this->input->post('id_satuan');
		$data = $this->model->klik_satuan($id_satuan);
		echo json_encode($data);
	}

	function data_peralatan(){
		$keyword = $this->input->post('keyword');
		$urutkan = $this->input->post('urutkan');
		$urutkan_stok = $this->input->post('urutkan_stok');
		$data = $this->model->data_peralatan($keyword,$urutkan,$urutkan_stok);
		echo json_encode($data);
	}

	private function set_upload_options(){   
	    //upload an image options
	    $config = array();
	    $config['upload_path'] = './files/foto_alat/';
	    $config['allowed_types'] = '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

	    return $config;
	}

	function upload($file){   
	    $this->load->library('upload');

	    $files = $_FILES;
	    if(isset($_FILES[$file])){
	        $_FILES[$file]['name'] = str_replace(' ', '_', $files[$file]['name']);
	        $_FILES[$file]['type'] = $files[$file]['type'];
	        $_FILES[$file]['tmp_name'] = $files[$file]['tmp_name'];
	        $_FILES[$file]['error'] = $files[$file]['error'];
	        $_FILES[$file]['size'] = $files[$file]['size'];    

	        $this->upload->initialize($this->set_upload_options());
	        $this->upload->do_upload($file);
	        $gambar = $this->upload->data();

	        // echo $files[$file]['name'][$i];
	        // print_r($gambar);
	        // die();
	    }
	}

	function simpan(){
		$id_setup_nama_alat = $this->input->post('id_nama_alat');
		$id_satuan = $this->input->post('id_satuan');
		$pemakaian = $this->input->post('pemakaian');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$isi = str_replace(',', '', $this->input->post('isi'));
		$total = str_replace(',', '', $this->input->post('total'));
		$satuan_isi = 'Buah';
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli'));
		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
	    $datetime = new DateTime();
	    $format = $datetime->setTimezone($tz_object);
	    $waktu_masuk = $format->format('H:i:s');
		$aktif = "";
		$first_out = "";
		$urut_barang = "";
		$gambar = "";

		if(!empty($_FILES['userfile']['name'])){
			$gambar = $_FILES['userfile']['name'];
			$this->upload('userfile');
		}else{
			$gambar = $this->input->post('file_hidden');
		}

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM log_peralatan_medis WHERE ID_SETUP_NAMA_ALAT = '$id_setup_nama_alat'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total_data = $qry_cek->TOTAL;

		if($total_data == 0){
			$aktif = '1';
			$urut_barang = '1';
			$first_out = '1';
		}else{
			$aktif = '0';
			$sql = "SELECT * FROM log_peralatan_medis WHERE ID_SETUP_NAMA_ALAT = '$id_setup_nama_alat' ORDER BY URUT_BARANG DESC LIMIT 1";
			$qry = $this->db->query($sql)->row();
			$urut = $qry->URUT_BARANG;
			$urut_barang = $urut+1;
			$first_out = $urut_barang;
		}

		$this->model->simpan(
			$id_setup_nama_alat,
			$id_satuan,
			$pemakaian,
			$jumlah,
			$isi,
			$total,
			$satuan_isi,
			$harga_beli,
			$tanggal_masuk,
			$waktu_masuk,
			$aktif,
			$first_out,
			$urut_barang,
			$gambar);

		$this->session->set_flashdata('sukses','1');
		redirect('logistik/log_peralatan_medis_c');
	}

}