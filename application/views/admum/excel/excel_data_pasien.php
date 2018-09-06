<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=$filename.xls");
?>

<br/>

<table>
	<tr>
		<td colspan="3" style="text-align: center;">Laporan Pasien Per Poli</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; font-weight:bold;">No</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Nama Poli</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Jumlah Pasien</th>
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
			<td style="height:25px;"><?php echo $val->NAMA_POLI; ?></td>
			<td style="height:25px; text-align: center;"><?php echo $val->JUMLAH_PASIEN; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>