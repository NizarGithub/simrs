<?PHP  
	ob_start();         
?>

<style type="text/css">
.footer{
    position:absolute;
    left: :0;
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

$sql = "SELECT * FROM admum_setup_logo WHERE POSISI = 'Normal'";
$qry = $this->db->query($sql);
$row = $qry->row();
$logo = '';
if($row->LOGO == ''){
	$logo = base_url().'picture/noimage.png';
}else{
	$logo = base_url().'picture/logo/'.$row->LOGO;
}
?>

<table align="left">
	<tr>
		<td style="padding-top: 0px;">
			<img src="<?php echo $logo; ?>" style="width: 70px; height: 70px;">
		</td>
		<td style="text-align: right; vertical-align: bottom;">
			<b><?php echo $row->NAMA; ?></b><br>
			<p style="font-size: 11px; padding-left: 35px;">
			<?php echo $row->ALAMAT; ?><br>
			Telp. (031) <?php echo $row->TELEPON; ?>, Fax (031) <?php echo $row->FAX; ?>
			</p>
		</td>
	</tr>
</table>

<br><br>

<table align="center">
	<tr>
		<td style="font-size:14px; text-align: center;">
			<b><u>SURAT KETERANGAN RAWAT INAP</u></b>
		</td>
	</tr>
</table>

<br><br>

<table align="left">
	<tr>
		<td>Menerangkan dengan sebenarnya bahwa :</td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>Nama</td>
		<td>:</td>
		<td><?php echo $data1->NAMA; ?></td>
	</tr>
	<tr>
		<td>Umur</td>
		<td>:</td>
		<td><?php echo $data1->UMUR; ?> Tahun</td>
	</tr>
	<tr>
		<td>Jenis Kelamin</td>
		<td>:</td>
		<td><?php echo $data1->JENIS_KELAMIN; ?></td>
	</tr>
	<tr>
		<td style="vertical-align: top;">Alamat</td>
		<td style="vertical-align: top;">:</td>
		<td style="width: 250px; word-wrap: break-word;"><?php echo $data1->ALAMAT; ?></td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>
			<p style="line-height: 20px;">
			Menjalani rawat inap di Rumah Sakit SOERYA mulai <br>
			tanggal : <u><?php echo $data1->MULAI_TANGGAL; ?></u> s/d <u><?php echo $data1->SAMPAI_TANGGAL; ?></u> <br>
			dengan diagnosa : <u><?php echo $data1->PENYAKIT; ?></u>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<p style="line-height: 20px;">Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana semestinya.</p>
		</td>
	</tr>
</table>

<br>

<div class="footer">
	<table align="right">
		<tr>
			<td style="text-align: center;">
				Sidoarjo, <?php echo date('d-m-Y'); ?><br>
				Dokter
			</td>
		</tr>
		<tr>
			<td style="height:50px;">&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				(<?php echo $data1->NAMA_DOKTER; ?>)
			</td>
		</tr>
	</table>
</div>

<?PHP
    // ----ukuran kertas dalam inch----//
    // custom
    $width_custom = 3.92126;
    $height_custom = 6.84252;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    $content = ob_get_clean();
    $width_in_inches = $width_custom;
    $height_in_inches = $height_custom;
    $width_in_mm = $width_in_inches * 25.4;
    $height_in_mm = $height_in_inches * 25.4;
    $html2pdf = new HTML2PDF('P',array($height_in_mm,$width_in_mm),'en');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');
    // $content = ob_get_clean();
    // $html2pdf = new HTML2PDF('L','A4','fr');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');
	exit();
?>