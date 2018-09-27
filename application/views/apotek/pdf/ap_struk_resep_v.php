<?php
  ob_start();
?>
<style type="text/css">
.struk{
    line-height: normal;
    width: 10cm;
	/*height:123.7mm;*/
	margin-left:auto;
	margin-right:auto;
}
table{
   width: 100%;
}
.square {
    background: #fff;
    width: 15px;
    height: 13px;
    border: 1px solid #000;
    display: inline;
}
.checkbox {
  width:20px;
  height:20px;
  border: 1px solid #000;
  display: inline-block;
}
/* This is what simulates a checkmark icon */
.checkbox.checked:after {
  content: '';
  display: block;
  width: 4px;
  height: 7px;
  /* "Center" the checkmark */
  position:relative;
  top:4px;
  left:7px;
  border: solid #000;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.footer{
    position:absolute;
    left:0;
    bottom:0;
}
</style>
<div class="struk">
	<table style="width: 75%;">
    <tr>
      <td style="text-align:center; width: 10%;" rowspan="7">
        <img src="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png" style="width: 100px; height: 100px;">
      </td>
    </tr>
    <tr>
      <td style="text-align:center; font-weight: bold; width: 100%;">Nama RS</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 13px; width: 100%;">Alamat</td>
    </tr>
    <tr>
      <td style="text-align:center; width: 100%;">S I D O A R J O</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 12px; width: 100%;">Telepon</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 12px; width: 100%;">No Ijin</td>
    </tr>
	</table>
  <br>
  <table>
    <tr>
      <td style="width: 100%;"><hr style="border-top: 3px double #000;"></td>
    </tr>
  </table>
	<table>
		<tr>
			<td style="font-size: 18px; font-weight: bold; text-align: center; width: 100%">
				<i>Bismillahhirrohmanirrohim</i>
			</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<td>Alergi / Obat :</td>
			<td style="width: 175px;">&nbsp;</td>
			<td><div class="square">&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp; RANAP</td>
		</tr>
		<tr>
			<td>
        <?php
          if ($row['ALERGI_OBAT'] == 'Iya') {
        ?>
        Ya &nbsp;<div class="square">&nbsp;v&nbsp;&nbsp;</div>&nbsp;
				Tidak &nbsp;<div class="square">&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <?php
          }else {
        ?>
        Ya &nbsp;<div class="square">&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;
				Tidak &nbsp;<div class="square">&nbsp;v&nbsp;&nbsp;</div>
        <?php
          }
         ?>
			</td>
			<td>&nbsp;</td>
			<td><div class="square">&nbsp;v&nbsp;&nbsp;</div>&nbsp; RAJAL</td>
		</tr>
	</table>
	<br>
	<table>
		<tr>
			<td style="text-align: right; width: 100%;">Sidoarjo,
        <?php
          $tgl = date('d-m-Y');
          $dat = substr($tgl,0,2);
          $m = substr($tgl,3,2);
          $y = substr($tgl,6);
          $strBulan = "";
          if($m == '01'){
            $strBulan = "Januari";
          }else if($m == '02'){
            $strBulan = "Februari";
          }else if($m == '03'){
            $strBulan = "Maret";
          }else if($m == '04'){
            $strBulan = "April";
          }else if($m == '05'){
            $strBulan = "Mei";
          }else if($m == '06'){
            $strBulan = "Juni";
          }else if($m == '07'){
            $strBulan = "Juli";
          }else if($m == '08'){
            $strBulan = "Agustus";
          }else if($m == '09'){
            $strBulan = "September";
          }else if($m == '10'){
            $strBulan = "Oktober";
          }else if($m == '11'){
            $strBulan = "November";
          }else if($m == '12'){
            $strBulan = "Desember";
          }
            $tanggal = $dat." ".$strBulan." ".$y;
         ?>
        <?php echo $tanggal; ?>
      </td>
		</tr>
		<tr>
			<td>Dokter : <?php echo $row['NAMA_PEGAWAI']; ?></td>
		</tr>
	</table>
  <br>
	<table style="border-collapse: collapse;">
		<tbody>
			<tr>
				<td style="font-weight: bold; font-size: 16px;"><i>RI</i></td>
			</tr>
      <tr><td><br></td></tr>
      <?php
        $id_resep = $row['ID_RESEP'];
        $dt = $this->model->detail_resep($id_resep);

        foreach ($dt as $key => $val) {
      ?>
        <tr>
          <td style="font-size: 14px;"><?php echo $val->NAMA_OBAT; ?></td>
          <td style="font-size: 14px;">x <?php echo $val->JUMLAH_BELI; ?></td>
          <td style="font-size: 14px;">, <?php echo $val->TAKARAN; ?></td>
          <td style="font-size: 14px;">, <?php echo $val->ATURAN_MINUM; ?></td>
        </tr>
      <?php
        }
      ?>
		</tbody>
	</table>
</div>
<div class="footer">
  <table>
    <tr>
      <td>Nama</td>
      <td style="width: 43%;">: <?php echo $row['NAMA']; ?></td>
      <td>Umur / BB</td>
      <td>: <?php echo $row['UMUR']; ?> Tahun / -</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>: <?php echo $row['ALAMAT_PASIEN']; ?></td>
      <td>No. RM</td>
      <td>: <?php echo $row['KODE_PASIEN']; ?></td>
    </tr>
    <tr>
      <td>No. HP</td>
      <td>: <?php echo $row['TELEPON_PASIEN']; ?></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
      <td style="text-align: center; width: 63%;" colspan="4"><i>Penggantian resep harus seijin dokter</i></td>
    </tr>
  </table>
</div>
<?PHP
    // ----ukuran kertas dalam inch----//
    // custom
    $width_custom = 4.92126;
    $height_custom = 9.84252;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    $content = ob_get_clean();
    $width_in_inches = $width_custom;
    $height_in_inches = $height_custom;
    $width_in_mm = $width_in_inches * 25.4;
    $height_in_mm = $height_in_inches * 25.4;
    $html2pdf = new HTML2PDF('P',array($height_in_mm,$width_in_mm),'en');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');
    // $content = ob_get_clean();
    // $html2pdf = new HTML2PDF('L','A4','fr');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');
	exit();
?>
