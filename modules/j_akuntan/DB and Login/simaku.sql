-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 13, 2016 at 12:05 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `simaku`
--

-- --------------------------------------------------------

--
-- Table structure for table `ak_kode_akuntansi`
--

CREATE TABLE IF NOT EXISTS `ak_kode_akuntansi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KODE_AKUN` varchar(255) DEFAULT NULL,
  `NAMA_AKUN` varchar(255) DEFAULT NULL,
  `KATEGORI` varchar(255) DEFAULT NULL,
  `DESKRIPSI` text,
  `LEVEL` varchar(255) DEFAULT NULL,
  `ANAK_DARI` varchar(255) DEFAULT NULL,
  `ID_KLIEN` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `ak_kode_akuntansi`
--

INSERT INTO `ak_kode_akuntansi` (`ID`, `KODE_AKUN`, `NAMA_AKUN`, `KATEGORI`, `DESKRIPSI`, `LEVEL`, `ANAK_DARI`, `ID_KLIEN`) VALUES
(1, '1-1000', 'Kas', 'Kas & Bank', NULL, NULL, NULL, 13),
(2, '1-1001', 'Rekening Bank', 'Kas & Bank', 'Rekening Utama Perusahaan', NULL, NULL, 13),
(3, '1-1200', 'Piutang Usaha', 'Akun Piutang', NULL, NULL, NULL, 13),
(4, '1-1210', 'Piutang Lainnya', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(5, '1-1300', 'Dana Belum Disetor', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(6, '1-1400', 'Persediaan Barang', 'Persediaan', NULL, NULL, NULL, 13),
(7, '1-1500', 'Uang Muka Pembelian', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(8, '1-1600', 'Pinjaman Karyawan', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(9, '1-1700', 'Pinjaman Lainnya', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(10, '1-1800', 'Aset Tetap', 'Aktiva Tetap', NULL, NULL, NULL, 13),
(11, '1-1801', 'Penyusutan Aset Tetap', 'Depresiasi & Amortisasi', NULL, NULL, NULL, 13),
(12, '1-1810', 'Aset Tak Berwujud', 'Aktiva Tetap', NULL, NULL, NULL, 13),
(13, '1-1820', 'Investasi', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(14, '1-1900', 'PPN Masukan', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(15, '1-1910', 'Pajak Masukan Lainnya', 'Aktiva Lancar Lainnya', NULL, NULL, NULL, 13),
(16, '2-2000', 'Hutang Usaha', 'Akun Hutang', NULL, NULL, NULL, 13),
(17, '2-2010', 'Hutang Gaji Karyawan', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(18, '2-2020', 'Hutang Dividen', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(19, '2-2030', 'Hutang Lainnya', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(20, '2-2090', 'Uang Muka Penjualan', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(21, '2-2100', 'Hutang Bank', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(22, '2-2200', 'PPN Pengeluaran', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(23, '2-2210', 'PPh Pasal 21 - Pengeluaran Pajak Payroll', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(24, '2-2230', 'PPh Pasal 23 - Pengeluaran Pajak Penghasilan Usaha', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(25, '2-2299', 'PPh Pengeluaran Lainnya', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(26, '2-2910', 'Hutang dari Pemegang Saham', 'Kewajiban Lancar Lainnya', NULL, NULL, NULL, 13),
(27, '3-3000', 'Modal Awal', 'Ekuitas', NULL, NULL, NULL, 13),
(28, '3-3100', 'Laba Ditahan', 'Ekuitas', NULL, NULL, NULL, 13),
(29, '3-3200', 'Dividen', 'Ekuitas', NULL, NULL, NULL, 13),
(30, '3-3900', 'Ekuitas Saldo Awal', 'Ekuitas', NULL, NULL, NULL, 13),
(31, '4-4000', 'Penjualan', 'Pendapatan', NULL, NULL, NULL, 13),
(32, '4-4100', 'Diskon Penjualan', 'Pendapatan', NULL, NULL, NULL, 13),
(33, '4-4200', 'Retur Penjualan', 'Pendapatan', NULL, NULL, NULL, 13),
(34, '5-5000', 'Harga Pokok Penjualan (COGS)', 'Harga Pokok Penjualan', NULL, NULL, NULL, 13),
(35, '5-5100', 'Diskon Pembelian', 'Harga Pokok Penjualan', NULL, NULL, NULL, 13),
(36, '5-5200', 'Retur Pembelian', 'Harga Pokok Penjualan', NULL, NULL, NULL, 13),
(37, '5-5300', 'Pengiriman & Pengangkutan', 'Harga Pokok Penjualan', NULL, NULL, NULL, 13),
(38, '5-5900', 'Biaya Produksi', 'Harga Pokok Penjualan', NULL, NULL, NULL, 13),
(39, '6-6000', 'Iklan & Promosi', 'Beban', NULL, NULL, NULL, 13),
(40, '6-6001', 'Piutang Tak Tertagih', 'Beban', NULL, NULL, NULL, 13),
(41, '6-6002', 'Bank', 'Beban', NULL, NULL, NULL, 13),
(42, '6-6003', 'Kontribusi Sosial', 'Beban', NULL, NULL, NULL, 13),
(43, '6-6004', 'Biaya Tenaga Kerja', 'Beban', NULL, NULL, NULL, 13),
(44, '6-6005', 'Komisi & Upah', 'Beban', NULL, NULL, NULL, 13),
(45, '6-6006', 'Biaya Pembuangan', 'Beban', NULL, NULL, NULL, 13),
(46, '6-6007', 'Iuran & Langganan', 'Beban', NULL, NULL, NULL, 13),
(47, '6-6008', 'Hiburan', 'Beban', NULL, NULL, NULL, 13),
(48, '6-6009', 'Makanan Hiburan', 'Beban', NULL, NULL, NULL, 13),
(49, '6-6010', 'Penyewaan Alat', 'Beban', NULL, NULL, NULL, 13),
(50, '6-6011', 'Asuransi', 'Beban', NULL, NULL, NULL, 13),
(51, '6-6012', 'Bunga Hutang', 'Beban', NULL, NULL, NULL, 13),
(52, '6-6013', 'Bahan Pekerjaan', 'Beban', NULL, NULL, NULL, 13),
(53, '6-6014', 'Legal & Profesional', 'Beban', NULL, NULL, NULL, 13),
(54, '6-6015', 'Pengobatan', 'Beban', NULL, NULL, NULL, 13),
(55, '6-6016', 'Biaya Kantor', 'Beban', NULL, NULL, NULL, 13),
(56, '6-6017', 'Biaya Administrasi & Umum Lainnya', 'Beban', NULL, NULL, NULL, 13),
(57, '6-6018', 'Sewa Tempat', 'Beban', NULL, NULL, NULL, 13),
(58, '6-6019', 'Pemeliharaan & Perbaikan Gedung', 'Beban', NULL, NULL, NULL, 13),
(59, '6-6020', 'Alat Tulis Kantor & Printing', 'Beban', NULL, NULL, NULL, 13),
(60, '6-6021', 'Bea Materai', 'Beban', NULL, NULL, NULL, 13),
(61, '6-6022', 'Pemborong', 'Beban', NULL, NULL, NULL, 13),
(62, '6-6023', 'Persediaan & Bahan', 'Beban', NULL, NULL, NULL, 13),
(63, '6-6024', 'Pajak & Lisensi', 'Beban', NULL, NULL, NULL, 13),
(64, '6-6025', 'Alat-alat', 'Beban', NULL, NULL, NULL, 13),
(65, '6-6026', 'Perjalanan & Transportasi', 'Beban', NULL, NULL, NULL, 13),
(66, '6-6027', 'Makanan Perjalanan', 'Beban', NULL, NULL, NULL, 13),
(67, '6-6028', 'Fasilitas/Utilitas', 'Beban', NULL, NULL, NULL, 13),
(68, '6-6029', 'Kendaraan & Mesin', 'Beban', NULL, NULL, NULL, 13),
(69, '6-6030', 'Denda & Hukuman', 'Beban', NULL, NULL, NULL, 13),
(70, '6-6031', 'Upah & Gaji', 'Beban', NULL, NULL, NULL, 13),
(71, '6-6032', 'Bonus Karyawan', 'Beban', NULL, NULL, NULL, 13),
(72, '6-6900', 'Pengeluaran Barang Rusak', 'Beban', NULL, NULL, NULL, 13),
(73, '7-7000', 'Pendapatan Pengiriman & Pengangkutan', 'Pendapatan Lainnya', NULL, NULL, NULL, 13),
(74, '7-7100', 'Pendapatan Bunga & Jasa Giro', 'Pendapatan Lainnya', NULL, NULL, NULL, 13),
(75, '7-7900', 'Pendapatan Lainnya', 'Pendapatan Lainnya', NULL, NULL, NULL, 13),
(76, '8-8000', 'Pengeluaran Lainnya', 'Beban Lainnya', NULL, NULL, NULL, 13),
(77, '8-8001', 'Biaya Amortisasi', 'Beban Lainnya', NULL, NULL, NULL, 13),
(78, '8-8002', 'Biaya Penyusutan', 'Beban Lainnya', NULL, NULL, NULL, 13),
(79, '8-8003', 'Untung/Rugi Pertukaran Kurs', 'Beban Lainnya', NULL, NULL, NULL, 13),
(80, '8-8900', 'Penyesuaian Persediaan Barang', 'Beban Lainnya', NULL, NULL, NULL, 13),
(82, '1-1002', 'Bank BNI Syariah', 'Kas & Bank', 'Akun Bank BNI Syariah', NULL, NULL, 13),
(83, '2-2101', 'Cimb Niaga CC', 'Credit Card', 'Credit Card for Cimb Niaga (Master Card)', NULL, NULL, 13);

-- --------------------------------------------------------

--
-- Table structure for table `ak_nomor`
--

CREATE TABLE IF NOT EXISTS `ak_nomor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `TIPE` varchar(255) DEFAULT NULL,
  `NEXT` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ak_nomor`
--

INSERT INTO `ak_nomor` (`ID`, `ID_KLIEN`, `TIPE`, `NEXT`) VALUES
(2, 13, 'Terima Uang', 10001),
(4, 13, 'Trf Uang', 10001),
(5, 13, 'Kirim Uang', 10001),
(6, 13, 'Penjualan', 10001);

-- --------------------------------------------------------

--
-- Table structure for table `ak_pelanggan`
--

CREATE TABLE IF NOT EXISTS `ak_pelanggan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NAMA_PELANGGAN` varchar(255) DEFAULT NULL,
  `NPWP` varchar(255) DEFAULT NULL,
  `ALAMAT_TAGIH` text,
  `ALAMAT_KIRIM` text,
  `NO_TELP` varchar(255) DEFAULT NULL,
  `NO_HP` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `WAKTU` varchar(255) DEFAULT NULL,
  `WAKTU_EDIT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ak_pelanggan`
--

INSERT INTO `ak_pelanggan` (`ID`, `ID_KLIEN`, `NAMA_PELANGGAN`, `NPWP`, `ALAMAT_TAGIH`, `ALAMAT_KIRIM`, `NO_TELP`, `NO_HP`, `EMAIL`, `WAKTU`, `WAKTU_EDIT`) VALUES
(1, 13, 'Toko Bu Romlah', '-', 'Jl. Panji Suroso gg 2 no 181', 'Jl. Panji Suroso gg 2 no 181', '081324424593', '081324424593', 'warung.romlah@gmail.com', '02-08-2016, 11:15', '-');

-- --------------------------------------------------------

--
-- Table structure for table `ak_pembelian`
--

CREATE TABLE IF NOT EXISTS `ak_pembelian` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NO_BUKTI` varchar(255) DEFAULT NULL,
  `PELANGGAN` varchar(255) DEFAULT NULL,
  `ALAMAT` text,
  `TGL_TRX` varchar(255) DEFAULT NULL,
  `TGL_JATUH_TEMPO` varchar(255) DEFAULT NULL,
  `ID_PAJAK` int(11) DEFAULT NULL,
  `SUB_TOTAL` double DEFAULT NULL,
  `NILAI_PAJAK` double DEFAULT NULL,
  `TOTAL` double DEFAULT NULL,
  `LUNAS` int(11) DEFAULT '0',
  `AKUN_SETOR` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `ak_pembelian_detail`
--

CREATE TABLE IF NOT EXISTS `ak_pembelian_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `ID_PENJUALAN` int(11) DEFAULT NULL,
  `NAMA_PRODUK` varchar(255) DEFAULT NULL,
  `QTY` int(11) DEFAULT NULL,
  `SATUAN` varchar(255) DEFAULT NULL,
  `HARGA_SATUAN` double DEFAULT NULL,
  `TOTAL` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `ak_penerimaan_kas_bank`
--

CREATE TABLE IF NOT EXISTS `ak_penerimaan_kas_bank` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `KODE_AKUN` varchar(255) DEFAULT NULL,
  `NO_BUKTI` varchar(255) DEFAULT NULL,
  `TGL` varchar(255) DEFAULT NULL,
  `DEBET` double DEFAULT NULL,
  `KREDIT` double DEFAULT NULL,
  `DESKRIPSI` text,
  `TIPE` varchar(255) DEFAULT NULL,
  `KONTAK` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ak_penerimaan_kas_bank`
--

INSERT INTO `ak_penerimaan_kas_bank` (`ID`, `ID_KLIEN`, `KODE_AKUN`, `NO_BUKTI`, `TGL`, `DEBET`, `KREDIT`, `DESKRIPSI`, `TIPE`, `KONTAK`) VALUES
(2, 13, '1-1000', 'WD-10001', '12-08-2016', 8750000, 0, NULL, 'PENERIMAAN', 'Toko Bu Romlah (Pelanggan)'),
(5, 13, '1-1000', 'BANKTRF-10001', '12-08-2016', 0, 5000000, 'Transfer Uang dari Kas & Bank ke Akun BNI Syariah', 'TRANSFER UANG', NULL),
(6, 13, '1-1002', 'BANKTRF-10001', '12-08-2016', 5000000, 0, 'Transfer Uang dari Kas & Bank ke Akun BNI Syariah', 'TRANSFER UANG', NULL),
(7, 13, '1-1002', 'PO-10001', '12-08-2016', 0, 2000000, NULL, 'PENERIMAAN', 'Yamois Cab. Malang (Supplier)');

-- --------------------------------------------------------

--
-- Table structure for table `ak_penerimaan_kas_bank_detail`
--

CREATE TABLE IF NOT EXISTS `ak_penerimaan_kas_bank_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KAS_BANK` int(11) DEFAULT NULL,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NO_BUKTI` varchar(255) DEFAULT NULL,
  `KODE_AKUN` varchar(255) DEFAULT NULL,
  `DESKRIPSI` varchar(255) DEFAULT NULL,
  `NILAI` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ak_penerimaan_kas_bank_detail`
--

INSERT INTO `ak_penerimaan_kas_bank_detail` (`ID`, `ID_KAS_BANK`, `ID_KLIEN`, `NO_BUKTI`, `KODE_AKUN`, `DESKRIPSI`, `NILAI`) VALUES
(1, 2, 13, 'WD-10001', '4-4000', 'Pembayaran Pelanggan untuk pembelian berbagai macam sayuran pada Tgl 12 Agustus 2016', 2750000),
(2, 2, 13, 'WD-10001', '1-1500', 'Uang Muka Pelanggan untuk pembelian selanjutnya', 1500000),
(3, 2, 13, 'WD-10001', '1-1200', 'Pembayaran Hutang Pelanggan', 4500000),
(4, 7, 13, 'PO-10001', '2-2000', 'Pembayaran Hutang periode 1', 2000000);

-- --------------------------------------------------------

--
-- Table structure for table `ak_penjualan`
--

CREATE TABLE IF NOT EXISTS `ak_penjualan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NO_BUKTI` varchar(255) DEFAULT NULL,
  `PELANGGAN` varchar(255) DEFAULT NULL,
  `ALAMAT` text,
  `TGL_TRX` varchar(255) DEFAULT NULL,
  `TGL_JATUH_TEMPO` varchar(255) DEFAULT NULL,
  `ID_PAJAK` int(11) DEFAULT NULL,
  `SUB_TOTAL` double DEFAULT NULL,
  `NILAI_PAJAK` double DEFAULT NULL,
  `TOTAL` double DEFAULT NULL,
  `LUNAS` int(11) DEFAULT '0',
  `AKUN_SETOR` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ak_penjualan`
--

INSERT INTO `ak_penjualan` (`ID`, `ID_KLIEN`, `NO_BUKTI`, `PELANGGAN`, `ALAMAT`, `TGL_TRX`, `TGL_JATUH_TEMPO`, `ID_PAJAK`, `SUB_TOTAL`, `NILAI_PAJAK`, `TOTAL`, `LUNAS`, `AKUN_SETOR`) VALUES
(3, 13, 'INV-10001', 'Toko Bu Romlah', 'Jl. Panji Suroso gg 2 no 181', '12-08-2016', '12-08-2016', 1, 55000, 5500, 60500, 1, '-999');

-- --------------------------------------------------------

--
-- Table structure for table `ak_penjualan_detail`
--

CREATE TABLE IF NOT EXISTS `ak_penjualan_detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `ID_PENJUALAN` int(11) DEFAULT NULL,
  `NAMA_PRODUK` varchar(255) DEFAULT NULL,
  `QTY` int(11) DEFAULT NULL,
  `SATUAN` varchar(255) DEFAULT NULL,
  `HARGA_SATUAN` double DEFAULT NULL,
  `TOTAL` double DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ak_penjualan_detail`
--

INSERT INTO `ak_penjualan_detail` (`ID`, `ID_KLIEN`, `ID_PENJUALAN`, `NAMA_PRODUK`, `QTY`, `SATUAN`, `HARGA_SATUAN`, `TOTAL`) VALUES
(2, 13, 3, 'Bawang Merah', 1, 'Kg', 55000, 55000);

-- --------------------------------------------------------

--
-- Table structure for table `ak_produk`
--

CREATE TABLE IF NOT EXISTS `ak_produk` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `KODE_PRODUK` varchar(255) DEFAULT NULL,
  `NAMA_PRODUK` varchar(255) DEFAULT NULL,
  `DESKRIPSI` text,
  `SATUAN` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ak_produk`
--

INSERT INTO `ak_produk` (`ID`, `ID_KLIEN`, `KODE_PRODUK`, `NAMA_PRODUK`, `DESKRIPSI`, `SATUAN`) VALUES
(1, 13, 'BW-MRH', 'Bawang Merah', 'Bawang Merah Asli Palembang', 'Kg');

-- --------------------------------------------------------

--
-- Table structure for table `ak_profil_usaha`
--

CREATE TABLE IF NOT EXISTS `ak_profil_usaha` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NAMA_PERUSAHAAN` varchar(255) DEFAULT NULL,
  `ALAMAT` varchar(255) DEFAULT NULL,
  `TELEPON` varchar(255) DEFAULT NULL,
  `FAX` varchar(255) DEFAULT NULL,
  `NPWP` varchar(255) DEFAULT NULL,
  `WEBSITE` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `NAMA_BANK` varchar(255) DEFAULT NULL,
  `CABANG_BANK` varchar(255) DEFAULT NULL,
  `NO_AKUN_BANK` varchar(255) DEFAULT NULL,
  `ATAS_NAMA` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ak_profil_usaha`
--

INSERT INTO `ak_profil_usaha` (`ID`, `ID_KLIEN`, `NAMA_PERUSAHAAN`, `ALAMAT`, `TELEPON`, `FAX`, `NPWP`, `WEBSITE`, `EMAIL`, `NAMA_BANK`, `CABANG_BANK`, `NO_AKUN_BANK`, `ATAS_NAMA`) VALUES
(1, 13, 'DNA Cafe & Resto', 'Jl. Teluk Etna II no 148, Malang', '0341453584', '0341453584', '01.123.456.7-521.000', 'http://j-tech.co.id', 'mykeppo@gmail.com', 'BNI SYARIAH', 'MALANG', '0313424395', 'ADITYA EKA NUGRAHA');

-- --------------------------------------------------------

--
-- Table structure for table `ak_satuan`
--

CREATE TABLE IF NOT EXISTS `ak_satuan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `SATUAN` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ak_setup_pajak`
--

CREATE TABLE IF NOT EXISTS `ak_setup_pajak` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NAMA_PAJAK` varchar(255) DEFAULT NULL,
  `PROSEN` int(11) DEFAULT NULL,
  `PAJAK_PENJUALAN` varchar(255) DEFAULT NULL,
  `PAJAK_PEMBELIAN` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ak_setup_pajak`
--

INSERT INTO `ak_setup_pajak` (`ID`, `ID_KLIEN`, `NAMA_PAJAK`, `PROSEN`, `PAJAK_PENJUALAN`, `PAJAK_PEMBELIAN`) VALUES
(1, 13, 'PPN', 10, '2-2200', '1-1900');

-- --------------------------------------------------------

--
-- Table structure for table `ak_supplier`
--

CREATE TABLE IF NOT EXISTS `ak_supplier` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `NAMA_SUPPLIER` varchar(255) DEFAULT NULL,
  `NPWP` varchar(255) DEFAULT NULL,
  `ALAMAT_TAGIH` text,
  `NO_TELP` varchar(255) DEFAULT NULL,
  `NO_HP` varchar(255) DEFAULT NULL,
  `EMAIL` varchar(255) DEFAULT NULL,
  `WAKTU` varchar(255) DEFAULT NULL,
  `WAKTU_EDIT` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ak_supplier`
--

INSERT INTO `ak_supplier` (`ID`, `ID_KLIEN`, `NAMA_SUPPLIER`, `NPWP`, `ALAMAT_TAGIH`, `NO_TELP`, `NO_HP`, `EMAIL`, `WAKTU`, `WAKTU_EDIT`) VALUES
(2, 13, 'Yamois Cab. Malang', '1000238349722449', 'Jl. R. Panji Suroso !V no. 165 Malang', '0341433893', '-', 'yamois@gmail.com', '03-08-2016, 15:53', '03-08-2016, 15:55');

-- --------------------------------------------------------

--
-- Table structure for table `ak_user`
--

CREATE TABLE IF NOT EXISTS `ak_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_KLIEN` int(11) DEFAULT NULL,
  `USERNAME` varchar(255) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `NAMA` varchar(255) DEFAULT NULL,
  `FOTO` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 MAX_ROWS=4294967295 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ak_user`
--

INSERT INTO `ak_user` (`ID`, `ID_KLIEN`, `USERNAME`, `PASSWORD`, `NAMA`, `FOTO`) VALUES
(1, 13, 'adit', '4301c003db9de718fe74d8e7bd019986', 'Naima Karin', '11070824_284179148372570_9114247542983732935_n.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
