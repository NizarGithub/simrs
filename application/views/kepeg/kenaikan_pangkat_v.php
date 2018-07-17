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

$get_pegawai = $this->model->get_all_pegawai();
?>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box table-responsive">
            <h3 class="header-title m-t-0 m-b-10"> Daftar Pegawai yang akan naik pangkat  </h3>
            <h4 class="header-title m-t-0 m-b-30" style="color: red; font-weight: bold;"> Terdapat <?=count($get_pegawai);?> Pegawai  </h4>

			<table class="table table-bordered" id="">
				<thead>
					<tr>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> NIP </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Nama Pegawai </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Jabatan </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Tanggal Akhir Pangkat </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Pangkat </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Naik ke </th>
						<th style="text-align:center; background: #71b6f9; color: #FFF;"> Aksi </th>
					</tr>
				</thead>
			<tbody>
				<?PHP 
				
				foreach ($get_pegawai as $key => $peg) {
					$get_next_pangkat = $this->model->get_next_pangkat($peg->ID_PANGKAT);
				?>
				<tr>
					<td style="vertical-align:middle;" align="center"> <?=$peg->NIP;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->NAMA;?> </td>
					<td style="vertical-align:middle;" align="left"> <?=$peg->JABATAN;?> </td>
					<td style="vertical-align:middle;" align="center"> <?=date('d F Y', strtotime($peg->TGL_AKHIR_PANGKAT));?> </td>
					<td style="vertical-align:middle;" align="center"> <?=str_replace('Golongan', 'Gol', $peg->GOLONGAN)."/".$peg->RUANG." - ".$peg->NAMA_PANGKAT;?>  </td>
					<td style="vertical-align:middle;" align="center"> <?=str_replace('Golongan', 'Gol', $get_next_pangkat->GOLONGAN)."/".$get_next_pangkat->RUANG." - ".$get_next_pangkat->NAMA;?> </td>
					<td style="vertical-align:middle;" align="center"> 
						<button onclick="peg_detail('<?=$peg->ID;?>', '<?=str_replace('Golongan', 'Gol', $peg->GOLONGAN)."/".$peg->RUANG." - ".$peg->NAMA_PANGKAT;?>', '<?=str_replace('Golongan', 'Gol', $get_next_pangkat->GOLONGAN)."/".$get_next_pangkat->RUANG." - ".$get_next_pangkat->NAMA;?>', '<?=$peg->ID_PANGKAT;?>', '<?=$get_next_pangkat->ID;?>');" data-toggle="modal" data-target=".bs-example-modal-lg" type="button" class="btn btn-success"> Naik Pangkat </button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="judul_modal_detail_gaji"> Kenaikan Pangkat Pegawai </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">
            	<div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nama_pegawai" class="control-label">Nama Pegawai</label>
                            <input id="nama_pegawai" name="nama_pegawai" type="text" class="form-control" readonly>
                            <input id="id_pegawai" name="id_pegawai" type="hidden" class="form-control">
                            <input id="id_pangkat_skrg" name="id_pangkat_skrg" type="hidden" class="form-control">
                            <input id="id_pangkat_baru" name="id_pangkat_baru" type="hidden" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pangkat_sekarang" class="control-label"> Pangkat Sekarang </label>
                            <input id="pangkat_sekarang" name="pangkat_sekarang" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pangkat_baru" class="control-label"> Pangkat Baru </label>
                            <input id="pangkat_baru" name="pangkat_baru" type="text" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="no_sk" class="control-label"> Nomor SK  </label>
                            <input type="text" class="form-control" id="no_sk" name="no_sk">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_sk" class="control-label"> Tanggal SK  </label>
                            <input type="text" data-mask="99-99-9999" class="form-control" id="tgl_sk" name="tgl_sk" value="<?=date('d-m-Y');?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_awal_pangkat" class="control-label"> Tanggal Awal  </label>
                            <input type="text" data-mask="99-99-9999" class="form-control" id="tgl_awal_pangkat" name="tgl_awal_pangkat" value="<?=date('d-m-Y');?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_akhir_pangkat" class="control-label"> Tanggal Akhir  </label>
                            <input type="text" data-mask="99-99-9999" class="form-control" id="tgl_akhir_pangkat" name="tgl_akhir_pangkat">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            	<input type="submit" class="btn btn-info" value="Simpan" name="simpan"/>
                 &nbsp;&nbsp;&nbsp;
				<button type="button" data-dismiss="modal" class="btn btn-danger">Tutup</button>
			</div>
			</form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

function peg_detail(id_peg, pangkat_skrg, pangkat_baru, id_pangkat_skrg, id_pangkat_baru){

	$('#id_pegawai').val(id_peg);
	$('#pangkat_sekarang').val(pangkat_skrg);
	$('#pangkat_baru').val(pangkat_baru);	
	$('#id_pangkat_skrg').val(id_pangkat_skrg);
	$('#id_pangkat_baru').val(id_pangkat_baru);

	$.ajax({
		url : '<?=base_url();?>kepeg/kenaikan_pangkat_c/peg_detail',
		dataType : 'json',
		type : 'POST',
		data : {
			id_peg:id_peg,
		},
		success : function(res){
			$('#nama_pegawai').val(res.NAMA);
		}	
	});
}

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