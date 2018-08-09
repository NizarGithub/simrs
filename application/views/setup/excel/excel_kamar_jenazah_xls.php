<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_kamar_jenazah.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center;" width="50">No</th>
            <th style="height:30px; text-align:center;">Kode Kamar</th>
            <th style="height:30px; text-align:center;">Nama Kamar</th>
            <th style="height:30px; text-align:center;">Biaya</th>
            <th style="height:30px; text-align:center;">Jumlah Lemari</th>
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
			<td style="height:25px;"><?php echo $val->KODE_KAMAR; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA_KAMAR; ?></td>
			<td style="height:25px;"><?php echo $val->BIAYA; ?></td>
			<td style="height:25px;"><?php echo $val->JUMLAH_LEMARI; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>