<?PHP 
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_jurnal_bayar_kas_bank.xls");
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
  padding-left: 10px;
  padding-right:10px;
  padding-top:5px;
  padding-bottom:5px;
}

.kolom_header{
    height: 20px;
    vertical-align: middle;
}

</style>


<table align="center" style="width:100%;">
    <tr>
        <td align="center">
            <h3>
                Laporan Jurnal Bayar Kas & Bank <br>
                <?=$judul;?>
            </h3>

            <h2 style="margin-top:-10px;"> <?=$data_usaha->NAMA_LAPORAN;?> </h2>
        </td>
    </tr>
</table>

<hr style="margin-top:-15px;">

<br>

<?PHP if(count($data) > 0) { ?>

<table align="center" class="grid" style="width:100%;">
    <?php

        echo "<tr>" ;
            echo "<th style='text-align:center;' class='kolom_header' colspan='2'> NO. TRANSAKSI </th>" ;
            echo "<th style='text-align:center;' class='kolom_header' rowspan='2'> URAIAN </th>" ;
            echo "<th style='text-align:center;' class='kolom_header' colspan='2'> CEK / GIRO </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> TRX AKUNTANSI </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> KREDIT </th>" ;
            echo "<th style='text-align:center;' class='kolom_header' colspan='6'> DEBET </th>" ;
        echo "</tr>" ;

        echo "<tr>" ;
            echo "<th style='text-align:center;' class='kolom_header'> TANGGAL </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> NO </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> TANGGAL</th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> NO </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> TOTAL DEBET </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> KAS BANK </th>" ;

            echo "<th style='text-align:center;' class='kolom_header'> UTANG USAHA </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> UTANG NON USAHA </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPn </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 21 </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 4(2) </th>" ;
            echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 23 </th>" ;
        echo "</tr>" ;

        $tot_debet = 0;        
        $tot_kas_bank = 0;        
        $tot_utang_usaha = 0;        
        $tot_utang_non_usaha = 0;        
        $tot_ppn = 0;        
        $tot_pph21 = 0;        
        $tot_pph42 = 0;        
        $tot_pph23 = 0;        

        foreach ($data as $key => $row) {

            $utang_usaha = 0;
            $utang_non_usaha = 0;
            $ppn = 0;
            $pph21 = 0;
            $pph42 = 0;
            $pph23 = 0;

            if($row->KODE_AKUN == '2-2000'){
                $utang_usaha = $row->DEBET;
            } else if($row->KODE_AKUN == '2-2030'){
                $utang_non_usaha = $row->DEBET;
            } else if($row->KODE_AKUN == '1-1900'){
                $ppn = $row->DEBET;
            } else if($row->KODE_AKUN == '2-2210'){
                $pph21 = $row->DEBET;
            } else if($row->KODE_AKUN == '2-2299'){
                $pph42 = $row->DEBET;
            } else if($row->KODE_AKUN == '2-2230'){
                $pph23 = $row->DEBET;
            }

            $tot_debet += $row->DEBET_VOUCHER;
            $tot_kas_bank += $row->KREDIT;
            $tot_utang_usaha += $utang_usaha;        
            $tot_utang_non_usaha += $utang_non_usaha;        
            $tot_ppn   += $ppn;        
            $tot_pph21 += $pph21;        
            $tot_pph42 += $pph42;        
            $tot_pph23 += $pph23;

            echo "<tr>" ;
                echo "<td class='gridtd' align='center' style='width:5%;'> ".$row->TGL_VOUCHER." </td>" ;
                echo "<td class='gridtd' align='center' style='width:10%;'> ".$row->NO_VOUCHER." </td>" ;
                echo "<td class='gridtd' align='left'   style='width:15%;'> ".$row->URAIAN." </td>" ;
                echo "<td class='gridtd' align='center'> ".$row->TGL_CEK."</td>" ;
                echo "<td class='gridtd' align='center'> ".$row->CEK_GIRO."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($row->DEBET_VOUCHER)." </td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($row->SISA_HUTANG)." </td>" ;

                echo "<td class='gridtd' align='right'> ".format_akuntansi($utang_usaha)."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($utang_non_usaha)."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($ppn)."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($pph21)."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($pph42)."</td>" ;
                echo "<td class='gridtd' align='right'> ".format_akuntansi($pph23)."</td>" ;

            echo "</tr>" ;  

               
        } 

        echo "<tr>" ;
                echo "<td class='gridtd' align='center' colspan='5'> <b> JUMLAH PINDAHAN </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_debet)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_kas_bank)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_utang_usaha)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_utang_non_usaha)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_ppn)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_pph21)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_pph42)." </b> </td>" ;
                echo "<td class='gridtd' align='right'> <b> ".format_akuntansi($tot_pph23)." </b> </td>" ;

        echo "</tr>" ; 


    ?>

</table>

<?PHP } else { ?>

<table align="center" class="grid" style="width:100%;">
    <?PHP 
    echo "<tr>" ;
        echo "<th style='text-align:center;' class='kolom_header' colspan='2'> NO. TRANSAKSI </th>" ;
        echo "<th style='text-align:center;' class='kolom_header' rowspan='2'> URAIAN </th>" ;
        echo "<th style='text-align:center;' class='kolom_header' colspan='2'> CEK / GIRO </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> TRX AKUNTANSI </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> KREDIT </th>" ;
        echo "<th style='text-align:center;' class='kolom_header' colspan='6'> DEBET </th>" ;
    echo "</tr>" ;

    echo "<tr>" ;
        echo "<th style='text-align:center;' class='kolom_header'> TANGGAL </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> NO </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> TANGGAL</th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> NO </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> TOTAL DEBET </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> KAS BANK </th>" ;

        echo "<th style='text-align:center;' class='kolom_header'> UTANG USAHA </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> UTANG NON USAHA </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPn </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 21 </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 4(2) </th>" ;
        echo "<th style='text-align:center;' class='kolom_header'> PAJAK PPh 23 </th>" ;
    echo "</tr>" ;

    echo "<tr>" ;
        echo "<td class='gridtd' align='center' colspan='13'> <b style='font-size:18px;'> Tidak ada data untuk periode yang anda pilih </b> </td>" ;

    echo "</tr>" ;
    ?>
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