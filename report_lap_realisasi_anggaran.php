<?PHP  
ob_start(); 
// header("Cache-Control: no-cache, no-store, must-revalidate");  
// header("Content-Type: application/vnd.ms-excel");  
// //header("Content-Disposition: attachment; filename=laporan_revisi_rkap.xls");               
// header("Content-Disposition: attachment; filename=tes.xls");  
?>

<style>

.grid th {
	background: #1793d1;
	vertical-align: middle;
	color : #FFF;
	width: 90px;
    text-align: center;
    height: 40px;
}
.grid td {
	background: #FFFFF0;
	vertical-align: middle;
	font: 11px/15px sans-serif;
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
}
.grid {
	background: #FAEBD7;
	border: 2px solid #C5C5C5;
	width: 800px;
    border-spacing: 0;
}

.judul{
    height: 50px;
}

.kolom_header {
    height        : 40px;
    background    : #dadada ;
    font-weight   : bold; 
    text-align    : center;
    border-style  : solid;
    border-width  : thin;
    font-size     : 12px; 
  }

.title_header {
font-weight   : bold; 
text-align    : left;
font-size     : 14px;
}

.isi_table  {
  font-size     : 11px;
  border-style  : solid;
  border-width  : thin;   
text-align    : left;
}

#persegipanjangmerah{
    width:50px;
    height:20px;
    background:#ff2b00;
}
#persegipanjangkuning{
    width:50px;
    height:20px;
    background:#ffe600;
}
#persegipanjangbiru{
    width:50px;
    height:20px;
    background:#0079C1;
}
#persegipanjangorange{
    width:50px;
    height:20px;
    background:#faa43d;
}
#persegipanjangungu{
    width:50px;
    height:20px;
    background:#d0006f;
}
</style>

<?php
function angka_positif($angka){
    if($angka < 0){
        $angka = -$angka;
        $angka = "(".$angka.")";
    }else{
        $angka = $angka;
    }
    return $angka;
}

function angka_positif_rp($angka){
    if($angka < 0){
        $angka = -$angka;
        $angka = "(Rp. ".number_format($angka,2,',','.').")";
    }else{
        $angka = number_format($angka,2,',','.');
    }
    return $angka;
}
?>
<table align="center">
    <tr>
        <td>
            <img src="<?=base_url();?>files/pdam/logo-pdamtp.png" width="110" height="90" alt="KOP PDAM">
        </td>
        <td><img src="<?=base_url();?>files/pdam/kosong.png" width="700" height="90" alt="KOP PDAM"></td>
        <td>
            <img src="<?=base_url();?>files/pdam/KOP_tulisan.png" width="550" height="90" alt="KOP PDAM">
        </td>
        <td><img src="<?=base_url();?>files/pdam/kosong.png" width="700" height="90" alt="KOP PDAM"></td>
        <td>
            <img src="<?=base_url();?>files/pdam/logo_perpamsi_glossy.png" width="160" height="90" alt="KOP PDAM">
        </td>
    </tr>
</table>

<hr>

<table align="center">
    <tr>
        <td align="center">
            <h3>
                <?php echo $title; ?> <br>
                <?php echo $bag_subbag; ?> <br>
                TAHUN <?php echo $thn;?>
            </h3>
        </td>
    </tr>
</table>

<br>

<table align="center" class="grid">
    <thead>
        <tr>
            <th width="120" style="text-align:center;"> NO. ANGGARAN</th>
            <th width="170" style="text-align:center;"> URAIAN </th>
            <th width="150" style="text-align:center;"> SUB BAGIAN </th>
            <th width="80" style="text-align:center;"> VOL </th>
            <th width="80" style="text-align:center;"> SATUAN </th>
            <th width="120" style="text-align:center;"> HARGA<br/>SATUAN </th>
            <th width="120" style="text-align:center;"> RKAP </th>
            <th width="220" style="text-align:center;"> NO BUKTI </th>
            <th width="80" style="text-align:center;"> NO KEU </th>
            <th width="150" style="text-align:center;"> TANGGAL </th>
            <th width="100" style="text-align:center;"> REALISASI<br/>(VOL) </th>
            <th width="120" style="text-align:center;"> REALISASI<br/>(HARGA/UNIT) </th>
            <th width="120" style="text-align:center;"> REALISASI<br/>(TOTAL) </th>
            <th width="100" style="text-align:center;"> SISA<br/>(VOL) </th>
            <th width="120" style="text-align:center;"> SISA<br/>(RP) </th>
        </tr>
    </thead>
    <tbody>
    <?php
        $tot_rkap = 0;
        $tot_realisasi = 0;
        $old_judul = "";
        $old_judul2 = "";
        $next_judul1 = "";
        $next_judul2 = "";
        $total_semua1 = 0;
        $total_semua2 = 0;
        $total_all1 = 0;
        $total_all2 = 0;
        $last_key = end(array_keys($kd_perkiraan));

        foreach ($kd_perkiraan as $key => $koper) {
            $kode_perkiraan = $koper->KODE_PERKIRAAN2;

            $dat_nama = $this->db->query("select a.NAMA_PERKIRAAN from stp_setup_kode_perkiraan a 
                                    where trim(a.KODE_PERKIRAAN)= '$kode_perkiraan'")->row();
            if ($dat_nama != null) {
                $nama_perkiraan = $dat_nama->NAMA_PERKIRAAN;
            } else {
                $nama_perkiraan = "" ;
            }

            $judul1 = TRIM($koper->INDUK_KODE);

            if ($old_judul != $judul1) {
                $old_judul  = $judul1 ;
    ?>
        <tr>
            <td colspan="15"><b><?php echo $koper->INDUK_KODE." - ".$koper->NAMA_PERKIRAAN2; ?></b></td>
        </tr>
    <?php
            }
    ?>
        <tr>
            <td colspan="15"><b><?php echo $koper->KODE_PERKIRAAN." - ".$koper->NAMA_PERKIRAAN; ?></b></td>
        </tr>
        <?php
            $kode_anggaran_kosong = "";
            $uraian = "";
            $divisi = "";
            $jumlah = "";
            $satuan = "";
            $harga = "";
            $rkap = "";
            // $no_bukti = "";
            // $no_keu = "";
            // $tanggal = "";
            // $real_vol = 0;
            // $real_harga = 0;
            $real = "";
            $real2 = "";
            $sisa_vol = "";
            $sisa_rp = "";

            //untuk tabel
            $kode_anggaran = "";

            $realisasi = $this->model->laporan_realisasi($thn,$kriteria,$bagian,$sub_bagian,$koper->KODE_PERKIRAAN);
            foreach ($realisasi as $data_detil) {
                
                $tot_real = $this->model->hitung_realisasi($thn,$kriteria,$bagian,$sub_bagian,$koper->KODE_PERKIRAAN,$data_detil->KODE_ANGGARAN);
                foreach ($tot_real as $row_real) {
                    //$rkap_old = $pdf->format_number($pdf->to_float($row_real->TOTAL));
                    $real = $row_real->REALISASI;
                    $real2 = $row_real->REALISASI;
                    $real_vol_fix = $row_real->REAL_VOL;
                    $sisa_rp = $row_real->SISA;
                    $sisa_vol = $row_real->SISA_VOL;
                    $warna = $row_real->WARNA;
                }

                if($kode_anggaran != $data_detil->KODE_ANGGARAN){
                    $kode_anggaran = $data_detil->KODE_ANGGARAN;
                }else{
                    $kode_anggaran = "";
                }

                //ADENDUM
                $no_adendum = $data_detil->ADENDUM;
                $nilai_adendum = $data_detil->NILAI_ADENDUM;
                
                $no_bukti = $data_detil->NO_BUKTI;
                $no_keu = $data_detil->NO_KEU;
                $tanggal = $data_detil->TANGGAL;
                $real_vol = $data_detil->REAL_VOL;
                $real_harga = $data_detil->HARGA_SATUAN; 

                $uraian = $data_detil->URAIAN;
                $divisi = $data_detil->NAMA_DIVISI;
                $jumlah = 0;
                if($data_detil->JUMLAH_REV == null){
                    $jumlah = $data_detil->JUMLAH;
                }else{
                    $jumlah = $data_detil->JUMLAH_REV;
                }
                $satuan = $data_detil->SATUAN;
                $harga = $data_detil->HARGA;
                $rkap = $jumlah * $harga;
                $real = $real;
                $sisa_vol_fix = $jumlah-$real_vol_fix;
                $sisa_vol = angka_positif($sisa_vol_fix);
                $sisa_rp_fix = $rkap-$real;
                $sisa_rp = angka_positif_rp($sisa_rp_fix);

                if($kode_anggaran == $data_detil->KODE_ANGGARAN){
                    $kode_anggaran = $kode_anggaran;
                    $uraian = $uraian;
                    $divisi = $divisi;
                    $jumlah = $jumlah;
                    $satuan = $satuan;
                    $harga = number_format($harga,2,',','.');
                    $rkap = number_format($rkap,2,',','.');
                    $real = "Rp. ".number_format($real,2,',','.');
                    $sisa_vol = $sisa_vol;
                    $sisa_rp = $sisa_rp;
                }else{
                    $kode_anggaran = "";
                    $uraian = "";
                    $divisi = "";
                    $jumlah = "";
                    $satuan = "";
                    $harga = "";
                    $rkap = "";
                    $real = "";
                    $sisa_vol = "";
                    $sisa_rp = "";
                }

                $color = "";

                if($data_detil->STS_TAMBAHAN == 4){
                    if($warna == "kuning"){
                        //kuning
                        $color = "style='background:#ffe600;'";
                    }else if($warna == "merah"){
                        //merah
                        $color = "style='background:#ff2b00; color:#fff;'";
                    }else if($warna == "putih"){
                        //putih
                        $color = "style='background:#fff;'";
                    }
                }else{
                    if($warna == "kuning"){
                        //kuning
                        $color = "style='background:#ffe600;'";
                    }else if($warna == "merah"){
                        //merah
                        $color = "style='background:#ff2b00; color:#fff;'";
                    }else if($warna == "orange"){
                        //orange
                        $color = "style='background:#FF7F00; color:#fff;'";
                    }
                }

                $no_keu = $no_keu == ""?"-":$no_keu;
                $no_bukti = $no_bukti == ""?"-":$no_bukti;
                $tanggal = $tanggal == ""?"-":$tanggal;
    ?>
        <tr>
            <td <?php echo $color; ?> width="120" align="center"><?php echo $kode_anggaran; ?></td>
            <td <?php echo $color; ?> width="170"><?php echo $uraian; ?></td>
            <td <?php echo $color; ?> width="150" align="center"><?php echo $divisi; ?></td>
            <td <?php echo $color; ?> width="80" align="center"><?php echo $jumlah; ?></td>
            <td <?php echo $color; ?> width="80" align="center"><?php echo $satuan; ?></td>
            <td <?php echo $color; ?> style="width:120px;"><?php echo $harga; ?></td>
            <td <?php echo $color; ?> style="width:120px;"><?php echo $rkap; ?></td>
            <td <?php echo $color; ?> width="220"><?php echo $no_bukti; ?></td>
            <td <?php echo $color; ?> width="80" align="center"><?php echo $no_keu; ?></td>
            <td <?php echo $color; ?> width="150" align="center"><?php echo $tanggal; ?></td>
            <td <?php echo $color; ?> width="100" align="center"><?php echo $real_vol; ?></td>
            <td <?php echo $color; ?> style="width:120px;"><?php echo "Rp. ".number_format($real_harga,2,',','.'); ?></td>
            <td <?php echo $color; ?> style="width:120px;"><?php echo $real; ?></td>
            <td <?php echo $color; ?> width="100" align="center"><?php echo $sisa_vol; ?></td>
            <td <?php echo $color; ?> style="width:120px;"><?php echo $sisa_rp; ?></td> 
        </tr>
        
        <?php if($no_adendum != "" || $no_adendum != null){ ?>
        <tr>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> ><?php echo $no_adendum; ?></td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> ><?php echo "Rp. ".number_format($nilai_adendum,2,',','.'); ?></td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
            <td <?php echo $color; ?> >&nbsp;</td>
        </tr>
        <?php } ?>

    <?php
                $kode_anggaran = $data_detil->KODE_ANGGARAN;
                $uraian = $data_detil->URAIAN;
                $divisi = $data_detil->NAMA_DIVISI;
                $jumlah = $data_detil->JUMLAH;
                $satuan = $data_detil->SATUAN;
                
                $tot_rkap += $data_detil->RKAP;
                $tot_realisasi += $real2;

                $total_semua1 += $data_detil->RKAP;
                $total_semua2 += $real2;
            }
    ?>
        <?php
            if($key < $last_key) {
                $k = $key + 1;
                $next_judul1 = $kd_perkiraan[$k]->INDUK_KODE;
            }else{
                $next_judul1 = "" ;
            }

            if($judul1 != $next_judul1) { 
        ?>
            <tr>
                <td colspan="6"><b>Total <?=$koper->INDUK_KODE." - ".$koper->NAMA_PERKIRAAN2;?> </b></td>
                <td><b><?php echo "Rp. ".str_replace(',', '.', number_format($total_semua1, 2, ",", ".")) ;?></b></td>
                <td colspan="5">&nbsp;</td>
                <td><b><?php echo "Rp. ".str_replace(',', '.', number_format($total_semua2, 2, ",", ".")) ;?></b></td>
                <td colspan="2">&nbsp;</td>
            </tr>
        <?php
                $total_semua1 = 0;
                $total_semua2 = 0;
            }
        ?>
    <?php
        }  
    ?>
        <tr>
            <td colspan="6" align="center"><b>Total</b></td>
            <td align="center"><b><?php echo number_format($tot_rkap,2,',','.'); ?></b></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
            <td align="center"><b><?php echo number_format($tot_realisasi,2,',','.'); ?></b></td>
            <td align="center">&nbsp;</td>
            <td align="center">&nbsp;</td>
        </tr>
    </tbody>
</table>

<br/>

<table>
    <tr>
        <td><div id="persegipanjangmerah"></div></td>
        <td>Nilai Realisasi lebih besar dari nilai RKAP</td>
    </tr>
    <tr>
        <td><div id="persegipanjangkuning"></div></td>
        <td>Data sudah direalisasi</td>
    </tr>
    <tr>
        <td><div id="persegipanjangorange"></div></td>
        <td>Anggaran Tambahan</td>
    </tr>
</table>
<?PHP
    //----ukuran kertas dalam inch----//
    // custom
    $width_custom = 24.4;
    $height_custom = 16.5;
    //A2
    $width_a2 = 23.4;
    $height_a2 = 16.5;
    //------------------------//
    $content = ob_get_clean();
    $width_in_inches = $width_custom;
    $height_in_inches = $height_custom;
    $width_in_mm = $width_in_inches * 25.4;
    $height_in_mm = $height_in_inches * 25.4;
    $html2pdf = new HTML2PDF('L',array($width_in_mm,$height_in_mm),'en');
    // $html2pdf = new HTML2PDF('L','A2','fr');
    $html2pdf->pdf->SetTitle("Laporan Realisasi Anggaran $thn");
    $html2pdf->WriteHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('laporan_realisasi_anggaran.pdf');
?>

