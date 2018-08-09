<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_poli.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; font-weight:bold;">No</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Nama Poli</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Inisial Poli</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Jenis</th>
            <th style="height:30px; text-align:center; font-weight:bold;">Nama Dokter</th>
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
			<td style="height:25px;"><?php echo $val->NAMA; ?></td>
			<td style="height:25px;"><?php echo $val->INITIAL_POLI; ?></td>
			<td style="height:25px;"><?php echo $val->JENIS; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA_DOKTER; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>