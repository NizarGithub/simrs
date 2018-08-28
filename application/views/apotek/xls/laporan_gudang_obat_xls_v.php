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
            <th style="color:#000; text-align:center;">Nama Obat</th>
            <th style="color:#000; text-align:center;">Jenis Obat</th>
            <th style="color:#000; text-align:center;">Status Obat</th>
            <th style="color:#000; text-align:center;">Golongan Obat</th>
            <th style="color:#000; text-align:center;">Kategori Obat</th>
            <th style="color:#000; text-align:center;">Jumlah</th>
            <th style="color:#000; text-align:center;">Isi</th>
            <th style="color:#000; text-align:center;">Jumlah Isi</th>
            <th style="color:#000; text-align:center;">Stok</th>
            <th style="color:#000; text-align:center;">Harga Beli</th>
            <th style="color:#000; text-align:center;">Harga Jual</th>
            <th style="color:#000; text-align:center;">Tanggal Expired</th>
            <th style="color:#000; text-align:center;">Tanggal Masuk</th>
        </tr>
    </thead>
    <tbody>
        <?PHP
        $no = 0;
        foreach ($dt as $key => $row) {
          $satuan = "";
          if($row->JUMLAH_BUTIR != 0){
              $satuan = $row->SATUAN_ISI;
          }else{
              $satuan = $row->NAMA_SATUAN;
          }
    			$status_obat = "";
    			if($row->STATUS_RACIK == 0){
    					$status_obat = "Obat Umum";
    			}else{
    					$status_obat = "Obat Racik";
    			}
    			$go = $row->ID_GOLONGAN;
    			if ($go == '' || $go == NULL) {
    				$golongan = 'Golongan Obat Kosong';
    			}else {
    				$gol = array(
    					1 => 'Alkes',
    					2 => 'OKT (Obat Keras Tertentu)',
    					3 => 'Injeksi',
    					4 => 'Supro (Suprositoria)',
    					5 => 'Vaksin',
    					6 => 'Cream',
    					7 => 'Drop',
    					8 => 'HV / OTC',
    					9 => 'Susu',
    					10 => 'Sirup',
    					11 => 'Tablet',
    					12 => 'Generik',
    				);
    				$golongan = $gol[intval($go)];
    			}
    			$ka = $row->ID_KATEGORI;
    			if ($ka == '' || $ka == NULL) {
    				$kategori = 'Kategori Obat Kosong';
    			}else {
    				$kat = array(
    					1 => 'Obat Bebas',
    					2 => 'Obat Bebas Terbatas',
    					3 => 'Obat Keras',
    					4 => 'Jamu',
    					5 => 'Obat Herbal Terstandar',
    					6 => 'Fitofarmaka',
    				);
    				$kategori = $kat[intval($ka)];
    			}
        ?>
        <tr>
            <td>
                <b><?=$row->NAMA_OBAT?></b><br/>
                <smasll><?=$row->KODE_OBAT?></small>
            </td>
            <td><?=$row->NAMA_JENIS?></td>
            <td><?php echo $status_obat; ?></td>
            <td><?php echo $golongan; ?></td>
            <td><?php echo $kategori; ?></td>
            <td><?php echo $row->JUMLAH; ?></td>
            <td><?php echo $row->ISI; ?></td>
            <td><?php echo $row->JUMLAH_BUTIR; ?></td>
            <td><?=$row->TOTAL;?></td>
            <td style='text-align:right;'><?=$row->HARGA_BELI;?></td>
            <td style='text-align:right;'><?=$row->HARGA_JUAL;?></td>
            <td><?=$row->KADALUARSA;?></td>
            <td><?=$row->TANGGAL_MASUK;?></td>
        </t
        <?PHP } ?>
        <?PHP if(count($dt) == 0){
            echo "<tr><td align='center' colspan='10'> <b> Tidak ada data pembayaran untuk bulan dan tahun ini </b> </td></tr>";
        } ?>
    </tbody>
</table>

<?php
    exit();
?>
