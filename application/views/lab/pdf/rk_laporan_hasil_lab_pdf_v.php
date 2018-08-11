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
?>

<table align="left">
	<tr>
		<td>
			<img src="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png" style="width:75px; height:75px;">
		</td>
        <td>&nbsp;</td>
        <td>
            <b style="font-size:16px;">My Company</b><br/>
            <h5>
                My Company Address
                <br/>
                (031) 123456
            </h5>
        </td>
	</tr>
</table>

<br/>
<hr>
<br/>

<?php if($view == 'rj'){ ?>
<table align="left">
    <tr>
        <td style="width:120px;">No. RM</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data1->KODE_PASIEN; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Tanggal</td>
        <td>:</td>
        <td style="width:175px;"><?php echo $data1->HARI; ?>, <?php echo formatTanggal($data1->TANGGAL); ?></td>
    </tr>
    <tr>
        <td style="width:120px;">Nama Pasien</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data1->NAMA; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">No. Lab</td>
        <td>:</td>
        <td style="width:175px;"><?php echo $data3->KODE_LAB; ?></td>
    </tr>
    <tr>
        <td style="width:120px;">Umur</td>
        <td>:</td>
        <td style="width:130px;"><?php echo $data1->UMUR; ?> Tahun</td>
    </tr>
    <tr>
        <td style="width:120px;">Jenis Kelamin</td>
        <td>:</td>
        <td style="width:130px;">
            <?php
                if($data1->JENIS_KELAMIN == 'L'){
                    echo "Laki - Laki";
                }else{
                    echo "Perempuan";
                }
            ?>
        </td>
    </tr>
</table>
<?php }else if($view == 'igd'){ ?>
<table align="left">
    <tr>
        <td style="width:70px;">No. RM</td>
        <td>:</td>
        <td style="width:150px;"><?php echo $data1->KODE_PASIEN; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Jenis Kelamin</td>
        <td>:</td>
        <td style="width:175px;">
        <?php
            if($data1->JENIS_KELAMIN == 'L'){
                echo "Laki - Laki";
            }else{
                echo "Perempuan";
            }
        ?>  
        </td>
    </tr>
    <tr>
        <td style="width:70px;">Pasien</td>
        <td>:</td>
        <td style="width:150px;"><?php echo $data1->NAMA_PASIEN; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Umur</td>
        <td>:</td>
        <td style="width:175px;"><?php echo $data1->UMUR; ?> Tahun</td>
    </tr>
    <tr>
        <td style="width:70px;">Agama</td>
        <td>:</td>
        <td style="width:150px;"><?php echo $data1->AGAMA; ?></td>
        <td style="width:205px;">&nbsp;</td>
        <td style="width:85px;">Tgl Masuk</td>
        <td>:</td>
        <td style="width:175px;"><?php echo formatTanggal($data1->TANGGAL); ?></td>
    </tr>
</table>
<?php } ?>

<br/>

<table align="left">
    <tr>
        <td style="width:85px;">No. Lab</td>
        <td>:</td>
        <td style="width:175px;"><?php echo $data3->KODE_LAB; ?></td>
    </tr>
</table>

<br/>

<table align="left" class="tabel">
    <thead>
        <tr>
            <th>PEMERIKSAAN</th>
            <th>HASIL</th>
            <th>NILAI RUJUKAN</th> 
            <th>TARIF</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $total = 0;
        foreach ($data2 as $value) {
            $total += $value->TARIF;
    ?>
        <tr>
            <td style="width:210px;"><?php echo $value->NAMA_PEMERIKSAAN; ?></td>
            <td style="width:150px; text-align:center;"><?php echo $value->HASIL; ?></td>
            <td style="width:200px; text-align:center;"><?php echo $value->NILAI_RUJUKAN; ?></td>
            <td style="width:100px; text-align:right;"><?php echo number_format($value->TARIF,0,'.',','); ?></td>
        </tr>
    <?php
        }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; font-size: 11px;">TOTAL</td>
            <td style="text-align: right; font-weight: bold; font-size: 11px;"><?php echo number_format($total,0,'.',','); ?></td>
        </tr>
    </tfoot>
</table>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 11.69;
    $height_custom = 8.27;
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
    $html2pdf = new HTML2PDF('L','A5','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>