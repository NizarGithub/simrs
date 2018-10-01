<?PHP  
	ob_start();         
?>

<style type="text/css">
.footer{
    position:absolute;
    right: :0;
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

<hr style="border-top: double 1px;">

<table align="center">
	<tr>
		<td style="font-size:14px; text-align: center;">
			<b>PENGANTAR PASIEN MASUK RUMAH SAKIT<br>(RAWAT INAP)</b>
		</td>
	</tr>
</table>

<br>

<table align="right">
	<tr>
		<td><?php echo $data1->KODE_SURAT_PENGANTAR_RI; ?></td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>Dengan ini menerangkan :</td>
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
		<td>TB/BB</td>
		<td>:</td>
		<td><?php echo $data1->TINGGI_BADAN; ?> cm / <?php echo $data1->BERAT_BADAN; ?> kg</td>
	</tr>
	<tr>
		<td>Jenis Kelamin</td>
		<td>:</td>
		<td><?php echo $data1->JENIS_KELAMIN; ?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>:</td>
		<td><?php echo $data1->ALAMAT; ?></td>
	</tr>
</table>

<br><br>

<table align="left">
	<tr>
		<td colspan="2">Untuk masuk rumah sakit / menjalani rawat inap, dengan :</td>
	</tr>
	<tr>
		<td>Dx</td>
		<td>: <?php echo $data1->DIAGNOSA; ?></td>
	</tr>
	<tr>
		<td>Tx</td>
		<td>: <?php echo $data1->TERAPI; ?></td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>
			Keterangan :<br>
			Surat pengantar ini harus selalu terlampir pada status rawat inap.
		</td>
	</tr>
</table>

<br>

<div class="footer">
	<table align="left">
		<tr>
			<td style="width:200px;">
				Tanggal, <?php echo date('d-m-Y'); ?><br>
				Pengirim
			</td>
		</tr>
		<tr>
			<td style="height:50px; width:200px;">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:200px;">
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