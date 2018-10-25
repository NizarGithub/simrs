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
    .footer{
        position:absolute;
        left:0;
        bottom:0;
    }
    .grid th {
    	background: white;
    	vertical-align: middle;
    	color : black;
        text-align: center;
        height: 30px;
        font-size: 13px;
        padding: 5px;
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
    	background: black;
    	border: 1px solid black;
        border-spacing: 0;

    }
    </style>
      <div style="text-align: center;">
        <span style="text-transform: uppercase; font-size: 15px;">rekap pendapatan ksir</span><br>
        <span style="text-transform: uppercase; font-size: 15px;">rumah sakit anak & bersalin "soerya"</span><br>
        <span style="text-transform: uppercase; font-size: 15px;">tanggal : <?php echo $tanggal; ?> shift : <?php echo $shift; ?></span><br>
      </div>
      <div style="clear: both;"></div>
        <table style="width: 100%;">
          <tr>
            <td style="text-align: left; width: 42%;">DATE : </td>
            <td style="text-align: right;">HAL : </td>
          </tr>
        </table>
      <div style="clear: both;"></div>
      <div style="margin: 0 auto; max-width: 1030px;">
        <table align="left" class="grid">
        	<thead>
                <tr>
                    <th>RUANG</th>
                    <th>TOTAL</th>
                    <th>TUNAI</th>
                    <th>KREDIT</th>
                    <th>RETUR</th>
                </tr>
        	</thead>
            <tbody>
              <?php
                $subtotal_poli = 0;
                $subtotal_lab = 0;
                $subtotal_rj = 0;
                $subtotal_hv = 0;

                $subtotal_poli_tunai = 0;
                $subtotal_lab_tunai = 0;
                $subtotal_rj_tunai = 0;
                $subtotal_hv_tunai = 0;

                $subtotal_poli_non_tunai = 0;
                $subtotal_lab_non_tunai = 0;
                $subtotal_rj_non_tunai = 0;
                $subtotal_hv_non_tunai = 0;

                $sql_poli = $this->db->query("SELECT
                                              *
                                              FROM
                                              admum_poli
                                              ")->result_array();
                foreach ($sql_poli as $sp) {
                  $id_poli = $sp['ID'];

                  $sql_semua_poli = $this->db->query("SELECT
                                                      SUM(a.BIAYA_POLI) AS TOTAL_BIAYA_POLI,
                                                      (SELECT SUM(a.BIAYA_POLI) AS TUNAI FROM rk_pembayaran_kasir a
                                                      	LEFT JOIN admum_rawat_jalan b ON a.ID_PELAYANAN = b.ID
                                                      	WHERE a.JENIS_PEMBAYARAN = 'Tunai'
                                                      	AND a.SHIFT = '$shift'
                                                      	AND b.STS_CLOSING = '1'
                                                      	AND b.ID_POLI = '$id_poli'
                                                        AND a.TANGGAL = '$tanggal'
                                                      	) AS TUNAI,
                                                      	(SELECT SUM(a.BIAYA_POLI) AS TUNAI FROM rk_pembayaran_kasir a
                                                      	LEFT JOIN admum_rawat_jalan b ON a.ID_PELAYANAN = b.ID
                                                      	WHERE a.JENIS_PEMBAYARAN = 'Non Tunai'
                                                      	AND a.SHIFT = '$shift'
                                                      	AND b.STS_CLOSING = '1'
                                                      	AND b.ID_POLI = '$id_poli'
                                                        AND a.TANGGAL = '$tanggal'
                                                      	) AS NON_TUNAI
                                                      FROM rk_pembayaran_kasir a
                                                      LEFT JOIN admum_rawat_jalan b ON a.ID_PELAYANAN = b.ID
                                                      WHERE a.SHIFT = '$shift'
                                                      AND b.STS_CLOSING = '1'
                                                      AND b.ID_POLI = '$id_poli'
                                                      AND a.TANGGAL = '$tanggal'
                                                      ")->row_array();

                                                      $total_poli = $sql_semua_poli['TOTAL_BIAYA_POLI'];
                                                      if ($total_poli == '' || $total_poli == NULL) {
                                                        $total_poli = '0';
                                                      }
                                                      $subtotal_poli += $total_poli;

                                                      $tunai_poli = $sql_semua_poli['TUNAI'];
                                                      if ($tunai_poli == '' || $tunai_poli == NULL) {
                                                        $tunai_poli = '0';
                                                      }
                                                      $subtotal_poli_tunai += $tunai_poli;

                                                      $non_tunai_poli = $sql_semua_poli['NON_TUNAI'];
                                                      if ($non_tunai_poli == '' || $non_tunai_poli == NULL) {
                                                        $non_tunai_poli = '0';
                                                      }
                                                      $subtotal_poli_non_tunai += $non_tunai_poli;
               ?>
               <tr>
                 <td style="padding: 10px; text-transform: uppercase;"><?php echo $sp['NAMA']; ?> - <?php echo $sp['STATUS']; ?></td>
                 <td style="padding: 10px; text-align: right;"><?php echo number_format($total_poli); ?></td>
                 <td style="padding: 10px; text-align: right;"><?php echo number_format($tunai_poli); ?></td>
                 <td style="padding: 10px; text-align: right;"><?php echo number_format($non_tunai_poli); ?></td>
                 <td style="padding: 10px; text-align: right;">0</td>
               </tr>
               <?php
                 }
                ?>
                <tr>
                  <td style="padding: 10px; text-transform: uppercase;">laboratorium ap</td>
                  <td style="padding: 10px; text-align: right;">0</td>
                  <td style="padding: 10px; text-align: right;">0</td>
                  <td style="padding: 10px; text-align: right;">0</td>
                  <td style="padding: 10px; text-align: right;">0</td>
                </tr>
                <tr>
                  <td style="padding: 10px; text-transform: uppercase;">apotik resep hv</td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $total_hv = $obat_hv['TOTAL_HV'];
                      if ($total_hv == '' || $total_hv == NULL) {
                        $total_hv = '0';
                      }
                      $subtotal_hv = $total_hv;
                      echo number_format($total_hv);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $tunai_hv = $obat_hv['TUNAI'];
                      if ($tunai_hv == '' || $tunai_hv == NULL) {
                        $tunai_hv = '0';
                      }
                      $subtotal_hv_tunai = $tunai_hv;
                      echo number_format($tunai_hv);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $non_tunai_hv = $obat_hv['NON_TUNAI'];
                      if ($non_tunai_hv == '' || $non_tunai_hv == NULL) {
                        $non_tunai_hv = '0';
                      }
                      $subtotal_hv_non_tunai = $non_tunai_hv;
                      echo number_format($non_tunai_hv);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">0</td>
                </tr>
                <tr>
                  <td style="padding: 10px; text-transform: uppercase;">apotik resep rj</td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $total_rj = $obat_rj['TOTAL_RJ'];
                      if ($total_rj == '' || $total_rj == NULL) {
                        $total_rj = '0';
                      }
                      $subtotal_rj = $total_rj;
                      echo number_format($total_rj);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $tunai_rj = $obat_rj['TUNAI'];
                      if ($tunai_rj == '' || $tunai_rj == NULL) {
                        $tunai_rj = '0';
                      }
                      $subtotal_rj_tunai = $tunai_rj;
                      echo number_format($tunai_rj);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">
                    <?php
                      $non_tunai_rj = $obat_rj['NON_TUNAI'];
                      if ($non_tunai_rj == '' || $non_tunai_rj == NULL) {
                        $non_tunai_rj = '0';
                      }
                      $subtotal_rj_non_tunai = $non_tunai_rj;
                      echo number_format($non_tunai_rj);
                     ?>
                  </td>
                  <td style="padding: 10px; text-align: right;">0</td>
                </tr>
                <tr>
                  <td style="text-align: center;"> TOTAL </td>
                  <td style="text-align: right; padding: 5px;">
                    <?php
                      $subtot = $subtotal_poli+$subtotal_hv+$subtotal_rj;
                      echo number_format($subtot);
                    ?>
                  </td>
                  <td style="text-align: right; padding: 5px;">
                    <?php
                    $subtot_tunai = $subtotal_poli_tunai + $subtotal_rj_tunai + $subtotal_hv_tunai;
                    echo number_format($subtot_tunai);
                     ?>
                  </td>
                  <td style="text-align: right; padding: 5px;">
                    <?php
                    $subtot_non_tunai = $subtotal_poli_non_tunai + $subtotal_rj_non_tunai + $subtotal_hv_non_tunai;
                    echo number_format($subtot_non_tunai);
                     ?>
                  </td>
                  <td style="text-align: right; padding: 5px;">0</td>
                </tr>
            </tbody>
        </table>
      </div>
    <?PHP
        // ----ukuran kertas dalam inch----//
        // custom
        $width_custom = 4.527559;
        $height_custom = 5.11811;

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
