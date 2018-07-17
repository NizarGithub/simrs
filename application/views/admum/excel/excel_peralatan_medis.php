<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_peralatan_medis.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center;" width="50">No</th>
            <th style="height:30px; text-align:center;">Kode Barang</th>
            <th style="height:30px; text-align:center;">Barcode</th>
            <th style="height:30px; text-align:center;">Nama Barang</th>
            <th style="height:30px; text-align:center;">Merk</th>
            <th style="height:30px; text-align:center;">Jenis Alat</th>
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
			<td style="height:25px;"><?php echo $val->KODE_ALAT; ?></td>
			<td style="height:25px;"><?php echo $val->BARCODE; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA_ALAT; ?></td>
			<td style="height:25px;"><?php echo $val->MERK; ?></td>
			<td style="height:25px;"><?php echo $val->JENIS_ALAT; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>