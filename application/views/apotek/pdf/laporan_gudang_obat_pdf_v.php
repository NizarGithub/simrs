<?PHP
	ob_start();
?>

<style>
table{
	width: 14%;
}

.grid th {
	background: #01579B;
	vertical-align: middle;
	color : #fff;
	width: 68px;
    text-align: center;
    height: 30px;
}
.grid td {
	background: #FFFFF0;
	vertical-align: middle;
	/* font: 1px sans-serif; */
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
    border-spacing: 0;
}
.grid tfoot td{
	background: #eeeeee;
	vertical-align: middle;
	color : #003865;
	width: 68px;
    text-align: center;
    height: 30px;
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
<table align="center">
	<tr>
		<td style="font-size:18px; text-align:center; color:#205081; text-transform: uppercase;">
			<b>LAPORAN GUDANG OBAT</b><br>
			<b>BERDASARKAN
				<?php
					if ($urutkan == 'Default') {
					?>
					Semua Data
				<?php
					}elseif ($urutkan == 'Nama Obat') {
					?>
					Nama Obat
				<?php
					}elseif ($urutkan == 'Stok') {
						if ($urutkan_stok == 'Tinggi') {
							?>
							Stok Tertinggi
				<?php
							}else {
							?>
							Stok Terendah
				<?php
							}
					}elseif ($urutkan == 'Expired') {
							?>
					Kadaluarsa
				<?php
					}
				 ?>
			</b>
		</td>
	</tr>
	<!-- <tr>
		<td style="font-size:12px; text-align:center; color:#990033;">
			<b>Bulan <?php //echo $bulan; ?> <?php echo $tahun; ?></b>
		</td>
	</tr> -->
</table>

<br/><br/>

<table class="grid">
	<thead>
		<tr>
			<th style="text-align:center;">No</th>
			<th>Nama Obat</th>
			<th>Jenis Obat</th>
      <th>Status Obat</th>
      <th>Golongan Obat</th>
      <th>Kategori Obat</th>
			<th>Sisa Stok</th>
      <th>Harga Beli</th>
      <th>Harga Jual</th>
			<th>Expired</th>
      <th>Tanggal Masuk</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$no = 0;

		foreach ($dt as $key => $value) {
			$no++;

			$status_obat = "";
			if($value->STATUS_RACIK == 0){
					$status_obat = "Obat Umum";
			}else{
					$status_obat = "Obat Racik";
			}
	?>
		<tr>
			<td><?php echo $no; ?></td>
			<td style="font-size: 12px;">
				<?php echo $value->NAMA_OBAT; ?>
				<br>
				<small><b><?php echo $value->KODE_OBAT; ?></b></small>
			</td>
			<td width="86" style="font-size: 11px; text-align: center;"><?php echo $value->NAMA_JENIS; ?></td>
			<td width="86" style="font-size: 12px;"><?php echo $status_obat ?></td>
			<td width="86" style="font-size: 12px;"><?php echo $value->GOLONGAN_OBAT ?></td>
			<td width="86" style="font-size: 12px;"><?php echo $value->KATEGORI_OBAT ?></td>
			<td style="text-align:center;"><?php echo $value->TOTAL ?></td>
			<td style="text-align:right">Rp. <?php echo number_format($value->HARGA_BELI); ?></td>
			<td style="text-align:right">Rp. <?php echo number_format($value->TOTAL_HARGA); ?></td>
			<td><?php echo formatTanggal($value->KADALUARSA); ?></td>
			<td><?php echo $value->TANGGAL_MASUK ?></td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 20.69;
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
    $html2pdf = new HTML2PDF('L','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>
