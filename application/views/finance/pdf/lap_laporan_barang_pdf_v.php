<?PHP
	ob_start();
?>

<style>
.grid th {
  background: #1793d1;
  vertical-align: middle;
  color : #FFF;
    text-align: center;
    height: 30px;
    font-size: 12px;
}
.grid td {
  background: #FFFFFF;
  vertical-align: middle;
  font: 11px/15px sans-serif;
  font-size: 11px;
    height: 20px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
  background: #FAEBD7;
  border: 1px solid #C5C5C5;
    border-spacing: 0;
}
</style>

<?php
$sql = "SELECT * FROM admum_setup_logo WHERE ID = '1'";
$qry = $this->db->query($sql);
$row = $qry->row();
$logo = $row->LOGO;
?>

<table align="left">
  <tr>
    <td style="padding-top: 0px;">
      <img src="<?php echo base_url(); ?>picture/logo/<?php echo $logo; ?>" style="width: 70px; height: 70px;">
    </td>
    <td style="text-align: right; vertical-align: bottom;">
      <b><?php echo $row->NAMA; ?></b><br>
      <p style="font-size: 11px; padding-left: 780px;">
      <?php echo $row->ALAMAT; ?><br>
      Telp. (031) <?php echo $row->TELEPON; ?>, Fax (031) <?php echo $row->FAX; ?>
      </p>
    </td>
  </tr>
</table>

<br>

<table align="center">
  <tr>
    <td style="font-size:14px; text-align: center;">
      <b><?php echo $settitle; ?></b>
    </td>
  </tr>
  <tr>
    <td style="font-size:12px; text-align: center;">
      <?php echo $judul; ?>
    </td>
  </tr>
</table>

<br>

<table align="center" class="grid">
    <thead>
        <tr>
            <th style="width: 30px; text-align: center;">NO</th>
            <th style="width: 100px; text-align: center;">KODE BARANG</th>
            <th style="width: 200px; text-align: center;">NAMA BARANG</th>
            <th style="width: 150px; text-align: center;">KATEGORI</th> 
            <th style="width: 70px; text-align: center;">HARGA</th>
            <th style="width: 70px; text-align: center;">STOK</th>
            <th style="width: 305px; text-align: center;">KETERANGAN</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        if($dt == "" || $dt == null){
          echo "<tr><td colspan='7' style='text-align:center;'>Data Tidak Ada</td></tr>";
        }else{
            foreach ($dt as $value) {
            $no++;
        ?>
        <tr>
            <td style="width: 30px; text-align: center;"><?php echo $no; ?></td>
            <td style="width: 100px; text-align: center;"><?php echo $value->KODE_ALAT; ?></td>
            <td style="width: 200px;"><?php echo $value->NAMA_ALAT; ?></td>
            <td style="width: 150px; text-align:center;"><?php echo $value->NAMA_KATEGORI; ?></td>
            <td style="width: 70px; text-align:right;"><?php echo number_format($value->HARGA_BELI,0,'.',','); ?></td>
            <td style="width: 70px; text-align:center;"><?php echo number_format($value->TOTAL,0,'.',','); ?></td>
            <td style="width: 305px;"><?php echo $value->KETERANGAN; ?></td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
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
