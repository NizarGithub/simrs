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

.footer{
	position: absolute;
	bottom: 0;
	right: 0;
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
</table>

<br>

<table align="left">
	<tr>
		<td style="font-size:12px;">Kode Permintaan</td>
		<td style="font-size:12px;">: <?php echo $dt_row->KODE_PO; ?></td>
	</tr>
	<tr>
		<td style="font-size:12px;">Tgl Permintaan</td>
		<td style="font-size:12px;">: <?php echo $dt_row->TANGGAL; ?></td>
	</tr>
</table>

<br>

<table align="left" class="grid">
	<thead>
        <tr>
            <th>NO</th>
            <th>NAMA BARANG</th>
            <th>KATEGORI</th> 
            <th>JUMLAH</th>
            <th>DEPARTEMEN</th>
            <th>DIVISI</th>
        </tr>
	</thead>
    <tbody>
        <?php
        $no = 0;
        foreach ($dt_res as $value) {
        	$no++;
        ?>
        <tr>
            <td style="width:30px; text-align: center;"><?php echo $no; ?></td>
            <td style="width: 220px;"><?php echo $value->NAMA_ALAT; ?></td>
            <td style="width: 100px; text-align:center;"><?php echo $value->NAMA_KATEGORI; ?></td>
            <td style="width: 70px; text-align:center;"><?php echo number_format($value->JUMLAH_PERMINTAAN,0,'.',','); ?></td>
            <td style="width: 100px; text-align:center;"><?php echo $value->NAMA_DEP; ?></td>
            <td style="width: 100px; text-align:center;"><?php echo $value->NAMA_DIV; ?></td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>

<br>

<div class="footer">
	<table align="right">
		<tr>
			<td style="text-align:center;">
				Sidoarjo, <?php echo date('d'); ?> <?php echo $bulan_arr[date('n')]; ?> <?php echo date('Y'); ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:center;">
				Yang menyetujui
			</td>
		</tr>
		<tr>
			<td style="height:50px;">&nbsp;</td>
		</tr>
		<tr>
			<td style="text-align:center;">
				(<?php echo $pegawai; ?>)
			</td>
		</tr>
	</table>
</div>

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