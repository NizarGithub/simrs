<?PHP 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Buku_Besar.xls");
?>

<style>
.gridth {
    background: #1793d1;
    vertical-align: middle;
    color : #FFF;
    text-align: center;
    height: 30px;
    font-size: 14px;
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
  padding-top:5px;
  padding-bottom:5px;
}

.kolom_header{

}

</style>

<table align="center" style="width:100%;">
    <tr>
        <td align="center">
            <h3>
                Laporan Buku Besar <br>
                <?=$judul;?>
            </h3>

            <h2 style="margin-top:-10px;"> <?=$data_usaha->NAMA_LAPORAN;?> </h2>
        </td>
    </tr>
</table>


<table align="center" class="grid" style="width:100%;">
    <?php

        $u          = 1 ;
        $baris      = 0 ;
        $kolom      = 0 ;
        $debet      = 0 ;
        $kredit     = 0 ;
        $old_koper  = '' ;
        $next_koper = '' ;
        $last_key   = end(array_keys($data));
        $total_debet  = 0;
        $total_kredit = 0;
        $debet2  = 0;
        $kredit2 = 0;

        foreach ($data as $key => $row) {
            $nilDebet   = str_replace(',', '.', $row->DEBET);
            $nilKredit  = str_replace(',', '.', $row->KREDIT); 
            $koper      = trim($row->KODE_AKUN);           


            if ($old_koper != $koper) {
                
                echo "<tr>" ;
                    echo "<td style='background:#FFF; border:none; height:25px;' class='isi_no_border' colspan='6'>  </td>" ;
                echo "</tr>" ;
                echo "<tr>" ;
                    echo "<td style='background:#FFF; border:none; height:25px;' class='isi_no_border' colspan='6'>  </td>" ;
                echo "</tr>" ;


                echo "<tr>" ;
                echo "<td style='vertical-align:middle; background:#FFF; border-bottom:1px solid black; border-left:none; border-right:none;' class='isi_no_border'> <font style='font-size:18px;'> KODE AKUN : </font> </td>" ;
                echo "<td style='vertical-align:middle; background:#FFF; border-bottom:1px solid black; border-left:none; border-right:none;' class='isi_no_border' colspan='5'> <b style='font-size:18px;'>(".$koper.") ".$row->NAMA_AKUN."</b> </td>" ;
                echo "</tr>" ;



                echo "<tr>" ;
                echo "<th class='kolom_header' style='text-align:center; width:5%;'> No </th>" ;
                echo "<th class='kolom_header' style='text-align:center; width:10%;'> Tanggal </th>" ;
                echo "<th class='kolom_header' style='text-align:center; width:30%;'> Uraian </th>" ;
                echo "<th class='kolom_header' style='text-align:center; width:30%;'> Nomor Bukti </th>" ;
                echo "<th class='kolom_header' style='text-align:center; width:10%;'> Debet </th>" ;
                echo "<th class='kolom_header' style='text-align:center; width:10%;'> Kredit </th>" ;
                echo "</tr>" ;

                $old_koper = $koper ;
            }

            if($row->NO_BUKTI == 'SALDO AWAL'){

            echo "<tr>" ;
            echo "<td class='gridtd' style='text-align:center;'> <b> ".$u++." </b> </td>" ;
            echo "<td class='gridtd' style='text-align:center;'> <b> ".$row->TGL." </b> </td>" ;
            echo "<td class='gridtd'><b>".$row->URAIAN." </b> </td>" ;
            echo "<td class='gridtd'><b>".$row->NO_BUKTI." </b> </td>" ;
            echo "<td class='gridtd'><b>".number_format($nilDebet, 2, '.', ',')." </b> </td>" ; 
            echo "<td class='gridtd'><b>".number_format($nilKredit, 2, '.', ',')." </b> </td>" ; 
            echo "</tr>" ;

            } else {

            echo "<tr>" ;
            echo "<td class='gridtd' style='text-align:center;'>".$u++."</td>" ;
            echo "<td class='gridtd' style='text-align:center;'>".$row->TGL."</td>" ;
            echo "<td class='gridtd'>".$row->URAIAN."</td>" ;
            echo "<td class='gridtd'>".$row->NO_BUKTI."</td>" ;
            echo "<td class='gridtd'>".number_format($nilDebet, 2, '.', ',')."</td>" ; 
            echo "<td class='gridtd'>".number_format($nilKredit, 2, '.', ',')."</td>" ; 
            echo "</tr>" ;

            }

            $debet  += $nilDebet;
            $kredit += $nilKredit;

            if ($key < $last_key) {
                $k          = $key + 1;
                $next_koper = trim($data[$k]->KODE_AKUN) ;
            }

            if ($koper != $next_koper || $key >= $last_key) {
                echo "<tr>" ;
                echo "<td class='gridtd' colspan='4' style='text-align:center;'> JUMLAH </td>" ;
                echo "<td class='gridtd'>".number_format($debet, 2, '.', ',')."</td>" ;
                echo "<td class='gridtd'>".number_format($kredit, 2, '.', ',')."</td>" ;
                echo "</tr>" ;
                
                // $debet       = 0 ;
                // $kredit      = 0 ; 
                // $u           = 1 ;

                $debet2 += $debet;
                $kredit2 += $kredit;

                $saldo_akhir = $debet2 - $kredit2;
                if($saldo_akhir > 0){
                    $total_debet = $saldo_akhir;
                    $total_kredit = 0;
                } else if($saldo_akhir < 0) {
                    $total_debet =  0;
                    $total_kredit = abs($saldo_akhir);
                } else if($saldo_akhir == 0)  {
                    $total_debet =  0;
                    $total_kredit = 0;
                }

                echo "<tr>" ;
                        echo "<td class='gridtd' colspan='4' style='text-align:center;'> <b> SALDO AKHIR </b> </td>" ;
                        echo "<td class='gridtd'><b>".number_format($total_debet, 2, '.', ',')."</b></td>" ;
                        echo "<td class='gridtd'><b>".number_format($total_kredit, 2, '.', ',')."</b></td>" ;
                echo "</tr>" ;

                $debet2 = 0;
                $kredit2 = 0;
                  
                $debet  = 0;
                $kredit = 0;

                $u = 1;    

            }

            

            

        }
    ?>

</table>

<?PHP if(count($data) == 0){ ?>

<table align="center" class="grid" style="width:100%;">
    <tr>
        <th style='text-align:center; width:15%;' class='kolom_header'> No </th>
        <th style='text-align:center; width:15%;' class='kolom_header'> Tanggal </th>
        <th style='text-align:center; width:15%;' class='kolom_header'> Uraian </th>
        <th style='text-align:center; width:15%;' class='kolom_header'> Nomor Bukti </th>
        <th style='text-align:center; width:15%;' class='kolom_header'> Debet </th>
        <th style='text-align:center; width:15%;' class='kolom_header'> Kredit </th>
    </tr>
    <tr>
        <td class='gridtd' colspan='6' align="center"> <b> Tidak ada data yang dapat ditampilkan </b> </td>
    </tr>
</table>

<?PHP } ?>


<?PHP
exit();
?>
