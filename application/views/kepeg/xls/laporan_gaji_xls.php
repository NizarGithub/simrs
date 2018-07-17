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
            <img src="<?php echo $image; ?>" style="width:200px; height:70px;">
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

<table align="center">
    <tr>
        <td align="center" colspan="3">
            <h2><u><?php echo $title; ?></u></h2>
        </td>
    </tr>
</table>

<br/><br/>

<table align="center" class="tabel">
    <thead>
        <tr>
            <th style="text-align:center; white-space:nowrap;">NIP</th>
            <th style="text-align:center; white-space:nowrap;">Nama Pegawai</th>
            <th style="text-align:center; white-space:nowrap;">Jabatan</th>
            <th style="text-align:center; white-space:nowrap;">Departemen</th>
            <th style="text-align:center; white-space:nowrap;">Divisi</th>
            <th style="text-align:center; white-space:nowrap;">Gaji Pokok</th>
            <?PHP foreach ($dt_setup_gaji as $key => $setup) {
                echo '<th style="text-align:center; white-space:nowrap;">'.$setup->NAMA_GAJI.' (Rp) </th>';
            } ?>

            <?PHP if($sts_thr == 1){ echo '<th style="text-align:center; white-space:nowrap;"> THR (Rp) </th>'; } ?>
        </tr>
    </thead>
    <tbody>
        <?PHP foreach ($dtAllPegawai as $key => $row) { ?>
        <tr>
            <td style="text-align:center;"> <?=$row->NIP;?> </td>
            <td style="text-align:left;"> <?=$row->NAMA;?> </td>
            <td style="text-align:left;"> <?=$row->JABATAN;?> </td>
            <td style="text-align:left;"> <?=$row->NAMA_DEP;?> </td>
            <td style="text-align:left;"> <?=$row->NAMA_DIV;?> </td>
            <td style="text-align:right;"> <?=number_format($row->GAPOK);?> </td>

            <?PHP 
            foreach ($dt_setup_gaji as $key => $setup) {
                $getGajiDetail = $this->model->getGajiDetail($setup->ID, $row->ID, $bulan, $tahun);
                @$nilai = $getGajiDetail->NILAI;
                if(empty($nilai) || $nilai == null ){
                    $nilai = 0;
                }
            ?>
                <td style="text-align:right;"> <?=number_format($nilai);?> </td>
            <?PHP 
            }
            ?>            

            <?PHP if($sts_thr == 1){ 
                $getGajiDetail_THR = $this->model->getGajiDetailTHR($row->ID, $bulan, $tahun);
                echo '<td style="text-align:right;">'.number_format($getGajiDetail_THR->NILAI).'</td>';

            } ?>
        </tr>
        <?PHP } ?>
    </tbody>
</table>

<?php
    exit();
?>