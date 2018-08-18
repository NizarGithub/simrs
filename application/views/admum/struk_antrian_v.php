<?php 
ob_start();
date_default_timezone_set('Asia/Jakarta');
$dt = $this->master_model_m->getJmlAntrianStruk($kode_antrian,$status,$id_user);
$tanggal = date('d-m-Y');
$sql = "SELECT COUNT(*) AS TOTAL FROM rk_antrian_pasien WHERE TANGGAL = '$tanggal' AND STATUS_CLOSING = '0' AND STATUS_PANGGIL = '0'";
$query = $this->db->query($sql);
$total = $query->row()->TOTAL;
?>

<hr>
<table align="center">
    <tr>
    	<td style="text-align:center; font-size: 10px;">My Company</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">My Company Address</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Telp. (031) 123456</td>
    </tr>
</table>
<hr>

<table align="center">
<?php
	foreach ($dt as $key => $val) {
?>
    <tr>
    	<td style="text-align:center; font-size: 10px;"><?php echo $val->TGL; ?>, <?php echo date('H:i:s'); ?></td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Helpdesk</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Nomor Antrian Anda :</td>
    </tr>
    <tr>
      	<td style="">&nbsp;</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 37px;"><?php echo $loket; ?>-<?php echo $val->URUT; ?></td>
    </tr>
    <tr>
      	<td style="">&nbsp;</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Jumlah Antrian yg belum dipanggil : <?php echo $total; ?></td>
    </tr>
<?php
	}
?>
</table>

<table align="center">
    <tr>
    	<td style="text-align:center; font-size: 10px;">= Terima Kasih =</td>
    </tr>
    <tr>
    	<td style="text-align:center; font-size: 10px;">Atas kunjungan Anda</td>
    </tr>
</table>

<?php
//----ukuran kertas dalam inch----//
// custom
$width_custom = 3.14961; //80 mm
$height_custom = 3.14961; //80 mm
//A2
// $width_a2 = 23.4;
// $height_a2 = 16.5;
//------------------------//
$content = ob_get_clean();
$width_in_inches = $width_custom;
$height_in_inches = $height_custom;
$width_in_mm = $width_in_inches * 25.4;
$height_in_mm = $height_in_inches * 25.4;
$html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
$html2pdf->pdf->SetTitle($settitle);
$html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
$html2pdf->Output($filename.'.pdf');

// $content = ob_get_clean();
// $html2pdf = new HTML2PDF('L','A5','fr');
// $html2pdf->pdf->SetTitle($settitle);
// $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
// $html2pdf->Output($filename.'.pdf');

// exit();
?>