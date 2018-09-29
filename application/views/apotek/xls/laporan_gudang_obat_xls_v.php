<?PHP
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=LaporanGajiPegawai.xls");
?>

<style>
.tabel {
    background: #fff;
    table-layout: fixed;
    border-collapse: collapse;
    border: 1px solid black;
    font-size: 14px;
}
.tabel th {
    vertical-align: middle;
    height: 30px;
    font-size: 16px;
    border: 1px solid #666;
}
.tabel td {
    background: #fff;
    vertical-align: middle;
    height: 30px;
    padding-left: 8px;
    padding-right: 8px;
    font-size: 14px;
    border: 1px solid black;
    width: 80px;
}

</style>

<?php
function angka_positif($angka){
    if($angka < 0){
        $angka = -$angka;
        $angka = "(".$angka.")";
    }else{
        $angka = $angka;
    }
    return $angka;
}

function angka_positif_rp($angka){
    if($angka < 0){
        $angka = -$angka;
        $angka = "(Rp. ".number_format($angka,2,',','.').")";
    }else{
        $angka = number_format($angka,2,',','.');
    }
    return $angka;
}
?>
<table align="center">
    <tr>
        <td align="center" colspan="13">
            <h2><?php echo $title; ?> <br>
              Berdasarkan
                <?php
        					if ($urutkan == 'Default') {
        					?>
        					Semua Data
        				<?php
        					}elseif ($urutkan == 'Nama Obat') {
        					?>
        					Nama Obat
        				<?php
        					}elseif ($urutkan == 'Stok') {
        						if ($urutkan_stok == 'Tinggi') {
        							?>
        							Stok Tertinggi
        				<?php
        							}else {
        							?>
        							Stok Terendah
        				<?php
        							}
        					}elseif ($urutkan == 'Expired') {
        							?>
        					Kadaluarsa
        				<?php
        					}
        				 ?>
             </h2>
        </td>
    </tr>
</table>
<br/><br/>
<table align="center" class="tabel">
    <thead>
        <tr>
            <th style="color:#000; text-align:center;">No</th>
            <th style="color:#000; text-align:center;">Nama Obat</th>
            <th style="color:#000; text-align:center;">Jenis Obat</th>
            <th style="color:#000; text-align:center;">Status Obat</th>
            <th style="color:#000; text-align:center;">Golongan Obat</th>
            <th style="color:#000; text-align:center;">Kategori Obat</th>
            <th style="color:#000; text-align:center;">Stok</th>
            <th style="color:#000; text-align:center;">Harga Beli</th>
            <th style="color:#000; text-align:center;">Harga Jual</th>
            <th style="color:#000; text-align:center;">Tanggal Expired</th>
            <th style="color:#000; text-align:center;">Tanggal Masuk</th>
        </tr>
    </thead>
    <tbody>
  	<?php
  		$no = 0;

  		foreach ($dt as $key => $value) {
  			$no++;

  			$status_obat = "";
  			if($value->STATUS_RACIK == 0){
  					$status_obat = "Obat Umum";
  			}else{
  					$status_obat = "Obat Racik";
  			}
  	?>
  		<tr>
  			<td><?php echo $no; ?></td>
  			<td style="font-size: 12px;">
  				<?php echo $value->NAMA_OBAT; ?>
  				<br>
  				<small><b><?php echo $value->KODE_OBAT; ?></b></small>
  			</td>
  			<td style="text-align: center;"><?php echo $value->NAMA_JENIS; ?></td>
  			<td><?php echo $status_obat ?></td>
  			<td><?php echo $value->GOLONGAN_OBAT ?></td>
  			<td><?php echo $value->KATEGORI_OBAT ?></td>
  			<td style="text-align:center;"><?php echo $value->TOTAL ?></td>
  			<td style="text-align:right">Rp. <?php echo number_format($value->HARGA_BELI); ?></td>
  			<td style="text-align:right">Rp. <?php echo number_format($value->TOTAL_HARGA); ?></td>
  			<td><?php echo $value->KADALUARSA ?></td>
  			<td><?php echo $value->TANGGAL_MASUK ?></td>
  		</tr>
  	<?php
  		}
  	?>
  	</tbody>
</table>

<?php
    exit();
?>
