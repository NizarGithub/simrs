<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=$filename.xls");
?>

<style type="text/css">
.judul{
	font-weight: bold;
}

.thead{
	background-color: #ed1c24;
	color: #fff;
	height:30px; 
	text-align:center; 
	font-weight:bold;
	padding-top: 5px;
	padding-bottom: 5px;
}

.td_judul{
	height:25px;
	font-weight: bold;
	padding-top: 5px;
	padding-bottom: 5px;
	padding-left: 10px;
	vertical-align: middle;
}

.td{
	height:25px;
	padding-top: 5px;
	padding-bottom: 5px;
	vertical-align: middle;
}
</style>

<table>
	<tr>
		<td colspan="5" class="judul">DAFTAR TARIF POLI UNTUK RAWAT JALAN</td>
	</tr>
	<tr>
		<td colspan="5" class="judul">RSIA SOERYA</td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
</table>

<table border="1">
    <thead>
        <tr>
            <th class="thead">No</th>
            <th class="thead">Nama Poli</th>
            <th class="thead">Jenis</th>
            <th class="thead">Keterangan</th>
            <th class="thead">Biaya</th>
        </tr>
    </thead>
    <tbody>
    <?php
	if($dt == "" || $dt == null){

	}else{
		foreach ($dt as $v) {
			$nama = $v->NAMA;
	?>
		<tr>
			<td colspan="5" class="td_judul"><?php echo $v->NAMA; ?></td>
		</tr>
		<?php
			$data = $this->model->data_cetak_poli($nama);
			$no = 0;
			foreach ($data as $key => $val) {
				$no++;
				$jenis = $val->JENIS.' - '.$val->STATUS;
		?>
			<tr>
				<td class="td" style="text-align:center;"><?php echo $no; ?></td>
				<td class="td" colspan="2"><?php echo $jenis; ?></td>
				<td class="td"><?php echo $val->KETERANGAN; ?></td>
				<td class="td" style="text-align: right;"><?php echo number_format($val->BIAYA,0,',','.'); ?></td>
			</tr>
		<?php
			}
		?>
	<?php	
		}
	}
    ?>
    </tbody>
</table>