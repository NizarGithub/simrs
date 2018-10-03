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
			<img src="<?php echo $logo; ?>" style="width: 90px; height: 90px;">
		</td>
		<td style="text-align: right; vertical-align: top;">
			<b><?php echo $row->NAMA; ?></b><br>
			<p style="font-size: 11px; padding-left: 190px;">
			<?php echo $row->ALAMAT; ?><br>
			Telp. (031) <?php echo $row->TELEPON; ?><br>
			Fax (031) <?php echo $row->FAX; ?>
			</p>
		</td>
	</tr>
</table>

<br>

<table align="center">
	<tr>
		<td style="font-size:14px; text-align: center;">
			<b><u>SURAT KETERANGAN SEHAT</u></b>
		</td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>Yang bertanda tangan dibawah ini dokter RS. Anak dan Bersalin SOERYA menerangkan bahwa :</td>
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
		<td style="vertical-align: top;">Alamat</td>
		<td style="vertical-align: top;">:</td>
		<td style="word-wrap: break-word;"><?php echo $data1->ALAMAT; ?></td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td colspan="3">Berdasarkan pemeriksaan :</td>
	</tr>
	<tr>
		<td>Pengelihatan</td>
		<td colspan="2">:</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>a. Pakai Kacamata</td>
		<td>: <?php echo $data1->PAKAI_KACA_MATA; ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>b. Tidak Pakai Kacamata</td>
		<td>: <?php echo $data1->TIDAK_PAKAI_KACA_MATA; ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>c. Buta Warna</td>
		<td>: <?php echo $data1->BUTA_WARNA; ?></td>
	</tr>
	<tr>
		<td>Pendengaran</td>
		<td colspan="2">: <?php echo $data1->PENDENGARAN; ?></td>
	</tr>
	<tr>
		<td>Tinggi Badan</td>
		<td colspan="2">: <?php echo $data1->TINGGI_BADAN; ?> cm</td>
	</tr>
	<tr>
		<td>Berat Badan</td>
		<td colspan="2">: <?php echo $data1->BERAT_BADAN; ?> kg</td>
	</tr>
	<tr>
		<td>Tensi</td>
		<td colspan="2">: <?php echo $data1->TENSI; ?></td>
	</tr>
	<tr>
		<td>Nadi</td>
		<td colspan="2">: <?php echo $data1->NADI; ?></td>
	</tr>
	<tr>
		<td colspan="3">Dinyatakan bahwa yang diperiksa :</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" <?php if($data1->DINYATAKAN == 'A'){ echo ""; }else{echo "style='text-decoration: line-through;'";} ?> >a. Sehat untuk bekerja</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" <?php if($data1->DINYATAKAN == 'B'){ echo ""; }else{echo "style='text-decoration: line-through;'";} ?> >b. Tidak sehat untuk bekerja</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td colspan="2" <?php if($data1->DINYATAKAN == 'C'){ echo ""; }else{echo "style='text-decoration: line-through;'";} ?> >c. Memenuhi syarat hanya untuk pekerjaan tertentu</td>
	</tr>
	<tr>
		<td>Untuk keperluan</td>
		<td colspan="2">: <?php echo $data1->UNTUK_KEPERLUAN; ?></td>
	</tr>
</table>

<br>

<table align="left">
	<tr>
		<td>
			Demikian surat keterangan dokter ini dibuat agar dapat dipergunakan sebagaimana semestinya.
		</td>
	</tr>
</table>

<br>

<div class="footer">
	<table align="right">
		<tr>
			<td style="text-align: center;">
				Sidoarjo, <?php echo date('d-m-Y'); ?>
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
    $width_custom = 5.90551;
    $height_custom = 8.26772;
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