<?PHP
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

$bulan_arr = array(
  1 =>  "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
  5 =>  "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
  9 =>  "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
);
?>

<table align="center">
    <tr>
        <td align="center" colspan="14">
            <h3><?php echo $settitle; ?></h3>
            <h4><?php echo $judul; ?></h4>
        </td>
    </tr>
</table>

<br>

<table align="center" class="grid">
    <thead>
        <tr>
            <th width="120" style="text-align:center;"> NO</th>
            <th width="80" style="text-align:center;"> KODE BARANG </th>
            <th colspan="3" width="80" style="text-align:center;"> NAMA BARANG </th>
            <th colspan="3" width="80" style="text-align:center;"> KATEGORI </th>
            <th colspan="2" width="120" style="text-align:center;"> STOK </th>
            <th colspan="2" width="80" style="text-align:center;"> HARGA BELI </th>
            <th colspan="2" width="250" style="text-align:center;"> KETERANGAN </th>
        </tr>
    </thead>
    <tbody>
    <?php
      $no = 0;
      if($dt == "" || $dt == null){
        echo "<tr><td colspan='14' style='text-align:center;'>Data Tidak Ada</td></tr>";
      }else{
        foreach ($dt as $value) {
        $no++;
    ?>
      <tr>
          <td width="120" style="text-align: center;"><?php echo $no; ?></td>
          <td width="170" style="text-align: center;"><?php echo $value->KODE_ALAT; ?></td>
          <td colspan="3" width="150"><?php echo $value->NAMA_ALAT; ?></td>
          <td colspan="3" width="150" style="text-align: center;"><?php echo $value->NAMA_KATEGORI; ?></td>
          <td colspan="2" style="width:120px; text-align: center;"><?php echo number_format($value->TOTAL,0,',','.'); ?></td>
          <td colspan="2" width="80" style="text-align: right;"><?php echo number_format($value->HARGA_BELI,0,',','.'); ?></td>
          <td colspan="2" width="250"><?php echo $value->KETERANGAN; ?></td>
      </tr>
    <?php
        }
      }
    ?>
    </tbody>
</table>

<?php
    exit();
?>
