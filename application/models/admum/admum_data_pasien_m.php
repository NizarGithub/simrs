<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admum_data_pasien_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function data_pasien_per_poli($keyword,$now){
		$where = "1 = 1";
		$order = "";

		if($keyword != ""){
			$where = $where." AND a.NAMA LIKE '%$keyword%'";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.ID,
				a.NAMA AS NAMA_POLI,
				COUNT(b.ID_PASIEN) AS JUMLAH_PASIEN
			FROM admum_poli a
			LEFT JOIN(
				SELECT ID,ID_PASIEN,ID_POLI FROM admum_rawat_jalan
				WHERE TANGGAL = '$now'
			) b ON b.ID_POLI = a.ID
			WHERE $where
			GROUP BY a.ID
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_poli_id($id){
		$sql = "SELECT * FROM admum_poli WHERE ID = '$id'";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function data_pasien_per_poli_id($id){
		$sql = "
			SELECT
				a.ID,
				a.NAMA AS NAMA_POLI,
				b.*
			FROM admum_poli a
			JOIN(
				SELECT 
					a.ID AS ID_RJ,
					a.ID_PASIEN,
					a.ID_POLI,
					b.KODE_PASIEN,
					b.NAMA AS NAMA_PASIEN,
					b.JENIS_KELAMIN,
					b.TANGGAL_LAHIR,
					b.UMUR,
					b.UMUR_BULAN,
					b.NAMA_AYAH,
					b.NAMA_IBU
				FROM admum_rawat_jalan a
				LEFT JOIN rk_pasien b ON b.ID = a.ID_PASIEN
				WHERE a.ID_POLI = '$id'
			) b ON b.ID_POLI = a.ID
			WHERE a.ID = '$id'
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_rj($keyword,$now){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.KODE_PASIEN LIKE '%$keyword%' OR b.NAMA LIKE '%$keyword%' OR b.NAMA_AYAH LIKE '%$keyword%' OR b.NAMA_IBU LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.ID,
				a.TANGGAL,
				a.STS_APPROVE_RM,
				b.KODE_PASIEN,
				b.NAMA,
				b.JENIS_KELAMIN,
				b.TANGGAL_LAHIR,
				b.UMUR,
				b.ALAMAT,
				b.NAMA_AYAH,
				b.NAMA_IBU,
				c.NAMA AS NAMA_POLI
			FROM admum_rawat_jalan a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			LEFT JOIN admum_poli c ON c.ID = a.ID_POLI
			WHERE $where
			AND a.TANGGAL = '$now'
			ORDER BY a.ID DESC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function data_pasien_ri($keyword){
		$where = "1 = 1";

		if($keyword != ""){
			$where = $where." AND (b.KODE_PASIEN LIKE '%$keyword%' OR b.NAMA LIKE '%$keyword%')";
		}else{
			$where = $where;
		}

		$sql = "
			SELECT
				a.ID,
				a.TANGGAL_MASUK,
				a.TANGGAL_KELUAR,
				a.STS_APPROVE_RM,
				b.KODE_PASIEN,
				b.NAMA,
				b.JENIS_KELAMIN,
				b.TANGGAL_LAHIR,
				b.UMUR,
				c.KODE_KAMAR,
				c.KELAS,
				d.NOMOR_BED
			FROM admum_rawat_inap a
			JOIN rk_pasien b ON b.ID = a.ID_PASIEN
			LEFT JOIN admum_kamar_rawat_inap c ON c.ID = a.ID_KAMAR
			LEFT JOIN admum_bed_rawat_inap d ON d.ID = a.ID_BED
			WHERE $where
			ORDER BY a.ID DESC
		";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function ubah_pasien(
		$id,
		$nama,
		$jenis_kelamin,
		$pendidikan,
		$agama,
		$alamat,
		$golongan_darah,
		$tempat_lahir,
		$tanggal_lahir,
		$umur,
		$kelurahan,
		$kecamatan,
		$kota,
		$provinsi,
		$status){

		$sql = "
			UPDATE rk_pasien SET
				NAMA = '$nama',
				JENIS_KELAMIN = '$jenis_kelamin',
				PENDIDIKAN = '$pendidikan',
				AGAMA = '$agama',
				ALAMAT = '$alamat',
				GOLONGAN_DARAH = '$golongan_darah',
				TEMPAT_LAHIR = '$tempat_lahir',
				TANGGAL_LAHIR = '$tanggal_lahir',
				UMUR = '$umur',
				KELURAHAN = '$kelurahan',
				KECAMATAN = '$kecamatan',
				KOTA = '$kota',
				PROVINSI = '$provinsi',
				STATUS = '$status'
			WHERE ID = '$id'
		";
		$this->db->query($sql);
	}

	function hapus_pasien($id){
		$sql = "DELETE FROM rk_pasien WHERE ID = '$id'";
		$this->db->query($sql);
	}

	function kota_kab(){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function provinsi($id_kota_kab){
		$sql = "
			SELECT
				LKS.lokasi_ID,
				LKS.lokasi_kode,
				LKS.lokasi_propinsi AS ID_PROV,
				LKS.lokasi_nama AS PROV,
				LKS2.lokasi_nama AS KOTA,
				LKS2.lokasi_propinsi,
				LKS2.lokasi_kabupatenkota
			FROM lokasi LKS
			JOIN(
				SELECT * FROM lokasi 
				WHERE lokasi_kabupatenkota != '0'
				AND lokasi_kecamatan = '0'
			) LKS2 ON LKS.lokasi_propinsi = LKS2.lokasi_propinsi
			WHERE LKS.lokasi_kabupatenkota = '0'
			AND LKS2.lokasi_nama = '$id_kota_kab'
			ORDER BY 
			LKS.lokasi_propinsi ASC,
			KOTA ASC
		";
		$query = $this->db->query($sql);
		return $query->row();
	}

	function simpan_permintaan($id_pegawai,$id_pasien,$permintaan){
		$sql = "INSERT INTO kepeg_permintaan(ID_PEGAWAI,ID_PASIEN,PERMINTAAN) VALUES ('$id_pegawai','$id_pasien','$permintaan')";
		$this->db->query($sql);
	}

	function getDetailLayananRJ($id_pasien, $tgl){

		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND a.TANGGAL LIKE '%$tgl%'";
		}

		$sql = "
		SELECT a.TANGGAL, a.KET, a.JUMLAH, a.HARGA, a.TARIF, a.ORD, a.TAB FROM(
			SELECT a.TANGGAL, b.JENIS_LABORAT AS KET, 1 AS JUMLAH, 0 AS HARGA, SUM(a.TOTAL_TARIF) AS TARIF, 'LABORAT' AS ORD, 4 AS TAB  FROM rk_laborat_rj a
			LEFT JOIN admum_setup_jenis_laborat b ON a.JENIS_LABORAT = b.ID
			WHERE a.ID_PASIEN = $id_pasien
			GROUP BY b.JENIS_LABORAT

			UNION ALL 

			SELECT a.TANGGAL, c.NAMA_TINDAKAN AS KET, b.JUMLAH, 0 AS HARGA, b.SUBTOTAL AS TARIF, 'TINDAKAN' AS ORD, 2 AS TAB  FROM rk_tindakan_rj a 
			JOIN rk_tindakan_rj_detail b ON a.ID = b.ID_TINDAKAN_RJ
			JOIN admum_setup_tindakan c ON b.TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, c.NAMA_OBAT AS KET, b.JUMLAH_BELI AS JUMLAH, b.HARGA, b.SUBTOTAL AS TARIF, 'OBAT' AS ORD, 3 AS TAB  FROM rk_resep_rj a 
			JOIN rk_resep_detail_rj b ON b.ID_RESEP = a.ID
			JOIN admum_setup_nama_obat c ON b.ID_OBAT = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, a.DIAGNOSA AS KET, 0 AS JUMLAH, 0 AS HARGA, 0 AS TARIF, 'DIAGNOSA' AS ORD, 1 AS TAB  FROM rk_diagnosa_rj a 
			WHERE a.ID_PASIEN = $id_pasien
		) a
		WHERE $where
		ORDER BY a.TAB ASC, a.TANGGAL DESC
		";

		return $this->db->query($sql)->result();
	}

	function getDetailLayananRI($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND a.TANGGAL LIKE '%$tgl%'";
		}

		$sql = "
		SELECT a.TANGGAL, a.KET, a.JUMLAH, a.TARIF, a.ORD FROM(
			SELECT a.TANGGAL, c.NAMA_TINDAKAN AS KET, b.JUMLAH, b.SUBTOTAL AS TARIF, 'TINDAKAN' AS ORD  FROM rk_ri_tindakan a 
			JOIN rk_ri_tindakan_detail b ON a.ID = b.ID_TINDAKAN
			JOIN admum_setup_tindakan c ON b.ID_SETUP_TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien
		) a
		WHERE $where
		ORDER BY a.ORD ASC, a.TANGGAL DESC
		";

		return $this->db->query($sql)->result();
	}

	function dataDetVisite_RI($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND RIVST.TANGGAL LIKE '%$tgl%'";
		}

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
			WHERE RIVST.ID_PASIEN = '$id_pasien' AND $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetGizi_RI($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND RIGZ.TANGGAL LIKE '%$tgl%'";
		}

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
			WHERE RIGZ.ID_PASIEN = '$id_pasien' AND $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetOksigen_RI($id_pasien, $tgl){

		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND TANGGAL LIKE '%$tgl%'";
		}

		$sql = "SELECT * FROM rk_ri_oksigen WHERE ID_PASIEN = '$id_pasien' AND $where";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetDiagnosa_RI($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND DG.TANGGAL LIKE '%$tgl%'";
		}

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
			WHERE DG.ID_PASIEN = '$id_pasien' AND $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function dataDetResep_RI($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND RSP.TANGGAL LIKE '%$tgl%'";
		}

		$sql = "
			SELECT
				DET.ID,
				RSP.ID_PASIEN,
				RSP.TANGGAL,
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
			WHERE RSP.ID_PASIEN = '$id_pasien' AND $where
		";
		$query = $this->db->query($sql);
		return $query->result();
	}

	function getDetailLayananIGD($id_pasien, $tgl){
		$where = "1=1";
		if($tgl != "" || $tgl != null){
			$where = $where." AND a.TANGGAL LIKE '%$tgl%'";
		}

		$sql = "
		SELECT a.TANGGAL, a.KET, a.JUMLAH, a.HARGA, a.TARIF, a.ORD, a.TAB FROM(
			SELECT a.TANGGAL, b.JENIS_LABORAT AS KET, 1 AS JUMLAH, 0 AS HARGA, SUM(a.TOTAL_TARIF) AS TARIF, 'LABORAT' AS ORD, 3 AS TAB  FROM rk_igd_laborat a
			LEFT JOIN admum_setup_jenis_laborat b ON a.JENIS_LABORAT = b.ID
			WHERE a.ID_PASIEN = $id_pasien
			GROUP BY b.JENIS_LABORAT

			UNION ALL 

			SELECT a.TANGGAL, c.NAMA_TINDAKAN AS KET, b.JUMLAH, 0 AS HARGA, b.SUBTOTAL AS TARIF, 'TINDAKAN' AS ORD, 2 AS TAB  FROM rk_igd_tindakan a 
			JOIN rk_igd_tindakan_detail b ON a.ID = b.ID_TINDAKAN_IGD
			JOIN admum_setup_tindakan c ON b.TINDAKAN = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, c.NAMA_OBAT AS KET, b.JUMLAH_BELI AS JUMLAH, b.HARGA, b.SUBTOTAL AS TARIF, 'OBAT' AS ORD, 4 AS TAB  FROM rk_igd_resep a 
			JOIN rk_igd_resep_detail b ON b.ID_RESEP = a.ID
			JOIN admum_setup_nama_obat c ON b.ID_OBAT = c.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, a.DIAGNOSA AS KET, 0 AS JUMLAH, 0 AS HARGA, 0 AS TARIF, 'DIAGNOSA' AS ORD, 1 AS TAB  FROM rk_igd_diagnosa a 
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, b.NAMA_RUANG AS KET, 0 AS JUMLAH, a.TARIF AS HARGA, a.TARIF, 'OPERASI' AS ORD, 5 AS TAB  FROM rk_igd_operasi a 
			JOIN admum_setup_ruang_operasi b ON a.ID_RUANG_OPERASI = b.ID
			WHERE a.ID_PASIEN = $id_pasien

			UNION ALL 

			SELECT a.TANGGAL, b.NAMA_RUANG AS KET, 0 AS JUMLAH, a.TARIF AS HARGA, a.TARIF, 'ICU' AS ORD, 6 AS TAB  FROM rk_igd_icu a 
			JOIN admum_setup_ruang_icu b ON a.ID_RUANG_ICU = b.ID
			WHERE a.ID_PASIEN = $id_pasien
		) a
		WHERE $where
		ORDER BY a.TAB ASC, a.TANGGAL DESC
		";

		return $this->db->query($sql)->result();
	}

}