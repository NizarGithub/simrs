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
    </style>
      <div style="text-align: center;">
        <span style="text-transform: uppercase;">jl. raya kalijaten 11-14 sepanjang - sidoarjo</span><br>
        <span>Telp. (031) 7885011, Fax. (031) 7873633</span><br>
        <span>Website: WWW.rsabsoerya.com</span>
      </div>
      <br>
      <div style="clear: both;"></div>
        <hr style="border: 1px dotted black; width: 10px;">
        <table style="width: 100%;">
          <tr>
            <td style="text-align: left; width: 88%;">Nomor: <?php echo $row['INVOICE']; ?></td>
            <td style="text-align: right;">Tanggal: <?php echo $row['TANGGAL']; ?></td>
          </tr>
        </table>
      <h2 style="text-transform: uppercase; font-weight: bold; text-align: center;">kwitansi pembayaran</h2>
      <div style="margin: 0 auto; max-width: 1030px;">
        <table style="width: 100%;">
          <tbody>
            <tr>
              <td style="width: 10%;">Terima Dari</td>
              <td style="width: 2%;">:</td>
              <td colspan="2" style="text-transform: uppercase;"><?php echo $row['NAMA_PASIEN']; ?></td>
            </tr>
            <tr>
              <td style="width: 10%;">Uang Sebesar</td>
              <td style="width: 2%;">:</td>
              <td colspan="2" style="text-transform: uppercase;">
                <?php
                function Terbilang($a) {
                    $ambil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
                    if ($a < 12)
                        return " " . $ambil[$a];
                    elseif ($a < 20)
                        return Terbilang($a - 10) . "belas";
                    elseif ($a < 100)
                        return Terbilang($a / 10) . " puluh" . Terbilang($a % 10);
                    elseif ($a < 200)
                        return " seratus" . Terbilang($a - 100);
                    elseif ($a < 1000)
                        return Terbilang($a / 100) . " ratus" . Terbilang($a % 100);
                    elseif ($a < 2000)
                        return " seribu" . Terbilang($a - 1000);
                    elseif ($a < 1000000)
                        return Terbilang($a / 1000) . " ribu" . Terbilang($a % 1000);
                    elseif ($a < 1000000000)
                        return Terbilang($a / 1000000) . " juta" . Terbilang($a % 1000000);
                    }
                  echo Terbilang($row['TOTAL']);
                 ?>
              </td>
            </tr>
            <tr>
              <td style="width: 10%;">Alamat</td>
              <td style="width: 2%;">:</td>
              <td colspan="2" style="text-transform: uppercase;"><?php echo $row['ALAMAT_PASIEN']; ?></td>
            </tr>
            <tr>
              <td style="width: 10%;">Nama Pasien</td>
              <td style="width: 2%;">:</td>
              <td colspan="2" style="text-transform: uppercase;"><?php echo $row['NAMA_PASIEN']; ?></td>
            </tr>
            <tr>
              <td style="width: 10%;">Pembayaran</td>
              <td style="width: 2%;">:</td>
              <td style="text-transform: uppercase; width: 21%;">apotik resep rj</td>
              <td>TUJUAN : <?php echo $row['NAMA_POLI']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div style="clear: both;"></div><br>
      <div style="margin: 0 auto; max-width: 950px;">
        <table style="width: 100%;">
          <?php
            $id_resep = $row['ID_RESEP'];
            $this->db->select('*');
            $this->db->from('rk_resep_detail_rj');
            $this->db->join('admum_setup_nama_obat', 'admum_setup_nama_obat.ID=rk_resep_detail_rj.ID_OBAT');
            $this->db->where('ID_RESEP', $id_resep);
            $result_obat = $this->db->get()->result_array();
            foreach ($result_obat as $ro) {
           ?>
          <tr>
            <td>-</td>
            <td style="text-transform: uppercase;"><?php echo $ro['NAMA_OBAT']; ?>, <?php echo $ro['TAKARAN']; ?></td>
            <td style="width: 2%;">,</td>
            <td><?php echo $ro['JUMLAH_BELI']; ?> X Rp. <?php echo number_format($ro['HARGA']); ?></td>
            <td style="width: 4%;">@ Rp.</td>
            <td><?php echo number_format($ro['SUBTOTAL']); ?></td>
          </tr>
          <?php
            }
           ?>
          <!-- <tr>
            <td style="width: 2%;"><hr style="border: 1px dotted black; width: 100%;"></td>
            <td style="text-transform: uppercase; width: 2%;">tremenza tablet 100's,, tablet</td>
            <td style="width: 2%;">,</td>
            <td>10</td>
          </tr> -->
        </table>
      </div>
      <div style="clear: both;"></div><br><br>

      <div class="footer">
        <table style="width: 100%;">
          <tr>
            <td style="width: 20%;">Nama Dokter</td>
            <td style="width: 3%;">:</td>
            <td style="text-transform: uppercase; width: 60%;"><?php echo $row['NAMA_DOKTER']; ?></td>
            <td>Sidoarjo,
              <?php
                $tgl = $row['TANGGAL'];
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
                  echo $tanggal;
               ?>
            </td>
          </tr>
          <tr>
            <td style="width: 20%;">Total Biaya</td>
            <td style="width: 3%;">:</td>
            <td colspan="2" style="text-transform: uppercase">Rp. <?php echo number_format($row['TOTAL']); ?> <?php echo $row['JENIS_PEMBAYARAN']; ?></td>
          </tr>
          <tr><td colspan="4"></td></tr>
          <tr><td colspan="4"></td></tr>
          <tr><td colspan="4"></td></tr>
          <tr><td colspan="4"></td></tr>
          <tr><td colspan="4"></td></tr>
          <tr><td colspan="4"></td></tr>
          <tr>
            <td style="width: 20%;">SHIFT</td>
            <td style="width: 3%;">:</td>
            <td style="text-transform: uppercase; width: 60%;"><?php echo $row['TANGGAL']; ?> (<?php echo $row['SHIFT']; ?>)</td>
            <td style="text-align: center;"><?php echo $row['NAMA_PEGAWAI']; ?></td>
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
        $html2pdf = new HTML2PDF('L','NCR','fr');
        $html2pdf->pdf->SetTitle($settitle);
        $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($filename.'.pdf');
    	exit();
    ?>
