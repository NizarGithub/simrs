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
            DAFTAR TARIF POLI UNTUK RAWAT JALAN <br>
            RSIA SOERYA
        </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
</table>

<table class="tabel">
    <thead>
        <tr>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;" width="50">No</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Jenis</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Status</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Keterangan</th>
            <th style="height:30px; text-align:center; background-color: #ed1c24; color: #fff;">Biaya</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if($dt == "" || $dt == null){

    }else{
        $n = 0;
        foreach ($dt as $v) {
            $n++;
            $nama = $v->NAMA;
    ?>
        <tr>
            <td style="height: 25px; font-weight: bold; text-align: center;"><?php echo $n; ?></td>
            <td style="height: 25px; font-weight: bold;" colspan="4"><?php echo $v->NAMA; ?></td>
        </tr>
        <?php
            $data = $this->model->data_cetak_poli($nama);
            $no = 0;
            foreach ($data as $key => $val) {
                $no++;
        ?>
            <tr>
                <td style="height: 25px; text-align:right;"><?php echo $n.'.'.$no; ?></td>
                <td style="height: 25px; width: 15%;"><?php echo $val->JENIS; ?></td>
                <td style="height: 25px; width: 30%;"><?php echo $val->STATUS; ?></td>
                <td style="height: 25px; width: 33%;"><?php echo $val->KETERANGAN; ?></td>
                <td style="height: 25px; text-align: right; width: 15%;"><?php echo number_format($val->BIAYA,0,',','.'); ?></td>
            </tr>
        <?php
            }
        ?>
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
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>