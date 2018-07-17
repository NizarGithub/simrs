<?PHP 
function datetostr($var){

 if($var == "01"){
 	$var = "Januari";
 } else if($var == "02"){
 	$var = "Februari";
 } else if($var == "03"){
 	$var = "Maret";
 } else if($var == "04"){
 	$var = "April";
 } else if($var == "05"){
 	$var = "Mei";
 } else if($var == "06"){
 	$var = "Juni";
 } else if($var == "07"){
 	$var = "Juli";
 } else if($var == "08"){
 	$var = "Agustus";
 } else if($var == "09"){
 	$var = "September";
 } else if($var == "10"){
 	$var = "Oktober";
 } else if($var == "11"){
 	$var = "November";
 } else if($var == "12"){
 	$var = "Desember";
 }

 return $var;

}
?>


<form class="horizontal-form" method="post" action="<?=base_url().$post_url;?>">
	<div class="form-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Bulan</label>
					<select class="form-control" name="bulan" id="bulan">
						<option <?PHP if($bln == "01"){ echo "selected"; } ?> value="01">Januari</option>
						<option <?PHP if($bln == "02"){ echo "selected"; } ?> value="02">Februari</option>
						<option <?PHP if($bln == "03"){ echo "selected"; } ?> value="03">Maret</option>
						<option <?PHP if($bln == "04"){ echo "selected"; } ?> value="04">April</option>
						<option <?PHP if($bln == "05"){ echo "selected"; } ?> value="05">Mei</option>
						<option <?PHP if($bln == "06"){ echo "selected"; } ?> value="06">Juni</option>
						<option <?PHP if($bln == "07"){ echo "selected"; } ?> value="07">Juli</option>
						<option <?PHP if($bln == "08"){ echo "selected"; } ?> value="08">Agustus</option>
						<option <?PHP if($bln == "09"){ echo "selected"; } ?> value="09">September</option>
						<option <?PHP if($bln == "10"){ echo "selected"; } ?> value="10">Oktober</option>
						<option <?PHP if($bln == "11"){ echo "selected"; } ?> value="11">November</option>
						<option <?PHP if($bln == "12"){ echo "selected"; } ?> value="12">Desember</option>
					</select>
				</div>
			</div>
			<!--/span-->
			<div class="col-md-2">
				<div class="form-group">
					<label class="control-label">Tahun</label>
					<select class="form-control" id="tahun" name="tahun">
                        <?php
                            $thn = date('Y');
                            for($i=2014; $i<=$thn+1; $i++) {
                                if ($i==$tahun_aktif){
                                    echo"<option selected='selected' value=".$i."> ".$i." </option>";
                                }else{
                                    echo"<option value=".$i."> ".$i." </option>";
                                }
                            }
                        ?>
                    </select>
				</div>
			</div>
			<!--/span-->

			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<input type="submit" name="cari" class="btn btn-success form-control" value="Tampilkan" />
				</div>
			</div>

		</div>
</form>

<div style="border-top: 1px dashed #ccc; margin-bottom: 15px; margin-top: 0px;"></div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
			<div class="dropdown pull-right">
                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                    <button type="button" class="btn btn-primary waves-effect waves-light w-md m-b-5">Cetak</button>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Cetak Excel</a></li>
                    <li><a href="#">Cetak PDF</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Print</a></li>
                </ul>
            </div>

            <h4 class="header-title m-t-0 m-b-30"> Data Absen Bulan <?=datetostr($bln);?>   </h4>

			<table class="table table-striped table-bordered" id="datatable">
				<thead>
					<tr>
						<th style="text-align:center;"> NIP </th>
						<th style="text-align:center;"> Nama Pegawai </th>
						<th style="text-align:center;"> Tanggal </th>
						<th style="text-align:center;"> Jam Datang</th>
						<th style="text-align:center;"> Keterangan </th>
						<th style="text-align:center;"> Denda </th>
						<th style="text-align:center;"> Status </th>
					</tr>
				</thead>
			<tbody>
			<?PHP 
			$no = 0;
			foreach ($dt as $key => $row) { 
				$no++;
				$sts_telat = "";
				if($row->STS == 0){
				// HITUNG SELISIH WAKTU KE MENIT
					$masuk   = $jam_masuk->JAM;
					$mulai   = $row->JAM.":00";
					$selesai = $masuk.":00";
					//list digunkaan untuk menangkap hasil
					//explode untuk membetuk array  dengan menhilangkan : pd contoh ini
					//list($jam,$menit,$detik)=explode(':',$mulai);
					$pecah = explode(":", $mulai);
				    $jam    = $pecah[0];
				    $menit  = $pecah[1];
				    $detik  = $pecah[2];

					$buatWaktuMulai=mktime($jam,$menit,$detik,1,1,1);

					$pecah2 = explode(":", $selesai);
				    $jam    = $pecah2[0];
				    $menit  = $pecah2[1];
				    $detik  = $pecah2[2];
					//-----membentuk waktu selesai
					$buatWaktuSelesai=mktime($jam,$menit,$detik,1,1,1);

					$to_time = strtotime($selesai);
					$from_time = strtotime($mulai);

					$selisihDetik = round( abs($to_time - $from_time) / 60,2 );
					//echo" Waktu $selisihDetik detik";
					
					$col = "";
					if($selisihDetik < 0){
						$sts_telat = "Tidak Terlambat";
						$col = "green";
					} else {
						$sts_telat = "Terlambat ".$selisihDetik." Menit";
						$col = "red";
						//echo gmdate("H:i:s", 685);
					}
				// END HITUNG SELISIH WAKTU KE MENIT

				//HITUNG DENDA
					$denda = 0;
					foreach ($denda_only as $key => $den) {
						$waktu_masuk = date('H:i', strtotime($row->JAM));
						$jam1 = date('H:i', strtotime($den->JAM));
						$jam2 = date('H:i', strtotime($den->JAM2));

						if($jam1 <= $waktu_masuk && $jam2 >= $waktu_masuk){
						  $denda = $den->DENDA;
						} 


				    }
				//END OF HITUNG DENDA

				}

				if($row->STS == 1 || $row->STS == 5){
					$denda = $row->DENDA_RP;
				}

				if($row->STS == 5){
					$col = "red";
				}

				if($row->STS == 1){
					$col = "orange";
				}


				$pecah_bln = explode("/", $row->TANGGAL);
				$tgl_1    = $pecah_bln[0];
				$bln_1    = $pecah_bln[1];
				$thn_1    = $pecah_bln[2];

				$tgl_full = $tgl_1." ".datetostr($bln_1)." ".$thn_1;

				if($denda == 0){
					$sts_telat = "Tidak Terlambat";
				    $col = "green";
				}

			?>

			<tr style="cursor:pointer;" <?php if($sts_telat != "Tidak Terlambat" ){?>  onclick="get_alasan(<?=$row->ID;?> , <?=$denda;?>);" <?php } ?> >
				<td style="text-align:center;"> <?=$row->NIP;?></td>
				<td> <?=$row->NAMA_PEGAWAI;?> </td>
				<td style="text-align:center;"> <?=$tgl_full;?> </td>
				<td style="text-align:center;"> <?=$row->JAM;?> </td>
				<td style="text-align:center; color:<?=$col;?>;"> 
					<?php 
						if($row->STS == 1 || $row->STS == 5){
							echo $row->KET_ALASAN;
						}  else {
							echo $sts_telat;
						}
					?>
				</td>
				<td> Rp. <?=number_format($denda);?> </td>
				<td align="center"> 
					<?PHP if($row->STS == 0){ ?>
						<?PHP if($sts_telat == "Tidak Terlambat"){ ?>
							<span class="label label-sm label-success">
							   On Time
							</span>
						<?PHP } else { ?>
							<span class="label label-sm label-danger">
							   Terlambat
							</span>
					<?PHP } 
						} else if($row->STS == 5){ ?>
					    <span class="label label-sm label-danger">
							   <?=$row->KET_STS;?>
						</span>
					<?PHP } else { ?>
					 	<span class="label label-sm label-warning">
							   <?=$row->KET_STS;?>
						</span>
					 <?PHP } ?>
				</td>
			</tr>

			<?PHP } ?>
			</tbody>
			</table>
		</div>
	</div>
		<!-- END EXAMPLE TABLE PORTLET-->
</div>



<!-- EDIT MODAL -->
<a id="modal-btn" style="display:none;" data-toggle="modal" href="#responsive" class="btn yellow"> Ubah </a>
<div id="responsive" class="modal fade" tabindex="-1" data-width="760">
	<form role="form" method="POST" action="<?=base_url().$post_url;?>">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title"> Alasan Terlambat </h4>
	</div>
	<div class="modal-body">		
		<div class="row">
			<div class="col-md-12">				
				<div class="form-body">

					<div class="form-group">
						<label style="color: #428BCA;">Nama Pegawai</label>
						<div class="input-group col-md-12">
							<input type="hidden" name="id_edit" id="id_edit" class="form-control">
							<input type="hidden" name="bln_aktif_ed" id="bln_aktif_ed" class="form-control" value="<?=$bln;?>">
							<input type="hidden" name="thn_aktif_ed" id="thn_aktif_ed" class="form-control" value="<?=$tahun_aktif;?>">
							<input type="text" name="ed_nama_pegawai" id="ed_nama_pegawai" class="form-control" readonly>
						</div>
					</div>

					<div class="form-group">
						<label style="color: #428BCA;">Alasan</label>
						<select class="form-control" name="ed_alasan" id="ed_alasan" required onchange="$('#ed_denda').val(0);">
							<option value=""> Pilih Alasan </option>
							<option value="Lupa Absen">Lupa Absen</option>
							<option value="Sakit">Sakit</option>
							<option value="Izin">Izin</option>
							<option value="Tugas / Dinas">Tugas / Dinas</option>
						</select>
					</div>

					<div class="form-group">
						<label style="color: #428BCA;">Keterangan</label>
						<div class="input-group col-md-12">
							<input type="text" name="ed_ket_alasan" id="ed_ket_alasan" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label style="color: #428BCA;">Denda</label>
						<div class="input-group col-md-5">
							<input type="text" name="ed_denda" id="ed_denda" onkeyup="FormatCurrency(this);" class="form-control" placeholder="">
						</div>
					</div>

				</div>			
			</div>
		</div>		
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Tutup</button>
		<input type="submit" name="alasan" class="btn blue" value="Simpan Alasan " />
	</div>
	</form>
</div>
<!-- END OF EDIT MODAL -->

<script type="text/javascript">

function get_alasan(id, denda){

	$('#id_edit').val(id);
	$.ajax({
		url : '<?=base_url();?>data_absensi_c/get_alasan',
		dataType : 'json',
		type : 'POST',
		data : {
			id:id,
		},
		success : function(res){
			var denda_plus = res.DENDA_RP;
			if(denda_plus == null || denda_plus == 0 ){
				denda_plus = denda;
			}

			$('#ed_nama_pegawai').val(res.NAMA_PEGAWAI);
			$('#ed_alasan').val(res.KET_STS);
			$('#ed_ket_alasan').val(res.KET_ALASAN);
			$('#ed_denda').val( NumberToMoney(denda_plus).split('.00').join('') );

			$('#modal-btn').click();
		}	
	});
}

</script>