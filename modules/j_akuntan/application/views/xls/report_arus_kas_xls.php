<?PHP 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Arus_Kas.xls");
?>
<style>
.gridth {
    background: #1793d1;
    vertical-align: middle;
    color : #FFF;
    text-align: center;
    height: 30px;
    font-size: 20px;
}
.gridtd {
    background: #FFFFF0;
    vertical-align: middle;
    font-size: 14px;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
    background: #FAEBD7;
    border-collapse: collapse;
}

.grid td, table th {
  border: 1px solid black;
}

.kolom_header{
    height: 20px;
}

</style>

<table align="center" style="width:100%;">
    <tr>
        <td align="center">
            <h3>
                Laporan Arus Kas <br>
                <?=$judul;?>
            </h3>

            <h2 style="margin-top:-10px;"> <?=$data_usaha->NAMA_LAPORAN;?> </h2>
        </td>
    </tr>
</table>


<br>

<?PHP if(count($data) > 0) { ?>

<table align="center" class="grid" style="width:100%;">
    <?php

        echo "<tr>" ;
            echo "<th style='font-size:14px; text-align:center; width:5%;'  class='kolom_header'> No </th>" ;
            echo "<th style='font-size:14px; text-align:center; width:15%;' class='kolom_header'> Kode Akun </th>" ;
            echo "<th style='font-size:14px; text-align:center; width:50%;' class='kolom_header'> Nama Akun </th>" ;
            echo "<th style='font-size:14px; text-align:center; width:30%;' class='kolom_header'> Jumlah </th>" ;
        echo "</tr>" ;

        $u          = 1 ;
        $baris      = 0 ;
        $kolom      = 0 ;
        $debet      = 0 ;
        $kredit     = 0 ;
        $pindahan_kas  = 0 ;
        $old_koper  = '' ;
        $koper2  = '' ;
        $next_koper = '' ;
        $judul_1 = '' ;
        $judul_2 = '' ;
        $last_key   = end(array_keys($data));
        $total_debet  = 0;
        $total_kredit = 0;
        $debet2  = 0;
        $kredit2 = 0;

        $total_pend_opr = 0;
        $total_biaya_opr = 0;
        $total_non_opr = 0;

        foreach ($data as $key => $row) {
            $nilDebet   = str_replace(',', '.', $row->JML);
            $nilKredit  = str_replace(',', '.', $row->JML); 

            if($row->TIPE == 'MINUS'){
                $nilDebet  = str_replace(',', '.', $row->JML * -1); 
            } else {
                $nilDebet  = str_replace(',', '.', $row->JML); 
            }
            $koper      = trim($row->JENIS);  

            if($koper == "Pendapatan Operasional"){
                $judul_1 = "Pendapatan";
            } else if($koper == "Biaya Operasional"){
                $judul_1 = "Biaya";
            } else if($koper == "Aktivitas Non Operasional"){
                $judul_1 = "Lainnya";
            }

            // TOTAL PER BAGIAN

            if($koper == "Pendapatan Operasional"){
                $total_pend_opr += $nilDebet;
            } else if($koper == "Biaya Operasional"){
                $total_biaya_opr += $nilDebet;
            } else if($koper == "Aktivitas Non Operasional"){
                $total_non_opr += $nilDebet;
            }

            // END OF TOTAL PER BAGIAN    

            $debet2  += $nilDebet;
            $kredit2 += $nilKredit;

            if ($old_koper != $koper) {

                if($judul_1 == "Pendapatan"){

                    echo "<tr>" ;
                        echo "<td class='kolom_header' align='left' colspan='4'> <b style='font-size:16px;'> Aktivitas Operasional </b> </td>" ;
                    echo "</tr>" ; 

                } else if($judul_1 == "Lainnya"){

                    echo "<tr>" ;
                        echo "<td class='kolom_header' align='left' colspan='4'> <b style='font-size:16px;'> Aktivitas Non Operasional </b> </td>" ;
                    echo "</tr>" ; 
                }

                echo "<tr>" ;
                    echo "<td class='kolom_header' align='left' colspan='4'> <b style='font-size:14px;'> ".$judul_1." </b> </td>" ;
                echo "</tr>" ;               

                $old_koper = $koper ;
            }

            echo "<tr>" ;
            echo "<td class='gridtd' align='center'>".$u++."</td>" ;
            echo "<td class='gridtd' align='center'>(".$row->KODE_AKUN.")</td>" ;
            echo "<td class='gridtd'>".$row->NAMA_AKUN."</td>" ;
            echo "<td align='right' class='gridtd'>".format_akuntansi($nilDebet)."</td>" ; 
            echo "</tr>" ;

            $debet  += $nilDebet;
            $kredit += $nilKredit;

            

            if ($key < $last_key) {
                $k          = $key + 1;
                $next_koper = trim($data[$k]->JENIS) ;
            }

            if ($koper != $next_koper || $key >= $last_key) {
                echo "<tr>" ;
                echo "<td class='gridtd' colspan='3' align='right'> <b> Total ".$koper." </b> </td>" ;
                echo "<td align='right' class='gridtd'> <b> ".format_akuntansi($debet)." </b> </td>" ;
                echo "</tr>" ;

                if($koper == "Biaya Operasional"){

                    $total_aktifitas_opr = $total_pend_opr - $total_biaya_opr;

                    echo "<tr>" ;
                    echo "<td style='color:#002663;' class='gridtd' colspan='3' align='right'> <b> TOTAL AKTIVITAS OPERASIONAL </b> </td>" ;
                    echo "<td style='color:#002663;' align='right' class='gridtd'> <b> ".format_akuntansi($total_aktifitas_opr)." </b> </td>" ;
                    echo "</tr>" ; 
                } 

                if($koper == "Aktivitas Non Operasional"){

                    $pindahan_kas = ($total_pend_opr - $total_biaya_opr) + $total_non_opr;

                    echo "<tr>" ;
                    echo "<td style='color:#002663;' class='gridtd' colspan='3' align='right'> <b> PERPINDAHAN KAS BERSIH </b> </td>" ;
                    echo "<td style='color:#002663;' align='right' class='gridtd'> <b> ".format_akuntansi($pindahan_kas)." </b> </td>" ;
                    echo "</tr>" ; 
                }                

                $debet  = 0;
                $kredit = 0;

                $u = 1;    

            }

            

            

        }

    ?>
</table>

<br>
<hr>

<h3> Ringkasan </h3>
<table align="left"  style="width:100%;">
<tr>
    <td style='font-size:15px; text-align:left; width:25%;'>  <b> Saldo Pembukaan </b> </td>
    <td style='font-size:15px; text-align:right; width:25%;'> <b> Rp <?=format_akuntansi($sa);?> </b> </td>
</tr> 

<tr>
    <td style='font-size:15px; text-align:left; width:25%;'>  <b> Perpindahan Kas Bersih </b> </td>
    <td style='font-size:15px; text-align:right; width:25%;'> <b> Rp <?=format_akuntansi($pindahan_kas);?> </b> </td>
</tr>   

<tr>
    <td style='font-size:15px; text-align:left; width:25%;'>  <b> Saldo Penutupan </b> </td>
    <td style='font-size:15px; text-align:right; width:25%;'> <b> Rp <?=format_akuntansi($sa + $pindahan_kas);?> </b> </td>
</tr>   
</table>

<?PHP } else { ?>

<table align="center" class="grid" style="width:100%;">
    <tr>
        <th style='text-align:center; width:25%;' class='kolom_header'> No </th>
        <th style='text-align:center; width:25%;' class='kolom_header'> Kode Akun </th>
        <th style='text-align:center; width:25%;' class='kolom_header'> Nama Akun </th>
        <th style='text-align:center; width:25%;' class='kolom_header'> Jumlah </th>
    </tr>
    <tr>
        <td class='gridtd' colspan='4' align="center"> <b> Tidak ada data yang dapat ditampilkan </b> </td>
    </tr>
</table>

<?PHP } ?>

<?PHP 
    function format_akuntansi($value)
    {
        if($value >= 0){
            $value = number_format($value, 2, '.', ',');
        } else {
            $value = number_format(abs($value), 2, '.', ',');
            $value = "(".$value.")";
        }

        return $value;
    }
?>


<?PHP
    exit();
?>