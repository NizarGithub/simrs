<?php
      ob_start();
    ?>
    <style media="screen">
    .struk{
        line-height: normal;
        width: 10cm;
      /*height:123.7mm;*/
      margin-left:auto;
      margin-right:auto;
    }
    table{
      /* border : 1px solid black; */
    }
    /* .grid th {
    	background: #fff;
    	vertical-align: middle;
    	width: 187px;
        text-align: center;
        height: 30px;
    }
    .grid td {
    	vertical-align: middle;
    	font: 12px sans-serif;
        height: 30px;
        padding-left: 5px;
        padding-right: 5px;
    } */
    /* .grid table{
        border-collapse: collapse;
        width: 100%;
    } */
    /* .grid tfoot td{
    	vertical-align: middle;
    	color : #003865;
    	width: 90px;
        text-align: center;
        height: 30px;
    } */
    /* .grid table, th, td{
    } */
    </style>
    <div class="struk">
      <table style="width: 86%;">
        <tr>
          <td style="width: 38%; text-align: right;">
            <img src="<?php echo base_url(); ?>picture/Indonesian_Red_Cross_Society_logo.png" style="width: 100px; height: 100px;">
          </td>
          <td style="text-align: center; font-weight: bold; width: 60%; font-size: 20px; vertical-align: middle;">NOTA PEMBELIAN NON RESEP</td>
        </tr>
    	</table>
      <div style="clear:both;"></div>
      <table style="border: 1px solid black; border-collapse: collapse;">
          <tr>
            <th style="border: 1px solid black; width: 18%; vertical-align: middle; height: 21px; font-size: 16px; text-align: center; font-weight: bold;">Banyaknya</th>
            <th style="border: 1px solid black; width: 33.6%; vertical-align: middle; height: 21px; font-size: 16px; text-align: center; font-weight: bold;">Nama Barang</th>
            <th style="border: 1px solid black; width: 15%; vertical-align: middle; height: 21px; font-size: 16px; text-align: center; font-weight: bold;">Jumlah</th>
            <th style="border: 1px solid black; width: 33.6%; vertical-align: middle; height: 21px; font-size: 16px; text-align: center; font-weight: bold;">Harga</th>
          </tr>
          <?php
          foreach ($data as $d) {
           ?>
          <tr>
            <td style="border: 1px solid black; text-align: center; height: 19px; vertical-align: middle;"><?php echo $d['JUMLAH_BELI']; ?></td>
            <td style="border: 1px solid black; text-align: center; height: 19px; vertical-align: middle;"><?php echo $d['NAMA_OBAT']; ?></td>
            <td style="border: 1px solid black; text-align: center; height: 19px; vertical-align: middle;"><?php echo $d['JUMLAH_BELI']; ?></td>
            <td style="border: 1px solid black; text-align: center; height: 19px; vertical-align: middle;"><?php echo number_format($d['TOTAL_OBAT']); ?></td>
          </tr>
          <?php
            }
           ?>
           <tr>
             <td style="border: 1px solid black; text-align: center; height: 21px; font-size: 16px; vertical-align: middle;" colspan="3"></td>
             <td style="border: 1px solid black; text-align: center; height: 21px; font-size: 16px; vertical-align: middle; font-weight: bold;">Total Rp <?php echo $row['TOTAL_SEMUA_OBAT']; ?></td>
           </tr>
      </table>
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
        $html2pdf = new HTML2PDF('P','A4','fr');
        $html2pdf->pdf->SetTitle($settitle);
        $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($filename.'.pdf');
    	exit();
    ?>
