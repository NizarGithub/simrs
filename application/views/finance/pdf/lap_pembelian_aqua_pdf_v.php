<?PHP  
	ob_start();         
?>

<style>
.grid th {
	background: #1793d1;
	vertical-align: middle;
	color : #FFF;
    text-align: center;
    height: 30px;
    font-size: 12px;
}
.grid td {
	background: #FFFFFF;
	vertical-align: middle;
	font: 11px/15px sans-serif;
	font-size: 11px;
    height: 20px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
    border-spacing: 0;
}
</style>

<?php
$sql = "SELECT * FROM admum_setup_logo WHERE ID = '1'";
$qry = $this->db->query($sql);
$row = $qry->row();
$logo = $row->LOGO;

$bulan_arr = array(
	1 =>	"Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
	5 =>	"Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
	9 =>	"September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
);
?>

<table align="left">
	<tr>
		<td style="padding-top: 0px;">
			<img src="<?php echo base_url(); ?>picture/logo/<?php echo $logo; ?>" style="width: 70px; height: 70px;">
		</td>
		<td style="text-align: right; vertical-align: bottom;">
			<b><?php echo $row->NAMA; ?></b><br>
			<p style="font-size: 11px; padding-left: 450px;">
			<?php echo $row->ALAMAT; ?><br>
			Telp. (031) <?php echo $row->TELEPON; ?>, Fax (031) <?php echo $row->FAX; ?>
			</p>
		</td>
	</tr>
</table>

<hr>
<br>

<table align="center">
	<tr>
		<td style="font-size:14px;">
			<b><?php echo $settitle; ?></b>
		</td>
	</tr>
	<tr>
		<td style="font-size:12px;">
			Bulan : <?php echo $bulan_arr[$bulan]; ?> Tahun : <?php echo $tahun; ?>
		</td>
	</tr>
</table>

<br>

<table align="left" class="grid">
	<thead>
        <tr>
            <th>NO</th>
            <th>KODE PEMBELIAN</th>
            <th>TANGGAL</th> 
            <th>NAMA BARANG</th>
            <th>HARGA</th>
            <th>JUMLAH</th>
            <th>TOTAL</th>
        </tr>
	</thead>
    <tbody>
        <?php
        $no = 0;
        $total = 0;
        foreach ($dt as $value) {
        	$no++;
            $total += $value->TOTAL;
        ?>
        <tr>
            <td style="width:30px; text-align: center;"><?php echo $no; ?></td>
            <td style="width: 100px; text-align: center;"><?php echo $value->KODE_PEMBELIAN; ?></td>
            <td style="width: 100px; text-align:center;"><?php echo $value->TANGGAL; ?></td>
            <td style="width: 160px;"><?php echo $value->NAMA_ALAT; ?></td>
            <td style="width: 70px; text-align:right;"><?php echo number_format($value->HARGA,0,'.',','); ?></td>
            <td style="width: 70px; text-align:center;"><?php echo $value->JUMLAH; ?></td>
            <td style="width: 70px; text-align:right;"><?php echo number_format($value->TOTAL,0,'.',','); ?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; font-size: 11px;">TOTAL</td>
            <td style="text-align: right; font-weight: bold; font-size: 11px;"><?php echo number_format($total,0,'.',','); ?></td>
        </tr>
    </tbody>
</table>

<?PHP
    // ----ukuran kertas dalam inch----//
    // custom
    $width_custom = 3.92126;
    $height_custom = 6.84252;
    //A2
    // $width_a2 = 23.4;
    // $height_a2 = 16.5;
    //------------------------//
    // $content = ob_get_clean();
    // $width_in_inches = $width_custom;
    // $height_in_inches = $height_custom;
    // $width_in_mm = $width_in_inches * 25.4;
    // $height_in_mm = $height_in_inches * 25.4;
    // $html2pdf = new HTML2PDF('P',array($height_in_mm,$width_in_mm),'en');
    // $html2pdf->pdf->SetTitle($settitle);
    // $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    // $html2pdf->Output($filename.'.pdf');

    $content = ob_get_clean();
    $html2pdf = new HTML2PDF('L','A5','fr');
    $html2pdf->pdf->SetTitle($settitle);
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($filename.'.pdf');
	exit();
?>