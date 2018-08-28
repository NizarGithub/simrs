<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_gudang_obat_c extends CI_Controller {

	function __construct()
	{
		//1 kg = 1000 ml
		parent::__construct();
		$this->load->model('apotek/ap_gudang_obat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{

		if($this->input->post('cetuk')){

				$this->cetak_excel();

		}

		$data = array(
			'page' => 'apotek/ap_gudang_obat_v',
			'title' => 'Gudang Obat',
			'subtitle' => 'Gudang Obat',
			'master_menu' => 'obat',
			'view' => 'obat',
			'url_simpan' => base_url().'apotek/ap_gudang_obat_c/simpan',
			'url_ubah' => base_url().'apotek/ap_gudang_obat_c/ubah',
			'url_hapus' => base_url().'apotek/ap_gudang_obat_c/hapus',
			'url_cetak' => base_url().'apotek/ap_gudang_obat_c/cetak_excel',
		);

		$this->load->view('apotek/ap_home_v',$data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function cetak_excel(){


		$data = array(
			'dt' => $this->model->data_obat_xls(),

			'title' => 'LAPORAN GUDANG OBAT',
			'image'	=> base_url().'picture/jtech-logo.png',
		);

		$this->load->view('apotek/xls/laporan_gudang_obat_xls',$data);
	}

	function kode_obat(){
		$id_klien = '1';
		$keterangan = 'GUDANG-OBAT';
		$tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql = "
			SELECT
				COUNT(*) AS TOTAL
			FROM nomor
			WHERE ID_KLIEN = '$id_klien'
			AND KETERANGAN = '$keterangan'
			AND BULAN = '$bulan'
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//K001-04/10/2016
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "K".$no."-".$tanggal."/".$bulan."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE ID_KLIEN = '$id_klien' AND KETERANGAN = '$keterangan' AND BULAN = '$bulan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "K".$no."-".$tanggal."/".$bulan."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode_obat(){
		$id_klien = '1';
	    $keterangan = 'GUDANG-OBAT';
	    $tanggal = date('d');
		$bulan = date('n');
		$tahun = date('Y');

		$sql_cek = "
			SELECT
				COUNT(*) AS TOTAL
			FROM nomor
			WHERE ID_KLIEN = '$id_klien'
			AND KETERANGAN = '$keterangan'
			AND BULAN = '$bulan'
			AND TAHUN = '$tahun'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(ID_KLIEN,NEXT,KETERANGAN,BULAN,TAHUN) VALUES ('$id_klien','1','$keterangan','$bulan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE ID_KLIEN = '$id_klien' AND BULAN = '$bulan' AND TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan' AND ID_KLIEN = '$id_klien'");
		}
	}

	function data_nama_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_nama_obat($keyword);
		echo json_encode($data);
	}

	function klik_nama_obat(){
		$id = $this->input->post('id');
		$data = $this->model->klik_nama_obat($id);
		echo json_encode($data);
	}

	function data_jenis_obat(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_jenis_obat($keyword);
		echo json_encode($data);
	}

	function klik_jenis(){
		$id_jenis = $this->input->post('id_jenis');
		$data = $this->model->klik_jenis($id_jenis);
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

	function get_data_obat(){
		$keyword = $this->input->get('keyword');
		$urutkan = $this->input->get('urutkan');
		$urutkan_stok = $this->input->get('urutkan_stok');
		$data = $this->model->data_obat($keyword,$urutkan,$urutkan_stok);
		echo json_encode($data);
	}

	function data_obat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_obat_id($id);
		echo json_encode($data);
	}

	private function set_upload_options(){
	    //upload an image options
	    $config = array();
	    $config['upload_path'] = './files/foto_obat/';
	    $config['allowed_types'] = '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

	    return $config;
	}

	function upload($file,$id){
	    $this->load->library('upload');

	    $files = $_FILES;
	    if(isset($_FILES[$file])){
	        $_FILES[$file]['name'] = strtolower(str_replace(' ', '_', $id.'_'.$files[$file]['name']));
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
		$id_nama_obat = $this->input->post('id_nama_obat');
		$id_jenis = $this->input->post('id_jenis');
		$id_satuan = $this->input->post('id_satuan');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$isi = str_replace(',', '', $this->input->post('isi'));
		$total = str_replace(',', '', $this->input->post('total'));
		$satuan_isi = 'kaplet';
		$jumlah_butir = str_replace(',', '', $this->input->post('jumlah_butir'));
		$satuan_butir = 'butir';
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli'));
		$harga_jual = str_replace(',', '', $this->input->post('harga_jual'));
		$kadaluarsa = $this->input->post('tanggal_expired');
		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
	    $datetime = new DateTime();
	    $format = $datetime->setTimezone($tz_object);
	    $waktu_masuk = $format->format('H:i:s');
		$aktif = "";
		// $urut_barang = "";
		$first_out = "";
		$status_racik = $this->input->post('status_obat');
		$gambar = "";
		$id_golongan = $this->input->post('id_golongan');
		$id_kategori = $this->input->post('id_kategori');

		$sq_cek = "SELECT ID FROM apotek_gudang_obat ORDER BY ID DESC LIMIT 1";
		$qr_cek = $this->db->query($sq_cek)->row();
		$last_id = $qr_cek->ID;
		$id = $last_id+1;

		if(!empty($_FILES['userfile']['name'])){
			$gambar = strtolower(str_replace(' ', '_', $id.'_'.$_FILES['userfile']['name']));
			$this->upload('userfile',$id);
		}

		$sql_cek = "SELECT COUNT(*) AS TOTAL FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$id_nama_obat'";
		$qry_cek = $this->db->query($sql_cek)->row();
		$total_data = $qry_cek->TOTAL;

		if($total_data == 0){
			$aktif = '1';
			// $urut_barang = '1';
			$first_out = '1';
		}else{
			$aktif = '0';
			// $sql = "SELECT * FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$id_nama_obat' ORDER BY URUT_BARANG DESC LIMIT 1";
			// $qry = $this->db->query($sql)->row();
			// $urut = $qry->URUT_BARANG;
			// $urut_barang = $urut+1;
			// $first_out = $urut_barang;
		}

		$this->model->simpan(
			$id_nama_obat,
			$id_jenis,
			$id_satuan,
			$jumlah,
			$isi,
			$total,
			$satuan_isi,
			$jumlah_butir,
			$satuan_butir,
			$harga_beli,
			$harga_jual,
			$kadaluarsa,
			$tanggal_masuk,
			$waktu_masuk,
			$aktif,
			$first_out,
			// $urut_barang,
			$status_racik,
			$gambar,
			$id_golongan,
			$id_kategori
		);

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$id_nama_obat = $this->input->post('id_nama_obat_ubah');
		$id_jenis = $this->input->post('id_jenis_ubah');
		$id_satuan = $this->input->post('id_satuan_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$isi = str_replace(',', '', $this->input->post('isi_ubah'));
		$total = str_replace(',', '', $this->input->post('total_ubah'));
		$jumlah_butir = str_replace(',', '', $this->input->post('jumlah_butir_ubah'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli_ubah'));
		$harga_jual = str_replace(',', '', $this->input->post('harga_jual_ubah'));
		$kadaluarsa = $this->input->post('tanggal_expired_ubah');
		$id_golongan = $this->input->post('id_golongan_ubah');
		$id_kategori = $this->input->post('id_kategori_ubah');

		$status_racik = "";
		if($this->input->post('checkbox2')){
			$status_racik = $this->input->post('status_obat_ubah');
		}else{
			$status_racik = $this->input->post('status_obat_hidden');
		}

		$gambar = "";
		if(!empty($_FILES['fileuser']['name'])){
			$gambar = strtolower(str_replace(' ', '_', $id.'_'.$_FILES['fileuser']['name']));
			@unlink('./files/foto_obat/'.$this->input->post('file_hidden'));
			$this->upload('fileuser',$id);
		}else{
			$gambar = $this->input->post('file_hidden');
		}

		$this->model->ubah($id,$id_nama_obat,$id_jenis,$id_satuan,$jumlah,$isi,$total,$jumlah_butir,$harga_beli,$harga_jual,$kadaluarsa,$status_racik,$gambar,$id_golongan,$id_kategori);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('apotek/ap_gudang_obat_c');
	}

}
