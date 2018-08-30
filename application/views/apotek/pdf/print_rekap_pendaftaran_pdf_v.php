<?PHP
	ob_start();
?>
<style>
table{
	width: 14.7%;
	background: #FAEBD7;
	border: 2px solid #C5C5C5;
  /* border-spacing: 0; */
}
th {
	background: #01579B;
	vertical-align: middle;
	color : #fff;
	width: 152px;
  text-align: center;
  height: 30px;
  /* border: 1px solid #C5C5C5; */
}
td {
	background: #FFFFF0;
	vertical-align: middle;
	font: 12px sans-serif;
  height: 30px;
  padding-left: 5px;
  padding-right: 5px;
  /* border: 1px solid #C5C5C5; */
}
</style>
<h3 style="text-align: center;">Rekap Pendaftaran <br>
  Berdasarkan
	<?php
	if ($by == 'semua') {
	?>
		Semua Data
	<?php
  }elseif ($by == 'tanggal') {
	?>
	<?php echo $tanggal_sekarang; ?> - <?php echo $tanggal_sampai; ?>
	<?php
  }elseif ($by == 'poli') {
	?>
		<?php echo $data_row['NAMA_POLI']; ?>
	<?php
		}
	 ?>
</h3>
<table>
        <tr>
            <th style="color:#fff; text-align:center;">No</th>
            <th style="color:#fff; text-align:center;">Tujuan</th>
            <th style="color:#fff; text-align:center;">Total</th>
            <th style="color:#fff; text-align:center;">Jumlah PX</th>
            <th style="color:#fff; text-align:center;">Tunai</th>
            <th style="color:#fff; text-align:center;">Kredit</th>
            <th style="color:#fff; text-align:center;">Retur Obat</th>
        </tr>
        <?php
        $no=0;
        foreach ($data as $d) {
        $no++;
         ?>
        <tr>
           <td><?php echo $no; ?></td>
           <td><?php echo $d['NAMA_POLI']; ?></td>
           <td><?php echo $d['TOTAL_POLI']; ?></td>
           <td></td>
           <td></td>
           <td></td>
           <td></td>
        </tr>
        <?php
        }
       ?>
</table>
<?PHP
    // ----ukuran kertas dalam inch----//
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
    $html2pdf = new HTML2PDF('L','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	exit();
?>
