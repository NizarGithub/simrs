<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=$filename.xls");
?>
<br/>

<table>
	<tr>
		<td colspan="8" style="text-align: center;">
			DAFTAR TARIF KAMAR DAN VISITE DOKTER <br>
			UNTUK RAWAT INAP RSIA SOERYA
		</td>
	</tr>
	<tr><td colspan="8">&nbsp;</td></tr>
</table>

<table border="1">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;" width="50">No</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Kode Kamar</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Kelas</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Biaya</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Visite Dokter Sp.</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Biaya Visite</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Jasa Sarana RS</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Peruntukan Kamar</th>
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
			<td style="height:25px; text-align:center;"><?php echo $val->KODE_KAMAR; ?></td>
			<td style="height:25px; text-align:center;"><?php echo $val->KELAS; ?></td>
			<td style="height:25px; text-align: right;"><?php echo number_format($val->BIAYA,0,',','.'); ?></td>
			<td style="height:25px; text-align:center;"><?php echo $val->VISITE_DOKTER; ?></td>
			<td style="height:25px; text-align: right;"><?php echo number_format($val->BIAYA_VISITE,0,',','.'); ?></td>
			<td style="height:25px; text-align: right;"><?php echo number_format($val->JASA_SARANA,0,',','.'); ?></td>
			<td style="height:25px;"><?php echo $val->PERUNTUKAN_KAMAR; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>