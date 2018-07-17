<?PHP 
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=LaporanGajiPegawaiDanPajak.xls");
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
            <img src="<?php echo $image; ?>" width="140">
        </td>
        <td>&nbsp;</td>
        <td colspan="6">
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

<table align="center">
    <tr>
        <td align="left" colspan="8">
            <h3><u><?php echo $title; ?></u></h3>
        </td>
    </tr>
</table>

<br/><br/>

<table align="center" class="tabel">
    <thead>
        <tr>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">NO</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">NAMA PEGAWAI</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">STATUS</th>

            <th style="text-align:center; white-space:nowrap;" colspan="12">BULAN</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">THR</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">GAJI BRUTO</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">BIAYA JABATAN</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">GAJI NETTO</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">PTKP</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">PKP</th>
            <th style="text-align:center; white-space:nowrap;" rowspan="2">PPH 21 TERHUTANG</th>
        </tr>
        <tr>
            <th style="text-align:center; white-space:nowrap;">1</th>
            <th style="text-align:center; white-space:nowrap;">2</th>
            <th style="text-align:center; white-space:nowrap;">3</th>
            <th style="text-align:center; white-space:nowrap;">4</th>
            <th style="text-align:center; white-space:nowrap;">5</th>
            <th style="text-align:center; white-space:nowrap;">6</th>
            <th style="text-align:center; white-space:nowrap;">7</th>
            <th style="text-align:center; white-space:nowrap;">8</th>
            <th style="text-align:center; white-space:nowrap;">9</th>
            <th style="text-align:center; white-space:nowrap;">10</th>
            <th style="text-align:center; white-space:nowrap;">11</th>
            <th style="text-align:center; white-space:nowrap;">12</th>
        </tr>
    </thead>
    <tbody>
        <?PHP 
        $no = 0;
        foreach ($dtAllPegawai as $key => $row) {
            $no++;
            $gaji_bruto = ($row->GAPOK * 12) + $row->THR;
            $biaya_jabatan = ($gaji_bruto * 5) / 100;
            $gaji_netto = $gaji_bruto - $biaya_jabatan;
            $ptkp  = $row->PTKP;
            $pkp = $gaji_netto - $ptkp;

            $prosen_pkp = 0;
            foreach ($dt_PKPRange as $key => $range) {
                if($range->NILAI_AWAL <= $pkp && $range->NILAI_AKHIR >= $pkp){
                    $prosen_pkp = $range->PROSEN;
                }
            }

            $pph21 = ($prosen_pkp * $pkp) / 100;
            


        ?>
        <tr>
            <td style="text-align:center;"> <?=$no;?> </td>
            <td style="text-align:left;"> <?=$row->NAMA;?> </td>
            <td style="text-align:center;"> <?=$row->KODE_GOLONGAN;?> </td>

            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>
            <td style="text-align:right;"> <?=number_format($row->THR);?>   </td>

            <td style="text-align:right;"> <?=number_format($gaji_bruto);?>   </td>
            <td style="text-align:right;"> <?=number_format($biaya_jabatan);?>   </td>
            <td style="text-align:right;"> <?=number_format($gaji_netto);?>   </td>
            <td style="text-align:right;"> <?=number_format($ptkp);?>   </td>
            <td style="text-align:right;"> <?=number_format($pkp);?>   </td>
            <td style="text-align:right;"> <?=number_format($pph21);?>   </td>

        </tr>
        <?PHP } ?>
    </tbody>
</table>

<?php
    exit();
?>