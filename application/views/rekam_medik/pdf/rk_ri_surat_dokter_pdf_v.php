<?PHP  
	ob_start();         
?>

<?php
function formatTanggal($tgl){
	//22-11-2016
	$d = substr($tgl,0,2);
	$m = substr($tgl,3,2);
	$y = substr($tgl,6);

	$strBulan = "";

	if($m == '01'){
		$strBulan = "Januari";
	}else if($m == '02'){
		$strBulan = "Februari";
	}else if($m == '03'){
		$strBulan = "Maret";
	}else if($m == '04'){
		$strBulan = "April";
	}else if($m == '05'){
		$strBulan = "Mei";
	}else if($m == '06'){
		$strBulan = "Juni";
	}else if($m == '07'){
		$strBulan = "Juli";
	}else if($m == '08'){
		$strBulan = "Agustus";
	}else if($m == '09'){
		$strBulan = "September";
	}else if($m == '10'){
		$strBulan = "Oktober";
	}else if($m == '11'){
		$strBulan = "November";
	}else if($m == '12'){
		$strBulan = "Desember";
	}

	return $d." ".$strBulan." ".$y;
}
?>

<table align="left">
    <tr>
    	<td style="text-align:left;">My Company</td>
    </tr>
    <tr>
      	<td style="text-align:left;">My Company Address</td>
    </tr>
    <tr>
      	<td style="text-align:left;">Telp. (031) 123456</td>
    </tr>
</table>

<br/>

<table align="center">
	<tr>
		<td style="font-size:18px;">
			<b><u>SURAT KETERANGAN</u></b>
		</td>
	</tr>
</table>

<br/><br/>

<table align="left">
	<tr>
		<td>Yang bertanda tangan dibawah ini menerangkan bahwa</td>
	</tr>
</table>

<br/>

<table align="left">
	<tr>
		<td style="width:50px;">&nbsp;</td>
		<td>Nama</td>
		<td>:</td>
		<td><?php echo $data1->NAMA; ?></td>
	</tr>
	<tr>
		<td style="width:50px;">&nbsp;</td>
		<td>Umur</td>
		<td>:</td>
		<td><?php echo $data1->UMUR; ?> Tahun</td>
	</tr>
	<tr>
		<td style="width:50px;">&nbsp;</td>
		<td>Pekerjaan</td>
		<td>:</td>
		<td>
			<?php 
				$data1->PEKERJAAN = $data1->PEKERJAAN==null?"-":$data1->PEKERJAAN;
				echo $data1->PEKERJAAN; 
			?>
		</td>
	</tr>
	<tr>
		<td style="width:50px;">&nbsp;</td>
		<td>Alamat</td>
		<td>:</td>
		<td><?php echo $data1->ALAMAT; ?></td>
	</tr>
</table>

<br/>

<table align="left">
	<tr>
		<td>
			Oleh karena SAKIT, perlu diberikan ISTIRAHAT selama <?php echo $data1->WAKTU_ISTIRAHAT; ?> hari
			terhitung mulai tanggal <?php echo $data1->MULAI_TANGGAL; ?> s/d <?php echo $data1->SAMPAI_TANGGAL; ?>
		</td>
	</tr>
	<tr>
		<td>Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dipergunakan semestinya.</td>
	</tr>
</table>

<br/><br/><br/><br/><br/>

<table align="right">
	<tr>
		<td style="text-align:left; width:200px;">
			Malang, <?php echo formatTanggal(date('d-m-Y')); ?>
		</td>
	</tr>
	<!-- <tr>
		<td style="text-align:center; width:200px;">Nama Jelas,</td>
	</tr>
	<tr>
		<td style="height:50px; width:200px;">&nbsp;</td>
	</tr>
	<tr>
		<td style="text-align:center; width:200px;">
			(<?php //echo $data1->NAMA_PEGAWAI; ?>)
		</td>
	</tr>
	<tr>
		<td style="text-align:center; width:200px;">
			NIP. <?php// echo $data1->NIP; ?>
		</td>
	</tr> -->
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