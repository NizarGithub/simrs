<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_kasus_diagnosa.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center;" width="50">No</th>
            <th style="height:30px; text-align:center;">Kode</th>
	        <th style="height:30px; text-align:center;">Nama Kasus</th>
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
			<td style="height:25px;"><?php echo $val->KODE; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA_KASUS; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>