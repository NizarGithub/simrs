<?PHP
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename.xls");
?>
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
 <table style="border: 1px solid black;">
         <tr style="background-color: #01579B;">
             <th style="color:#fff; text-align:center;" colspan="2">No</th>
             <th style="color:#fff; text-align:center;" colspan="2">Nama Alat</th>
             <th style="color:#fff; text-align:center;" colspan="2">No. FIFO</th>
             <th style="color:#fff; text-align:center;" colspan="2">Jenis Alat</th>
             <th style="color:#fff; text-align:center;" colspan="2">Harga Beli</th>
             <th style="color:#fff; text-align:center;" colspan="2">Stok</th>
             <th style="color:#fff; text-align:center;" colspan="2">Tanggal Masuk</th>
             <th style="color:#fff; text-align:center;" colspan="2">Waktu</th>
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
         <tr style="height: 20px; margin: 0 auto; text-align: center;">
            <td colspan="2"><?php echo $no; ?></td>
            <td colspan="2"><?php echo $d->NAMA_ALAT; ?></td>
            <td colspan="2"><?php echo $d->BARCODE; ?></td>
            <td colspan="2"><?php echo $d->JENIS_ALAT; ?></td>
            <td colspan="2"><?php echo number_format($d->HARGA_BELI); ?></td>
            <td colspan="2"><?php echo $d->ISI; ?></td>
            <td colspan="2"><?php echo $tanggal_masuk; ?></td>
            <td colspan="2"><?php echo $d->WAKTU_MASUK; ?></td>
         </tr>
         <?php
         }
        ?>
 </table>
<?php
    exit();
?>
