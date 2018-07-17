<?PHP  
	ob_start();         
?>

<style>

.grid th {
	background: #4bad31;
	vertical-align: middle;
	color : #fff;
	width: 90px;
    text-align: center;
    height: 30px;
}
.grid td {
	background: #FFFFF0;
	vertical-align: middle;
	font: 12px sans-serif;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
    border-spacing: 0;
}
.grid tfoot td{
	background: #eeeeee;
	vertical-align: middle;
	color : #003865;
	width: 90px;
    text-align: center;
    height: 30px;
}
</style>

<table align="center">
	<tr>
		<td style="font-size:18px; text-align:center; color:#205081;">
			<b>LAPORAN PENJUALAN</b>
		</td>
	</tr>
	<!-- <tr>
		<td style="font-size:12px; text-align:center; color:#990033;">
			<b>Bulan <?php //echo $bulan; ?> <?php echo $tahun; ?></b>
		</td>
	</tr> -->
</table>

<br/><br/>

<table class="grid">
	<thead>
		<tr>
			<th width="50" style="text-align:center;">No</th>
			<th width="200" style="text-align:center;">Nama Obat</th>
			<th width="180" style="text-align:center;">Harga</th>
			<th width="100" style="text-align:center;">Qty</th>
			<th width="200" style="text-align:center;">Subtotal</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$no = 0;
		$tot_qty = 0;
		$tot_subtot = 0;

		foreach ($dt as $key => $value) {
			$no++;
			$tot_qty += $value->JUMLAH_BELI;
			$tot_subtot += $value->SUBTOTAL;
	?>
		<tr>
			<td style="text-align:center;"><?php echo $no; ?></td>
			<td>
				<?php echo $value->NAMA_OBAT; ?>
				<br>
				<small><b><?php echo $value->KODE_OBAT; ?></b></small>
			</td>
			<td style="text-align:right;"><?php echo number_format($value->HARGA,0,'.',','); ?></td>
			<td style="text-align:center;"><?php echo $value->JUMLAH_BELI; ?></td>
			<td style="text-align:right;"><?php echo number_format($value->SUBTOTAL,0,'.',','); ?></td>
		</tr>
	<?php
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3"><b>TOTAL</b></td>
			<td style="text-align:center;"><b><?php echo $tot_qty; ?></b></td>
			<td style="text-align:right;"><b><?php echo number_format($tot_subtot,0,'.',','); ?></b></td>
		</tr>
	</tfoot>
</table>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 11.69;
    $height_custom = 8.27;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>