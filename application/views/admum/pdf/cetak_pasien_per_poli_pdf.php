<?php  
	ob_start();
?>

<style>
.grid th {
	background: #1793d1;
	vertical-align: middle;
	color : #FFF;
	width: 100px;
    text-align: center;
    height: 40px;
}
.grid td {
	background: #FFFFFF;
	vertical-align: middle;
	font: 11px/15px sans-serif;
	font-size: 11px;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
	width: 800px;
    border-spacing: 0;
}
</style>

<br/>

<table align="center">
	<tr>
		<td style="text-align: center;">Laporan Pasien Per Poli</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<table align="center" class="grid">
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
			<td style="height:25px; width: 60%;"><?php echo $val->NAMA_POLI; ?></td>
			<td style="height:25px; text-align: center;"><?php echo $val->JUMLAH_PASIEN; ?></td>
		</tr>
	<?php	
		}
	}
    ?>
    </tbody>
</table>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    // $width_custom = 11.69;
    // $height_custom = 8.27;
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
    $html2pdf = new HTML2PDF('P','A4','en');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>