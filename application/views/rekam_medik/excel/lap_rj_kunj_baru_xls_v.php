<?php  
	header("Cache-Control: no-cache, no-store, must-revalidate");  
	header("Content-Type: application/vnd.ms-excel");  
	header("Content-Disposition: attachment; filename=$filename.xls");
?>

<style>
.grid th {
	background: #1793d1;
	vertical-align: middle;
	color : #FFF;
	width: 90px;
    text-align: center;
    height: 40px;
    font-size: 14px;
    border: 1px solid #C5C5C5;
}
.grid td {
	background: #FFFFF0;
	vertical-align: middle;
	font: 13px sans-serif;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    border: 1px solid #C5C5C5;
}
.grid {
	background: #FAEBD7;
	border: 1px solid #C5C5C5;
	width: 800px;
    border-spacing: 0;
}
</style>

<?php
$sql = "SELECT * FROM admum_setup_logo WHERE ID = '1'";
$qry = $this->db->query($sql);
$row = $qry->row();
$logo = $row->LOGO;
?>

<table align="center">
    <tr>
        <td align="center" colspan="14">
            <h3>
                <?php echo $settitle; ?> <br>
                <?php echo $judul; ?>
            </h3>
        </td>
    </tr>
</table>

<br>

<table align="center" class="grid">
    <thead>
        <tr>
            <th width="120" style="text-align:center;"> NO</th>
            <th colspan="2" width="150" style="text-align:center;"> TANGGAL </th>
            <th colspan="2" width="170" style="text-align:center;"> NO RM </th>
            <th colspan="5" width="80" style="text-align:center;"> NAMA PASIEN </th>
            <th colspan="2" width="80" style="text-align:center;"> JENIS KELAMIN </th>
            <th colspan="2" width="120" style="text-align:center;"> STATUS </th>
        </tr>
    </thead>
    <tbody>
    	<?php
        $no = 0;
        $total = 0;
        foreach ($dt as $value) {
        	$no++;
            $total = count($dt);
            $value->JENIS_KELAMIN = $value->JENIS_KELAMIN=='L'?'Laki - Laki':'Perempuan';
        ?>
        <tr>
            <td width="120" style="text-align: center;"><?php echo $no; ?></td>
            <td colspan="2" width="150" style="text-align: center;"><?php echo $value->TANGGAL_DAFTAR; ?></td>
            <td colspan="2" width="170" style="text-align: center;"><?php echo $value->KODE_PASIEN; ?></td>
            <td colspan="5" width="150"><?php echo $value->NAMA; ?></td>
            <td colspan="2" width="80" style="text-align: center;"><?php echo $value->JENIS_KELAMIN; ?></td>
            <td colspan="2" width="80" style="text-align: center;"><?php echo $value->JENIS_PASIEN; ?></td>
        </tr>
        <?php
            }
        ?>
        <tr>
        	<td colspan="12" style="text-align: center; font-weight: bold;">TOTAL</td>
        	<td colspan="2" style="text-align: right; font-weight: bold;"><?php echo number_format($total,0,',','.'); ?></td>
        </tr>
    </tbody>
</table>