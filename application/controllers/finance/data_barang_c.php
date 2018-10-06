<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_barang_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('finance/data_barang_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/data_barang_v',
			'title' => 'Data Barang',
			'subtitle' => 'Data Barang',
			'master_menu' => 'pengadaan_barang',
			'view' => 'data_barang',
			'url_simpan' => base_url().'finance/data_barang_c/simpan',
			'url_ubah' => base_url().'finance/data_barang_c/ubah',
			'url_hapus' => base_url().'finance/data_barang_c/hapus'
		);

		$this->load->view('finance/finance_home_v',$data);
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
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_satuan($keyword);
		echo json_encode($data);
	}

	function klik_satuan(){
		$id_satuan = $this->input->post('id_satuan');
		$data = $this->model->klik_satuan($id_satuan);
		echo json_encode($data);
	}

	function data_departemen(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_departemen($keyword);
		echo json_encode($data);
	}

	function klik_departemen(){
		$id_departemen = $this->input->post('id_departemen');
		$data = $this->model->klik_departemen($id_departemen);
		echo json_encode($data);
	}

	function data_divisi(){
		$keyword = $this->input->get('keyword');
		$id_departemen = $this->input->get('id_departemen');
		$data = $this->model->data_divisi($keyword,$id_departemen);
		echo json_encode($data);
	}

	function klik_divisi(){
		$id_divisi = $this->input->post('id_divisi');
		$data = $this->model->klik_divisi($id_divisi);
		echo json_encode($data);
	}

	function data_peralatan(){
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$urutkan_stok = $this->input->get('urutkan_stok');
		$data = $this->model->data_peralatan($keyword,$urutkan,$urutkan_stok);
		echo json_encode($data);
	}

	function get_edit_data(){
		$id = $this->input->post('id');
		$data = $this->model->data_peralatan_id($id);
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
		$id_barang = $this->input->post('id_barang');
		$id_satuan = $this->input->post('id_satuan');
		$golongan = $this->input->post('golongan');
		$merk = $this->input->post('merk');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$isi = str_replace(',', '', $this->input->post('isi'));
		$total = str_replace(',', '', $this->input->post('total'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli'));
		$total_harga = str_replace(',', '', $this->input->post('total_harga'));
		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
	    $datetime = new DateTime();
	    $format = $datetime->setTimezone($tz_object);
	    $waktu_masuk = $format->format('H:i:s');
		$bulan = date('n');
		$tahun = date('Y');
		$gambar = "";

		if(!empty($_FILES['userfile']['name'])){
			$gambar = $_FILES['userfile']['name'];
			$this->upload('userfile');
		}else{
			$gambar = $this->input->post('file_hidden');
		}

		$this->model->simpan(
			$id_barang,
			$id_satuan,
			$golongan,
			$merk,
			$jumlah,
			$isi,
			$total,
			$harga_beli,
			$total_harga,
			$tanggal_masuk,
			$waktu_masuk,
			$bulan,
			$tahun,
			$gambar);

		$this->session->set_flashdata('sukses','1');
		redirect('finance/data_barang_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$id_barang = $this->input->post('id_nama_alat_ubah');
		$id_satuan = $this->input->post('id_satuan_ubah');
		$golongan = $this->input->post('golongan_ubah');
		$merk = $this->input->post('merk_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$isi = str_replace(',', '', $this->input->post('isi_ubah'));
		$total = str_replace(',', '', $this->input->post('total_ubah'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli_ubah'));	
		$total_harga = str_replace(',', '', $this->input->post('total_harga_ubah'));
		$gambar = "";
		$keterangan = $this->input->post('keterangan_ubah');

		if(!empty($_FILES['userfile_ubah']['name'])){
			$gambar = $_FILES['userfile_ubah']['name'];
			$this->upload('userfile_ubah');
		}else{
			$gambar = $this->input->post('file_hidden');
		}

		$this->model->ubah($id,$id_barang,$id_satuan,$golongan,$merk,$jumlah,$isi,$total,$harga_beli,$total_harga,$gambar,$keterangan);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/data_barang_c');
	}
	function data_peralatan_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_peralatan_id($id);
		echo json_encode($data);
	}
	function hapus(){
		$id = $this->input->post('id_hapus');
		$data = $this->model->hapus($id);
		redirect('finance/data_barang_c');
	}
}
