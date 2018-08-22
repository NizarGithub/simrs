<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_kasir_rajal_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_pasien($tanggal,$keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (a.NAMA LIKE '%$keyword%' OR a.NAMA_ORTU LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.*,
				(a.TOT_POLI+a.TOT_TINDAKAN+a.TOT_RESEP+a.TOT_LAB) AS TOTAL
			FROM(
				SELECT
					RJ.ID,
					RJ.ID_PASIEN,
					PS.NAMA,
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
			) a
			WHERE $where
			AND a.TANGGAL = '$tanggal'
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_poli_by_rj($id){
		$sql = "
			SELECT
				RJ.*,
				PS.NAMA AS NAMA_PASIEN,
				P.ID AS ID_POLI,
				P.NAMA AS NAMA_POLI,
				P.BIAYA,
				PEG.NAMA AS NAMA_DOKTER
			FROM admum_rawat_jalan RJ
			LEFT JOIN admum_poli P ON P.ID = RJ.ID_POLI
			LEFT JOIN kepeg_pegawai PEG ON PEG.ID = P.ID_PEG_DOKTER
			LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
			WHERE RJ.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function get_tindakan_det($id_tindakan){
		$sql = "
			SELECT
				DET.*,
				TD.NAMA_TINDAKAN,
				TD.TARIF
			FROM rk_tindakan_rj_detail DET
			LEFT JOIN admum_setup_tindakan TD ON TD.ID = DET.TINDAKAN
			WHERE DET.ID_TINDAKAN_RJ = '$id_tindakan'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_resep2($id_resep){
		$sql = "
			SELECT
				DET.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.NAMA_OBAT,
				DET.TAKARAN,
				DET.ATURAN_MINUM,
				DET.HARGA,
				DET.JUMLAH_BELI,
				DET.SUBTOTAL
			FROM rk_resep_detail_rj DET
			LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = GD.ID_SETUP_NAMA_OBAT
			WHERE DET.ID_RESEP = '$id_resep'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_obat($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (NM_OBT.NAMA_OBAT LIKE '%$keyword%' OR NM_OBT.BARCODE LIKE '%$keyword%' OR NM_OBT.KODE_OBAT LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				OBAT.ID,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				SUP.MERK,
				JENIS.NAMA_JENIS,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK,
				OBAT.WAKTU_MASUK,
				OBAT.AKTIF,
				OBAT.URUT_BARANG
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			WHERE $where
			AND OBAT.AKTIF = '1'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_obat_id($id){
		$sql = "
			SELECT
				OBAT.ID,
				OBAT.ID_SETUP_NAMA_OBAT,
				NM_OBT.KODE_OBAT,
				NM_OBT.BARCODE,
				NM_OBT.NAMA_OBAT,
				NM_OBT.ID_MERK,
				SUP.MERK,
				OBAT.ID_JENIS_OBAT,
				JENIS.NAMA_JENIS,
				OBAT.ID_SATUAN_OBAT,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_BELI,
				OBAT.HARGA_JUAL,
				OBAT.KADALUARSA,
				OBAT.TANGGAL_MASUK
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_supplier SUP ON SUP.ID = NM_OBT.ID_MERK
			LEFT JOIN obat_jenis JENIS ON JENIS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			WHERE OBAT.ID = '$id'
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

	function get_jenis_obat(){
		$sql = "SELECT * FROM obat_jenis ORDER BY ID ASC LIMIT 5";

		return $this->db->query($sql)->result();
	}

	function get_all_obat(){
		$sql = "
			SELECT
				OBAT.ID,
				OBAT.ID_SETUP_NAMA_OBAT,
				NM_OB.KODE_OBAT,
				NM_OB.BARCODE,
				NM_OB.NAMA_OBAT,
				OBAT.ID_JENIS_OBAT,
				JNS.NAMA_JENIS,
				OBAT.ID_SATUAN_OBAT,
				SAT.KODE_SATUAN,
				SAT.NAMA_SATUAN,
				OBAT.JUMLAH,
				OBAT.ISI,
				OBAT.TOTAL,
				OBAT.SATUAN_ISI,
				OBAT.JUMLAH_BUTIR,
				OBAT.SATUAN_BUTIR,
				OBAT.HARGA_JUAL,
				OBAT.GAMBAR
			FROM apotek_gudang_obat OBAT
			LEFT JOIN admum_setup_nama_obat NM_OB ON NM_OB.ID = OBAT.ID_SETUP_NAMA_OBAT
			LEFT JOIN obat_jenis JNS ON JNS.ID = OBAT.ID_JENIS_OBAT
			LEFT JOIN obat_satuan SAT ON SAT.ID = OBAT.ID_SATUAN_OBAT
			ORDER BY OBAT.ID DESC
		";
		return $this->db->query($sql)->result();
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

}
