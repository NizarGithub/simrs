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
			'title' => 'Faktur',
			'subtitle' => 'Faktur',
			'master_menu' => 'obat',
			'view' => 'obat',
			'url_simpan' => base_url().'apotek/ap_gudang_obat_c/simpan',
			'url_ubah' => base_url().'apotek/ap_gudang_obat_c/ubah',
			'url_hapus' => base_url().'apotek/ap_gudang_obat_c/hapus',
			'url_cetak' => base_url().'apotek/ap_gudang_obat_c/cetak_excel',
			'url_simpan_obat' => base_url().'apotek/ap_gudang_obat_c/simpan_obat',
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

	function data_faktur_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_faktur_id($id);
		echo json_encode($data);
	}

	function data_faktur_id_row(){
		$id = $this->input->post('id');
		$data = $this->model->data_faktur_id_row($id);
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
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$isi = str_replace(',', '', $this->input->post('isi'));
		$total = str_replace(',', '', $this->input->post('total'));
		$jumlah_butir = str_replace(',', '', $this->input->post('jumlah_butir'));
		$harga_pertablet = str_replace(',', '', $this->input->post('harga_pertablet'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli'));
		$harga_jual = str_replace(',', '', $this->input->post('harga_jual'));
		$harga_bulat = str_replace(',', '', $this->input->post('harga_bulat'));
		$id_supplier = $this->input->post('id_supplier');
		$no_faktur = $this->input->post('no_faktur');
		$diskon = str_replace(',', '', $this->input->post('diskon'));
		$grand_total = str_replace(',', '', $this->input->post('grand_total'));

		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu_masuk = $format->format('H:i:s');

		$status_obat_ubah = '';
		if ($this->input->post('status_obat_ubah') == '0') {
			$status_obat_ubah = 'Kosong';
		}elseif ($this->input->post('status_obat_ubah') == '1') {
			$status_obat_ubah = 'Persen';
		}elseif ($this->input->post('status_obat_ubah') == '2') {
			$status_obat_ubah = 'Harga';
		}

		$data_faktur = array(
			'ID_SUPPLIER' => $id_supplier,
			'NO_FAKTUR' => $no_faktur,
			'TANGGAL' => $tanggal_masuk,
			'WAKTU' => $waktu_masuk,
			'DISKON' => $diskon,
			'TOTAL' => $grand_total,
			'CEK_DISKON' => $status_obat_ubah
		);

		$this->db->insert('faktur', $data_faktur);
		$insert_id = $this->db->insert_id();
		// $sq_cek = "SELECT ID FROM apotek_gudang_obat ORDER BY ID DESC LIMIT 1";
		// $qr_cek = $this->db->query($sq_cek)->row();
		// $last_id = $qr_cek->ID;
		// $id = $last_id+1;
		//


	foreach ($id_nama_obat as $key => $value) {

		// $sql_cek = "SELECT COUNT(*) AS TOTAL FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$value'";
		// $qry_cek = $this->db->query($sql_cek)->row();
		// $total_data = $qry_cek->TOTAL;

		// if($total_data == 0){
		// 	$aktif = '1';
		// 	$urut_barang = '1';
		// 	$first_out = '1';
		// }else{
		// 	$aktif = '0';
		// 	$sql = "SELECT * FROM apotek_gudang_obat WHERE ID_SETUP_NAMA_OBAT = '$id_nama_obat' ORDER BY URUT_BARANG DESC LIMIT 1";
		// 	$qry = $this->db->query($sql)->row();
		// 	$urut = $qry->URUT_BARANG;
		// 	$urut_barang = $urut+1;
		// 	$first_out = $urut_barang;
		// }

		$this->model->simpan(
			$insert_id,
			$value,
			$jumlah[$key],
			$isi[$key],
			$total[$key],
			$jumlah_butir[$key],
			$harga_pertablet[$key],
			$harga_beli[$key],
			$harga_jual[$key],
			$harga_bulat[$key],
			$tanggal_masuk,
			$waktu_masuk
		);
	}

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function simpan_obat(){
		$id_faktur = $this->input->post('id_faktur');
		$id_nama_obat = $this->input->post('id_nama_obat');
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$isi = str_replace(',', '', $this->input->post('isi'));
		$total = str_replace(',', '', $this->input->post('total'));
		$jumlah_butir = str_replace(',', '', $this->input->post('jumlah_butir'));
		$harga_pertablet = str_replace(',', '', $this->input->post('harga_pertablet'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli'));
		$harga_jual = str_replace(',', '', $this->input->post('harga_jual'));
		$harga_bulat = str_replace(',', '', $this->input->post('harga_bulat'));
		$id_supplier = $this->input->post('id_supplier');
		$no_faktur = $this->input->post('no_faktur');
		$diskon = str_replace(',', '', $this->input->post('diskon'));
		$grand_total = str_replace(',', '', $this->input->post('grand_total'));

		$tanggal_masuk = date('d-m-Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu_masuk = $format->format('H:i:s');

		$status_obat_ubah = '';
		if ($this->input->post('status_obat_ubah') == '0') {
			$status_obat_ubah = 'Kosong';
		}elseif ($this->input->post('status_obat_ubah') == '1') {
			$status_obat_ubah = 'Persen';
		}elseif ($this->input->post('status_obat_ubah') == '2') {
			$status_obat_ubah = 'Harga';
		}

		$data_faktur = array(
			'DISKON' => $diskon,
			'TOTAL' => $grand_total,
			'CEK_DISKON' => $status_obat_ubah
		);

		$this->db->where('ID', $id_faktur);
		$this->db->update('faktur', $data_faktur);


	foreach ($id_nama_obat as $key => $value) {

		$this->model->simpan_obat(
			$id_faktur,
			$value,
			$jumlah[$key],
			$isi[$key],
			$total[$key],
			$jumlah_butir[$key],
			$harga_pertablet[$key],
			$harga_beli[$key],
			$harga_jual[$key],
			$harga_bulat[$key],
			$tanggal_masuk,
			$waktu_masuk
		);
	}

		$this->session->set_flashdata('sukses','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$id_supplier = $this->input->post('id_supplier_ubah');
		$id_nama_obat = $this->input->post('id_nama_obat_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$isi = str_replace(',', '', $this->input->post('isi_ubah'));
		$total = str_replace(',', '', $this->input->post('total_ubah'));
		$jumlah_butir = str_replace(',', '', $this->input->post('jumlah_butir_ubah'));
		$harga_pertablet = str_replace(',', '', $this->input->post('harga_pertablet_ubah'));
		$harga_beli = str_replace(',', '', $this->input->post('harga_beli_ubah'));
		$harga_jual = str_replace(',', '', $this->input->post('harga_jual_ubah'));

		$this->model->ubah(
			$id,
			$id_supplier,
			$id_nama_obat,
			$jumlah,
			$isi,
			$total,
			$jumlah_butir,
			$harga_pertablet,
			$harga_beli,
			$harga_jual
		);

		$this->session->set_flashdata('ubah','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('apotek/ap_gudang_obat_c');
	}

	function data_nama_supplier(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_nama_supplier($keyword);
		echo json_encode($data);
	}

	function klik_nama_supplier(){
		$id = $this->input->post('id');
		$data = $this->model->klik_nama_supplier($id);
		echo json_encode($data);
	}
}
