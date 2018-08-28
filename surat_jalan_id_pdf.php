<?PHP  
ob_start();
// header("Cache-Control: no-cache, no-store, must-revalidate");  
// header("Content-Type: application/vnd.ms-excel");  
// header("Content-Disposition: attachment; filename=tes.xls");          
?>

<style>
.tabel {
    background: #fff;
    table-layout: fixed;
    border-collapse: collapse;
    border: 1px solid black;
    font-size: 14px;
    width: 900px;
}
.tabel th {
	background: #fff;
	vertical-align: middle;
	color : #000;
    height: 30px;
    font-size: 14px;
    border: 1px solid black;
}
.tabel td {
	background: #fff;
    vertical-align: middle;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    font-size: 14px;
    border: 1px solid black;
}

.footer{
    position:absolute;
    left:0;
    bottom:0;
}

.ttd{
    border-collapse: collapse;
    border: 1px solid black;
}
.ttd td{
    background: #fff;
    height: 30px;
    font-size: 14px;
    border: 1px solid black;
}

#wrapper {width:auto ;}
#left{
float:left;
width:65%;
background:orange;

}
#right{
background:red;
float:right;

}

.clear {clear:both;}
</style>

<table align="center">
    <tr>
        <td align="center">
            <h2><u>SURAT JALAN</u></h2>
        </td>
    </tr>
</table>

<br/><br/>

<table align="left" class="tulisan">
    <tr>
        <td style="font-size: 14px; vertical-align: middle;">No. Surat Jalan</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:300px; vertical-align: middle;"><b><?php echo $data->NOMOR_INVOICE; ?></b></td>
        <td style="font-size: 14px; width:130px;">&nbsp;</td>
        <td style="font-size: 14px; vertical-align: middle;">No. DO</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:160px; vertical-align: middle;"><b><?php echo $data->NOMOR_DO; ?></b></td>
    </tr>
    <tr>
        <td style="font-size: 14px; vertical-align: middle;">Tgl Surat Jalan</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:300px; vertical-align: middle;"><b><?php echo date('d-m-Y'); ?></b></td>
        <td style="font-size: 14px; width:130px;">&nbsp;</td>
        <td style="font-size: 14px; vertical-align: middle;">Tanggal Kirim</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:160px; vertical-align: middle;"><b><?php echo $data->TGL_PENGIRIMAN; ?></b></td>
    </tr>
    <tr>
        <td style="font-size: 14px; vertical-align: middle;">Penerima</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:300px; vertical-align: middle;"><b><?php echo $data->NAMA_PENERIMA; ?></b></td>
        <td style="font-size: 14px; width:130px;">&nbsp;</td>
        <td style="font-size: 14px; vertical-align: middle;">Tujuan</td>
        <td style="font-size: 14px; vertical-align: middle;">:</td>
        <td style="font-size: 14px; width:160px; vertical-align: middle;"><b><?php echo $data->tujuan; ?></b></td>
    </tr>
</table>

<br/><br/>

<table align="left" class="tabel">
    <thead>
        <tr>
            <th style="width:30px; text-align: center;">NO</th>
            <th style="width:200px; text-align: center;">KODE BARANG</th>
            <th style="width:300px; text-align: center;">JENIS BARANG</th>
            <th style="width:100px; text-align: center;">BERAT</th>
            <th style="width:100px; text-align: center;">HARGA</th>
            <!-- <th style="width:200px; text-align: center;">JUMLAH</th> -->
        </tr>
    </thead>
    <tbody>
    <?php
        $id_do = $data->ID;
        $sql = "SELECT
                    a.ID,
                    a.ID_DO,
                    a.ID_BARANG,
                    b.kode_barang,
                    b.nama_barang,
                    a.BERAT,
                    c.kode_satuan,
                    a.HARGA,
                    a.JUMLAH
                FROM do_detail a
                LEFT JOIN master_barang b ON b.id_barang = a.ID_BARANG
                LEFT JOIN master_satuan c ON c.id_satuan = b.id_satuan
                WHERE a.ID_DO = '$id_do'";
        $qry = $this->db->query($sql);
        $res = $qry->result();
        $no = 0;
        $tot_jumlah = 0;

        foreach ($res as $key => $val) {
            $no++;
            $tot_jumlah += $val->JUMLAH;
    ?>
        <tr>
            <td style="text-align: center;"><?php echo $no; ?></td>
            <td style="text-align: center;"><?php echo $val->kode_barang; ?></td>
            <td style="text-align: left;"><?php echo $val->nama_barang; ?></td>
            <td style="text-align: center;"><?php echo number_format($val->BERAT,0,',','.'); ?> <?php echo $val->kode_satuan; ?></td>
            <td style="text-align: right;"><?php echo number_format($val->HARGA,0,',','.'); ?></td>
            <!-- <td style="text-align: right;"><?php echo number_format($val->JUMLAH,0,',','.'); ?></td> -->
        </tr>
    <?php
        }
    ?>
    </tbody>
</table>
<br>
<p>Kendaraan No. : .............................................</p>
<div class="footer">
    <table align="left" class="ttd">
        <tr>
            <td style="text-align:center; vertical-align: middle; width:130px;">Hormat kami,</td>
            <!-- <td style="text-align:center; vertical-align: middle; width:130px;">Mengetahui / Gudang</td> -->
            <td style="text-align:center; vertical-align: middle; width:130px;">Sopir</td>
            <td style="text-align:center; vertical-align: middle; width:130px;">Penerima </td>
        </tr>
        <tr>
            <td style="height:80px; width:130px;">&nbsp;</td>
            <!-- <td style="height:80px; width:130px;">&nbsp;</td> -->
            <td style="height:80px; width:130px;">&nbsp;</td>
            <td style="height:80px; width:130px;">&nbsp;</td>
        </tr>
    </table>
</div>

<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 8.27;
    $height_custom = 11.69;
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
    $html2pdf = new HTML2PDF('L','A5','fr');
    $html2pdf->pdf->SetTitle('Surat Jalan');
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');

	// exit();
?>