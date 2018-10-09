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
        <img src="<?php echo base_url(); ?>picture/obat.png" style="width: 100px; height: 90px;">
      </td>
    </tr>
    <tr>
      <td style="text-align:center; font-weight: bold; width: 100%;">Instalasi Farmasi RSIA</td>
    </tr>
    <tr>
      <td style="text-align:center; width: 100%; font-size: 24px;">" SOERYA "</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 13px; width: 100%;">Jl. Kalijaten - 15 Sepanjang Sidoarjo</td>
    </tr>
    <tr>
      <td style="text-align:center; font-size: 12px; width: 100%;">Telp. (031) 788501, Fax (031) 7873633</td>
    </tr>
	</table>
  <br>
  <div style="clear: both;"></div>
    <table style="width: 100%; font-size: 11px;">
      <tr>
        <td style="text-align: left; width: 63%;">Apoteker : <?php echo $row['NAMA_APOTEKER']; ?></td>
        <td style="text-align: right;">SIA.551.41/74/SIA/404.32/2007</td>
      </tr>
    </table>
      <hr style="border-top: 4px double #000;">
    <div style="clear: both;"></div>
	<table>
		<tr>
			<td style="font-size: 18px; font-weight: bold; text-align: center; width: 100%">
				TURUNAN RESEP
			</td>
		</tr>
	</table>
	<div style="clear: both;"></div>
	<table>
		<tr>
			<td style="width: 22%;">Dokter</td>
      <td>:</td>
      <td style="width: 33%;"><?php echo $row['NAMA_DOKTER']; ?></td>
      <td style="width: 16%;">Tertulis tgl</td>
      <td>:</td>
      <td><?php echo date('d-m-Y'); ?></td>
		</tr>
    <tr>
      <td style="width: 22%;">Dibuat</td>
      <td>:</td>
      <td style="width: 33%;"><?php echo $row['TANGGAL_DIBUAT']; ?></td>
      <td style="width: 16%;">No</td>
      <td>:</td>
      <td><?php echo $row['KODE_RESEP']; ?></td>
    </tr>
    <tr>
      <td style="width: 22%;">Pasien</td>
      <td>:</td>
      <td style="width: 33%;"><?php echo $row['NAMA_PASIEN']; ?></td>
      <td style="width: 16%;">Umur</td>
      <td>:</td>
      <td><?php echo $row['UMUR']; ?></td>
    </tr>
    <tr>
      <td style="width: 22%;">Alamat Pasien</td>
      <td>:</td>
      <td><?php echo $row['ALAMAT_PASIEN']; ?></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td style="width: 30%;"></td>
      <td style="width: 16%;">Iter</td>
      <td>:</td>
      <td><?php echo $row['ITER']; ?></td>
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
        $id_resep = $row['ID_ITER'];
        $this->db->select('*');
        $this->db->from('ap_iter_detail');
        // $this->db->join('apotek_gudang_obat', 'apotek_gudang_obat.ID = ap_iter_detail.ID_OBAT');
        $this->db->join('admum_setup_nama_obat', 'admum_setup_nama_obat.ID = ap_iter_detail.ID_OBAT');
        $this->db->where('ID_ITER', $id_resep);

        $dt = $this->db->get()->result();

        foreach ($dt as $key => $val) {
      ?>
        <tr>
          <td style="font-size: 14px;"><?php echo $val->NAMA_OBAT; ?></td>
          <td style="font-size: 14px;">, x <?php echo $val->JUMLAH_BELI; ?></td>
          <!-- <td style="font-size: 14px;">, <?php //echo $val->TAKARAN; ?></td>
          <td style="font-size: 14px;">, <?php //echo $val->ATURAN_MINUM; ?></td> -->
        </tr>
        <tr>
          <td>
            <br>
          </td>
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
      <td>Pcc</td>
      <td colspan="3"> : </td>
    </tr>
    <tr>
      <td>
        <br>
      </td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <td style="width: 55%;"></td>
      <td>Sepanjang
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
