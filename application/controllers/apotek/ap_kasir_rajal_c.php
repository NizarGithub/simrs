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
		$tanggal = date('d-m-Y');
		// $tanggal = '29-08-2018';
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
		$sql = "SELECT
						TD.*
						FROM rk_tindakan_rj TD
						WHERE TD.ID_PASIEN = '$id_pasien'
						AND TD.TANGGAL = '$tanggal'
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
		$sql = "SELECT
						*
						FROM
						rk_resep_rj
						WHERE ID_PASIEN = '$id_pasien'
						AND TANGGAL = '$tanggal'";
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

	function get_laborat(){
		$id_pasien = $this->input->post('id_pasien');
		$tanggal = date('d-m-Y');
		$sql = "SELECT * FROM rk_laborat_rj WHERE ID_PASIEN = '$id_pasien' AND TANGGAL = '$tanggal'";
		$query = $this->db->query($sql);
		$id_laborat = '';
		$data = '';
		if($query->num_rows() > 0){
			$data = $query->row();
			$id_laborat = $data->ID;
		}else{
			$id_laborat = '';
		}

		$resep['ind'] = $data;
		$resep['det'] = $this->model->get_laborat($id_laborat);
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
		$id_resep = $this->input->post('id_resep');
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
		$bayar = str_replace(',', '', $this->input->post('bayar2'));
		$kartu_provider = $this->input->post('kartu_provider');
		$no_kartu = $this->input->post('no_kartu');
		$tambahan = str_replace(',', '', $this->input->post('tambahan'));
		$status_iter = $this->input->post('status_iter');

		$this->model->simpan_pembayaran(
			$invoice,
			$id_rj,
			$id_pasien,
			$id_poli,
			$id_resep,
			$id_pegawai,
			$shift,
			$tanggal,
			$waktu,
			$biaya_poli,
			$biaya_tindakan,
			$biaya_resep,
			$biaya_lab,
			$total,
			$jenis_pembayaran,
			$bayar,
			$kartu_provider,
			$no_kartu,
			$tambahan,
			$status_iter
		);

		$this->db->query("UPDATE admum_rawat_jalan SET STS_BAYAR = '1' WHERE ID = '$id_rj'");
		$this->insert_kode();

		echo '1';
	}

	function simpan_pembayaran_obat(){
		$invoice = $this->input->post('invoice_penjualan');
		$id_penjualan = $this->input->post('id_penjualan');
		$id_pegawai = $this->input->post('id_pegawai');
		$shift = $this->input->post('shift');
		$tanggal = $this->input->post('tanggal');
		$waktu = $this->input->post('waktu');
		$total = str_replace(',', '', $this->input->post('grand_total_pj'));
		$jenis_pembayaran = $this->input->post('jenis_bayar');
		$bayar = str_replace(',', '', $this->input->post('b_bayar'));
		$kartu_provider = $this->input->post('kartu_provider');
		$no_kartu = $this->input->post('no_kartu');
		$tambahan = str_replace(',', '', $this->input->post('tambahan_pj'));
		$kembali = str_replace(',', '', $this->input->post('b_kembali'));
		$tipe = $this->input->post('tipe');
		$id_resep = $this->input->post('id_resep');
		$id_dokter = $this->input->post('id_dokter');
		$id_pasien = $this->input->post('id_pasien');

		$this->model->simpan_pembayaran_obat(
			$invoice,
			$id_penjualan,
			$id_pegawai,
			$shift,
			$tanggal,
			$waktu,
			$total,
			$jenis_pembayaran,
			$bayar,
			$kartu_provider,
			$no_kartu,
			$tambahan,
			$kembali,
			$tipe,
			$id_dokter,
			$id_pasien,
			$id_resep
		);

		if ($tipe == '2') {
			$this->db->query("UPDATE ap_penjualan_obat_hv SET STS_BAYAR = '1' WHERE ID = '$id_penjualan'");
		}elseif ($tipe == '3') {
			$this->db->query("UPDATE ap_penjualan_obat_paket SET STS_BAYAR = '1' WHERE ID = '$id_penjualan'");
		}elseif ($tipe == '4') {
			$this->db->query("UPDATE rk_ri_resep SET STS_BAYAR = '1' WHERE ID = '$id_penjualan'");
		}elseif ($tipe == '5') {
			$this->db->query("UPDATE ap_iter SET STS_BAYAR = '1' WHERE ID = '$id_penjualan'");
		}

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

	function struk_resep_ranap($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_resep_ranap($id_rj);

		$data = array(
			'settitle' => 'Struk Resep',
      		'filename' => date('dmY').'_struk_resep',
			'title' => 'Struk Resep',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_resep_ranap_v',$data);
	}

	function struk_copy_resep($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_copy_resep($id_rj);

		$data = array(
			'settitle' => 'Struk Copy Resep',
      'filename' => date('dmY').'_struk_copy_resep',
			'title' => 'Struk Copy Resep',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_copy_resep_v',$data);
	}

	function struk_copy_resep_entry($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_copy_resep_entry($id_rj);

		$data = array(
			'settitle' => 'Struk Copy Entry Resep',
      'filename' => date('dmY').'_struk_copy_entry_resep',
			'title' => 'Struk Copy Entry Resep',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_copy_entry_resep_v',$data);
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

	function struk_pembayaran_hv($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_pembayaran_hv($id_rj);

		$data = array(
			'settitle' => 'Struk Pembayaran HV',
      'filename' => date('dmY').'struk_pembayaran_hv',
			'title' => 'Struk Pembayaran Hv',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_pembayaran_hv_pdf_v',$data);
	}

	function struk_pembayaran_ranap($id_rj){
		// $id_rj = base64_decode($id);
		$model = $this->model->struk_pembayaran_ranap($id_rj);

		$data = array(
			'settitle' => 'Struk Pembayaran',
      'filename' => date('dmY').'struk_pembayaran',
			'title' => 'Struk Pembayaran',
			'row' => $model
		);

		$this->load->view('apotek/pdf/ap_struk_pembayaran_ranap_pdf_v',$data);
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
		$id_pegawai = $this->input->post('id_pegawai');
		$shift = $this->input->post('shift');
		$tanggal = date('d-m-Y');
		$bulan = date('m');
		$tahun = date('Y');

		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$pukul = $format->format('H:i:s');

		$nilai_resep = $this->input->post('nilai_resep');
		$nilai_obat = $this->input->post('nilai_obat');
		$service = $this->input->post('service');
		$lembar_hv = $this->input->post('lembar_hv');
		$nilai_hv = $this->input->post('nilai_hv');
		$jumlah_total = $this->input->post('jumlah_total');
		$lembar_resep = $this->input->post('lembar_resep');

		$data_tutup = array(
			'ID_PEGAWAI' => $id_pegawai,
			'TANGGAL' => $tanggal,
			'BULAN' => $bulan,
			'TAHUN' => $tahun,
			'WAKTU' => $pukul,
			'LEMBAR_RESEP' => $lembar_resep,
			'NILAI_RESEP' => $nilai_resep,
			'NILAI_OBAT' => $nilai_obat,
			'SERVICE' => $service,
			'LEMBAR_HV' => $lembar_hv,
			'NILAI_HV' => $nilai_hv,
			'JUMLAH_TOTAL' => $jumlah_total,
			'SHIFT' => $shift
		);

		$this->db->insert('ap_tutup_kasir_rajal', $data_tutup);
		$data['id_tutup'] = $this->db->insert_id();

		echo json_encode($data);
	}

	function simpan_closing_rajal(){
			$id_semua = $this->input->post('id_rajal');
			$total = $this->input->post('total_rajal');
			$id_pegawai = $this->input->post('id_pegawai');
			$shift = $this->input->post('shift');
			$tanggal = date('d-m-Y');
			$bulan = date('m');
			$tahun = date('Y');
			$invoice = $this->input->post('invoice');

			$tz_object = new DateTimeZone('Asia/Jakarta');
			$datetime = new DateTime();
			$format = $datetime->setTimezone($tz_object);
			$pukul = $format->format('H:i:s');

			$id_tutup = $this->input->post('id_tutup');
			$this->model->simpan_closing_rajal($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice);
			echo "1";
	}

	function simpan_closing_hv(){
			$id_semua = $this->input->post('id_hv');
			$total = $this->input->post('total_hv');
			$id_pegawai = $this->input->post('id_pegawai');
			$shift = $this->input->post('shift');
			$tanggal = date('d-m-Y');
			$bulan = date('m');
			$tahun = date('Y');
			$invoice = $this->input->post('invoice');

			$tz_object = new DateTimeZone('Asia/Jakarta');
			$datetime = new DateTime();
			$format = $datetime->setTimezone($tz_object);
			$pukul = $format->format('H:i:s');

			$id_tutup = $this->input->post('id_tutup');
			$this->model->simpan_closing_hv($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice);
			echo "1";
	}

	// function simpan_closing_paket(){
	// 		$id_semua = $this->input->post('id_paket');
	// 		$total = $this->input->post('total_paket');
	// 		$id_pegawai = $this->input->post('id_pegawai');
	// 		$shift = $this->input->post('shift');
	// 		$tanggal = date('d-m-Y');
	// 		$bulan = date('m');
	// 		$tahun = date('Y');
	// 		$invoice = $this->input->post('invoice');
	//
	// 		$tz_object = new DateTimeZone('Asia/Jakarta');
	// 		$datetime = new DateTime();
	// 		$format = $datetime->setTimezone($tz_object);
	// 		$pukul = $format->format('H:i:s');
	//
	// 		$id_tutup = $this->input->post('id_tutup');
	// 		$this->model->simpan_closing_paket($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice);
	// 		echo "1";
	// }

	function simpan_closing_ranap(){
			$id_semua = $this->input->post('id_ranap');
			$total = $this->input->post('total_ranap');
			$id_pegawai = $this->input->post('id_pegawai');
			$shift = $this->input->post('shift');
			$tanggal = date('d-m-Y');
			$bulan = date('m');
			$tahun = date('Y');
			$invoice = $this->input->post('invoice');

			$tz_object = new DateTimeZone('Asia/Jakarta');
			$datetime = new DateTime();
			$format = $datetime->setTimezone($tz_object);
			$pukul = $format->format('H:i:s');

			$id_tutup = $this->input->post('id_tutup');
			$this->model->simpan_closing_ranap($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice);
			echo "1";
	}

	function simpan_closing_entry(){
			$id_semua = $this->input->post('id_entry_resep');
			$total = $this->input->post('total_entry');
			$id_pegawai = $this->input->post('id_pegawai');
			$shift = $this->input->post('shift');
			$tanggal = date('d-m-Y');
			$bulan = date('m');
			$tahun = date('Y');
			$invoice = $this->input->post('invoice');

			$tz_object = new DateTimeZone('Asia/Jakarta');
			$datetime = new DateTime();
			$format = $datetime->setTimezone($tz_object);
			$pukul = $format->format('H:i:s');

			$id_tutup = $this->input->post('id_tutup');
			$this->model->simpan_closing_entry($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice);
			echo "1";
	}

	function data_pembayaran(){
		$keyword = $this->input->get('keyword');
		$data = $this->model->data_pembayaran($keyword);
		echo json_encode($data);
	}

	function data_rekap_pendapatan(){
		$data = $this->model->data_rekap_pendapatan();
		echo json_encode($data);
	}

	function data_poli(){
		$data = $this->model->data_poli();
		echo json_encode($data);
	}

	function tanggal_filter(){
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $data = $this->model->tanggal_filter($tanggal_sekarang, $tanggal_sampai);
    echo json_encode($data);
  }

	function poli_filter(){
    $id_poli = $this->input->post('result_poli');
    $data = $this->model->poli_filter($id_poli);
    echo json_encode($data);
  }

	function print_pdf(){
    $by = $this->input->post('by');
    $tanggal_sekarang = $this->input->post('tanggal_sekarang');
    $tanggal_sampai = $this->input->post('tanggal_sampai');
    $id_poli = $this->input->post('id_poli');

    $data = $this->model->print_pdf(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $id_poli
    );

		$data_row = $this->model->print_pdf_row(
      $by,
      $tanggal_sekarang,
      $tanggal_sampai,
      $id_poli
    );

    $array = array(
      'settitle' => 'Rekap Pendapatan',
      'filename' => date('dmY').'_rekap_pendapatan',
      'data' => $data,
			'data_row' => $data_row,
			'by' => $by,
			'tanggal_sekarang' => $tanggal_sekarang,
			'tanggal_sampai' => $tanggal_sampai,
			'id_poli' => $id_poli
    );
    $this->load->view('apotek/pdf/print_rekap_pendaftaran_pdf_v', $array);
  }

	function get_hv_by_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_hv_by_id($id);
		echo json_encode($data);
	}

	function get_paket_by_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_paket_by_id($id);
		echo json_encode($data);
	}

	function get_ranap_by_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_ranap_by_id($id);
		echo json_encode($data);
	}

	function get_entry_resep_by_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_entry_resep_by_id($id);
		echo json_encode($data);
	}

	function get_pendapatan(){
		$tanggal = date('d-m-Y');
		$shift = $this->input->post('shift');
		$resep = $this->db->query("SELECT
															 COUNT(a.ID) AS TOTAL_RESEP
															 FROM
															 rk_resep_rj a
															 LEFT JOIN rk_pembayaran_kasir b ON a.ID_PELAYANAN = b.ID_PELAYANAN
															 LEFT JOIN admum_rawat_jalan c ON a.ID_PELAYANAN = c.ID
															 WHERE a.TANGGAL = '$tanggal'
															 AND b.SHIFT = '$shift'
															 AND b.STATUS_CLOSING = '0'
															 AND c.STS_BAYAR = '1'
														  ")->row_array();
		$resep_ranap = $this->db->query("SELECT
																		COUNT(a.ID) AS TOTAL_RESEP
																		FROM rk_pembayaran_resep_ranap a
																		LEFT JOIN rk_ri_resep b ON a.ID_RESEP_RANAP = b.ID
																		WHERE b.TANGGAL = '$tanggal'
																		AND a.SHIFT = '$shift'
																		AND b.STATUS_CLOSING = '0'
																		AND b.STS_BAYAR = '1'
																		")->row_array();
		$obat = $this->db->query("SELECT
															b.*,
															( b.TOTAL_SERVICE * b.TOTAL_BELI) AS TOTAL_SEMUA_SERVICE
															FROM(
															SELECT
															a.*,
															SUM(a.TOTAL) AS NILAI_OBAT,
															SUM(a.SERVICE) AS TOTAL_SERVICE,
															SUM(a.JUMLAH_BELI) AS TOTAL_BELI
															FROM(
															SELECT
															a.HARGA,
															a.JUMLAH_BELI,
															b.TANGGAL,
															(a.HARGA * a.JUMLAH_BELI) AS TOTAL,
															a.SERVICE,
															c.SHIFT,
															c.STATUS_CLOSING,
															d.STS_BAYAR
															FROM
															rk_resep_detail_rj a
															LEFT JOIN rk_resep_rj b ON a.ID_RESEP = b.ID
															LEFT JOIN rk_pembayaran_kasir c ON b.ID_PELAYANAN = c.ID_PELAYANAN
															LEFT JOIN admum_rawat_jalan d ON d.ID = b.ID_PELAYANAN
															)
															a
															WHERE a.TANGGAL = '$tanggal'
															AND a.SHIFT = '$shift'
															AND a.STATUS_CLOSING = '0'
															AND a.STS_BAYAR = '1'
															)
															b
														 ")->row_array();
		$obat_ranap = $this->db->query("SELECT
																		b.*,
																		( b.TOTAL_SERVICE * b.TOTAL_BELI) AS TOTAL_SEMUA_SERVICE
																		FROM(
																		SELECT
																		a.*,
																		SUM(a.TOTAL) AS NILAI_OBAT,
																		SUM(a.SERVICE) AS TOTAL_SERVICE,
																		SUM(a.JUMLAH_BELI) AS TOTAL_BELI
																		FROM(
																		SELECT
																		a.HARGA,
																		a.JUMLAH_BELI,
																		a.TANGGAL,
																		(a.HARGA * a.JUMLAH_BELI) AS TOTAL,
																		a.SERVICE,
																		c.SHIFT,
																		b.STATUS_CLOSING,
																		b.STS_BAYAR
																		FROM
																		rk_ri_resep_detail a
																		LEFT JOIN rk_ri_resep b ON a.ID_RESEP = b.ID
																		LEFT JOIN rk_pembayaran_resep_ranap c ON b.ID = c.ID_RESEP_RANAP
																		)
																		a
																		WHERE a.TANGGAL = '$tanggal'
																		AND a.SHIFT = '$shift'
																		AND a.STATUS_CLOSING = '0'
																		AND a.STS_BAYAR = '1'
																		)
																		b
																		")->row_array();

		$resep_entry = $this->db->query("SELECT
																			COUNT(a.ID) AS TOTAL_RESEP
																			FROM ap_pembayaran_iter a
																			LEFT JOIN ap_iter b ON a.ID_ITER = b.ID
																			WHERE b.TANGGAL = '$tanggal'
																			AND a.SHIFT = '$shift'
																			AND b.STATUS_CLOSING = '0'
																		")->row_array();
		$entry = $this->db->query("SELECT
																a.*,
																SUM(a.TOTAL_SERVICE * a.TOTAL_BELI) AS TOTAL_SEMUA_SERVICE
																FROM
																(
																SELECT
																SUM(a.TOTAL_NORMAL) AS NILAI_ENTRY,
																SUM(a.SERVICE) AS TOTAL_SERVICE,
																SUM(a.JUMLAH_BELI) AS TOTAL_BELI,
																a.TANGGAL,
																c.SHIFT,
																b.STATUS_CLOSING
																FROM
																ap_iter_detail a
																LEFT JOIN ap_iter b ON a.ID_ITER = b.ID
																LEFT JOIN ap_pembayaran_iter c ON b.ID = c.ID_ITER
																WHERE a.TANGGAL = '$tanggal'
																AND c.SHIFT = '$shift'
																AND b.STATUS_CLOSING = '0'
																)a

															")->row_array();
		$hv = $this->db->query("SELECT
														COUNT(a.ID) AS LEMBAR_HV,
														SUM(a.TOTAL) AS NILAI_HV
														FROM
														ap_pembayaran_hv a
														LEFT JOIN ap_penjualan_obat_hv b ON a.ID_PENJUALAN_HV = b.ID
														WHERE a.TANGGAL = '$tanggal'
														AND a.SHIFT = '$shift'
														AND b.STATUS_CLOSING = '0'
														")->row_array();
		$nilai_obat_rajal = $obat['NILAI_OBAT'];
		$nilai_service_rajal = $obat['TOTAL_SEMUA_SERVICE'];
		$nilai_obat_ranap = $obat_ranap['NILAI_OBAT'];
		$nilai_service_ranap = $obat_ranap['TOTAL_SEMUA_SERVICE'];

		$total_entry_resep = $entry['NILAI_ENTRY'];
		$nilai_service_entry = $entry['TOTAL_SEMUA_SERVICE'];

		$nilai_obat = $nilai_obat_rajal + $nilai_obat_ranap + $total_entry_resep;
		$nilai_service = $nilai_service_rajal + $nilai_service_ranap + $nilai_service_entry;
		$nilai_resep = $nilai_obat + $nilai_service;
		$nilai_hv = $hv['NILAI_HV'];
		$total = $nilai_resep + $nilai_hv;

		$resep_rajal = $resep['TOTAL_RESEP'];
		$resep_ranap = $resep_ranap['TOTAL_RESEP'];
		$jumlah_entry_resep = $resep_entry['TOTAL_RESEP'];
		$total_resep = $resep_rajal + $resep_ranap + $jumlah_entry_resep;

		$data['total_resep'] = $total_resep;
		$data['nilai_obat'] = $nilai_obat;
		$data['total_service'] = $nilai_service;
		$data['nilai_resep'] = $nilai_resep;
		$data['lembar_hv'] = $hv['LEMBAR_HV'];
		$data['nilai_hv'] = $hv['NILAI_HV'];
		$data['jumlah_total'] = $total;

		echo json_encode($data);
	}

	function tutup_rekap_pendapatan($id_tutup){
		$sql_tutup = $this->db->query("SELECT * FROM ap_tutup_kasir_rajal WHERE ID = '$id_tutup'")->row_array();
		$shift = $sql_tutup['SHIFT'];
		$tanggal = $sql_tutup['TANGGAL'];

		$obat_hv = $this->model->rekap_pendapatan_obat_hv($id_tutup, $shift, $tanggal);
		$obat_rj = $this->model->rekap_pendapatan_obat_rj($id_tutup, $shift, $tanggal);
		// $poli = $this->model->rekap_pendapatan_poli($id_tutup);

		$data = array(
			'settitle' => 'Tutup Rekap Pendapatan',
      'filename' => date('dmY').'_tutup_rekap_pendapatan',
			'title' => 'Tutup Rekap Pendapatan',
			'obat_hv' => $obat_hv,
			'obat_rj' => $obat_rj,
			'shift' => $shift,
			'tanggal' => $tanggal
		);

		$this->load->view('apotek/pdf/rekap_pendapatan_pdf_v', $data);
	}

}
