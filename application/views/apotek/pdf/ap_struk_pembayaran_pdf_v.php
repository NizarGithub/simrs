<?php
  ob_start();
?>
<style media="screen">
  .body{
    width: 100%;
  }
  .clearfix{
    clear: both;
  }
  .wrap-text-1{
    margin: 0 auto;
    max-width: 950px;
  }
  .wrap-text-2{
    margin: 0 auto;
    max-width: 1030px;
  }
  .wrap-text-3{
    margin: 0 auto;
    max-width: 950px;
  }
  .one-two{
    width: 50%;
    float: left;
}
</style>

  <div style="text-align: center;">
    <span style="text-transform: uppercase;">jl. raya kalijaten 11-14 sepanjang - sidoarjo</span><br>
    <span>Telp. (031) 7885011, Fax. (031) 7873633</span><br>
    <span>Website: WWW.rsabsoerya.com</span>
  </div>
  <br>
  <div style="clear: both;"></div>
    <hr style="border: 1px dotted black; width: 10px;">
  <div style="margin: 0 auto; max-width: 950px;">
    <span style="float: left;">Nomor: 19684</span>
    <span style="float: right;">Tanggal: 13-07-2018</span>
  </div>
  <h2 style="text-transform: uppercase; font-weight: bold; text-align: center;">kwitansi pembayaran</h2>
  <div style="margin: 0 auto; max-width: 1030px;">
    <table style="width: 100%;">
      <tbody>
        <tr>
          <td style="width: 10%;">Terima Dari</td>
          <td style="width: 2%;">:</td>
          <td colspan="2" style="text-transform: uppercase;">Yatno</td>
        </tr>
        <tr>
          <td style="width: 10%;">Uang Sebesar</td>
          <td style="width: 2%;">:</td>
          <td colspan="2" style="text-transform: uppercase;">TIGA RATUS DELAPAN PULUH TIGA RUBU EMPAT RATUS Rupiah</td>
        </tr>
        <tr>
          <td style="width: 10%;">Alamat</td>
          <td style="width: 2%;">:</td>
          <td colspan="2" style="text-transform: uppercase;">Dungu Lor RT.24/06 Sukodono Sidoarjo</td>
        </tr>
        <tr>
          <td style="width: 10%;">Nama Pasien</td>
          <td style="width: 2%;">:</td>
          <td colspan="2" style="text-transform: uppercase;">Yatno</td>
        </tr>
        <tr>
          <td style="width: 10%;">Pembayaran</td>
          <td style="width: 2%;">:</td>
          <td style="text-transform: uppercase; width: 21%;">apotik resep rj</td>
          <td>TUJUAN : APOTEK</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div style="clear: both;"></div><br>
  <div style="margin: 0 auto; max-width: 950px;">
    <table style="width: 100%;">
      <tr>
        <td style="width: 4%;"><hr style="border: 1px dotted black; width: 50%;"></td>
        <td style="text-transform: uppercase; width: 30%;">Vectrine Kaspul, biji, kapsul</td>
        <td style="width: 2%;">,</td>
        <td>10</td>
      </tr>
      <tr>
        <td style="width: 4%;"><hr style="border: 1px dotted black; width: 50%;"></td>
        <td style="text-transform: uppercase; width: 30%;">tremenza tablet 100's,, tablet</td>
        <td style="width: 2%;">,</td>
        <td>10</td>
      </tr>
    </table>
  </div>
  <div style="clear: both;"></div><br><br>
  <div style="margin: 0 auto; max-width: 1030px;">
    <div style="width: 50%; float: left;">
      <table style="width: 100%;">
        <tr>
          <td style="width: 20%;">Nama Dokter</td>
          <td style="width: 3%;">:</td>
          <td style="text-transform: uppercase">Dr. Heny P.</td>
        </tr>
        <tr>
          <td style="width: 20%;">Total Biaya</td>
          <td style="width: 3%;">:</td>
          <td style="text-transform: uppercase">Rp. 383,400 TUNAI</td>
        </tr>
      </table>
    </div>
    <div style="width: 50%; float: left;">
        <span>Sidoarjo, 13 Juli 2018</span>
    </div>
  </div>
  <div style="clear: both;"></div><br><br>
  <div style="margin: 0 auto; max-width: 1030px;">
    <div style="width: 50%; float: left;">
        <span style="text-transform: uppercase; font-weight: bold;">SHIFT: 13-07-2018 (1)</span>
    </div>
    <div style="width: 50%; float: left;">
        <span style="text-transform: uppercase; font-weight: bold;">ADmin</span>
    </div>
  </div>

<?PHP
    // ----ukuran kertas dalam inch----//
    // custom
    $width_custom = 4.527559;
    $height_custom = 3.74016;

    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('P',array($height_in_mm,$width_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('L','NCR','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');
	exit();
?>
