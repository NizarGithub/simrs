<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#tombol_reset{
	display: none;
}

.loading_tabel_lainnya{
    z-index: 9999;
    position: absolute;
    left: 45%;
    top: 40%;
    display: none;
}
</style>
<?php
$s = "SELECT * FROM admum_biaya_reg_pasien WHERE STATUS = 'Admin Ranap'";
$q = $this->db->query($s)->row();
$biaya_admin = $q->BIAYA;
?>
<script type="text/javascript">
$(document).ready(function(){
	get_kamar_paket();

	$('#btn_kembali').click(function(){
		window.location = "<?php echo base_url(); ?>finance/setup_nama_paket_c";
	});

	toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

    $('.btn_tindakan').click(function(){
    	$('#cari_tindakan').val("");
		$('#popup_tindakan').click();
		load_tindakan();
	});

	$('#simpan').click(function(){
		$('#popup_load').show();
		var biaya = $('#biaya').val();
		var biaya_pelayanan = $('#biaya_pelayanan').val();
		var biaya_obat = $('#biaya_obat').val();
		var jasa_operator = $('#jasa_operator').val();

		if(biaya == ""){
			toastr["error"]("Biaya kamar tidak boleh kosong!", "Notifikasi");
		}else if(biaya_pelayanan == ""){
			toastr["error"]("Biaya pelayanan dan tindakan tidak boleh kosong!", "Notifikasi");
		}else if(biaya_obat == ""){
			toastr["error"]("Biaya obat dan alkes tidak boleh kosong!", "Notifikasi");
		}else if(jasa_operator == ""){
			toastr["error"]("Jasa operator tidak boleh kosong!", "Notifikasi");
		}else{
			$.ajax({
				url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/simpan_kamar',
				data : $('#form_tambah').serialize(),
				type : "POST",
				dataType : "json",
				success : function(row){
					toastr["success"]("Data berhasil disimpan!", "Notifikasi");
					setTimeout(function(){
						$('#popup_load').hide();
						window.location.reload();
					}, 6000);
				}
			});
		}
	});

	$('#batal').click(function(){
		window.location.reload();
	});
});

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_kamar tbody tr");
    }

    window.tp = new Pagination('#tablePaging', {
        itemsCount:$selector.length,
        pageSize : parseInt(jumlah_tampil),
        onPageSizeChange: function (ps) {
            console.log('changed to ' + ps);
        },
        onPageChange: function (paging) {
            //custom paging logic here
            //console.log(paging);
            var start = paging.pageSize * (paging.currentPage - 1),
                end = start + paging.pageSize,
                $rows = $selector;

            $rows.hide();

            for (var i = start; i < end; i++) {
                $rows.eq(i).show();
            }
        }
    });
}

function get_kamar_paket(){
	$('#popup_load').show();
	var id_paket = $('#id_paket').val();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/get_kamar_paket',
		data : {id_paket:id_paket},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var kamar_bersalin = result[i].BIAYA_KAMAR_BERSALIN;
					var kamar_perawatan = result[i].BIAYA_KAMAR_PERAWATAN;
					var kamar_neo = result[i].BIAYA_KAMAR_NEO;
					var tot_lain = result[i].LAINNYA;
					var biaya_admin = "<?php echo $biaya_admin; ?>";
					var total = parseFloat(kamar_bersalin)+parseFloat(kamar_perawatan)+parseFloat(kamar_neo)+parseFloat(tot_lain)+parseFloat(biaya_admin);

					var lainnya = "<button type='button' class='btn waves-light btn-primary btn-sm' onclick='klik_lainnya("+result[i].ID+");'>"+formatNumber(tot_lain)+"</button>";
					var subtotal = "<button type='button' class='btn waves-light btn-inverse btn-sm'>"+formatNumber(total)+"</button>";

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_data('+result[i].ID+');">'+
									'<i class="fa fa-pencil"></i>'+
								'</button>&nbsp;'+
						   		'<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_data('+result[i].ID+');">'+
						   			'<i class="fa fa-trash"></i>'+
						   		'</button>';

					$tr += "<tr>"+
								"<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
								"<td style='vertical-align:middle; text-align:center;'>"+result[i].KELAS+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(kamar_bersalin)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(kamar_perawatan)+"</td>"+
								"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(kamar_neo)+"</td>"+
								"<td align='center'>"+lainnya+"</td>"+
								"<td align='center'>"+subtotal+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_kamar tbody').html($tr);
			paging();
			$('#total_data').html(parseInt(result.length));
			$('#popup_load').hide();
		}
	});
}

function klik_lainnya(id){
	$('#popup_lainnya').click();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/get_kamar_paket_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(res){
			$tr1 = "<tr>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].BIAYA_PELAYANAN)+"</td>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].BIAYA_PAKET_OBAT)+"</td>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].BUKU_PASPOR)+"</td>"+
					"</tr>";

			$('#tb_lainnya1 tbody').html($tr1);

			$tr2 = '';
			var no = 0; 
			for(var i=0; i<res['tdk'].length; i++){
				no++;

				$tr2 += "<tr>"+
							"<td style='text-align:center;'>"+no+"</td>"+
							"<td>"+res['tdk'][i].NAMA_TINDAKAN+"</td>"+
							"<td style='text-align:right;'>"+formatNumber(res['tdk'][i].TARIF)+"</td>"+
						"</tr>";
			}

			$('#tb_lainnya2 tbody').html($tr2);

			$tr3 = "<tr>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].JASA_OPERATOR)+"</td>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].VISITE_DOKTER)+"</td>"+
					"</tr>";

			$('#tb_lainnya3 tbody').html($tr3);

			$tr4 = "<tr>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].VISITE_PROF)+"</td>"+
					"</tr>";

			$('#tb_lainnya4 tbody').html($tr4);

			$tr5 = "<tr>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].JASA_ANASTESI)+"</td>"+
						"<td style='text-align:right;'>"+formatNumber(res['row'].JASA_PENATA_ANASTESI)+"</td>"+
					"</tr>";

			$('#tb_lainnya5 tbody').html($tr5);
		}
	});
}

function ubah_data(id){

}

function hapus_data(id){

}

function load_tindakan(){
	$('.loading_tabel_tdk').show();
	var keyword = $('#cari_tindakan').val();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/load_tindakan',
		data : {keyword:keyword},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){ 
					no++;

					$tr += "<tr style='cursor:pointer;' onclick='klik_tindakan("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+result[i].KODE+"</td>"+
								"<td>"+result[i].NAMA_TINDAKAN+"</td>"+
								"<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"</tr>";
				}
			}

			$('#tb_tindakan tbody').html($tr);
			$('.loading_tabel_tdk').hide();
		}
	});

	$('#cari_tindakan').off('keyup').keyup(function(){
		load_tindakan();
	});
}

function klik_tindakan(id){
	$('#popup_load').show();
	$('#tutup_tindakan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>finance/setup_nama_paket_c/klik_tindakan',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			for(var i=0; i<result.length; i++){
				var jumlah_data = $('#tr_'+result[i].ID).length;

				var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow(this);'><i class='fa fa-times'></i></button>";

				if(jumlah_data > 0){
					var jumlah = $('#jumlah_'+result[i].ID).val();
					$('#jumlah_'+result[i].ID).val(parseInt(jumlah)+1);
				}else{
					$tr = "<tr id='tr_"+result[i].ID+"'>"+
							"<input type='hidden' name='id_tindakan[]' value='"+result[i].ID+"'>"+
							"<input type='hidden' id='tarif_"+result[i].ID+"' value='"+result[i].TARIF+"'>"+
							"<td style='vertical-align:middle;'>"+result[i].NAMA_TINDAKAN+"</td>"+
							"<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
							"<td align='center'>"+aksi+"</td>"+
						  "</tr>";
				}
			}

			$('#tabel_tambah_tindakan tbody').append($tr);
			$('#popup_load').hide();
		}
	});
}

function deleteRow(btn){
	var row = btn.parentNode.parentNode;
	row.parentNode.removeChild(row);
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation">
                    <a data-toggle="tab" role="tab" href="#daftar1"><i class="fa fa-list"></i> Data Paket</a>
                </li>
                <li role="presentation">
                    <a data-toggle="tab" role="tab" href="#import1"><i class="fa fa-plus"></i> Tambah Data</a>
                </li>
            </ul>
            <div class="tab-content">
            	<div id="daftar1" class="tab-pane fade in active" role="tabpanel">
            		<form class="form-horizontal" role="form">
			        	<!-- <div class="form-group">
			                <div class="col-md-12">
				                <div class="input-group m-t-10">
				                    <input type="text" class="form-control" id="cari_data" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
				                    <span class="input-group-btn">
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
				                    		<i class="fa fa-search"></i>
				                    	</button>
				                    	<button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
				                    		<i class="fa fa-refresh"></i>
				                    	</button>
				                    </span>
				                </div>
			                </div>
			            </div> -->
			        	<div class="form-group">
			        		<div class="col-md-12">
			                    <div class="table-responsive">
						            <table id="tabel_kamar" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">No</th>
						                        <th style="color:#fff; text-align:center;">Kelas</th>
						                        <th style="color:#fff; text-align:center;">Kamar Bersalin</th>
						                        <th style="color:#fff; text-align:center;">Kamar Perawatan Ibu</th>
						                        <th style="color:#fff; text-align:center;">Kamar Neo</th>
						                        <th style="color:#fff; text-align:center;">Lainnya</th>
						                        <th style="color:#fff; text-align:center;">Subtotal</th>
						                        <th style="color:#fff; text-align:center;">Aksi</th>
						                    </tr>
						                </thead>
						                <tbody>

						                </tbody>
						            </table>
						        </div>
			                </div>
			            </div>
			        	<div class="form-group">
			        		<div class="col-md-10">
			                    <div id="tablePaging"> </div>
			                </div>
			                <div class="col-md-2">
			                    <h4 class="header-title pull-right">Total Data : <b id="total_data"></b></h4>
			                </div>
			            </div>
			            <div class="form-group">
			        		<div class="col-md-9">
			        			&nbsp;
			        		</div>
			                <label class="col-md-2 control-label">Jumlah Tampil</label>
			                <div class="col-md-1 pull-right">
				                <select class="form-control" id="jumlah_tampil">
			                        <option value="10">10</option>
			                        <option value="20">20</option>
			                        <option value="50">50</option>
			                        <option value="100">100</option>
			                    </select>
			                </div>
			            </div>
			        </form>
            	</div>

            	<div id="import1" class="tab-pane fade in" role="tabpanel">
            		<form class="form-horizontal" role="form" id="form_tambah">
			            <input type="hidden" name="id_paket" id="id_paket" value="<?php echo $id_paket; ?>">
						<div class="form-group">
	                        <label class="col-md-2 control-label">Kelas</label>
	                        <div class="col-md-3">
	                            <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
	                                <option value="SVIP">SVIP</option>
			                        <option value="VIP">VIP</option>
			                        <option value="1A">I A</option>
			                        <option value="1B">I B</option>
			                        <option value="2A">II A</option>
			                        <option value="2B">II B</option>
	                            </select>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Biaya Kamar Bersalin</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_kamar_bersalin" id="biaya_kamar_bersalin" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Biaya Kamar Perawatan Ibu</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_kamar_perawatan" id="biaya_kamar_perawatan" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Biaya Kamar Neonatus</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_kamar_neo" id="biaya_kamar_neo" value="" onkeyup="FormatCurrency(this);">
	                            <span class="help-block"><small>*jika tidak ada boleh dikosongi</small></span>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Biaya Pelayanan & Tindakan</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_pelayanan" id="biaya_pelayanan" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Biaya Paket Obat & Alkes</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_obat" id="biaya_obat" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Buku Paspor</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="buku_paspor" id="buku_paspor" value="" onkeyup="FormatCurrency(this);">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Tindakan</label>
	                        <div class="col-md-3">
	                            <div class="input-group">
	                                <input type="text" class="form-control btn_tindakan" value="" placeholder="Klik disini..." readonly>
	                                <span class="input-group-btn">
	                                    <button type="button" class="btn btn-inverse btn_tindakan"><i class="fa fa-search"></i></button>
	                                </span>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">&nbsp;</label>
	                        <div class="col-md-6">
	                            <div class="table-responsive">
						            <table id="tabel_tambah_tindakan" class="table table-bordered">
						                <thead>
						                    <tr class="kuning_tr">
						                        <th style="color:#fff; text-align:center;">Tindakan</th>
						                        <th style="color:#fff; text-align:center;">Tarif</th>
						                        <th style="color:#fff; text-align:center;">#</th>
						                    </tr>
						                </thead>
						                <tbody>
						                    
						                </tbody>
						            </table>
						        </div>
	                        </div>
	                    </div>
	                    <hr>
	                    <h4 class="header-title m-t-0 m-b-20">Dokter Kandungan</h4>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jasa Operator</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="jasa_operator" id="jasa_operator" value="" onkeyup="FormatCurrency(this);" required>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Visite Dokter</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_visite" id="biaya_visite" value="" onkeyup="FormatCurrency(this);">
	                            <span class="help-block"><small>*jika tidak ada boleh dikosongi</small></span>
	                        </div>
	                    </div>
	                    <h4 class="header-title m-t-0 m-b-20">Dokter Anak</h4>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Visite Dokter (Profesor)</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="biaya_visite_prof" id="biaya_visite_prof" value="" onkeyup="FormatCurrency(this);">
	                            <span class="help-block"><small>*jika tidak ada boleh dikosongi</small></span>
	                        </div>
	                    </div>
	                    <h4 class="header-title m-t-0 m-b-20">Penata Instrumen</h4>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jasa Anastesi</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="jasa_anastesi" id="jasa_anastesi" value="" onkeyup="FormatCurrency(this);">
	                            <span class="help-block"><small>*jika tidak ada boleh dikosongi</small></span>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-2 control-label">Jasa Penata Anastesi</label>
	                        <div class="col-md-3">
	                            <input type="text" class="form-control" name="jasa_penata_anastesi" id="jasa_penata_anastesi" value="" onkeyup="FormatCurrency(this);">
	                            <span class="help-block"><small>*jika tidak ada boleh dikosongi</small></span>
	                        </div>
	                    </div>
	                    <hr>
			            <div class="form-group">
			            	<label class="col-md-2 control-label">&nbsp;</label>
			                <div class="col-md-7">
			                    <button type="button" class="btn btn-success waves-effect w-md waves-light" id="simpan">Simpan</button>
			                    <button type="reset" class="btn btn-danger waves-effect w-md waves-light" id="batal">Batal</button>
			                </div>
			            </div>
			        </form>
            	</div>
           	</div>

           	<form class="form-horizontal" role="form">
	        	<div class="form-group">
	                <div class="col-md-4">
				        <button class="btn btn-purple btn-block m-t-10" type="button" id="btn_kembali">
							<i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
						</button>
	                </div>
	            </div>
	        </form>
        </div>

    </div>
</div>

<!-- TINDAKAN -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1" id="popup_tindakan" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Tindakan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_tindakan" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
		        <div class="loading_tabel_tdk">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover table-bordered" id="tb_tindakan">
		                    <thead>
		                        <tr class="hijau_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Kode</th>
		                            <th style="text-align:center; color: #fff;">Tindakan</th>
		                            <th style="text-align:center; color: #fff;">Tarif</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_tindakan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // -->

<!-- LAINNYA -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal2" id="popup_lainnya" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Biaya Lainnya</h4>
            </div>
            <div class="modal-body">
		        <div class="loading_tabel_lainnya">
		        	<img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
		        </div>
            	<div class="table-responsive">
	                <table class="table table-hover table-bordered" id="tb_lainnya1">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">Biaya Pelayanan & Tindakan</th>
	                            <th style="text-align:center; color: #fff;">Biaya Paket Obat & Alkes</th>
	                            <th style="text-align:center; color: #fff;">Buku Paspor</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>

            	<h4 class="header-title m-t-15 m-b-15">Tindakan</h4>
            	<div class="table-responsive">
	                <table class="table table-hover table-bordered" id="tb_lainnya2">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">No</th>
	                            <th style="text-align:center; color: #fff;">Nama Tindakan</th>
	                            <th style="text-align:center; color: #fff;">Tarif</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>

            	<h4 class="header-title m-t-15 m-b-15">Dokter Kandungan</h4>
            	<div class="table-responsive">
	                <table class="table table-hover table-bordered" id="tb_lainnya3">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">Jasa Operator</th>
	                            <th style="text-align:center; color: #fff;">Visite Dokter</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>

            	<h4 class="header-title m-t-15 m-b-15">Dokter Anak</h4>
            	<div class="table-responsive">
	                <table class="table table-hover table-bordered" id="tb_lainnya4">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">Visite Dokter (Profesor)</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>

            	<h4 class="header-title m-t-15 m-b-15">Penata Instrumen</h4>
            	<div class="table-responsive">
	                <table class="table table-hover table-bordered" id="tb_lainnya5">
	                    <thead>
	                        <tr class="hijau_popup">
	                            <th style="text-align:center; color: #fff;">Jasa Anastesi</th>
	                            <th style="text-align:center; color: #fff;">Jasa Penata Anastesi</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        
	                    </tbody>
	                </table>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_lainnya">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- // -->