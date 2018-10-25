<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_entry_paket_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('apotek/Ap_entry_paket_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index(){
		$data = array(
			'title' => 'Sistem Informasi Rumah Sakit',
      'page' => 'apotek/Ap_entry_paket_v',
      'master_menu' => 'kasir_aa',
			'view' => 'entry_paket',
      'subtitle' => ''
		);

		$this->load->view('apotek/ap_entry_paket_v',$data);
	}

	function get_data_pasien(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->get_data_pasien($keyword);
		echo json_encode($data);
	}

	function klik_pasien(){
    $id = $this->input->post('id');
    $data = $this->model->klik_pasien($id);
    echo json_encode($data);
  }

  function get_data_paket(){
		$keyword = $this->input->get('keyword');
    $data = $this->model->get_data_paket($keyword);
    echo json_encode($data);
  }

  function klik_paket(){
    $id = $this->input->post('id');
    $data = $this->model->klik_paket($id);
    echo json_encode($data);
  }

  function get_data_dokter(){
    $keyword = $this->input->get('keyword');
    $data = $this->model->get_data_dokter($keyword);
    echo json_encode($data);
  }

  function klik_dokter(){
    $id = $this->input->post('id');
    $data = $this->model->klik_dokter($id);
    echo json_encode($data);
  }

  function data_obat(){
    $keyword = $this->input->get('keyword');
    $data = $this->model->data_obat($keyword);
    echo json_encode($data);
  }

  function data_keranjang(){
    $data = $this->model->data_keranjang();
    echo json_encode($data);
  }

  function simpan_keranjang(){
    $id_gudang = $this->input->post('id');
    $harga_beli = $this->input->post('harga_beli');
		$service = $this->input->post('service');

    $sql_check = $this->db->query("SELECT COUNT(*) AS TOTAL FROM keranjang_beli_paket WHERE ID_GUDANG_OBAT = '$id_gudang'")->row();
    if ($sql_check->TOTAL == '0') {
      $this->model->simpan_keranjang($id_gudang, $harga_beli, $service);
    }else {
    }
    echo '1';
  }

  function hapus_keranjang(){
    $id = $this->input->post('id');
    $data = $this->model->hapus_keranjang($id);
    echo json_encode($data);
  }

  function simpan_proses(){
    $id_gudang_obat = $this->input->post('id_gudang_obat');
    $total_keranjang_name = $this->input->post('total_keranjang_name');
    $jumlah_beli = $this->input->post('jumlah_beli');
    $harga_obat = $this->input->post('harga_obat');
		$id_paket = $this->input->post('id_paket');
		$service = $this->input->post('service');
		$biaya_paket_obat = $this->input->post('biaya_paket_obat');
    // $id_dokter = $this->input->post('id_dokter');
		// $id_pasien = $this->input->post('id_pasien');

    $invoice = $this->input->post('invoice');
    $shift = $this->input->post('shift');
    $id_pegawai = $this->input->post('id_pegawai');
    $total = str_replace(',','',  $this->input->post('total_tagihan'));
		$tanggal = date('d-m-Y');
    $tahun = date('Y');
    $bulan = date('m');
		$waktu = date('H:i:s');

    $data = array(
      'INVOICE' => $invoice,
      'SHIFT' => $shift,
      'ID_PEGAWAI' => $id_pegawai,
      'TOTAL' => $total,
			'BIAYA_PAKET_OBAT' => $biaya_paket_obat,
			'ID_PAKET' => $id_paket,
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'WAKTU' => $waktu
    );
    $this->db->insert('setup_paket_obat', $data);
    $id_setup_paket = $this->db->insert_id();

    foreach ($id_gudang_obat as $key => $value){
      $this->model->simpan_proses($value, $total_keranjang_name[$key], $jumlah_beli[$key], $harga_obat[$key], $id_setup_paket, $service[$key]);
    }

    $sql = $this->db->query("SELECT * FROM setup_paket_obat WHERE ID = '$id_setup_paket'");
    $back = $sql->row_array();
    echo json_encode($back);

    $this->insert_kode();
  }

  function get_invoice(){
    $keterangan = 'KODE-TRX-OBAT';
    $tanggal = date('d');
    $bulan = date('n');
    $tahun = date('Y');

    $sql = "
      SELECT
        COUNT(*) AS TOTAL
      FROM nomor
      WHERE KETERANGAN = '$keterangan'
      AND TAHUN = '$tahun'
    ";
    $qry = $this->db->query($sql);
    $total = $qry->row()->TOTAL;
    $kode = "";

    if($total == 0){
      $no = $this->add_leading_zero(1,3);
      $kode = $tahun.$bulan.$tanggal.$no;
    }else{
      $s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND TAHUN = '$tahun'";
      $q = $this->db->query($s)->row();
      $next = $q->NEXT+1;
      $no = $this->add_leading_zero($next,3);
      $kode = $tahun.$bulan.$tanggal.$no;
    }

    echo json_encode($kode);
  }

  function insert_kode(){
      $keterangan = 'KODE-TRX-OBAT';
    $tahun = date('Y');

    $sql_cek = "
      SELECT
        COUNT(*) AS TOTAL
      FROM nomor
      WHERE KETERANGAN = '$keterangan'
      AND TAHUN = '$tahun'
    ";
    $total = $this->db->query($sql_cek)->row()->TOTAL;

    if($total == 0){
      $this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,TAHUN) VALUES ('1','$keterangan','$tahun')");
    }else{
      $sql = "SELECT * FROM nomor WHERE TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
      $query = $this->db->query($sql)->row();
      $next = $query->NEXT+1;
      $id = $query->ID;
      $this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
    }
  }

  }

  /* End of file welcome.php */
  /* Location: ./application/controllers/welcome.php */
