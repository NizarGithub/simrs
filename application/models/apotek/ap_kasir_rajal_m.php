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
			$where = $where." AND (a.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "SELECT
							a.*,
							(a.TOT_POLI+a.TOT_TDK+a.TOT_RESEP+a.TOT_TARIF+a.BIAYA_REG+a.BIAYA_ADMIN) AS TOTAL
							FROM(
							SELECT
							a.ID,
							a.TANGGAL,
							a.ID_PASIEN,
							b.NAMA,
							a.STS_BAYAR,
							c.ID AS ID_KASIR_RAJAL,
							IFNULL(d.BIAYA,0) AS TOT_POLI,
							IFNULL(e.TOTAL,0) AS TOT_TDK,
							IFNULL(f.TOTAL,0) AS TOT_RESEP,
							IFNULL(g.TOTAL_TARIF,0) AS TOT_TARIF,
							IFNULL(a.BIAYA_REG,0) AS BIAYA_REG,
							IFNULL(a.BIAYA_ADMIN,0) AS BIAYA_ADMIN,
							'Rawat Jalan' AS STATUS,
							'1' AS TIPE,
							a.STS_CLOSING AS STATUS_CLOSING,
							'' AS ID_HV,
							'' AS ID_PAKET,
							'' AS ID_RESEP_RANAP,
							'' AS ID_ENTRY_RESEP,
							c.INVOICE
						FROM admum_rawat_jalan a
						LEFT JOIN rk_pasien b ON b.ID = a.ID_PASIEN
						LEFT JOIN rk_pembayaran_kasir c ON c.ID_PELAYANAN = a.ID
						LEFT JOIN admum_poli d ON d.ID = a.ID_POLI
						LEFT JOIN rk_tindakan_rj e ON e.ID_PELAYANAN = a.ID
						LEFT JOIN rk_resep_rj f ON f.ID_PELAYANAN = a.ID
						LEFT JOIN rk_laborat_rj g ON g.ID_PELAYANAN = a.ID

						UNION ALL

						SELECT
							a.ID,
							a.TANGGAL,
							'' AS ID_PASIEN,
							a.INVOICE AS NAMA,
							a.STS_BAYAR,
							'' AS ID_KASIR_RAJAL,
							'0' AS TOT_POLI,
							'0' AS TOT_TDK,
							'0' AS TOT_RESEP,
							a.TOTAL AS TOT_TARIF,
							'0' AS BIAYA_REG,
							'0' AS BIAYA_ADMIN,
							a.STATUS,
							'2' AS TIPE,
							a.STATUS_CLOSING,
							a.ID AS ID_HV,
							'' AS ID_PAKET,
							'' AS ID_RESEP_RANAP,
							'' AS ID_ENTRY_RESEP,
							a.INVOICE
						FROM ap_penjualan_obat_hv a

						UNION ALL

						-- SELECT
						-- 	a.ID,
						-- 	a.TANGGAL,
						-- 	'' AS ID_PASIEN,
						-- 	a.INVOICE AS NAMA,
						-- 	a.STS_BAYAR,
						-- 	'' AS ID_KASIR_RAJAL,
						-- 	'0' AS TOT_POLI,
						-- 	'0' AS TOT_TDK,
						-- 	'0' AS TOT_RESEP,
						-- 	a.TOTAL AS TOT_TARIF,
						-- 	'0' AS BIAYA_REG,
						-- 	'0' AS BIAYA_ADMIN,
						-- 	a.STATUS,
						-- 	'3' AS TIPE,
						-- 	a.STATUS_CLOSING,
						-- 	'' AS ID_HV,
						-- 	a.ID AS ID_PAKET,
						-- 	'' AS ID_RESEP_RANAP,
						-- 	'' AS ID_ENTRY_RESEP,
						-- 	a.INVOICE
						-- FROM ap_penjualan_obat_paket a
						--
						-- UNION ALL

						SELECT
							a.ID,
							a.TANGGAL,
							a.ID_PASIEN,
							b.NAMA,
							a.STS_BAYAR,
							'' AS ID_KASIR_RAJAL,
							'0' AS TOT_POLI,
							'0' AS TOT_TDK,
							'0' AS TOT_RESEP,
							a.TOTAL AS TOT_TARIF,
							'0' AS BIAYA_REG,
							'0' AS BIAYA_ADMIN,
							'Rawat Inap' AS STATUS,
							'4' AS TIPE,
							a.STATUS_CLOSING,
							'' AS ID_HV,
							'' AS ID_PAKET,
							a.ID AS ID_RESEP_RANAP,
							'' AS ID_ENTRY_RESEP,
							a.KODE_RESEP AS INVOICE
						FROM rk_ri_resep a
						LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID

						UNION ALL

						SELECT
							a.ID,
							a.TANGGAL,
							a.ID_PASIEN,
							b.NAMA,
							a.STS_BAYAR,
							'' AS ID_KASIR_RAJAL,
							'0' AS TOT_POLI,
							'0' AS TOT_TDK,
							'0' AS TOT_RESEP,
							a.TOTAL AS TOT_TARIF,
							'0' AS BIAYA_REG,
							'0' AS BIAYA_ADMIN,
							a.STATUS,
							'5' AS TIPE,
							a.STATUS_CLOSING,
							'' AS ID_HV,
							'' AS ID_PAKET,
							'' AS ID_RESEP_RANAP,
							a.ID AS ID_ENTRY_RESEP,
							a.INVOICE
						FROM ap_iter a
						LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID
						) a
						WHERE 1=1
						AND a.TANGGAL = '$tanggal'
						AND a.STATUS_CLOSING = '0'
						ORDER BY a.ID DESC
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_poli_by_rj($id){
		$sql = "SELECT
							RJ.*,
							PS.NAMA AS NAMA_PASIEN,
							PS.JENIS_PASIEN,
							P.ID AS ID_POLI,
							P.NAMA AS NAMA_POLI,
							P.BIAYA,
							PEG.NAMA AS NAMA_DOKTER,
							RES.ID AS ID_RESEP,
							RES.STS_ITER
						FROM admum_rawat_jalan RJ
						LEFT JOIN admum_poli P ON P.ID = RJ.ID_POLI
						LEFT JOIN kepeg_pegawai PEG ON PEG.ID = P.ID_PEG_DOKTER
						LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
						LEFT JOIN rk_resep_rj RES ON RES.ID_PELAYANAN = RJ.ID
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
		$sql = "SELECT
							DET.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.NAMA_OBAT,
							DET.TAKARAN,
							DET.ATURAN_MINUM,
							DET.HARGA,
							DET.JUMLAH_BELI,
							DET.SUBTOTAL
							-- IFNULL(DET.SUBTOTAL,0) AS SUBTOTAL
						FROM rk_resep_detail_rj DET
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = DET.ID_OBAT
						WHERE DET.ID_RESEP = '$id_resep'
					";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_laborat($id_laborat){
		$sql = "SELECT
							DET.ID,
							SP.NAMA_PEMERIKSAAN,
							SP.TARIF,
							LB.ID AS ID_LABORAT,
							LB.TOTAL_TARIF
						FROM rk_laborat_rj_detail DET
						LEFT JOIN admum_setup_pemeriksaan SP ON SP.ID = DET.PEMERIKSAAN
						LEFT JOIN rk_laborat_rj LB ON LB.ID = DET.ID_PEMERIKSAAN_RJ
						WHERE DET.ID_PEMERIKSAAN_RJ = '$id_laborat'
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

	function simpan_pembayaran($invoice,
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
															$status_iter){
		$sql = "INSERT INTO rk_pembayaran_kasir(
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
							TIPE,
							BAYAR,
							KARTU_PROVIDER,
							NO_KARTU,
							TAMBAHAN
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
							'RJ',
							'$bayar',
							'$kartu_provider',
							'$no_kartu',
							'$tambahan'
						)
					";
		$this->db->query($sql);

		$data_update = array(
			'TOTAL' => $biaya_resep
		);

		$this->db->where('ID_PELAYANAN', $id_rj);
    $this->db->update('rk_resep_rj', $data_update);

		if ($status_iter == '1') {
			$this->db->query("UPDATE rk_resep_rj SET ITER_KURANG = ITER_KURANG - 1 WHERE ID = '$id_resep'");
		}	else {

		}
	}

	function simpan_pembayaran_obat($invoice,
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
																){
    if ($tipe == '2') {
    	$data= array(
				'ID_PENJUALAN_HV' => $id_penjualan,
				'ID_PEGAWAI' => $id_pegawai,
				'SHIFT' => $shift,
				'TANGGAL' => $tanggal,
				'WAKTU' => $waktu,
				'TOTAL' => $total,
				'JENIS_PEMBAYARAN' => $jenis_pembayaran,
				'BAYAR' => $bayar,
				'KARTU_PROVIDER' => $kartu_provider,
				'NO_KARTU' => $no_kartu,
				'TAMBAHAN' => $tambahan,
				'KEMBALI' => $kembali
			);

			$this->db->insert('ap_pembayaran_hv', $data);

			$query = $this->db->query("SELECT * FROM ap_penjualan_obat_hv_detail WHERE ID_PENJUALAN_OBAT_HV = '$id_penjualan'")->result_array();
			foreach ($query as $q) {
				$id_gudang = $q['ID_GUDANG_OBAT'];
				$jumlah_beli = $q['JUMLAH_BELI'];

				$this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$id_gudang'");
			}
    }elseif ($tipe == '3') {
			$data= array(
				'ID_PENJUALAN_PAKET' => $id_penjualan,
				'ID_PEGAWAI' => $id_pegawai,
				'SHIFT' => $shift,
				'TANGGAL' => $tanggal,
				'WAKTU' => $waktu,
				'TOTAL' => $total,
				'JENIS_PEMBAYARAN' => $jenis_pembayaran,
				'BAYAR' => $bayar,
				'KARTU_PROVIDER' => $kartu_provider,
				'NO_KARTU' => $no_kartu,
				'TAMBAHAN' => $tambahan,
				'KEMBALI' => $kembali
			);

			$this->db->insert('ap_pembayaran_paket', $data);

			$query = $this->db->query("SELECT * FROM ap_penjualan_obat_paket_detail WHERE ID_PENJUALAN_OBAT_PAKET = '$id_penjualan'")->result_array();
			foreach ($query as $q) {
				$id_gudang = $q['ID_GUDANG_OBAT'];
				$jumlah_beli = $q['JUMLAH_BELI'];

				$this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$id_gudang'");
			}
    }elseif ($tipe == '4') {
			$data= array(
				'ID_RESEP_RANAP' => $id_penjualan,
				'ID_PEGAWAI' => $id_pegawai,
				'SHIFT' => $shift,
				'TANGGAL' => $tanggal,
				'WAKTU' => $waktu,
				'TOTAL' => $total,
				'JENIS_PEMBAYARAN' => $jenis_pembayaran,
				'BAYAR' => $bayar,
				'KARTU_PROVIDER' => $kartu_provider,
				'NO_KARTU' => $no_kartu,
				'TAMBAHAN' => $tambahan,
				'KEMBALI' => $kembali
			);

			$this->db->insert('rk_pembayaran_resep_ranap', $data);

			$query = $this->db->query("SELECT * FROM rk_ri_resep_detail WHERE ID_RESEP = '$id_penjualan'")->result_array();
			foreach ($query as $q) {
				$id_gudang = $q['ID_OBAT'];
				$jumlah_beli = $q['JUMLAH_BELI'];

				$this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$id_gudang'");
			}
    }elseif ($tipe == '5') {
			$data= array(
				'ID_ITER' => $id_penjualan,
				'ID_PEGAWAI' => $id_pegawai,
				'SHIFT' => $shift,
				'TANGGAL' => $tanggal,
				'WAKTU' => $waktu,
				'TOTAL' => $total,
				'JENIS_PEMBAYARAN' => $jenis_pembayaran,
				'BAYAR' => $bayar,
				'KARTU_PROVIDER' => $kartu_provider,
				'NO_KARTU' => $no_kartu,
				'TAMBAHAN' => $tambahan,
				'KEMBALI' => $kembali,
				'ID_RESEP' => $id_resep,
				'ID_PASIEN' => $id_pasien,
				'ID_DOKTER' => $id_dokter
			);

			$this->db->insert('ap_pembayaran_iter', $data);

			$query = $this->db->query("SELECT * FROM ap_penjualan_obat_detail WHERE ID_PENJUALAN_OBAT = '$id_penjualan'")->result_array();
			foreach ($query as $q) {
				$id_gudang = $q['ID_GUDANG_OBAT'];
				$jumlah_beli = $q['JUMLAH_BELI'];

			  $this->db->query("UPDATE apotek_gudang_obat SET STOK = STOK - $jumlah_beli WHERE ID = '$id_gudang'");
    }

			$this->db->query("UPDATE rk_resep_rj SET ITER_KURANG = ITER_KURANG - 1 WHERE ID = '$id_resep'");
	}
}

	function struk_resep($id_rj){
		$sql = $this->db->query("SELECT
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

	function struk_copy_resep($id_rj){
		$sql = $this->db->query("SELECT
															RJ.ID,
															RJ.ID_PASIEN,
															PS.NAMA,
															PS.UMUR,
															PS.ALAMAT AS ALAMAT_PASIEN,
															PS.KODE_PASIEN,
															PS.TELEPON AS TELEPON_PASIEN,
															RJ.TANGGAL,
															RJ.ID_POLI,
															PEG.NAMA AS NAMA_DOKTER,
															RJ.STS_BAYAR,
															RS.ID AS ID_RESEP,
															RS.ALERGI_OBAT,
															RS.KODE_RESEP,
															PAG.NAMA AS NAMA_PEGAWAI,
															RS.ITER
														FROM admum_rawat_jalan RJ
														LEFT JOIN rk_pasien PS ON PS.ID = RJ.ID_PASIEN
														LEFT JOIN (SELECT * FROM admum_poli WHERE AKTIF = '1') PL ON PL.ID = RJ.ID_POLI
														LEFT JOIN kepeg_pegawai PEG ON PEG.ID = RJ.ID_DOKTER
														LEFT JOIN rk_pembayaran_kasir KAS ON KAS.ID_PELAYANAN = RJ.ID
														LEFT JOIN kepeg_pegawai PAG ON PAG.ID = KAS.ID_PEGAWAI
														LEFT JOIN rk_tindakan_rj TD ON TD.ID_PELAYANAN = RJ.ID
														LEFT JOIN rk_resep_rj RS ON RS.ID_PELAYANAN = RJ.ID
														LEFT JOIN rk_laborat_rj LAB ON LAB.ID_PELAYANAN = RJ.ID
													  WHERE RJ.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function struk_resep_ranap($id_rj){
		$sql = $this->db->query("SELECT
														*,
														a.ID AS ID_RESEP,
														b.NAMA AS NAMA_PASIEN,
														b.TELEPON AS TELEPON_PASIEN,
														b.ALAMAT AS ALAMAT_PASIEN,
														b.UMUR AS UMUR_PASIEN,
														d.NAMA AS NAMA_PEGAWAI
														FROM
														rk_ri_resep a
														LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID
														LEFT JOIN admum_rawat_inap c ON a.ID_PELAYANAN = c.ID
														LEFT JOIN kepeg_pegawai d ON c.ID_DOKTER = d.ID
														WHERE a.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function detail_resep($id_resep){
		$sql = "SELECT
							DET.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.NAMA_OBAT,
							DET.TAKARAN,
							DET.ATURAN_MINUM,
							DET.HARGA,
							DET.JUMLAH_BELI,
							DET.SUBTOTAL
						FROM rk_resep_detail_rj DET
						-- LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = DET.ID_OBAT
						WHERE DET.ID_RESEP = '$id_resep'
					";
		return $this->db->query($sql)->result();
	}

	function detail_resep_ranap($id_resep){
		$sql = "SELECT
							DET.ID,
							NM_OBT.KODE_OBAT,
							NM_OBT.NAMA_OBAT,
							DET.TAKARAN,
							DET.ATURAN_MINUM,
							DET.HARGA,
							DET.JUMLAH_BELI,
							DET.SUBTOTAL
						FROM rk_ri_resep_detail DET
						-- LEFT JOIN apotek_gudang_obat GD ON GD.ID = DET.ID_OBAT
						LEFT JOIN admum_setup_nama_obat NM_OBT ON NM_OBT.ID = DET.ID_OBAT
						WHERE DET.ID_RESEP = '$id_resep'
					";
		return $this->db->query($sql)->result();
	}

	function simpan_closing_rajal($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice){
		$data = array(
			'ID_TUTUP' => $id_tutup,
			'ID_KASIR_RAJAL' => $id_semua,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift,
			'TOTAL' => $total,
			'INVOICE' => $invoice,
			'STATUS' => 'Kasir Rajal'
		);

	  $this->db->insert('ap_tutup_kasir_rajal_detail', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);
		$this->db->where('ID', $id_semua);
    $this->db->update('rk_pembayaran_kasir', $data_update);

		$this->db->select('*');
		$this->db->from('rk_pembayaran_kasir');
		$this->db->where('ID', $id_semua);
		$row_rajal = $this->db->get()->row_array();

		$id_pelayanan = $row_rajal['ID_PELAYANAN'];

		$data_update_rajal = array(
			'STS_CLOSING' => 1
		);
		$this->db->where('ID', $id_pelayanan);
    $this->db->update('admum_rawat_jalan', $data_update_rajal);
	}

	function simpan_closing_hv($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice){
		$data = array(
			'ID_TUTUP' => $id_tutup,
			'ID_HV' => $id_semua,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift,
			'TOTAL' => $total,
			'INVOICE' => $invoice,
			'STATUS' => 'Penjualan HV / OTC'
		);

	  $this->db->insert('ap_tutup_kasir_rajal_detail', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);
		$this->db->where('ID', $id_semua);
    $this->db->update('ap_penjualan_obat_hv', $data_update);
	}

	// function simpan_closing_paket($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice){
	// 	$data = array(
	// 		'ID_TUTUP' => $id_tutup,
	// 		'ID_PAKET' => $id_semua,
	// 		'TANGGAL' => $tanggal,
	// 		'WAKTU' => $pukul,
	// 		'ID_PEGAWAI' => $id_pegawai,
	// 		'SHIFT' => $shift,
	// 		'TOTAL' => $total,
	// 		'INVOICE' => $invoice,
	// 		'STATUS' => 'Penjualan Paket'
	// 	);
	//
	//   $this->db->insert('ap_tutup_kasir_rajal_detail', $data);
	//
	// 	$data_update = array(
	// 		'STATUS_CLOSING' => 1
	// 	);
	// 	$this->db->where('ID', $id_semua);
  //   $this->db->update('ap_penjualan_obat_paket', $data_update);
	// }

	function simpan_closing_ranap($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice){
		$data = array(
			'ID_TUTUP' => $id_tutup,
			'ID_RANAP' => $id_semua,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift,
			'TOTAL' => $total,
			'INVOICE' => $invoice,
			'STATUS' => 'Kasir Ranap'
		);

	  $this->db->insert('ap_tutup_kasir_rajal_detail', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);
		$this->db->where('ID', $id_semua);
    $this->db->update('rk_ri_resep', $data_update);
	}

	function simpan_closing_entry($id_semua, $id_pegawai, $shift, $tanggal, $pukul, $total, $id_tutup, $invoice){
		$data = array(
			'ID_TUTUP' => $id_tutup,
			'ID_ITER' => $id_semua,
			'TANGGAL' => $tanggal,
			'WAKTU' => $pukul,
			'ID_PEGAWAI' => $id_pegawai,
			'SHIFT' => $shift,
			'TOTAL' => $total,
			'INVOICE' => $invoice,
			'STATUS' => 'Entry Resep'
		);

	  $this->db->insert('ap_tutup_kasir_rajal_detail', $data);

		$data_update = array(
			'STATUS_CLOSING' => 1
		);
		$this->db->where('ID', $id_semua);
    $this->db->update('ap_iter', $data_update);
	}

	function data_pembayaran($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.NAMA LIKE '%$keyword%' OR a.TANGGAL LIKE '%$keyword%' OR a.STATUS LIKE '%$keyword%' OR a.INVOICE LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$query = $this->db->query("SELECT
															 a.*,
															 b.NAMA AS NAMA_PEGAWAI
															 FROM
															 ap_tutup_kasir_rajal_detail a
															 LEFT JOIN kepeg_pegawai AS b ON a.ID_PEGAWAI=b.ID
															 WHERE $where
		");
		return $query->result_array();
	}

	function data_rekap_pendapatan(){
		$query = $this->db->query("SELECT
																*
																FROM
																(SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																PEM.INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Rajal' AS STATUS,
																'1' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																RAJAL.ID AS ID_SEMUA
																FROM
																rk_pembayaran_kasir AS PEM
																LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
																LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																c.STATUS,
																'2' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_PENJUALAN_HV AS ID_SEMUA
																FROM
																ap_pembayaran_hv a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_penjualan_obat_hv c ON a.ID_PENJUALAN_HV = c.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																'Iter Resep' AS STATUS,
																'3' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_ITER AS ID_SEMUA
																FROM
																ap_pembayaran_iter a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_iter c ON a.ID_ITER = c.ID

																UNION ALL

																SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																RESEP.KODE_RESEP AS INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Ranap' AS STATUS,
																'4' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																PEM.ID_RESEP_RANAP AS ID_SEMUA
																FROM
																rk_pembayaran_resep_ranap AS PEM
																LEFT JOIN rk_ri_resep RESEP ON RESEP.ID=PEM.ID_RESEP_RANAP
																LEFT JOIN admum_rawat_inap AS RANAP ON RESEP.ID_PELAYANAN=RANAP.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON RANAP.ID_POLI=POLI.ID)
																a
																ORDER BY
														    STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') DESC
		");
		return $query->result_array();
	}

	function tanggal_filter($tanggal_sekarang, $tanggal_sampai){
		$query = $this->db->query("SELECT
																*
																FROM
																(SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																PEM.INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Rajal' AS STATUS,
																'1' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																RAJAL.ID AS ID_SEMUA
																FROM
																rk_pembayaran_kasir AS PEM
																LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
																LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																c.STATUS,
																'2' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_PENJUALAN_HV AS ID_SEMUA
																FROM
																ap_pembayaran_hv a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_penjualan_obat_hv c ON a.ID_PENJUALAN_HV = c.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																'Iter Resep' AS STATUS,
																'3' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_ITER AS ID_SEMUA
																FROM
																ap_pembayaran_iter a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_iter c ON a.ID_ITER = c.ID

																UNION ALL

																SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																RESEP.KODE_RESEP AS INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Ranap' AS STATUS,
																'4' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																PEM.ID_RESEP_RANAP AS ID_SEMUA
																FROM
																rk_pembayaran_resep_ranap AS PEM
																LEFT JOIN rk_ri_resep RESEP ON RESEP.ID=PEM.ID_RESEP_RANAP
																LEFT JOIN admum_rawat_inap AS RANAP ON RESEP.ID_PELAYANAN=RANAP.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON RANAP.ID_POLI=POLI.ID)
																a
																WHERE STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
																AND STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y')
																ORDER BY
														    STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') DESC
		");
		return $query->result_array();
	}

	// function poli_filter($id_poli){
	// 	$query = $this->db->query("SELECT
	// 															PEM.ID,
	// 															PEM.TANGGAL,
	// 															PEM.SHIFT,
	// 															PEM.INVOICE,
	// 															PEM.TOTAL,
	// 															RAJAL.ID AS ID_RAJAL,
	// 															PEGAWAI.NAMA AS NAMA_PEGAWAI,
	// 															RESEP.KODE_RESEP,
	// 															POLI.NAMA AS NAMA_POLI
	// 															FROM
	// 															rk_pembayaran_kasir AS PEM
	// 															LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
	// 															LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
	// 															LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
	// 															LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
	// 															LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID
	// 															WHERE POLI.ID = '$id_poli'
	// 	");
	// 	return $query->result_array();
	// }

	function poli_filter($id_poli){
		$query = $this->db->query("SELECT
																*
																FROM
																(SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																PEM.INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Rajal' AS STATUS,
																'1' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																RAJAL.ID AS ID_SEMUA
																FROM
																rk_pembayaran_kasir AS PEM
																LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
																LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																c.STATUS,
																'2' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_PENJUALAN_HV AS ID_SEMUA
																FROM
																ap_pembayaran_hv a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_penjualan_obat_hv c ON a.ID_PENJUALAN_HV = c.ID

																UNION ALL

																SELECT
																a.ID,
																a.TANGGAL,
																a.SHIFT,
																c.INVOICE,
																a.TOTAL,
																b.NAMA AS NAMA_PEGAWAI,
																'Iter Resep' AS STATUS,
																'3' AS TIPE,
																'' AS KODE_RESEP,
																'' AS NAMA_POLI,
																a.ID_ITER AS ID_SEMUA
																FROM
																ap_pembayaran_iter a
																LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
																LEFT JOIN ap_iter c ON a.ID_ITER = c.ID

																UNION ALL

																SELECT
																PEM.ID,
																PEM.TANGGAL,
																PEM.SHIFT,
																RESEP.KODE_RESEP AS INVOICE,
																PEM.TOTAL,
																PEGAWAI.NAMA AS NAMA_PEGAWAI,
																'Kasir Ranap' AS STATUS,
																'4' AS TIPE,
																RESEP.KODE_RESEP,
																POLI.NAMA AS NAMA_POLI,
																PEM.ID_RESEP_RANAP AS ID_SEMUA
																FROM
																rk_pembayaran_resep_ranap AS PEM
																LEFT JOIN rk_ri_resep RESEP ON RESEP.ID=PEM.ID_RESEP_RANAP
																LEFT JOIN admum_rawat_inap AS RANAP ON RESEP.ID_PELAYANAN=RANAP.ID
																LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
																LEFT JOIN admum_poli AS POLI ON RANAP.ID_POLI=POLI.ID)
																a
																WHERE a.TIPE = '$id_poli'
																ORDER BY
														    STR_TO_DATE(a.TANGGAL,'%d-%m-%Y') DESC
		");
		return $query->result_array();
	}

	function nota_poli($id_rj){
		$sql = $this->db->query("SELECT
															a.ID_PELAYANAN,
															a.INVOICE,
															a.TANGGAL,
															a.SHIFT,
															a.JENIS_PEMBAYARAN,
															a.TOTAL,
															b.NAMA AS NAMA_PASIEN,
															b.ALAMAT AS ALAMAT_PASIEN,
															d.TAKARAN,
															d.JUMLAH_BELI,
															d.ID_RESEP,
															e.NAMA_OBAT,
															f.NAMA AS NAMA_PEGAWAI,
															g.NAMA AS NAMA_POLI,
															h.NAMA AS NAMA_DOKTER
															FROM
															rk_pembayaran_kasir a
															LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID
															LEFT JOIN rk_resep_rj c ON a.ID_PELAYANAN = c.ID_PELAYANAN
															LEFT JOIN rk_resep_detail_rj d ON c.ID = d.ID_RESEP
															LEFT JOIN admum_setup_nama_obat e ON d.ID_OBAT = e.ID
															LEFT JOIN kepeg_pegawai f ON a.ID_PEGAWAI = f.ID
															LEFT JOIN admum_poli g ON a.ID_POLI = g.ID
															LEFT JOIN kepeg_pegawai h ON h.ID = g.ID_PEG_DOKTER
															WHERE a.ID_PELAYANAN = '$id_rj'
		");

		return $sql->row_array();
	}

	function struk_pembayaran($id_rj){
		$sql = $this->db->query("SELECT
															a.INVOICE,
															a.TANGGAL,
															a.SHIFT,
															a.JENIS_PEMBAYARAN,
															a.TOTAL,
															b.NAMA AS NAMA_PASIEN,
															b.ALAMAT AS ALAMAT_PASIEN,
															d.TAKARAN,
															d.JUMLAH_BELI,
															d.ID_RESEP,
															e.NAMA_OBAT,
															f.NAMA AS NAMA_PEGAWAI,
															g.NAMA AS NAMA_POLI,
															h.NAMA AS NAMA_DOKTER
															FROM
															rk_pembayaran_kasir a
															LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID
															LEFT JOIN rk_resep_rj c ON a.ID_PELAYANAN = c.ID_PELAYANAN
															LEFT JOIN rk_resep_detail_rj d ON c.ID = d.ID_RESEP
															LEFT JOIN admum_setup_nama_obat e ON d.ID_OBAT = e.ID
															LEFT JOIN kepeg_pegawai f ON a.ID_PEGAWAI = f.ID
															LEFT JOIN admum_poli g ON a.ID_POLI = g.ID
															LEFT JOIN kepeg_pegawai h ON h.ID = g.ID_PEG_DOKTER
															WHERE a.ID_PELAYANAN = '$id_rj'
		");

		return $sql->row_array();
	}

	function struk_pembayaran_ranap($id_rj){
		$sql = $this->db->query("SELECT
														a.TOTAL,
														a.SHIFT,
														a.TANGGAL,
														a.ID_RESEP_RANAP AS ID_RESEP,
														a.JENIS_PEMBAYARAN,
														b.KODE_RESEP,
														c.NAMA AS NAMA_PEGAWAI,
														d.NAMA AS NAMA_PASIEN,
														d.ALAMAT AS ALAMAT_PASIEN,
														f.NAMA AS NAMA_DOKTER
														FROM
														rk_pembayaran_resep_ranap a
														LEFT JOIN rk_ri_resep b ON a.ID_RESEP_RANAP = b.ID
														LEFT JOIN kepeg_pegawai c ON a.ID_PEGAWAI = c.ID
														LEFT JOIN rk_pasien d ON b.ID_PASIEN = d.ID
														LEFT JOIN admum_rawat_inap e ON b.ID_PELAYANAN = e.ID
														LEFT JOIN kepeg_pegawai f ON e.ID_DOKTER = f.ID
														WHERE a.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function data_poli(){
		$sql = $this->db->query("SELECT a.ID AS id_poli, a.NAMA AS nama_poli FROM admum_poli a");
		return $sql->result_array();
	}

	function print_pdf(
		$by,
		$tanggal_sekarang,
		$tanggal_sampai,
		$id_poli
	){
		$where = '1=1';
		if ($by == 'semua'){
		}elseif ($by == 'tanggal') {
			$where = $where."  AND STR_TO_DATE(PEM.TANGGAL,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
												AND STR_TO_DATE(PEM.TANGGAL,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'poli') {
			$where = $where." AND POLI.ID = '$id_poli' ";
		}
		$sql = "SELECT
						POLI.NAMA AS NAMA_POLI,
						SUM(PEM.BIAYA_POLI) AS TOTAL_POLI
						FROM
						rk_pembayaran_kasir AS PEM
						LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
						LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
						LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
						LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
						LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID
						WHERE $where
						GROUP BY PEM.ID_POLI
					";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function print_pdf_row(
		$by,
		$tanggal_sekarang,
		$tanggal_sampai,
		$id_poli
	){
		$where = '1=1';
		if ($by == 'semua'){
		}elseif ($by == 'tanggal') {
			$where = $where."  AND STR_TO_DATE(PEM.TANGGAL,'%d-%m-%Y') >= STR_TO_DATE('$tanggal_sekarang','%d-%m-%Y')
												AND STR_TO_DATE(PEM.TANGGAL,'%d-%m-%Y') <= STR_TO_DATE('$tanggal_sampai','%d-%m-%Y') ";
		}elseif ($by == 'poli') {
			$where = $where." AND POLI.ID = '$id_poli' ";
		}
		$sql = "SELECT
						PEM.ID,
						PEM.TANGGAL,
						PEM.SHIFT,
						PEM.INVOICE,
						PEM.TOTAL,
						RAJAL.ID AS ID_RAJAL,
						PEGAWAI.NAMA AS NAMA_PEGAWAI,
						RESEP.KODE_RESEP,
						POLI.NAMA AS NAMA_POLI
						FROM
						rk_pembayaran_kasir AS PEM
						LEFT JOIN rk_pasien AS PASIEN ON PEM.ID_PASIEN=PASIEN.ID
						LEFT JOIN admum_rawat_jalan AS RAJAL ON PEM.ID_PELAYANAN=RAJAL.ID
						LEFT JOIN rk_resep_rj RESEP ON RESEP.ID_PELAYANAN=RAJAL.ID
						LEFT JOIN kepeg_pegawai AS PEGAWAI ON PEM.ID_PEGAWAI=PEGAWAI.ID
						LEFT JOIN admum_poli AS POLI ON PEM.ID_POLI=POLI.ID
						WHERE $where
					";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	function get_hv_by_id($id){
		$query = $this->db->query("SELECT * FROM ap_penjualan_obat_hv WHERE ID = '$id'");
		return $query->row_array();
	}

	function get_paket_by_id($id){
		$query = $this->db->query("SELECT * FROM ap_penjualan_obat_paket WHERE ID = '$id'");
		return $query->row_array();
	}

	function get_entry_resep_by_id($id){
		$query = $this->db->query("SELECT * FROM ap_iter WHERE ID = '$id'");
		return $query->row_array();
	}

	function get_ranap_by_id($id){
		$query = $this->db->query("SELECT * FROM rk_ri_resep a LEFT JOIN rk_pasien b ON a.ID_PASIEN = b.ID WHERE a.ID = '$id'");
		return $query->row_array();
	}

	function struk_pembayaran_hv($id_rj){
		$sql = $this->db->query("SELECT
														a.TOTAL,
														a.SHIFT,
														a.TANGGAL,
														a.ID_PENJUALAN_HV,
														a.JENIS_PEMBAYARAN,
														b.INVOICE,
														c.NAMA AS NAMA_PEGAWAI
														FROM
														ap_pembayaran_hv a
														LEFT JOIN ap_penjualan_obat_hv b ON a.ID_PENJUALAN_HV = b.ID
														LEFT JOIN kepeg_pegawai c ON a.ID_PEGAWAI = c.ID
														WHERE b.ID = '$id_rj'
		");

		return $sql->row_array();
	}

	function struk_copy_resep_entry($id_rj){
			$sql = $this->db->query("SELECT
															b.NAMA AS NAMA_PEGAWAI,
															c.INVOICE,
															-- c.TANGGAL,
															d.NAMA AS NAMA_APOTEKER,
															e.NAMA AS NAMA_PASIEN,
															e.UMUR,
															e.ALAMAT AS ALAMAT_PASIEN,
															f.TANGGAL AS TANGGAL_DIBUAT,
															g.NAMA AS NAMA_DOKTER,
															f.KODE_RESEP,
															f.ITER,
															a.ID_ITER
															FROM
															ap_pembayaran_iter a
															LEFT JOIN kepeg_pegawai b ON a.ID_PEGAWAI = b.ID
															LEFT JOIN ap_iter c ON a.ID_ITER = c.ID
															LEFT JOIN kepeg_pegawai d ON c.ID_PEGAWAI = d.ID
															LEFT JOIN rk_pasien e ON c.ID_PASIEN = e.ID
															LEFT JOIN rk_resep_rj f ON f.ID = c.ID_RESEP
															LEFT JOIN kepeg_pegawai g ON g.ID = c.ID_DOKTER
															WHERE c.ID = '$id_rj'
			");

			return $sql->row_array();
	}

	function rekap_pendapatan_poli($id_tutup){
		$sql = $this->db->query("SELECT
														 *
														 FROM
														 ap_tutup_kasir_rajal_detail
														 WHERE ID_KASIR_RAJAL IS NOT NULL
														 AND ID_TUTUP = '21'")->result_array();
		return 	$sql;
	}

	// function proses_poli($id_kasir_rajal){
	// 	$sql_rajal = $this->db->query("SELECT
	// 																	SUM(b.BIAYA) AS TOTAL_BIAYA_POLI
	// 																	FROM
	// 																	admum_rawat_jalan a
	// 																	LEFT JOIN admum_poli b ON a.ID_POLI = b.ID
	// 																	LEFT JOIN rk_pembayaran_kasir c ON a.ID = c.ID_PELAYANAN
	// 																	WHERE a.ID = '$id_kasir_rajal'
	// 																");
	// 	return $sql_rajal->row_array();
	// }

	function rekap_pendapatan_obat_hv($id_tutup, $shift, $tanggal){
		$sql = $this->db->query("SELECT
															SUM(a.TOTAL) AS TOTAL_HV,
															(SELECT SUM(a.TOTAL) AS TUNAI FROM ap_penjualan_obat_hv a
															LEFT JOIN ap_pembayaran_hv b ON a.ID = b.ID_PENJUALAN_HV
															WHERE b.JENIS_PEMBAYARAN = 'Tunai'
															AND a.SHIFT = '$shift'
															AND a.STS_BAYAR = '1'
															AND a.STATUS_CLOSING = '1'
															AND a.STATUS_CLOSING = '1'
															AND a.TANGGAL = '$tanggal'
															) AS TUNAI,
															(SELECT SUM(a.TOTAL) AS NON_TUNAI FROM ap_penjualan_obat_hv a
															LEFT JOIN ap_pembayaran_hv b ON a.ID = b.ID_PENJUALAN_HV
															WHERE b.JENIS_PEMBAYARAN = 'Non Tunai'
															AND a.SHIFT = '$shift'
															AND a.STS_BAYAR = '1'
															AND a.STATUS_CLOSING = '1'
															AND a.TANGGAL = '$tanggal'
															) AS NON_TUNAI
															FROM
															ap_penjualan_obat_hv a
															LEFT JOIN ap_pembayaran_hv b ON a.ID = b.ID_PENJUALAN_HV
															WHERE a.STATUS_CLOSING = '1'
															AND a.STS_BAYAR = '1'
															AND a.SHIFT = '$shift'
															AND a.TANGGAL = '$tanggal'
														");

		return $sql->row_array();
	}

	function rekap_pendapatan_obat_rj($id_tutup, $shift, $tanggal){
		$sql = $this->db->query("SELECT
															SUM(b.BIAYA_RESEP) AS TOTAL_RJ,
															(SELECT
															SUM(b.BIAYA_RESEP) AS TUNAI
															FROM admum_rawat_jalan a
															LEFT JOIN rk_pembayaran_kasir b ON a.ID = b.ID_PELAYANAN
															WHERE b.JENIS_PEMBAYARAN = 'Tunai'
															AND b.SHIFT = '$shift'
															AND a.STS_BAYAR = '1'
															AND a.STS_CLOSING = '1'
															AND a.TANGGAL = '$tanggal'
															) AS TUNAI,
															(SELECT
															SUM(b.BIAYA_RESEP) AS NON_TUNAI
															FROM admum_rawat_jalan a
															LEFT JOIN rk_pembayaran_kasir b ON a.ID = b.ID_PELAYANAN
															WHERE b.JENIS_PEMBAYARAN = 'Non Tunai'
															AND b.SHIFT = '$shift'
															AND a.STS_BAYAR = '1'
															AND a.STS_CLOSING = '1'
															AND a.TANGGAL = '$tanggal'
															) AS NON_TUNAI
															FROM admum_rawat_jalan a
															LEFT JOIN rk_pembayaran_kasir b ON a.ID = b.ID_PELAYANAN
															WHERE a.STS_CLOSING = '1'
															AND a.STS_BAYAR = '1'
															AND b.SHIFT = '$shift'
															AND a.TANGGAL = '$tanggal'
														");

		return $sql->row_array();
	}
}
