<?PHP
	ob_start();
?>
<style>
table{
	width: 12.8%;
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
  border-spacing: 0;
}
th {
	background: #01579B;
	vertical-align: middle;
	color : #fff;
	width: 100%;
  text-align: center;
  height: 30px;
}
td {
	background: #FFFFF0;
	vertical-align: middle;
	font: 12px sans-serif;
  height: 30px;
  padding-left: 5px;
  padding-right: 5px;
}
</style>
<h3 style="text-align: center;">Laporan Barang <br>
	<?php
	if ($by == 'Semua') {
	?>
		Semua Data
	<?php
	}elseif ($by == 'Tanggal') {
	?>
	<?php echo $tanggal_sekarang; ?> Sampai <?php echo $tanggal_sampai; ?>
	<?php
	}elseif ($by == 'Bulan') {
		$strBulan = "";
		if($bulan == '01'){
			$strBulan = "Januari";
		}else if($bulan == '02'){
			$strBulan = "Februari";
		}else if($bulan == '03'){
			$strBulan = "Maret";
		}else if($bulan == '04'){
			$strBulan = "April";
		}else if($bulan == '05'){
			$strBulan = "Mei";
		}else if($bulan == '06'){
			$strBulan = "Juni";
		}else if($bulan == '07'){
			$strBulan = "Juli";
		}else if($bulan == '08'){
			$strBulan = "Agustus";
		}else if($bulan == '09'){
			$strBulan = "September";
		}else if($bulan == '10'){
			$strBulan = "Oktober";
		}else if($bulan == '11'){
			$strBulan = "November";
		}else if($bulan == '12'){
			$strBulan = "Desember";
		}
			$bulan_dipilih = $strBulan;
	?>
	Bulan <?php echo $bulan_dipilih; ?>
	<?php
	}elseif ($by == 'Divisi') {
		$this->db->select('*');
		$this->db->from('kepeg_divisi');
		$this->db->where('ID', $id_divisi);
		$row_array = $this->db->get()->row_array();
	?>
		Divisi <?php echo $row_array['NAMA_DIV']; ?>
	<?php
		}
	 ?>
</h3>
 <table>
         <tr>
             <th style="color:#fff; text-align:center;">No</th>
             <th style="color:#fff; text-align:center;">Nama Alat</th>
             <th style="color:#fff; text-align:center;">No. FIFO</th>
             <th style="color:#fff; text-align:center;">Jenis Alat</th>
             <th style="color:#fff; text-align:center;">Harga Beli</th>
             <th style="color:#fff; text-align:center;">Stok</th>
             <th style="color:#fff; text-align:center;">Tanggal Masuk</th>
             <th style="color:#fff; text-align:center;">Waktu</th>
         </tr>
         <?php
         $no=0;
         foreach ($data as $key => $d) {

         $no++;

         $tgl = $d->TANGGAL_MASUK;
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
           $tanggal_masuk = $dat." ".$strBulan." ".$y;
          ?>
         <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $d->NAMA_ALAT; ?></td>
            <td><?php echo $d->BARCODE; ?></td>
            <td><?php echo $d->JENIS_ALAT; ?></td>
            <td><?php echo number_format($d->HARGA_BELI); ?></td>
            <td><?php echo $d->ISI; ?></td>
            <td><?php echo $tanggal_masuk; ?></td>
            <td><?php echo $d->WAKTU_MASUK; ?></td>
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
