<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pembelian_aqua_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('finance/pembelian_aqua_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect('login_c');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'finance/pembelian_aqua_v',
			'title' => 'Pembelian Aqua',
			'subtitle' => 'Pembelian Aqua',
			'childtitle' => '',
			'master_menu' => 'pengadaan_barang',
			'view' => 'pembelian_aqua'
		);

		$this->load->view('finance/finance_home_v',$data);
	}

	function data_pembelian(){
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->data_pembelian($bulan,$tahun);
		echo json_encode($data);
	}

	function data_pembelian_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_pembelian_id($id);
		echo json_encode($data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
	}

	function romanic_number($integer, $upcase = true) { 
	    $table = array(
	    	'M'		=>1000, 
	    	'CM'	=>900, 
	    	'D'		=>500, 
	    	'CD'	=>400, 
	    	'C'		=>100, 
	    	'XC'	=>90, 
	    	'L'		=>50, 
	    	'XL'	=>40, 
	    	'X'		=>10, 
	    	'IX'	=>9, 
	    	'V'		=>5, 
	    	'IV'	=>4, 
	    	'I'		=>1
	    ); 
	    
	    $return = ''; 
	    while($integer > 0) 
	    { 
	        foreach($table as $rom=>$arb) 
	        { 
	            if($integer >= $arb) 
	            { 
	                $integer -= $arb; 
	                $return .= $rom; 
	                break; 
	            } 
	        } 
	    } 

	    return $return; 
	}

	function get_kode_pb(){
		$keterangan = "PB-FINANCE";
		$bulan = date('n');
		$tahun = date('Y');

		$sql = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor 
			WHERE KETERANGAN = '$keterangan'
			AND BULAN = '$bulan' 
			AND TAHUN = '$tahun'
		";
		$qry = $this->db->query($sql);
		$total = $qry->row()->TOTAL;
		$kode = "";

		//PB/ADM/001/X/2018
		if($total == 0){
			$no = $this->add_leading_zero(1,3);
			$kode = "PB/FN/".$no."/".$this->romanic_number($bulan)."/".$tahun;
		}else{
			$s = "SELECT * FROM nomor WHERE KETERANGAN = '$keterangan' AND BULAN = '$bulan' AND TAHUN = '$tahun'";
			$q = $this->db->query($s)->row();
			$next = $q->NEXT+1;
			$no = $this->add_leading_zero($next,3);
			$kode = "PB/FN/".$no."/".$this->romanic_number($bulan)."/".$tahun;
		}

		echo json_encode($kode);
	}

	function insert_kode(){
		$keterangan = "PB-FINANCE";
		$bulan = date('n');
		$tahun = date('Y');

		$sql_cek = "
			SELECT 
				COUNT(*) AS TOTAL 
			FROM nomor
			WHERE BULAN = '$bulan' 
			AND TAHUN = '$tahun'
			AND KETERANGAN = '$keterangan'
		";
		$total = $this->db->query($sql_cek)->row()->TOTAL;

		if($total == 0){
			$this->db->query("INSERT INTO nomor(NEXT,KETERANGAN,BULAN,TAHUN) VALUES ('1','$keterangan','$bulan','$tahun')");
		}else{
			$sql = "SELECT * FROM nomor WHERE BULAN = '$bulan' AND TAHUN = '$tahun' AND KETERANGAN = '$keterangan'";
			$query = $this->db->query($sql)->row();
			$next = $query->NEXT+1;
			$id = $query->ID;
			$this->db->query("UPDATE nomor SET NEXT = '$next' WHERE ID = '$id' AND KETERANGAN = '$keterangan'");
		}
	}

	function data_barang(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_barang($keyword);
		echo json_encode($data);
	}

	function klik_barang(){
		$id = $this->input->post('id');
		$data = $this->model->data_barang_id($id);
		echo json_encode($data);
	}
	
	function simpan(){
		$kode = $this->input->post('kode_pembelian');
		$tanggal = $this->input->post('tanggal');
		$bulan = date('n');
		$tahun = date('Y');
		$id_barang_gudang = $this->input->post('id_barang_gudang');
		$harga = str_replace(',', '', $this->input->post('harga'));
		$jumlah = str_replace(',', '', $this->input->post('jumlah'));
		$total = str_replace(',', '', $this->input->post('total_harga'));

		$this->model->simpan($kode,$tanggal,$bulan,$tahun,$id_barang_gudang,$harga,$jumlah,$total);
		$this->insert_kode();

		$this->session->set_flashdata('sukses','1');
		redirect('finance/pembelian_aqua_c');
	}

	function ubah(){
		$id = $this->input->post('id_ubah');
		$tanggal = $this->input->post('tanggal_ubah');
		$jumlah = str_replace(',', '', $this->input->post('jumlah_ubah'));
		$total = str_replace(',', '', $this->input->post('total_harga_ubah'));

		$this->model->ubah($id,$tanggal,$jumlah,$total);

		$this->session->set_flashdata('ubah','1');
		redirect('finance/pembelian_aqua_c');
	}

	function hapus(){
		$id = $this->input->post('id_hapus');
		$this->model->hapus($id);

		$this->session->set_flashdata('hapus','1');
		redirect('finance/pembelian_aqua_c');
	}

	function cetak(){
		$jenis = $this->input->post('jenis_laporan');
		if($jenis == 'excel'){
			$this->cetak_excel();
		}else{
			$this->cetak_pdf();
		}
	}

	function cetak_excel(){
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->data_pembelian($bulan,$tahun);

		$data = array(
			'dt' => $data,
			'bulan' => $bulan,
			'tahun' => $tahun,
			'settitle' => 'Laporan Pembelian Aqua',
			'filename' => date('dmY').'_lap_pembelian_aqua'
		);

		$this->load->view('finance/xls/lap_pembelian_aqua_xls_v',$data);
	}

	function cetak_pdf(){
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->model->data_pembelian($bulan,$tahun);

		$data = array(
			'dt' => $data,
			'bulan' => $bulan,
			'tahun' => $tahun,
			'settitle' => 'Laporan Pembelian Aqua',
			'filename' => date('dmY').'_lap_pembelian_aqua'
		);

		$this->load->view('finance/pdf/lap_pembelian_aqua_pdf_v',$data);
	}

}