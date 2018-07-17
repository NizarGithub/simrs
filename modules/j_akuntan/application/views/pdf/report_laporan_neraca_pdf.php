<?PHP  ob_start(); ?>

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

table th {
  border: 1px solid black;
}

.grid td {
    border-left: 1px solid black;
    border-right: 1px solid black;
    border-top: none;
    border-bottom: none;
}

.kolom_header{
    height: 20px;
    padding: 10px;
    vertical-align: middle;
}

</style>



<table align="center" style="width:100%;">
    <tr>
        <td align="center">
            <h3>
                Laporan Neraca <br>
                <?=$judul;?>               
            </h3>

            <h2 style="margin-top:-10px;"> <?=$data_usaha->NAMA_LAPORAN;?> </h2>
        </td>
    </tr>
</table>

<table style="width: 100%;" cellspacing="0">
    <tr>
        <td style="vertical-align:top; width:50%;">
            <table align="right" class="grid" style="width:100%;">
                    <tr>
                        <th style='text-align:center; width:50%;' class='kolom_header'> URAIAN </th>
                        <th style='text-align:center;' class='kolom_header'> BULAN INI </th>
                        <th style='text-align:center;' class='kolom_header'> BULAN LALU </th>
                        <th style='text-align:center;' class='kolom_header'> JUMLAH</th>

                    </tr>

                    <?PHP 
                    $u              = 1 ;
                    $baris          = 0 ;
                    $kolom          = 0 ;
                    $saldo          = 0 ;
                    $saldo_lalu     = 0 ;
                    $saldo2         = 0 ;
                    $saldo_lalu2    = 0 ;
                    $saldo_all      = 0;
                    $saldo_lalu_all = 0;
                    $old_koper      = '' ;
                    $koper2         = '' ;
                    $next_koper     = '' ;
                    $last_key       = end(array_keys($dt_aktiva));

                    $next_koper2 = '' ;
                    $last_key2   = end(array_keys($dt_wajib));

                    $total_debet  = 0;
                    $total_kredit = 0;

                    foreach ($dt_aktiva as $key => $row_aktiva) {
                        $nilSaldo   = str_replace(',', '.', $row_aktiva->SALDO);
                        $nilSaldoLalu  = str_replace(',', '.', $row_aktiva->SALDO_LALU); 
                        $koper      = trim($row_aktiva->JUDUL);  

                        $saldo_all  += $nilSaldo;
                        $saldo_lalu_all += $nilSaldoLalu;

                        if ($old_koper != $koper) {

                            echo "<tr>" ;
                                echo "<td class='gridtd' align='left'> <b style='font-size:14px;'> ".$koper." </b> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                            echo "</tr>" ;               

                            $old_koper = $koper ;
                        }

                        echo "<tr>" ;
                        echo "<td class='gridtd' align='left'> &nbsp;&nbsp;&nbsp; - ".$row_aktiva->NAMA_AKUN."</td>" ;
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldo)."</td>" ; 
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldoLalu)."</td>" ; 
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldo + $nilSaldoLalu)."</td>" ; 
                        echo "</tr>" ;

                        $saldo  += $nilSaldo;
                        $saldo_lalu += $nilSaldoLalu;
                        

                        if ($key < $last_key) {
                            $k          = $key + 1;
                            $next_koper = trim($dt_aktiva[$k]->JUDUL) ;
                        }

                        if ($koper != $next_koper || $key >= $last_key) {
                            echo "<tr>" ;
                                echo "<td style='border:1px solid black;' align='left'  class='gridtd'> &nbsp;&nbsp;&nbsp; <b> Jumlah ".$koper." </b> </td>" ;
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo)."</b></td>" ; 
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_lalu)."</b></td>" ; 
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo + $saldo_lalu)."</b></td>" ; 
                            echo "</tr>" ; 

                            $saldo  = 0;
                            $saldo_lalu = 0;

                        }

                    }

                    echo "<tr>" ;
                        echo "<td style='border:1px solid black;' align='left'  class='gridtd'> <b> TOTAL ASET </b> </td>" ;
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_all)."</b></td>" ; 
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_lalu_all)."</b></td>" ; 
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_all + $saldo_lalu_all)."</b></td>" ; 
                    echo "</tr>" ; 

                    ?>

            </table>
        </td>

        <td style="vertical-align:top; width:50%;">
            <table align="left" class="grid" style="width:100%;">    
                    <tr>
                        <th style='text-align:center; width:50%;' class='kolom_header'> URAIAN </th>
                        <th style='text-align:center;' class='kolom_header'> BULAN INI </th>
                        <th style='text-align:center;' class='kolom_header'> BULAN LALU </th>
                        <th style='text-align:center;' class='kolom_header'> JUMLAH</th>
                    </tr>

                    <?PHP 
                    $u               = 1 ;
                    $baris           = 0 ;
                    $kolom           = 0 ;
                    $saldo           = 0 ;
                    $saldo_lalu      = 0 ;
                    $saldo2          = 0 ;
                    $saldo_lalu2     = 0 ;
                    $saldo_all2      = 0;
                    $saldo_lalu_all2 = 0;
                    $old_koper  = '' ;
                    $koper2  = '' ;
                    $next_koper = '' ;
                    $last_key   = end(array_keys($dt_aktiva));

                    $next_koper2 = '' ;
                    $last_key2   = end(array_keys($dt_wajib));

                    $total_debet  = 0;
                    $total_kredit = 0;
                   
                    foreach ($dt_wajib as $key2 => $row_wajib) {
                        $koper      = trim($row_wajib->JUDUL);  
                        if($row_wajib->NAMA_AKUN == "Laba Ditahan"){
                            $nilSaldo2   = str_replace(',', '.', $laba->JML *(1) );
                            $nilSaldoLalu2  = str_replace(',', '.', $laba_lalu->JML *(1) ); 
                        } else {
                            $nilSaldo2   = str_replace(',', '.', $row_wajib->SALDO * (-1));
                            $nilSaldoLalu2  = str_replace(',', '.', $row_wajib->SALDO_LALU * (-1)); 
                        }
                        
                        $saldo_all2  += $nilSaldo2;
                        $saldo_lalu_all2 += $nilSaldoLalu2;

                        if ($old_koper != $koper) {

                            echo "<tr>" ;
                                echo "<td class='gridtd' align='left'> <b style='font-size:14px;'> ".$koper." </b> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                                echo "<td class='gridtd' align='left'> </td>" ;
                            echo "</tr>" ;               

                            $old_koper = $koper ;
                        }

                        echo "<tr>" ;
                        echo "<td class='gridtd' align='left'> &nbsp;&nbsp;&nbsp; - ".$row_wajib->NAMA_AKUN."</td>" ;
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldo2)."</td>" ; 
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldoLalu2)."</td>" ; 
                        echo "<td align='right' class='gridtd'>".format_akuntansi($nilSaldo2 + $nilSaldoLalu2)."</td>" ; 
                        echo "</tr>" ;

                        $saldo2  += $nilSaldo2;
                        $saldo_lalu2 += $nilSaldoLalu2;
                        

                        if ($key2 < $last_key2) {
                            $k2          = $key2 + 1;
                            $next_koper2 = trim($dt_wajib[$k2]->JUDUL) ;
                        }

                        if ($koper != $next_koper2 || $key2 >= $last_key2) {
                            echo "<tr>" ;
                                echo "<td style='border:1px solid black;' align='left'  class='gridtd'> &nbsp;&nbsp;&nbsp; <b> Jumlah ".$koper." </b> </td>" ;
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo2)."</b></td>" ; 
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_lalu2)."</b></td>" ; 
                                echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo2 + $saldo_lalu2)."</b></td>" ; 
                            echo "</tr>" ; 

                            $saldo2  = 0;
                            $saldo_lalu2 = 0;     

                        }

                    }

                    echo "<tr>" ;
                        echo "<td align='left'  class='gridtd'> </td>" ;
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                    echo "</tr>" ;

                    echo "<tr>" ;
                        echo "<td align='left'  class='gridtd'> </td>" ;
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                    echo "</tr>" ;

                    echo "<tr>" ;
                        echo "<td align='left'  class='gridtd'> </td>" ;
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                    echo "</tr>" ;

                    echo "<tr>" ;
                        echo "<td align='left'  class='gridtd'> </td>" ;
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                        echo "<td align='right' class='gridtd'> </td>" ; 
                    echo "</tr>" ;

                    echo "<tr>" ;
                        echo "<td style='height:30.2px; border:1px solid black;' align='left'  class='gridtd'> </td>" ;
                        echo "<td style='height:30.2px; border:1px solid black;' align='right' class='gridtd'> </td>" ; 
                        echo "<td style='height:30.2px; border:1px solid black;' align='right' class='gridtd'> </td>" ; 
                        echo "<td style='height:30.2px; border:1px solid black;' align='right' class='gridtd'> </td>" ; 
                    echo "</tr>" ; 

                    echo "<tr>" ;
                        echo "<td style='border:1px solid black;' align='left'  class='gridtd'> <b> TOTAL KEWAJIBAN DAN EKUITAS </b> </td>" ;
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_all2)."</b></td>" ; 
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_lalu_all2)."</b></td>" ; 
                        echo "<td style='border:1px solid black;' align='right' class='gridtd'><b>".format_akuntansi($saldo_all2 + $saldo_lalu_all2)."</b></td>" ; 
                    echo "</tr>" ; 

                    ?>

            </table>
        </td>
    </tr>
</table>



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
    $width_custom = 14;
    $height_custom = 8.50;
    
    $content = ob_get_clean();
    $width_in_inches = $width_custom;
    $height_in_inches = $height_custom;
    $width_in_mm = $width_in_inches * 22.4;
    $height_in_mm = $height_in_inches * 2.4;
    $html2pdf = new HTML2PDF('L','A3','en');
    $html2pdf->pdf->SetTitle('Laporan Neraca');
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Laporan_neraca.pdf');
?>