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
                            for($i=2015; $i<=$thn+1; $i++) {
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

            <h4 class="header-title m-t-0 m-b-30"> Data Gaji Pegawai Bulan <?=datetostr($bln);?>   </h4>

			<table class="table table-striped table-bordered" id="datatable">
				<thead>
					<tr>
						<th style="text-align:center;"> NIP </th>
						<th style="text-align:center;"> Nama Pegawai </th>
						<th style="text-align:center;"> Jabatan </th>
						<th style="text-align:center;"> Departemen </th>
						<th style="text-align:center;"> Divisi </th>
						<th style="text-align:center;"> Gaji Bersih </th>
						<th style="text-align:center;"> Aksi </th>
					</tr>
				</thead>
			<tbody>
				<?PHP 
				$get_pegawai = $this->model->get_all_pegawai();
				foreach ($get_pegawai as $key => $peg) {
					$get_gaji_pegawai = $this->model->get_gaji_pegawai($peg->ID, $bln, $tahun_aktif);
				?>
				<tr>
					<td style="vertical-align:middle;" align="center"> <?=$peg->NIP;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->NAMA;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->JABATAN;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->NAMA_DEP;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->NAMA_DIV;?> </td>
					<td style="vertical-align:middle;" align="right"> <?=number_format(($get_gaji_pegawai->GAPOK + $get_gaji_pegawai->TUNJANGAN) - $get_gaji_pegawai->DENDA);?>  </td>
					<td style="vertical-align:middle;" align="center"> 
						<button onclick="get_detail_gaji('<?=$peg->ID;?>','<?=$peg->NAMA;?>');" data-toggle="modal" data-target=".bs-example-modal-lg" type="button" class="btn btn-success"> Detail </button>
					</td>
				</tr>
				<?PHP } ?>
			</tbody>
			</table>
		</div>
	</div>
		<!-- END EXAMPLE TABLE PORTLET-->
</div>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="judul_modal_detail_gaji"> Detail Gaji Pegawai </h4>
            </div>
            <div class="modal-body">
              <table class="table table-bordered m-0" >
				    <thead>
				        <tr style="background:#1CA0DE;">
				            <th style="color:#FFF; text-align:center;"> No </th>
				            <th style="color:#FFF;"> Uraian </th>
				            <th style="color:#FFF;"> Nilai (Rp)</th>
				        </tr>
				    </thead>
				    <tbody id="det_gaji">

				    </tbody>
				</table>
            </div>
            <div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-danger">Tutup</button>
			</div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

function get_detail_gaji(id_pegawai, nama){

	var bulan = $('#bulan').val();
	var tahun = $('#tahun').val();

	$.ajax({
		url : '<?=base_url();?>kepeg/preview_gaji_c/get_detail_gaji',
		dataType : 'json',
		type : 'POST',
		data : {
			id_pegawai:id_pegawai,
			bulan:bulan,
			tahun:tahun,
		},
		success : function(result){
			if(result.length > 0){
                var isine = "";
                var no = 0;
                $.each(result,function(i,res){
                	no++;
                    isine +=    '<tr>'+
						            '<td scope="row" align="center">'+no+'</td>'+
						            '<td>'+res.NAMA_GAJI+'</td>'+
						            '<td>'+NumberToMoney(res.NILAI).split('.00').join('')+'</td>'+
						        '</tr>';
                });
            } else {
                var isine = "<tr><td align='center' colspan='3'> <b> Tidak ada detail gaji </b> </td></tr>";
            }

            $('#judul_modal_detail_gaji').html('Detail Gaji Pegawai <br> <b>'+nama+'</b>');
            $('#det_gaji').html(isine);

		}	
	});
}

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