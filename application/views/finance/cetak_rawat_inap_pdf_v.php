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
?>

<table align="left">
	<tr>
		<td rowspan="7">
			<img src="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png" style="width:75px; height:75px; margin-top: 80px; margin-right: 15px;">
		</td>
	</tr>
    <tr>
      <td style="text-align:center; font-weight: bold; width: 100%;">Nama RS</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 13px; width: 100%;">Alamat</td>
    </tr>
    <tr>
      <td style="text-align:center; width: 100%;">S I D O A R J O</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 12px; width: 100%;">Telepon</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 12px; width: 100%;">No Ijin</td>
    </tr>
</table>

<br/>
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
    <tbody>
        <tr>
            <th>KETERANGAN</th>
            <th>KELAS</th>
            <th>HARI</th> 
            <th>BIAYA</th>
            <th>JUMLAH</th>
        </tr>
    <?php
        $jumlah = 0;
        $jasa = 0;
        $visite = 0;
        $tarif_tdk = 0;
        $tarif_lab = 0;
        $tarif_resep = 0;
        $biaya_reg = 0;

        foreach ($data1 as $key => $val1) {
           $nomor = substr($val1->KELAS, 0,1);
           $huruf = substr($val1->KELAS, -1);
           $kelas = romanic_number($nomor)." ".$huruf;
           $jumlah = $val1->DIRAWAT_SELAMA * $val1->BIAYA;
           $jasa = $val1->JASA_SARANA;
           $visite = $val1->BIAYA_VISITE;
           $biaya_reg = $val1->BIAYA_REG;
    ?>
        <tr>
            <td style="width: 300px;">Kamar Rawat Inap</td>
            <td style="width:80px; text-align: center;"><?php echo $kelas; ?></td>
            <td style="width:80px; text-align: center;"><?php echo $val1->DIRAWAT_SELAMA; ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($val1->BIAYA,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($jumlah,0,',','.'); ?></td>
        </tr>
        <tr>
            <td style="width: 300px;">Jasa Sarana RS</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width:90px; text-align: right;"><?php echo number_format($jasa,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($jasa,0,',','.'); ?></td>
        </tr>
        <tr>
            <td style="width: 300px;">Visite Dokter</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width:90px; text-align: right;"><?php echo number_format($visite,0,',','.'); ?></td>
            <td style="width:90px; text-align: right;"><?php echo number_format($visite,0,',','.'); ?></td>
        </tr>
    <?php  
        }
    ?>
    <?php
        foreach ($data2 as $key => $val2) {
            $tarif_tdk += $val2->TARIF;
    ?>
        <tr>
            <td><?php echo $val2->NAMA_TINDAKAN; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($val2->TARIF,0,',','.'); ?></td>
            <td style="text-align: right;"><?php echo number_format($val2->TARIF,0,',','.'); ?></td>
        </tr>
    <?php
        }
    ?>
    <?php
        foreach ($data3 as $key => $val3) {
            $tarif_lab += $val3->SUBTOTAL;
    ?>
        <tr>
            <td><?php echo $val3->NAMA_PEMERIKSAAN; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($val3->SUBTOTAL,0,',','.'); ?></td>
            <td style="text-align: right;"><?php echo number_format($val3->SUBTOTAL,0,',','.'); ?></td>
        </tr>
    <?php
        } 
    ?>
    <?php
        foreach ($data4 as $key => $val4) {
            $tarif_resep += $val4->SUBTOTAL;
    ?>
        <tr>
            <td><?php echo $val4->NAMA_OBAT; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($val4->SUBTOTAL,0,',','.'); ?></td>
            <td style="text-align: right;"><?php echo number_format($val4->SUBTOTAL,0,',','.'); ?></td>
        </tr>
    <?php
        }
    ?>
        <tr>
            <td>Administrasi</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style="text-align: right;"><?php echo number_format($biaya_reg,0,',','.'); ?></td>
        </tr>
    <tr>
        <td colspan="4" style="text-align: right;">Subtotal</td>
        <?php
        $subtotal = $jumlah + $jasa + $visite + $tarif_tdk + $tarif_lab + $tarif_resep + $biaya_reg;
        ?>
        <td style="text-align: right;">
            <?php echo number_format($subtotal,0,',','.'); ?>    
        </td>
    </tr>
    </tbody>
</table>

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