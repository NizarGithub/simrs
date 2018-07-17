<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=$filename.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; font-weight:bold;">No</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Kode Pasien</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Nama Pasien</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Tanggal Lahir</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Umur</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Alamat</th>
        </tr>
    </thead>
    <tbody>
    <?php
	if($dt == "" || $dt == null){

	}else{
		$no = 0;

		foreach ($dt as $val) {
			$no++;
	?>
		<tr>
			<td style="height:25px; text-align:center;"><?php echo $no; ?></td>
			<td style="height:25px;"><?php echo $val->KODE_PASIEN; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA; ?></td>
			<td style="height:25px;"><?php echo $val->TANGGAL_LAHIR; ?></td>
			<td style="height:25px;"><?php echo $val->UMUR; ?> Tahun</td>
			<td style="height:25px;"><?php echo $val->ALAMAT; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>