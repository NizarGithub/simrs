<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ap_beli_obat_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
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

	function get_resep($keyword){
		//1 = Rawat Jalan, 2 = IGD
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.KODE_RESEP LIKE '%$keyword%' OR b.NAMA_PASIEN LIKE '%$keyword%')";
		}

		$sql = "
			SELECT
				b.*
			FROM(
				SELECT
					a.*
				FROM(
					SELECT
						RJ.ID,
						RJ.ID_PASIEN,
						PS.NAMA AS NAMA_PASIEN,
						RJ.KODE_RESEP,
						'1' AS DARI
					FROM rk_resep_rj RJ
					LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
					ORDER BY RJ.ID DESC
				) a

				UNION ALL

				SELECT
					a.*
				FROM(
					SELECT
						IGD.ID,
						IGD.ID_PASIEN,
						PS.NAMA AS NAMA_PASIEN,
						IGD.KODE_RESEP,
						'2' AS DARI
					FROM rk_igd_resep IGD
					LEFT JOIN rk_pasien PS ON PS.ID = IGD.ID_PASIEN
					ORDER BY IGD.ID DESC
				) a

				UNION ALL

				SELECT
					a.*
				FROM(
					SELECT
						RI.ID,
						RI.ID_PASIEN,
						PS.NAMA AS NAMA_PASIEN,
						RI.KODE_RESEP,
						'3' AS DARI
					FROM rk_ri_resep RI
					LEFT JOIN rk_pasien PS ON PS.ID = RI.ID_PASIEN
					ORDER BY RI.ID DESC
				) a
			) b
			WHERE $where
		";
		return $this->db->query($sql)->result();
	}

	function get_resep_id($id_resep,$dari){
		$sql = "
			SELECT
				a.*
			FROM(
				SELECT
					DET.ID,
					DET.ID_RESEP,
					DET.ID_OBAT,
					STP.KODE_OBAT,
					STP.NAMA_OBAT,
					DET.HARGA,
					DET.JUMLAH_BELI,
					DET.SUBTOTAL,
					DET.TAKARAN,
					DET.ATURAN_MINUM,
					'1' AS DARI
				FROM rk_resep_detail_rj DET
				LEFT JOIN apotek_gudang_obat OBAT ON OBAT.ID = DET.ID_OBAT
				LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBAT.ID_SETUP_NAMA_OBAT

				UNION ALL

				SELECT
					DET.ID,
					DET.ID_RESEP,
					DET.ID_OBAT,
					STP.KODE_OBAT,
					STP.NAMA_OBAT,
					DET.HARGA,
					DET.JUMLAH_BELI,
					DET.SUBTOTAL,
					DET.TAKARAN,
					DET.ATURAN_MINUM,
					'2' AS DARI
				FROM rk_igd_resep_detail DET
				LEFT JOIN apotek_gudang_obat OBAT ON OBAT.ID = DET.ID_OBAT
				LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBAT.ID_SETUP_NAMA_OBAT

				UNION ALL

				SELECT
					DET.ID,
					DET.ID_RESEP,
					DET.ID_OBAT,
					STP.KODE_OBAT,
					STP.NAMA_OBAT,
					DET.HARGA,
					DET.JUMLAH_BELI,
					DET.SUBTOTAL,
					DET.TAKARAN,
					DET.ATURAN_MINUM,
					'3' AS DARI
				FROM rk_ri_resep_detail DET
				LEFT JOIN apotek_gudang_obat OBAT ON OBAT.ID = DET.ID_OBAT
				LEFT JOIN admum_setup_nama_obat STP ON STP.ID = OBAT.ID_SETUP_NAMA_OBAT
			) a
			WHERE a.ID_RESEP = '$id_resep'
			AND a.DARI = '$dari'
		";
		return $this->db->query($sql)->result();
	}

}