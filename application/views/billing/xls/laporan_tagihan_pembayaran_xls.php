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
        <td>
            <img src="<?php echo $image; ?>" style="width:150px; height:40px;">
        </td>
        <td>&nbsp;</td>
        <td colspan="3">
            <h4>
                <b>MY COMPANY</b> <br/>
                MY COMPANY ADDRESS <br/>
                Telp. (031) 123124 - 0812345678 <br/>
                website : company.com
            </h4>
        </td>
    </tr>
</table>

<br/>

<table align="left">
    <tr>
        <td align="left" colspan="3">
            <h2><u><?php echo $title; ?></u></h2>
        </td>
    </tr>
</table>

<br/><br/>

<table align="center" class="tabel">
    <thead>
        <tr>
            <th style="text-align:center; white-space:nowrap;">No</th>
            <th style="text-align:center; white-space:nowrap;">No. Transaksi</th>
            <th style="text-align:center; white-space:nowrap;">Tanggal</th>
            <th style="text-align:center; white-space:nowrap;">Atas Nama</th>
            <th style="text-align:center; white-space:nowrap;">No. Pasien</th>
            <th style="text-align:center; white-space:nowrap;">Layanan</th>
            <th style="text-align:center; white-space:nowrap;">Sistem Pembayaran</th>
            <th style="text-align:center; white-space:nowrap;">Total Biaya (Rp) </th>
            <th style="text-align:center; white-space:nowrap;">Bayar (Rp) </th>
            <th style="text-align:center; white-space:nowrap;">Kembali (Rp) </th>
        </tr>
    </thead>
    <tbody>
        <?PHP 
        $no = 0;
        foreach ($dt as $key => $row) { 
            $no++;
        ?>
        <tr>
            <td style="text-align:center;"> <?=$no;?> </td>
            <td style="text-align:center;"> <?=$row->NO_BILLING;?> </td>
            <td style="text-align:center;"> <?=$row->TANGGAL.", ".$row->WAKTU;?> </td>
            <td style="text-align:left;"> <?=$row->NAMA;?> </td>
            <td style="text-align:left;"> <?=$row->KODE_PASIEN;?> </td>
            <td style="text-align:center;"> <?=$row->STS_LAYANAN;?> </td>
            <td style="text-align:center;"> <?=$row->SISTEM_BAYAR;?> </td>
            <td style="text-align:right;"> <?=number_format($row->TOTAL_BIAYA);?> </td>
            <td style="text-align:right;"> <?=number_format($row->BAYAR);?> </td>
            <td style="text-align:right;"> <?=number_format($row->KEMBALI);?> </td>
        </tr>
        <?PHP } ?>

        <?PHP if(count($dt) == 0){
            echo "<tr><td align='center' colspan='10'> <b> Tidak ada data pembayaran untuk bulan dan tahun ini </b> </td></tr>";
        } ?>
    </tbody>
</table>

<?php
    exit();
?>