<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_beli_obat_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('apotek/ap_beli_obat_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
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

		//TRX-001/X/2016
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

		$data = array(
			'page' => '',
			'title' => 'Pembelian Obat',
			'subtitle' => 'Pembelian Obat',
			'master_menu' => 'beli_obat',
			'view' => 'beli_obat',
			'url_simpan' => base_url().'apotek/ap_obat_racik_c/simpan',
			'get_invoice' => $kode,
		);

		$this->load->view('apotek/ap_kasir_v',$data);
	}

	function get_data_obat(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_obat($keyword);
		echo json_encode($data);
	}

	function get_data_obat2(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->data_obat($keyword);
		echo json_encode($data);
	}

	function data_obat_id(){
		$id = $this->input->post('id');
		$data = $this->model->data_obat_id($id);
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

	function simpan_trx(){
		$invoice = $this->input->post('invoice_hidden');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$atas_nama = addslashes($this->input->post('b_atas_nama'));
		$diskon = '0';
		$ppn = $this->input->post('ppn_hidden');
		$total = str_replace(',', '', $this->input->post('b_total_tagihan'));
		$bayar = str_replace(',', '', $this->input->post('b_bayar'));
		$kembali = str_replace(',', '', $this->input->post('b_kembali'));

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');

		$jenis_bayar = $this->input->post('jenis_bayar');
		$kartu_kredit = $this->input->post('kartu_provider');
		$nomor_kartu = $this->input->post('no_kartu');
		$id_transaksi = "";

		if($jenis_bayar == 'Tunai'){
			$this->model->simpan_trx($invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$diskon,$ppn,$total,$bayar,$kembali,$jenis_bayar);
			$id_transaksi = $this->db->insert_id();
		}else{
			$bayar = $total;
			$kembali = $bayar - $total;
			$this->model->simpan_trx_kredit($invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$diskon,$ppn,$total,$bayar,$kembali,$jenis_bayar,$kartu_kredit,$nomor_kartu);
			$id_transaksi = $this->db->insert_id();
		}

		$id_obat = $this->input->post('id_obat');
		$harga = $this->input->post('harga_obat');
		$jumlah_beli = $this->input->post('jumlah_obat');
		$subtotal = $this->input->post('subtotal_hidden');

		foreach ($id_obat as $key => $value) {
			$this->model->simpan_det_trx($id_transaksi,$value,$harga[$key],$jumlah_beli[$key],$subtotal[$key]);
			$j = $jumlah_beli[$key];
			$this->db->query("UPDATE apotek_gudang_obat SET TOTAL = TOTAL - $j WHERE ID = '$value'");
		}

		$this->insert_kode();

		echo '1';
	}

	function struk($invoice){
		$sql1 = "SELECT TRX.* FROM apotek_transaksi TRX WHERE TRX.INVOICE = '$invoice'";
		$data1 = $this->db->query($sql1)->row();
		$id_transaksi = $data1->ID;

		$sql2 = "
			SELECT
				DET.ID,
				DET.ID_TRANSAKSI,
				DET.ID_OBAT,
				STP.NAMA_OBAT,
				DET.HARGA,
				DET.JUMLAH_BELI,
				DET.SUBTOTAL
			FROM apotek_transaksi_detail DET
			LEFT JOIN apotek_gudang_obat OBT ON OBT.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBT.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_TRANSAKSI = '$id_transaksi'
		";
		$data2 = $this->db->query($sql2)->result();

		$sql3 = "SELECT COUNT(*) AS TOTAL FROM apotek_transaksi_detail WHERE ID_TRANSAKSI = '$id_transaksi'";
		$data3 = $this->db->query($sql3)->row();

		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
        $user = $this->master_model_m->get_user_info($id_user);

		$data = array(
			'invoice' => $invoice,
			'data1' => $data1,
			'data2' => $data2,
			'item' => $data3->TOTAL,
			'kasir' => strtoupper($user->NAMA),
		);

		$this->load->view('apotek/pdf/ap_struk_pembayaran_pdf_v',$data);
	}

	function get_resep(){
		$keyword = $this->input->post('keyword');
		$data = $this->model->get_resep($keyword);
		echo json_encode($data);
	}

	function get_resep_id(){
		$id_resep = $this->input->post('id_resep');
		$dari = $this->input->post('dari');
		$data = $this->model->get_resep_id($id_resep,$dari);
		echo json_encode($data);
	} 

}