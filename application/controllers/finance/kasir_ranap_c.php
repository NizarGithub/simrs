<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_ranap_c extends CI_Controller { 

	function __construct()  
	{ 
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->helper('url');
		$this->load->library('fpdf/HTML2PDF');
		$this->load->model('finance/kasir_ranap_m','model');
		$sess_user = $this->session->userdata('masuk_rs');
		$id_user = $sess_user['id'];
		if($id_user == "" || $id_user == null){
	        redirect(base_url());
	    }
	}

	function index()
	{
		$data = array(
			'title' => 'Kasir Rawat Inap',
			'subtitle' => 'Kasir Rawat Inap',
			'master_menu' => '',
			'view' => ''
		);

		$this->load->view('finance/kasir_ranap_v',$data);
	}

	function get_pasien(){
		$keyword = $this->input->get('keyword');
		$tanggal = date('d-m-Y');
		// $tanggal = '28-08-2018';
		$data = $this->model->get_pasien($tanggal,$keyword);
		echo json_encode($data);
	}

	function get_pasien_id(){
		$id = $this->input->post('id');
		$data = $this->model->get_pasien_id($id);
		echo json_encode($data);
	}

	function add_leading_zero($value, $threshold = 2) {
	    return sprintf('%0' . $threshold . 's', $value);
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

	function get_tindakan(){
		$id_ri = $this->input->post('id_ri');
		$data = $this->model->get_tindakan($id_ri);
		echo json_encode($data);
	}

	function get_resep(){
		$id_ri = $this->input->post('id_ri');
		$data = $this->model->get_resep($id_ri);
		echo json_encode($data);
	}

	function get_kamar(){
		$id_ri = $this->input->post('id_ri');
		$data = $this->model->get_kamar($id_ri);
		echo json_encode($data);
	}

	function get_lab(){
		$id_ri = $this->input->post('id_ri');
		$data = $this->model->get_lab($id_ri);
		echo json_encode($data);
	}

	function get_asuransi(){
		$id_ri = $this->input->post('id_ri');
		$data = $this->model->get_asuransi($id_ri);
		echo json_encode($data);
	}

	function simpan_trx(){
		$id = $this->input->post('id_ri');
		$id_user = $this->input->post('id_user');
		$shift = $this->input->post('shift');
		$invoice = $this->input->post('invoice');
		$tanggal = date('d-m-Y');
		$bulan = date('n');
		$tahun = date('Y');
		$tz_object = new DateTimeZone('Asia/Jakarta');
		$datetime = new DateTime();
		$format = $datetime->setTimezone($tz_object);
		$waktu = $format->format('H:i:s');
		$atas_nama = $this->input->post('b_atas_nama');
		$total = str_replace(',', '', $this->input->post('b_total_tagihan'));
		$bayar = str_replace(',', '', $this->input->post('b_bayar'));
		$tambahan = str_replace(',', '', $this->input->post('b_tambahan'));
		$kembali = str_replace(',', '', $this->input->post('b_kembali'));
		$jenis_bayar = $this->input->post('jenis_bayar');
		$sistem_bayar = $this->input->post('sistem_bayar');
		$kartu_kredit = $this->input->post('kartu_provider');
		$nomor_kartu = $this->input->post('no_kartu');

		if($jenis_bayar == 'Tunai'){
			$this->model->simpan_tunai($id,$id_user,$shift,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$kembali,$jenis_bayar,$sistem_bayar);
		}else{
			$this->model->simpan_non_tunai($id,$id_user,$shift,$invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$total,$bayar,$tambahan,$kembali,$jenis_bayar,$sistem_bayar,$kartu_kredit,$nomor_kartu);
		}

		$this->db->query("UPDATE admum_rawat_inap SET STATUS_BAYAR = '1' WHERE ID = '$id'");
		$this->insert_kode();

		echo '1';
	}

	function cetak($id){
		$data0 = $this->model->data_rawat_inap_id($id);
		$data1 = $this->model->data_cetak_ri($id);
		$data2 = $this->model->get_tindakan($id);
		$data3 = $this->model->get_lab($id);
		$data4 = $this->model->get_resep($id);

		$data = array(
			'settitle' => 'Pelayanan Rawat Inap',
			'filename' => date('dmY').'_cetak_rawat_inap',
			'view'	=> 'ri',
			'data0' => $data0,
			'data1' => $data1,
			'data2' => $data2,
			'data3' => $data3,
			'data4' => $data4
		);

		$this->load->view('finance/cetak_rawat_inap_pdf_v',$data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */