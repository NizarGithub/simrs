<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kasir_ranap_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_pasien($tanggal,$keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA_PASIEN LIKE '%$keyword%' OR a.NAMA_ORTU LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT
							a.*,
							(a.TOT_TINDAKAN+a.TOT_RESEP+a.BIAYA_KAMAR+a.BIAYA_VISITE+a.JASA_SARANA+a.TOT_LAB) AS TOTAL
						FROM(
							SELECT
								a.ID,
								a.ID_PASIEN,
								b.KODE_PASIEN,
								b.NAMA AS NAMA_PASIEN,
								b.JENIS_KELAMIN,
								b.NAMA_ORTU,
								a.TANGGAL_MASUK,
								a.WAKTU,
								a.BULAN,
								a.TAHUN,
								a.NAMA_PENANGGUNGJAWAB,
								a.TELEPON,
								a.SISTEM_BAYAR,
								a.ASAL_RUJUKAN,
								a.ID_DOKTER,
								a.KELAS,
								a.ID_KAMAR,
								a.ID_BED,
								a.STATUS_SUDAH,
								IFNULL(c.TOTAL,0) AS TOT_TINDAKAN,
								IFNULL(d.TOTAL,0) AS TOT_RESEP,
								e.BIAYA AS BIAYA_KAMAR,
								e.BIAYA_VISITE,
								e.JASA_SARANA,
								IFNULL(g.TOTAL_TARIF,0) AS TOT_LAB
							FROM admum_rawat_inap a
							JOIN rk_pasien b ON b.ID = a.ID_PASIEN
							LEFT JOIN rk_ri_tindakan c ON c.ID_PELAYANAN = a.ID
							LEFT JOIN rk_ri_resep d ON d.ID_PELAYANAN = a.ID
							LEFT JOIN admum_kamar_rawat_inap e ON e.ID = a.ID_KAMAR
							LEFT JOIN rk_ri_laborat g ON g.ID_PELAYANAN = a.ID
						) a
						WHERE $where
						AND a.TANGGAL_MASUK = '$tanggal'
						ORDER BY a.ID DESC
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_pasien_id($id){
		$sql = "
			SELECT
				a.*,
				(a.TOT_TINDAKAN+a.TOT_RESEP+a.BIAYA_KAMAR+a.BIAYA_VISITE+a.JASA_SARANA+a.TOT_LAB) AS TOTAL
			FROM(
				SELECT
					a.ID,
					a.ID_PASIEN,
					b.KODE_PASIEN,
					b.NAMA AS NAMA_PASIEN,
					b.JENIS_KELAMIN,
					b.NAMA_ORTU,
					a.TANGGAL_MASUK,
					a.WAKTU,
					a.BULAN,
					a.TAHUN,
					a.NAMA_PENANGGUNGJAWAB,
					a.TELEPON,
					a.SISTEM_BAYAR,
					a.ASAL_RUJUKAN,
					a.ID_DOKTER,
					a.KELAS,
					a.ID_KAMAR,
					a.ID_BED,
					a.STATUS_SUDAH,
					IFNULL(c.TOTAL,0) AS TOT_TINDAKAN,
					IFNULL(d.TOTAL,0) AS TOT_RESEP,
					e.BIAYA AS BIAYA_KAMAR,
					e.BIAYA_VISITE,
					e.JASA_SARANA,
					IFNULL(g.TOTAL_TARIF,0) AS TOT_LAB
				FROM admum_rawat_inap a
				JOIN rk_pasien b ON b.ID = a.ID_PASIEN
				LEFT JOIN rk_ri_tindakan c ON c.ID_PELAYANAN = a.ID
				LEFT JOIN rk_ri_resep d ON d.ID_PELAYANAN = a.ID
				LEFT JOIN admum_kamar_rawat_inap e ON e.ID = a.ID_KAMAR
				LEFT JOIN rk_ri_laborat g ON g.ID_PELAYANAN = a.ID
			) a
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan($id_ri){
		$sql = "
			SELECT
				DET.*,
				a.ID_PELAYANAN,
				b.NAMA_TINDAKAN,
				b.TARIF
			FROM rk_ri_tindakan_detail DET
			LEFT JOIN rk_ri_tindakan a ON DET.ID_TINDAKAN = a.ID
			LEFT JOIN admum_setup_tindakan b ON b.ID = DET.ID_SETUP_TINDAKAN
			WHERE a.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_resep($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_OBAT,
				b.NAMA_OBAT,
				a.SUBTOTAL
			FROM rk_ri_resep_detail a
			LEFT JOIN (
				SELECT a.ID,a.ID_SETUP_NAMA_OBAT,b.NAMA_OBAT FROM apotek_gudang_obat a
				LEFT JOIN admum_setup_nama_obat b ON b.ID = a.ID_SETUP_NAMA_OBAT
			) b ON b.ID = a.ID_OBAT
			LEFT JOIN rk_ri_resep c ON a.ID_RESEP = c.ID
			WHERE c.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_kamar($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.ID_PASIEN,
				b.KELAS,
				b.BIAYA,
				b.VISITE_DOKTER,
				b.BIAYA_VISITE,
				b.JASA_SARANA,
				c.NOMOR_BED
			FROM admum_rawat_inap a
			LEFT JOIN admum_kamar_rawat_inap b ON b.ID = a.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap c ON c.ID = a.ID_BED
			WHERE a.ID = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_lab($id_ri){
		$sql = "
			SELECT
				a.ID,
				a.PEMERIKSAAN,
				b.NAMA_PEMERIKSAAN,
				a.SUBTOTAL
			FROM rk_ri_laborat_detail a
			LEFT JOIN admum_setup_pemeriksaan b ON b.ID = a.PEMERIKSAAN
			LEFT JOIN rk_ri_laborat c ON a.ID_PEMERIKSAAN_RJ = c.ID
			WHERE c.ID_PELAYANAN = '$id_ri'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_user_detail($id_user){
		$sql = "
			SELECT
				a.*
			FROM kepeg_pegawai a
			WHERE a.ID = '$id_user'
		";

		return $this->db->query($sql)->row();
	}

	function simpan_trx($invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$diskon,$ppn,$total,$bayar,$kembali,$jenis_bayar){
		$sql = "
			INSERT INTO apotek_transaksi(
				INVOICE,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ATAS_NAMA,
				DISKON,
				PPN,
				TOTAL,
				BAYAR,
				KEMBALI,
				JENIS_BAYAR
			) VALUES (
				'$invoice',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$atas_nama',
				'$diskon',
				'$ppn',
				'$total',
				'$bayar',
				'$kembali',
				'$jenis_bayar'
			)
		";
		$this->db->query($sql);
	}

	function simpan_trx_kredit($invoice,$tanggal,$bulan,$tahun,$waktu,$atas_nama,$diskon,$ppn,$total,$bayar,$kembali,$jenis_bayar,$kartu_kredit,$nomor_kartu){
		$sql = "
			INSERT INTO apotek_transaksi(
				INVOICE,
				TANGGAL,
				BULAN,
				TAHUN,
				WAKTU,
				ATAS_NAMA,
				DISKON,
				PPN,
				TOTAL,
				BAYAR,
				KEMBALI,
				JENIS_BAYAR,
				KARTU_KREDIT,
				NOMOR_KARTU
			) VALUES (
				'$invoice',
				'$tanggal',
				'$bulan',
				'$tahun',
				'$waktu',
				'$atas_nama',
				'$diskon',
				'$ppn',
				'$total',
				'$bayar',
				'$kembali',
				'$jenis_bayar',
				'$kartu_kredit',
				'$nomor_kartu'
			)
		";
		$this->db->query($sql);
	}

	function simpan_det_trx($id_transaksi,$id_obat,$harga,$jumlah_beli,$subtotal){
		$sql = "
			INSERT INTO apotek_transaksi_detail(
				ID_TRANSAKSI,
				ID_OBAT,
				HARGA,
				JUMLAH_BELI,
				SUBTOTAL
			) VALUES (
				'$id_transaksi',
				'$id_obat',
				'$harga',
				'$jumlah_beli',
				'$subtotal'
			)
		";
		$this->db->query($sql);
	}

	function simpan_pembayaran($invoice,$id_rj,$id_pasien,$id_poli,$id_pegawai,$shift,$tanggal,$waktu,$biaya_poli,$biaya_tindakan,$biaya_resep,$biaya_lab,$total,$jenis_pembayaran){
		$sql = "
			INSERT INTO rk_pembayaran_kasir(
				INVOICE,
				ID_PELAYANAN,
				ID_PASIEN,
				ID_POLI,
				ID_PEGAWAI,
				SHIFT,
				TANGGAL,
				WAKTU,
				BIAYA_POLI,
				BIAYA_TINDAKAN,
				BIAYA_RESEP,
				BIAYA_LAB,
				TOTAL,
				JENIS_PEMBAYARAN,
				TIPE
			) VALUES (
				'$invoice',
				'$id_rj',
				'$id_pasien',
				'$id_poli',
				'$id_pegawai',
				'$shift',
				'$tanggal',
				'$waktu',
				'$biaya_poli',
				'$biaya_tindakan',
				'$biaya_resep',
				'$biaya_lab',
				'$total',
				'$jenis_pembayaran',
				'RJ'
			)
		";
		$this->db->query($sql);
	}

	function struk_resep($id_rj){
		$sql = $this->db->query("
			SELECT
				RJ.ID,
				RJ.ID_PASIEN,
				PS.NAMA,
				PS.UMUR,
				PS.ALAMAT AS ALAMAT_PASIEN,
				PS.KODE_PASIEN,
				PS.TELEPON AS TELEPON_PASIEN,
				IFNULL(PS.NAMA_ORTU,'-') AS NAMA_ORTU,
				RJ.TANGGAL,
				RJ.ID_POLI,
				IFNULL(PL.NAMA,'-') AS NAMA_POLI,
				PEG.NAMA AS NAMA_PEGAWAI,
				RJ.STS_BAYAR,
				IFNULL(PL.BIAYA,0) AS TOT_POLI,
				IFNULL(TD.TOTAL,0) AS TOT_TINDAKAN,
				IFNULL(RS.TOTAL,0) AS TOT_RESEP,
				IFNULL(LAB.TOTAL_TARIF,0) AS TOT_LAB,
				RS.ID AS ID_RESEP,
				RS.ALERGI_OBAT,
				RS.KODE_RESEP
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
			LEFT JOIN (
				SELECT * FROM admum_poli
				WHERE AKTIF = '1'
			) PL ON PL.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = PL.ID_PEG_DOKTER
			LEFT JOIN rk_tindakan_rj TD ON TD.ID_PELAYANAN = RJ.ID
			LEFT JOIN rk_resep_rj RS ON RS.ID_PELAYANAN = RJ.ID
			LEFT JOIN rk_laborat_rj LAB ON LAB.ID_PELAYANAN = RJ.ID
		  	WHERE RJ.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function struk_pembayaran($id_rj){
		$sql = $this->db->query("
			SELECT
				RJ.ID,
				RJ.ID_PASIEN,
				PS.NAMA,
				PS.UMUR,
				PS.ALAMAT AS ALAMAT_PASIEN,
				PS.KODE_PASIEN,
				PS.TELEPON AS TELEPON_PASIEN,
				IFNULL(PS.NAMA_ORTU,'-') AS NAMA_ORTU,
				RJ.TANGGAL,
				RJ.ID_POLI,
				IFNULL(PL.NAMA,'-') AS NAMA_POLI,
				PEG.NAMA AS NAMA_PEGAWAI,
				RJ.STS_BAYAR,
				IFNULL(PL.BIAYA,0) AS TOT_POLI,
				IFNULL(TD.TOTAL,0) AS TOT_TINDAKAN,
				IFNULL(RS.TOTAL,0) AS TOT_RESEP,
				IFNULL(LAB.TOTAL_TARIF,0) AS TOT_LAB,
				RS.ID AS ID_RESEP,
				RS.ALERGI_OBAT,
				RS.KODE_RESEP
			FROM admum_rawat_jalan RJ
			LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
			LEFT JOIN (
				SELECT * FROM admum_poli
				WHERE AKTIF = '1'
			) PL ON PL.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = PL.ID_PEG_DOKTER
			LEFT JOIN rk_tindakan_rj TD ON TD.ID_PELAYANAN = RJ.ID
			LEFT JOIN rk_resep_rj RS ON RS.ID_PELAYANAN = RJ.ID
			LEFT JOIN rk_laborat_rj LAB ON LAB.ID_PELAYANAN = RJ.ID
		  	WHERE RJ.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function detail_resep($id_resep){
		$sql = "
			SELECT
				a.*,
				b.NAMA_OBAT
			FROM rk_resep_detail_rj a
			JOIN admum_setup_nama_obat b ON b.ID = a.ID_OBAT
			WHERE a.ID_RESEP = '$id_resep'
		";
		return $this->db->query($sql)->result();
	}

	function simpan_closing($id_rajal, $id_pegawai, $shift, $tanggal, $pukul){
		$data = array(
			'ID_KASIR_RAJAL' => $id_rajal,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift
		);

	  $this->db->insert('ap_tutup_kasir_rajal', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);
		$this->db->where('ID', $id_rajal);
    $this->db->update('rk_pembayaran_kasir', $data_update);
	}

	function data_pembayaran(){
		$query = $this->db->query("
			SELECT
				TUTUP.ID AS ID_CLOSING,
				TUTUP.TANGGAL AS TANGGAL_CLOSING,
				TUTUP.SHIFT,
				BAYAR.INVOICE,
				RAJAL.ID AS ID_RAJAL,
				BAYAR.TOTAL,
				PEGAWAI.NAMA AS NAMA_PEGAWAI,
				RESEP.KODE_RESEP
			FROM
			ap_tutup_kasir_rajal AS TUTUP
			LEFT JOIN rk_pembayaran_kasir AS BAYAR ON TUTUP.ID_KASIR_RAJAL=BAYAR.ID
			LEFT JOIN rk_pasien AS PASIEN ON BAYAR.ID_PASIEN=PASIEN.ID
			LEFT JOIN admum_rawat_jalan AS RAJAL ON BAYAR.ID_PELAYANAN=RAJAL.ID
			LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN = RAJAL.ID
			LEFT JOIN kepeg_pegawai AS PEGAWAI ON TUTUP.ID_PEGAWAI=PEGAWAI.ID
		");
		return $query->result_array();
	}

	function nota_poli($id_rj){
		$sql = $this->db->query("
			SELECT
				TUTUP.ID AS ID_CLOSING,
				TUTUP.TANGGAL AS TANGGAL_CLOSING,
				TUTUP.SHIFT,
				BAYAR.INVOICE,
				RAJAL.ID AS ID_RAJAL,
				BAYAR.TOTAL,
				PEGAWAI.NAMA AS NAMA_PEGAWAI,
				RESEP.KODE_RESEP,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.ALAMAT AS ALAMAT_PASIEN,
				POLI.NAMA AS NAMA_POLI,
				DOKTER.NAMA AS NAMA_DOKTER
			FROM
			ap_tutup_kasir_rajal AS TUTUP
			LEFT JOIN rk_pembayaran_kasir AS BAYAR ON TUTUP.ID_KASIR_RAJAL=BAYAR.ID
			LEFT JOIN rk_pasien AS PASIEN ON BAYAR.ID_PASIEN=PASIEN.ID
			LEFT JOIN admum_rawat_jalan AS RAJAL ON BAYAR.ID_PELAYANAN=RAJAL.ID
			LEFT JOIN admum_poli AS POLI ON RAJAL.ID_POLI=POLI.ID
			LEFT JOIN kepeg_pegawai AS DOKTER ON POLI.ID_PEG_DOKTER=DOKTER.ID
			LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN = RAJAL.ID
			LEFT JOIN kepeg_pegawai AS PEGAWAI ON TUTUP.ID_PEGAWAI=PEGAWAI.ID
			WHERE RAJAL.ID = '$id_rj'
		");

		return $sql->row_array();
	}
}
