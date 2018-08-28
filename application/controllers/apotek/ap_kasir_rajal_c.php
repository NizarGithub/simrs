<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_kasir_rajal_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('apotek/ap_kasir_rajal_m','model');
		date_default_timezone_set('Asia/Jakarta');
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index(){
		$data = array(
			'page' => '',
			'title' => 'Pembelian Obat',
			'subtitle' => 'Pembelian Obat',
			'master_menu' => 'beli_obat',
			'view' => 'beli_obat',
			'url_simpan' => base_url().'apotek/ap_obat_racik_c/simpan'
		);

		$this->load->view('apotek/ap_kasir_v',$data);
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

	function get_pasien(){
		// $tanggal = date('d-m-Y');
		$tanggal = '28-08-2018';
		$keyword = $this->input->get('keyword');
		$data = $this->model->get_pasien($tanggal,$keyword);
		echo json_encode($data);
	}

	function get_poli_by_rj(){
		$id = $this->input->post('id');
		$data = $this->model->get_poli_by_rj($id);
		echo json_encode($data);
	}

	function get_tindakan(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$sql = "
			SELECT
				TD.*
			FROM rk_tindakan_rj TD
			WHERE TD.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		$id_tindakan = '';
		if($query->num_rows() > 0){
			$data = $query->row();
			$id_tindakan = $data->ID;
		}else{
			$id_tindakan = '';
		}

		$tindakan = $this->model->get_tindakan_det($id_tindakan);
		echo json_encode($tindakan);
	}

	function get_resep2(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$sql = "SELECT * FROM rk_resep_rj WHERE ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		$id_resep = '';
		$data = '';
		if($query->num_rows() > 0){
			$data = $query->row();
			$id_resep = $data->ID;
		}else{
			$id_resep = '';
		}

		$resep['ind'] = $data;
		$resep['det'] = $this->model->get_resep2($id_resep);
		echo json_encode($resep);
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

	function simpan_pembayaran(){
		$invoice = $this->input->post('invoice');
		$id_rj = $this->input->post('id_rj');
		$id_pasien = $this->input->post('id_pasien');
		$id_poli = $this->input->post('id_poli');
		$id_pegawai = $this->input->post('id_pegawai');
		$shift = $this->input->post('shift');
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$biaya_poli = str_replace(',', '', $this->input->post('biaya_poli'));
		$biaya_tindakan = str_replace(',', '', $this->input->post('biaya_tindakan'));
		$biaya_resep = str_replace(',', '', $this->input->post('biaya_resep'));
		$biaya_lab = str_replace(',', '', $this->input->post('biaya_lab'));
		$total = str_replace(',', '', $this->input->post('grandtotal2'));
		$jenis_pembayaran = $this->input->post('jenis_pembayaran');

		$this->model->simpan_pembayaran($invoice,$id_rj,$id_pasien,$id_poli,$id_pegawai,$shift,$tanggal,$waktu,$biaya_poli,$biaya_tindakan,$biaya_resep,$biaya_lab,$total,$jenis_pembayaran);
		$this->db->query("UPDATE admum_rawat_jalan SET STS_BAYAR = '1' WHERE ID = '$id_rj'");
		$this->insert_kode();

		echo '1';
	}

	function struk_resep($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_resep($id_rj);

		$data = array(
			'settitle' => 'Struk Resep',
      		'filename' => date('dmY').'_struk_resep',
			'title' => 'Struk Resep',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_resep_v',$data);
	}

	function nota_poli($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->nota_poli($id_rj);

		$data = array(
			'settitle' => 'Nota Poli',
      'filename' => date('dmY').'_nota_poli',
			'title' => 'Nota Poli',
			'row' => $model
		);

		$this->load->view('apotek/pdf/nota_poli_v',$data);
	}

	function struk_pembayaran($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_pembayaran($id_rj);

		$data = array(
			'settitle' => 'Struk Pembayaran',
      'filename' => date('dmY').'struk_pembayaran',
			'title' => 'Struk Pembayaran',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_pembayaran_pdf_v',$data);
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

	function simpan_closing(){
		$id_rajal = $this->input->post('id_rajal');
		$id_pegawai = $this->input->post('id_pegawai');
		$shift = $this->input->post('shift');
		$tanggal = date('d-m-Y');

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$pukul = $format->format('H:i:s');

		$this->model->simpan_closing($id_rajal, $id_pegawai, $shift, $tanggal, $pukul);

		echo "1";
	}

	function data_pembayaran(){
		$data = $this->model->data_pembayaran();
		echo json_encode($data);
	}
}
