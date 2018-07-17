<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=Data_supplier_barang.xls");
?>

<br/>
<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center;" width="50">No</th>
            <th style="height:30px; text-align:center;">Nama Supplier</th>
            <th style="height:30px; text-align:center;">Jenis Barang</th>
            <th style="height:30px; text-align:center;">Merk</th>
            <th style="height:30px; text-align:center;">Alamat</th>
            <th style="height:30px; text-align:center;">Email</th>
            <th style="height:30px; text-align:center;">Telepon</th>
            <th style="height:30px; text-align:center;">Aksi</th>
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
			<td style="height:25px;"><?php echo $val->NAMA_SUPPLIER; ?></td>
			<td style="height:25px;"><?php echo $val->KODE_SUPPLIER; ?></td>
			<td style="height:25px;"><?php echo $val->JENIS_BARANG; ?></td>
			<td style="height:25px;"><?php echo $val->MERK; ?></td>
			<td style="height:25px;"><?php echo $val->ALAMAT; ?></td>
			<td style="height:25px;"><?php echo $val->EMAIL; ?></td>
			<td style="height:25px;"><?php echo $val->TELEPON; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>