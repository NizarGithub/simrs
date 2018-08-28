<?PHP  
ob_start();        
?>

<style type="text/css">
.tabel {
    background: #fff;
    table-layout: fixed;
    border-collapse: collapse;
    border: 1px solid black;
    font-size: 14px;
    width: 100%;
}
.tabel th {
    background: #fff;
    vertical-align: middle;
    color : #000;
    font-size: 12px;
    border: 1px solid black;
    text-align: center;
}
.tabel td {
    background: #fff;
    vertical-align: middle;
    padding-left: 5px;
    padding-right: 5px;
    font-size: 10px;
    border: 1px solid black;
}
</style>

<table align="center">
    <tr>
        <td style="text-align: center;">
            DAFTAR TARIF KAMAR DAN VISITE DOKTER <br>
            UNTUK RAWAT INAP RSIA SOERYA
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>

<table align="center" class="tabel">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;" width="50">No</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Kode Kamar</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Kelas</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Biaya</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Visite Dokter Sp.</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Biaya Visite</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Jasa Sarana RS</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Peruntukan Kamar</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if($dt == "" || $dt == null){

    }else{
        $no = 0;

        foreach ($dt as $val) {
            $no++;
    ?>
        <tr>
            <td style="height:25px; width: 3%; text-align:center;"><?php echo $no; ?></td>
            <td style="height:25px; width: 10%; text-align:center;"><?php echo $val->KODE_KAMAR; ?></td>
            <td style="height:25px; width: 10%; text-align:center;"><?php echo $val->KELAS; ?></td>
            <td style="height:25px; width: 10%; text-align: right;"><?php echo number_format($val->BIAYA,0,',','.'); ?></td>
            <td style="height:25px; width: 15%; text-align:center;"><?php echo $val->VISITE_DOKTER; ?></td>
            <td style="height:25px; width: 10%; text-align: right;"><?php echo number_format($val->BIAYA_VISITE,0,',','.'); ?></td>
            <td style="height:25px; width: 10%; text-align: right;"><?php echo number_format($val->JASA_SARANA,0,',','.'); ?></td>
            <td style="height:25px; width: 15%;"><?php echo $val->PERUNTUKAN_KAMAR; ?></td>
        </tr>
    <?php   
        }
    }
    ?>
    </tbody>
</table>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 11.69;
    $height_custom = 8.27;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('L','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>