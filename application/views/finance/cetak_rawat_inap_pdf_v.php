<?PHP  
ob_start();
// header("Cache-Control: no-cache, no-store, must-revalidate");  
// header("Content-Type: application/vnd.ms-excel");  
// header("Content-Disposition: attachment; filename=tes.xls");          
?>

<style type="text/css">
.tabel {
    background: #fff;
    table-layout: fixed;
    border-collapse: collapse;
    border: 1px solid black;
    font-size: 14px;
}
.tabel th {
    background: #fff;
    vertical-align: middle;
    color : #000;
    height: 30px;
    font-size: 12px;
    border: 1px solid black;
    text-align: center;
}
.tabel td {
    background: #fff;
    vertical-align: middle;
    height: 20px;
    padding-left: 5px;
    padding-right: 5px;
    font-size: 10px;
    border: 1px solid black;
}
.footer{
    position:absolute;
    left:0;
    bottom:0;
}
</style>

<?php
function formatTanggal($tgl){
    //22-11-2016
    $d = substr($tgl,0,2);
    $m = substr($tgl,3,2);
    $y = substr($tgl,6);

    $strBulan = "";

    if($m == '01'){
        $strBulan = "Jan";
    }else if($m == '02'){
        $strBulan = "Feb";
    }else if($m == '03'){
        $strBulan = "Mar";
    }else if($m == '04'){
        $strBulan = "Apr";
    }else if($m == '05'){
        $strBulan = "Mei";
    }else if($m == '06'){
        $strBulan = "Jun";
    }else if($m == '07'){
        $strBulan = "Jul";
    }else if($m == '08'){
        $strBulan = "Agt";
    }else if($m == '09'){
        $strBulan = "Sep";
    }else if($m == '10'){
        $strBulan = "Okt";
    }else if($m == '11'){
        $strBulan = "Nov";
    }else if($m == '12'){
        $strBulan = "Des";
    }

    return $d."-".$strBulan."-".$y;
}

function romanic_number($integer, $upcase = true) { 
    $table = array(
        'M'     =>1000, 
        'CM'    =>900, 
        'D'     =>500, 
        'CD'    =>400, 
        'C'     =>100, 
        'XC'    =>90, 
        'L'     =>50, 
        'XL'    =>40, 
        'X'     =>10, 
        'IX'    =>9, 
        'V'     =>5, 
        'IV'    =>4, 
        'I'     =>1
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

function terbilang($a) {
    $ambil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($a < 12)
        return " " . $ambil[$a];
    elseif ($a < 20)
        return Terbilang($a - 10) . "Belas";
    elseif ($a < 100)
        return Terbilang($a / 10) . " Puluh" . Terbilang($a % 10);
    elseif ($a < 200)
        return " Seratus" . Terbilang($a - 100);
    elseif ($a < 1000)
        return Terbilang($a / 100) . " Ratus" . Terbilang($a % 100);
    elseif ($a < 2000)
        return " Seribu" . Terbilang($a - 1000);
    elseif ($a < 1000000)
        return Terbilang($a / 1000) . " Ribu" . Terbilang($a % 1000);
    elseif ($a < 1000000000)
        return Terbilang($a / 1000000) . " Juta" . Terbilang($a % 1000000);
}

$sql = "SELECT * FROM admum_setup_logo WHERE ID = '1'";
$qry = $this->db->query($sql);
$row = $qry->row();
$logo = $row->LOGO;
?>

<table align="left">
    <tr>
        <td style="padding-top: 0px;">
            <img src="<?php echo base_url(); ?>picture/logo/<?php echo $logo; ?>" style="width: 70px; height: 70px;">
        </td>
        <td style="vertical-align: bottom;">
            <b><?php echo $row->NAMA; ?></b><br>
            <p style="font-size: 11px;">
            <?php echo $row->ALAMAT; ?><br>
            Telp. (031) <?php echo $row->TELEPON; ?>, Fax (031) <?php echo $row->FAX; ?>
            </p>
        </td>
    </tr>
</table>

<hr>
<br/>

<table align="left">
    <tr>
        <td style="width:120px;">No. RM</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data0->KODE_PASIEN; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Tanggal</td>
        <td>:</td>
        <td style="width:175px;"><?php echo formatTanggal($data0->TANGGAL_MASUK); ?></td>
    </tr>
    <tr>
        <td style="width:120px;">Nama Pasien</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data0->NAMA_PASIEN; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Jenis Kelamin</td>
        <td>:</td>
        <td style="width:175px;">
            <?php
                if($data0->JENIS_KELAMIN == 'L'){
                    echo "Laki - Laki";
                }else{
                    echo "Perempuan";
                }
            ?>
        </td>
    </tr>
    <tr>
        <td style="width:120px;">Tgl Lahir</td>
        <td>:</td>
        <td style="width:130px;"><?php echo formatTanggal($data0->TANGGAL_LAHIR); ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:120px;">Umur</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data0->UMUR; ?> Tahun</td>
    </tr>
</table>

<br/>

<table align="left" class="tabel">
    <thead>
        <tr>
            <th>KETERANGAN</th>
            <th>KELAS</th>
            <th>HARI</th> 
            <th>BIAYA</th>
            <th>JUMLAH</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $biaya_charge = $data1->BIAYA_CHARGE_KAMAR;
            $biaya_visite = 0;
            $jasa_sarana = $data4->JASA_SARANA;
            $biaya_tindakan = 0;
            $biaya_resep = $data6->TOTAL;
            $biaya_lab = 0;
            $biaya_admin = $data0->BIAYA_REG;
        ?>
        <?php if($data1->BIAYA_CHARGE_KAMAR > 0){ ?>
        <tr>
            <td style="width: 300px;">Kamar Rawat Inap Charge 15% (08:00 - 11:59)</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:90px; text-align: right"><?php echo number_format($data1->BIAYA_KAMAR_FIX,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($data1->BIAYA_CHARGE_KAMAR,0,',','.'); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td style="width: 300px;">Kamar Rawat Inap</td>
            <td style="width:80px; text-align: center;"><?php echo $data2->KELAS; ?></td>
            <td style="width:80px; text-align: center;"><?php echo $data2->DIRAWAT_SELAMA; ?></td>
            <td style="width:90px; text-align: right"><?php echo number_format($data2->BIAYA_KAMAR_FIX,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;">
                <?php 
                    $biaya_kamar = $data2->BIAYA_KAMAR_FIX;
                    $dirawat = $data2->DIRAWAT_SELAMA;
                    $total_biaya_kamar = $biaya_kamar * $dirawat;
                    echo number_format($total_biaya_kamar,0,',','.'); 
                ?>
            </td>
        </tr>
        <?php
            foreach ($data3 as $val3) {
                $biaya_visite += $val3->BIAYA_VISITE;
        ?>
        <tr>
            <td style="width: 300px;">Visite Dokter Tgl <?php echo formatTanggal($val3->TANGGAL); ?></td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:90px; text-align: right"><?php echo number_format($val3->BIAYA_VISITE,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($val3->BIAYA_VISITE,0,',','.'); ?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td style="width: 300px;">Jasa Sarana RS</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:90px; text-align: right"><?php echo number_format($data4->JASA_SARANA,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($data4->JASA_SARANA,0,',','.'); ?></td>
        </tr>
        <?php
            foreach ($data5 as $val5) {
                $biaya_tindakan += $val5->TOTAL;
        ?>
        <tr>
            <td style="width: 300px;"><?php echo $val5->NAMA_TINDAKAN; ?></td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px; text-align: center;"><?php echo $val5->JUMLAH; ?></td>
            <td style="width:90px; text-align: right"><?php echo number_format($val5->HARGA,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($val5->TOTAL,0,',','.'); ?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td style="width: 300px;">Biaya Obat - Obatan</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:90px; text-align: right"><?php echo number_format($data6->TOTAL,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($data6->TOTAL,0,',','.'); ?></td>
        </tr>
        <tr>
            <td colspan="5">Pemeriksaan Laborat :</td>
        </tr>
        <?php
            foreach ($data7 as $val7) {
                $biaya_lab += $val7->SUBTOTAL;
        ?>
        <tr>
            <td style="width: 300px;">- <?php echo $val7->NAMA_PEMERIKSAAN; ?></td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:90px; text-align: right"><?php echo number_format($val7->SUBTOTAL,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($val7->SUBTOTAL,0,',','.'); ?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td style="width: 300px;">Administrasi</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width:80px;">&nbsp;</td>
            <td style="width: 90px; text-align: right;"><?php echo number_format($data0->BIAYA_REG,0,',','.'); ?></td>
            <td style="width: 90px; text-align: right;"><?php echo number_format($data0->BIAYA_REG,0,',','.'); ?></td>
        </tr>
        <tr>
            <td colspan="3">Titipan Uang</td>
            <td style="width: 90px; text-align: right;">Sub Total Rp</td>
            <td style="width: 90px; text-align: right;">
                <?php 
                    $subtotal = $biaya_charge + $total_biaya_kamar + $biaya_visite + $jasa_sarana + $biaya_tindakan + $biaya_resep + $biaya_lab + $biaya_admin;
                    echo number_format($subtotal,0,',','.');
                ?>
            </td>
        </tr>
        <tr>
            <td style="width: 300px;">Kurang Bayar</td>
            <td colspan="2" style="text-align: right;"><?php echo number_format($subtotal,0,',','.'); ?></td>
            <td style="width: 90px; text-align: right;">Discount Rp</td>
            <td style="width: 90px; text-align: right;">0</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td style="width: 90px; text-align: right;">Total Rp</td>
            <td style="width: 90px; text-align: right;"><?php echo number_format($subtotal,0,',','.'); ?></td>
        </tr>
    </tbody>
</table>

<br>

<table align="left">
    <tr>
        <td style="vertical-align: bottom;">
            <b><?php echo $row->NAMA; ?></b><br>
            <p style="font-size: 11px;">
            <?php echo $row->ALAMAT; ?><br>
            Telp. (031) <?php echo $row->TELEPON; ?>, Fax (031) <?php echo $row->FAX; ?>
            </p>
        </td>
    </tr>
</table>

<br>

<table align="center">
    <tr>
        <td style="text-align: center;">KWITANSI</td>
    </tr>
</table>

<br>

<table align="right">
    <tr>
        <td>No Kwitansi :</td>
        <td><?php echo $data8->INVOICE; ?></td>
    </tr>
</table>

<br>

<table align="left">
    <tr>
        <td>Sudah Terima dari</td>
        <td>: <?php echo $data0->NAMA_PASIEN; ?></td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>: <?php echo $data0->ALAMAT; ?></td>
    </tr>
    <tr>
        <td>Sejumlah</td>
        <td>: <?php echo terbilang($subtotal); ?> Rupiah</td>
    </tr>
    <tr>
        <td>Untuk Pembayaran</td>
        <td>: Rincian Perawatan Pasien <b>An. <?php echo $data0->NAMA_PASIEN; ?></b></td>
    </tr>
</table>

<div class="footer">
    <table align="right">
        <tr>
            <td style="text-align:center; width:200px;">
                Sepanjang, <?php echo formatTanggal(date('d-m-Y')); ?>
            </td>
        </tr>
        <tr>
            <td style="height:50px; width:200px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align:center; width:200px;">
                <?php echo $data9->NAMA; ?><br>(Cashier)
            </td>
        </tr>
    </table>
</div>
<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    // $width_custom = 11.69;
    // $height_custom = 8.27;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>