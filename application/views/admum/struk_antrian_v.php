<?php 
ob_start();
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('d-m-Y');
$s = "SELECT * FROM rk_antrian_pasien WHERE TANGGAL = '$tanggal' AND STATUS_CLOSING = '0' AND STATUS_PANGGIL = '0' ORDER BY ID DESC LIMIT 1";
$q = $this->db->query($s);
$dt = $q->result();

$sql = "SELECT COUNT(*) AS TOTAL FROM rk_antrian_pasien WHERE TANGGAL = '$tanggal' AND STATUS_CLOSING = '0' AND STATUS_PANGGIL = '0'";
$query = $this->db->query($sql);
$total = $query->row()->TOTAL;

$sql_rs = "SELECT * FROM admum_setup_logo WHERE POSISI = 'Normal'";
$qry_rs = $this->db->query($sql_rs);
$data_rs = $qry_rs->row();
$logo = '';
if($data_rs->LOGO == null || $data_rs->LOGO == ""){
  $logo = base_url().'picture/noimage.png';
}else{
  $logo = base_url().'picture/logo/'.$data_rs->LOGO;
}
?>

<hr>
<table align="left">
  <tr>
    <td style="padding-top: 0px;">
      <img src="<?php echo $logo; ?>" style="width: 40px; height: 40px;">
    </td>
    <td style="vertical-align: bottom;">
      <b><?php echo $data_rs->NAMA; ?></b><br>
      <p style="font-size: 11px;">
      <?php echo $data_rs->ALAMAT; ?><br>
      Telp. (031) <?php echo $data_rs->TELEPON; ?>, Fax (031) <?php echo $data_rs->FAX; ?>
      </p>
    </td>
  </tr>
</table>
<hr>

<table align="center">
<?php
	foreach ($dt as $key => $val) {
?>
    <tr>
    	<td style="text-align:center; font-size: 10px;"><?php echo $val->TANGGAL; ?>, <?php echo date('H:i:s'); ?></td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Nomor Antrian Anda :</td>
    </tr>
    <tr>
      	<td style="">&nbsp;</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 36px;"><?php echo $val->KODE_ANTRIAN; ?>-<?php echo $val->NOMOR_ANTRIAN; ?></td>
    </tr>
    <tr>
      	<td style="">&nbsp;</td>
    </tr>
    <tr>
      	<td style="text-align:center; font-size: 10px;">Jumlah Antrian yg belum dipanggil : <?php echo $total-1; ?></td>
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