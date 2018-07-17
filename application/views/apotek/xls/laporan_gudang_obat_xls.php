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
                <b>CV. MY COMPANY</b> <br/>
                Jl. Your Company <br/>
                Telp. Your Company <br/>
               
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
            <th style="color:#000; text-align:center;">Nama Obat</th>
            <th style="color:#000; text-align:center;">No. FIFO</th>
            <th style="color:#000; text-align:center;">Jenis Obat</th>
            <th style="color:#000; text-align:center;">Harga Beli</th>
            <th style="color:#000; text-align:center;">Harga Jual</th>
            <th style="color:#000; text-align:center;">Stok</th>
            <th style="color:#000; text-align:center;">Tanggal Expired</th>
            <th style="color:#000; text-align:center;">Tanggal Masuk</th>
        </tr>
    </thead>
    <tbody>
        <?PHP 
        $no = 0;
        foreach ($dt as $key => $row) { 
           
        ?>
        <tr>
            
            <td>
                <b><?=$row->NAMA_OBAT?></b><br/>
                <smasll><?=$row->KODE_OBAT?></small>
            </td>
            <td style='text-align:center;'><?=$row->URUT_BARANG?></td>
            <td><?=$row->NAMA_JENIS?></td>
            <td style='text-align:right;'><?=$row->HARGA_BELI;?></td>
            <td style='text-align:right;'><?=$row->HARGA_JUAL;?></td>
            <td><?=$row->TOTAL;?>&nbsp;<?=$row->SATUAN_ISI;?></td>
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