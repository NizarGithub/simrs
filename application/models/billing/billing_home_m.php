<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billing_home_m extends CI_Model
{
	function __construct() {
		  parent::__construct();
		  $this->load->database();
	} 

	function getNomorBilling(){
		$sql = "
		SELECT * FROM bill_nomor_trx
		";

		return $this->db->query($sql)->row();
	} 

	function getBillingPasien(){
		$sql = "
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_jalan b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RJ' AND (b.STATUS_PINDAH IS NULL OR b.STATUS_PINDAH = '')

		UNION ALL

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_inap b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RI'

		UNION ALL 

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_igd b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'IGD'
		";

		return $this->db->query($sql)->result();
	}

	function getBillingPasien_2($sts){
		$sql = "
		SELECT a.* FROM (
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_jalan b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RJ' AND (b.STATUS_PINDAH IS NULL OR b.STATUS_PINDAH = '')

		UNION ALL

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_inap b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'RI'

		UNION ALL 

		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_igd b ON a.ID = b.ID_PASIEN 
		WHERE a.STATUS = 'IGD'
		) a 
		WHERE a.STATUS = '$sts'
		ORDER BY a.ID
		";

		return $this->db->query($sql)->result();
	}

	function getBillingPasien_filter($filter_by){
		$where = "1=1";

		if($filter_by != "semua"){
			if($filter_by == "belum_lunas"){
				$where = $where." AND a.STS_BAYAR = 0";
			} else if($filter_by == "lunas"){
				$where = $where." AND a.STS_BAYAR = 1";
			}
		}

		$sql = "
		SELECT a.* FROM (
			SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
			JOIN admum_rawat_jalan b ON a.ID = b.ID_PASIEN 
			WHERE a.STATUS = 'RJ' AND (b.STATUS_PINDAH IS NULL OR b.STATUS_PINDAH = '')

			UNION ALL

			SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
			JOIN admum_rawat_inap b ON a.ID = b.ID_PASIEN 
			WHERE a.STATUS = 'RI'

			UNION ALL 

			SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
			JOIN admum_igd b ON a.ID = b.ID_PASIEN 
			WHERE a.STATUS = 'IGD'
		) a
		WHERE $where
		";

		return $this->db->query($sql)->result();
	}

	function getDetailPasien_RJ($id_pasien){
		$sql = "
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, a.TANGGAL_LAHIR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_jalan b ON a.ID = b.ID_PASIEN
		WHERE a.ID = $id_pasien 
		";

		return $this->db->query($sql)->row();
	}

	function getDetailPasien_RI($id_pasien){
		$sql = "
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, a.TANGGAL_LAHIR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_rawat_inap b ON a.ID = b.ID_PASIEN
		WHERE a.ID = $id_pasien 
		";

		return $this->db->query($sql)->row();
	}

	function getDetailPasien_IGD($id_pasien){
		$sql = "
		SELECT a.ID, a.STS_BAYAR, a.NAMA, a.STATUS, a.KODE_PASIEN, a.ALAMAT, a.TANGGAL_DAFTAR, a.TANGGAL_LAHIR, b.ASAL_RUJUKAN, b.SISTEM_BAYAR FROM rk_pasien a 
		JOIN admum_igd b ON a.ID = b.ID_PASIEN
		WHERE a.ID = $id_pasien 
		"; 

		return $this->db->query($sql)->row();
	}
	

	function getDetailLayananRJ($id_pasien){
		$sql = "
		SELECT a.KET, a.JUMLAH, a.HARGA, a.TARIF, a.ORD FROM(
			SELECT b.JENIS_LABORAT AS KET, 1 AS JUMLAH, 0 AS HARGA, SUM(a.TOTAL_TARIF) AS TARIF, 'LABORAT' AS ORD  FROM rk_laborat_rj a
			LEFT JOIN admum_setup_jenis_laborat b ON a.JENIS_LABORAT = b.ID
			WHERE a.ID_PASIEN = $id_pasien
			GROUP BY b.JENIS_LABORAT

			UNION ALL 

			SELECT c.NAMA_TINDAKAN AS KET, b.JUMLAH, 0 AS HARGA, b.SUBTOTAL AS TARIF, 'TINDAKAN' AS ORD  FROM rk_tindakan_rj a 
			JOIN rk_tindakan_rj_detail b ON a.ID = b.ID_TINDAKAN_RJ
			JOIN admum_setup_tindakan c ON b.TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT c.NAMA_OBAT AS KET, b.JUMLAH_BELI AS JUMLAH, b.HARGA, b.SUBTOTAL AS TARIF, 'OBAT' AS ORD  FROM rk_resep_rj a 
			JOIN rk_resep_detail_rj b ON b.ID_RESEP = a.ID
			JOIN admum_setup_nama_obat c ON b.ID_OBAT = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.DIAGNOSA AS KET, 0 AS JUMLAH, 0 AS HARGA, 0 AS TARIF, 'DIAGNOSA' AS ORD  FROM rk_diagnosa_rj a 
			WHERE a.ID_PASIEN = $id_pasien
		) a
		ORDER BY a.ORD ASC
		";

		return $this->db->query($sql)->result();
	}

	function getDetailLayananRI($id_pasien){
		$sql = "
		SELECT a.KET, a.JUMLAH, a.TARIF, a.ORD FROM(
			SELECT c.NAMA_TINDAKAN AS KET, b.JUMLAH, b.SUBTOTAL AS TARIF, 1 AS ORD  FROM rk_ri_tindakan a 
			JOIN rk_ri_tindakan_detail b ON a.ID = b.ID_TINDAKAN
			JOIN admum_setup_tindakan c ON b.ID_SETUP_TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien
		) a
		ORDER BY a.ORD ASC
		";

		return $this->db->query($sql)->result();
	}

	function getDetailLayananIGD($id_pasien){
		$sql = "
		SELECT a.KET, a.JUMLAH, a.HARGA, a.TARIF, a.ORD FROM(
			SELECT b.JENIS_LABORAT AS KET, 1 AS JUMLAH, 0 AS HARGA, SUM(a.TOTAL_TARIF) AS TARIF, 'LABORAT' AS ORD  FROM rk_igd_laborat a
			LEFT JOIN admum_setup_jenis_laborat b ON a.JENIS_LABORAT = b.ID
			WHERE a.ID_PASIEN = $id_pasien
			GROUP BY b.JENIS_LABORAT

			UNION ALL 

			SELECT c.NAMA_TINDAKAN AS KET, b.JUMLAH, 0 AS HARGA, b.SUBTOTAL AS TARIF, 'TINDAKAN' AS ORD  FROM rk_igd_tindakan a 
			JOIN rk_igd_tindakan_detail b ON a.ID = b.ID_TINDAKAN_IGD
			JOIN admum_setup_tindakan c ON b.TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT c.NAMA_OBAT AS KET, b.JUMLAH_BELI AS JUMLAH, b.HARGA, b.SUBTOTAL AS TARIF, 'OBAT' AS ORD  FROM rk_igd_resep a 
			JOIN rk_igd_resep_detail b ON b.ID_RESEP = a.ID
			JOIN admum_setup_nama_obat c ON b.ID_OBAT = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.DIAGNOSA AS KET, 0 AS JUMLAH, 0 AS HARGA, 0 AS TARIF, 'DIAGNOSA' AS ORD  FROM rk_igd_diagnosa a 
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT b.NAMA_RUANG AS KET, 0 AS JUMLAH, a.TARIF AS HARGA, a.TARIF, 'OPERASI' AS ORD  FROM rk_igd_operasi a 
			JOIN admum_setup_ruang_operasi b ON a.ID_RUANG_OPERASI = b.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT b.NAMA_RUANG AS KET, 0 AS JUMLAH, a.TARIF AS HARGA, a.TARIF, 'ICU' AS ORD  FROM rk_igd_icu a 
			JOIN admum_setup_ruang_icu b ON a.ID_RUANG_ICU = b.ID
			WHERE a.ID_PASIEN = $id_pasien
		) a
		";

		return $this->db->query($sql)->result();
	}

	function simpan_pembayaran($id_pasien, $nomor_bill, $sts_layanan, $sistem_bayar, $total_biaya, $bayar, $kembali){
		$tgl = date('d-m-Y');
		$waktu = date('H:i');

		$sql = "
		INSERT INTO bill_pembayaran_pasien
		(ID_PASIEN, NO_BILLING, STS_LAYANAN, SISTEM_BAYAR, TOTAL_BIAYA, BAYAR, KEMBALI, TANGGAL, WAKTU)
		VALUES 
		($id_pasien, '$nomor_bill', '$sts_layanan', '$sistem_bayar', $total_biaya, $bayar, $kembali, '$tgl', '$waktu')
		";

		$this->db->query($sql);
	}

	function roll_no_billing(){
		$sql = "
		UPDATE bill_nomor_trx SET NOMOR = NOMOR + 1 
		";

		$this->db->query($sql);
	}

	function update_sts_bayar_pasien($id_pasien){
		$sql = "
		UPDATE rk_pasien SET STS_BAYAR = 1
		WHERE ID = $id_pasien
		";

		$this->db->query($sql);
	}

	function simpanAntrian($kode_antrian, $jml_antrian, $id_antrian){
		$tgl = date('d-m-Y');
		$sql = "
		INSERT INTO kepeg_antrian
		(ID_KODE, KODE, URUT, TGL)
		VALUES 
		($id_antrian, '$kode_antrian', $jml_antrian, '$tgl')
		";

		$this->db->query($sql);
	}

	//DEVAN QUERY

	// RAWAT INAP

	function dataDetPasien_RI($id_pasien){
		$sql = "
			SELECT
				RI.ID,
				RI.ID_PASIEN,
				PASIEN.KODE_PASIEN,
				PASIEN.NAMA AS NAMA_PASIEN,
				PASIEN.ALAMAT,
				RI.TANGGAL_MASUK,
				STR_TO_DATE(RI.TANGGAL_MASUK,'%d-%m-%Y') AS TANGGAL_MASUK_BALIK,
				RI.ASAL_RUJUKAN,
				RI.NAMA_PENANGGUNGJAWAB,
				RI.SISTEM_BAYAR,
				RI.ID_KAMAR,
				KRI.KODE_KAMAR,
				KRI.NAMA_KAMAR,
				KRI.KELAS,
				KRI.BIAYA,
				RI.ID_BED,
				BED.NOMOR_BED,
				KA.KONDISI_AKHIR,
				KA.DIRAWAT_SELAMA
			FROM admum_rawat_inap RI
			LEFT JOIN rk_pasien PASIEN ON PASIEN.ID = RI.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap KRI ON KRI.ID = RI.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap BED ON BED.ID = RI.ID_BED
			LEFT JOIN rk_ri_kondisi_akhir KA ON KA.ID_PELAYANAN = RI.ID
			WHERE RI.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function dataDetVisite_RI($id_pasien){
		$sql = "
			SELECT 
				RIVST.ID,
				RIVST.TANGGAL,
				RIVST.ID_VISITE,
				VST.KODE,
				VST.NAMA_VISITE,
				VST.TARIF,
				RIVST.ID_DOKTER,
				PEG.NAMA AS NAMA_DOKTER
			FROM rk_ri_visite RIVST
			LEFT JOIN admum_setup_visite VST ON VST.ID = RIVST.ID_VISITE
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = RIVST.ID_DOKTER
			WHERE RIVST.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetGizi_RI($id_pasien){
		$sql = "
			SELECT
				RIGZ.ID,
				RIGZ.ID_PELAYANAN,
				RIGZ.ID_PASIEN,
				RIGZ.TANGGAL,
				RIGZ.ID_GIZI,
				GZ.KODE,
				GZ.NAMA_GIZI,
				GZ.TARIF
			FROM rk_ri_gizi RIGZ
			LEFT JOIN admum_setup_gizi GZ ON GZ.ID = RIGZ.ID_GIZI
			WHERE RIGZ.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetOksigen_RI($id_pasien){
		$sql = "SELECT * FROM rk_ri_oksigen WHERE ID_PASIEN = '$id_pasien'";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetDiagnosa_RI($id_pasien){
		$sql = "
			SELECT
				DG.ID,
				DG.TANGGAL,
				DG.ID_PASIEN,
				DG.DIAGNOSA,
				DG.TINDAKAN,
				DG.ID_KASUS,
				KA.NAMA_KASUS,
				DG.ID_SPESIALISTIK,
				SP.NAMA_SPESIALISTIK
			FROM rk_ri_diagnosa DG
			LEFT JOIN admum_setup_kasus_diagnosa KA ON KA.ID = DG.ID_KASUS
			LEFT JOIN admum_setup_spesialistik SP ON SP.ID = DG.ID_SPESIALISTIK
			WHERE DG.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetResep_RI($id_pasien){
		$sql = "
			SELECT
				DET.ID,
				RSP.ID_PASIEN,
				DET.ID_RESEP,
				DET.ID_OBAT,
				STP.NAMA_OBAT,
				SAT.NAMA_SATUAN,
				DET.HARGA,
				DET.JUMLAH_BELI,
				DET.SUBTOTAL
			FROM rk_ri_resep_detail DET
			LEFT JOIN rk_ri_resep RSP ON RSP.ID = DET.ID_RESEP
			LEFT JOIN apotek_gudang_obat OBAT ON OBAT.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			WHERE RSP.ID_PASIEN = '$id_pasien'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

}

?>