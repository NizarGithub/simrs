<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_satuan_barang.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center;" width="50">No</th>
            <th style="height:30px; text-align:center;">Kode Satuan</th>
            <th style="height:30px; text-align:center;">Nama Satuan</th>
            <th style="height:30px; text-align:center;">Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
	if($dt == "" || $dt == null){

	}else{
		$no = 0;

		foreach ($dt as $val) {
			$no++;

			$stt = "";
			if($val->AKTIF == 1){
				$stt = "Aktif";
			}else{
				$stt = "Tidak Aktif";
			}
	?>
		<tr>
			<td style="height:25px; text-align:center;"><?php echo $no; ?></td>
			<td style="height:25px;"><?php echo $val->KODE_SATUAN; ?></td>
			<td style="height:25px;"><?php echo $val->NAMA_SATUAN; ?></td>
			<td style="height:25px;"><?php echo $stt; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>