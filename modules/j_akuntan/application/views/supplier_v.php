<style type="text/css">
.recent_add td{
	background: #CDE69C;
}

#tes td {
	vertical-align: middle;
}
</style>

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-truck"></i>  Daftar Supplier </h3>

		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Supplier </li>
		</ul>
	</div>
</div>

<div class="row-fluid" id="view_data">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head orange">
				<h3>List Data Supplier </h3>
			</div>
			<div class="widget-container">
				<div class="control-group">
					<label class="control-label"> Cari Data Supplier </label>
					<div class="controls input-icon">
						<i class="icon-search"></i>
						<input type="text" onkeyup="cari_supplier(this.value);" class="span6" placeholder="Masukkan nama pelanggan disini">

						<span style="float:right;">
							<button type="button" class="btn btn-success" onclick="tambah_klik();"> 
								<i class="icon-plus" style="color: #FFF; font-size: 16px; left: 0; position: relative; top: 2px;"></i> Tambah Data Supplier 
							</button>
						</span>
					</div>


				</div>

				<table class="stat-table table table-hover">
					<thead>
						<tr>
							<th align="center"> No </th>
							<th align="center"> Nama Supplier </th>
							<th align="center"> NPWP </th>
							<th align="center"> Alamat Penagihan </th>
							<th align="center"> Telepon</th>
							<th align="center"> Aksi </th>
						</tr>						
					</thead>
					<tbody id="tes">
						<?PHP 
						$no = 0;
						foreach ($dt as $key => $row) { 
							$no++;
							$no_telp_rw = $row->NO_TELP;
							$no_hp_rw = $row->NO_HP;

							if($no_telp_rw == "-"){
								$no_telp_rw = "";
							}


							if($no_hp_rw == "-"){
								$no_hp_rw = "";
							}
						?>
						<tr>
							<td align="center" <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > <?=$no;?> </td>
							<td <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > 
								<?=$row->NAMA_SUPPLIER;?> <?PHP if($row->TIPE == 'Perusahaan'){ echo " <br> (".$row->NAMA_USAHA.")"; } ?> 
							</td>
							<td <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > <?=$row->NPWP;?> </td>
							<td <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > <?=$row->ALAMAT_TAGIH;?> </td>
							<td <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > <?=$no_telp_rw;?> <br> <?=$no_hp_rw;?> </td>
							<td align="center" <?PHP if($nama_supplier == $row->NAMA_SUPPLIER){ echo "style='background: #CDE69C;'"; } ?> > 								
								<div class="btn-group">
									<button data-toggle="dropdown" class="btn btn-info dropdown-toggle"> Aksi <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" style="background-color:rgba(255, 255, 255, 1);">
										<li>
										<a onclick="ubah_data_supplier(<?=$row->ID;?>);" href="javascript:;">Ubah</a>
										</li>
										<li>
										<a onclick="$('#dialog-btn').click(); $('#id_hapus').val('<?=$row->ID;?>');" href="javascript:;">Hapus</a>
										</li>
									</ul>
								</div>

								<button onclick="detail_supplier(<?=$row->ID;?>);" data-toggle="modal" data-target="#modal_detail" type="button" class="btn btn-small btn-primary"> <i class="icon-info-sign"></i> Detail </button>
							</td>
						</tr>
						<?PHP } ?>

						<?PHP if(count($dt) == 0){
							echo "<tr> <td colspan='6' align='center'> <b> Tidak ada data yang ditampilkan </b>  </td> </tr>";
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="row-fluid" id="add_data" style="display:none;">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head blue">
				<h3> <i class="icon-plus"></i> Tambah Data Supplier </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>">
					
					<div class="control-group">
						<label class="control-label"> <b> Tipe Supplier </b> </label>
						<div class="controls">
							<label class="radio inline">
							<input type="radio" name="tipe" id="perorang" value="Perorangan" checked="" onclick="isfilter();">
								Perorangan </label>
							<label class="radio inline">
							<input type="radio" name="tipe" id="perusaha" value="Perusahaan" onclick="isfilter();">
							    Perusahaan </label>
						</div>
					</div>

					<div class="control-group usaha_show" style="display:none;">
						<label class="control-label"> <b> Nama Perusahaan </b> </label>
						<div class="controls">
							<input type="text" placeholder="Nama Perusahaan / Badan Usaha" class="span12" value="" name="nama_usaha">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label orang_show"> <b> Nama Supplier </b> </label>
						<label class="control-label usaha_show" style="display:none;"> <b> Nama Pemilik </b> </label>
						<div class="controls">
							<input required type="text" placeholder="Nama Supplier atau Perusahaan" class="span12" value="" name="nama_supplier">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b>NPWP (jika ada)</b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" name="npwp">
						</div>
					</div>

					<div class="control-group usaha_show" style="display:none;">
						<label class="control-label"> <b> No. TDP </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="" name="tdp">
						</div>
					</div>

					<div class="control-group usaha_show" style="display:none;">
						<label class="control-label"> <b> No. SIUP </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="" name="siup">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Alamat Penagihan </b> </label>
						<div class="controls">
							<textarea rows="3" class="span12" name="alamat_tagih"></textarea>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> No. Telepon </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" name="no_telp">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Handphone </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" name="no_hp">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Email </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" name="email">
						</div>
					</div>


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="simpan" value="Simpan Data Supplier">
						<button type="button" onclick="batal_klik();" class="btn"> Batal dan Kembali </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row-fluid" id="edit_data" style="display:none;">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head blue">
				<h3> <i class="icon-edit"></i> Ubah Data Supplier </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>">
					
					<div class="control-group">
						<label class="control-label"> <b> Tipe Pelanggan </b> </label>
						<div class="controls">
							<label class="radio inline">
							<input type="radio" name="tipe_ed" id="perorang_ed" value="Perorangan" onclick="isfilter_ed();">
								Perorangan </label>
							<label class="radio inline">
							<input type="radio" name="tipe_ed" id="perusaha_ed" value="Perusahaan" onclick="isfilter_ed();">
							    Perusahaan </label>
						</div>
					</div>

					<div class="control-group usaha_show_ed" style="display:none;">
						<label class="control-label"> <b> Nama Perusahaan </b> </label>
						<div class="controls">
							<input type="text" placeholder="Nama Perusahaan / Badan Usaha" class="span12" value="" id="nama_usaha_ed" name="nama_usaha_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label orang_show_ed"> <b> Nama Supplier </b> </label>
						<label class="control-label usaha_show_ed" style="display:none;"> <b> Nama Pemilik </b> </label>
						<div class="controls">
							<input required type="text" placeholder="Nama Supplier atau Perusahaan" class="span12" value="" id="nama_supplier_ed" name="nama_supplier_ed">
							<input type="hidden" class="span12" value="" id="id_supplier" name="id_supplier">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b>NPWP (jika ada)</b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" id="npwp_ed" name="npwp_ed">
						</div>
					</div>

					<div class="control-group usaha_show_ed" style="display:none;">
						<label class="control-label"> <b> No. TDP </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="" name="tdp_ed" id="tdp_ed">
						</div>
					</div>

					<div class="control-group usaha_show_ed" style="display:none;">
						<label class="control-label"> <b> No. SIUP </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="" name="siup_ed" id="siup_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Alamat Penagihan </b> </label>
						<div class="controls">
							<textarea rows="3" class="span12" id="alamat_tagih_ed" name="alamat_tagih_ed"></textarea>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> No. Telepon </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" id="no_telp_ed" name="no_telp_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Handphone </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" id="no_hp_ed" name="no_hp_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Email </b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" id="email_ed" name="email_ed">
						</div>
					</div>


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="edit" value="Ubah Data Supplier">
						<button type="button" onclick="batal_edit_klik();" class="btn"> Batal dan Kembali </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- HAPUS MODAL -->
<a id="dialog-btn" href="javascript:;" class="cd-popup-trigger" style="display:none;">View Pop-up</a>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">

        <form id="delete" method="post" action="<?=base_url().$post_url;?>">
            <input type="hidden" name="id_hapus" id="id_hapus" value="" />
        </form>   
         
        <p>Apakah anda yakin ingin menghapus data ini?</p>
        <ul class="cd-buttons">            
            <li><a href="javascript:;" onclick="$('#delete').submit();">Ya</a></li>
            <li><a onclick="$('.cd-popup-close').click(); $('#id_hapus').val('');" href="javascript:;">Tidak</a></li>
        </ul>
        <a href="#0" onclick="$('#id_hapus').val('');" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->
<!-- END HAPUS MODAL -->


<!-- Modal Detail -->
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:none;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detail Supplier</h4>
      </div>
      <div class="modal-body">
        <div class="row-fluid">
			<div class="span6" style="font-size: 15px;">
				<address>
					<strong> Nama Supplier </strong><br>
					<font id="det_nama_pelanggan"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> NPWP </strong><br>
					<font id="det_npwp"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> No. Telepon </strong><br>
					<font id="det_no_telp"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> No. HP </strong><br>
					<font id="det_no_hp"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> Email </strong><br>
					<font id="det_email"> Dr. Aristo Jason </font> 
				</address>
			</div>
			<div class="span6" style="font-size: 15px;">

				<address>
					<strong> Alamat Penagihan </strong><br>
					<font id="det_alamat_tagih"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> Ditambahkan pada </strong><br>
					<font id="det_waktu"> Dr. Aristo Jason </font> 
				</address>

				<address style="margin-top: 18px;">
					<strong> Terakhir Diubah </strong><br>
					<font id="det_waktu_edit"> Dr. Aristo Jason </font> 
				</address>


			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
function cari_supplier(keyword) {
	$.ajax({
		url : '<?php echo base_url(); ?>supplier_c/cari_supplier',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$isi = "";
			if(result.length == 0){
				$isi = "<tr><td colspan='6' style='text-align:center;'> <b> Tidak ada data yang ditampilkan </b> </td></tr>";
			} else {
				$.each(result, function(i, field){
				$isi += 
					"<tr>"+
						"<td style='text-align:center;'>"+parseInt(i+1)+"</td>"+
						"<td>"+field.NAMA_SUPPLIER+"</td>"+
						"<td>"+field.NPWP+"</td>"+
						"<td>"+field.ALAMAT_TAGIH+"</td>"+
						"<td>"+field.NO_TELP+" <br> "+field.NO_HP+" </td>"+
						"<td style='text-align:center;'>"+
							"<div class='btn-group'>"+
								"<button data-toggle='dropdown' class='btn btn-info dropdown-toggle'> Aksi <span class='caret'></span></button>"+
									"<ul class='dropdown-menu'>"+
										"<li>"+
										"<a onclick='ubah_data_supplier("+field.ID+");' href='javascript:;'>Ubah</a>"+
										"</li>"+
										"<li>"+
										"<a onclick='hapus_klik("+field.ID+");' href='javascript:;'>Hapus</a>"+
										"</li>"+
									"</ul>"+
								"</div>"+

								"&nbsp; <button onclick='detail_supplier("+field.ID+");' data-toggle='modal' data-target='#modal_detail' type='button' class='btn btn-small btn-primary'> "+
								"<i class='icon-info-sign'></i> Detail "+
								"</button>"+
						"</td>"+
					"</tr>";
				});
			}

			$('#tes').html($isi);
		}
	});
}

function ubah_data_supplier(id){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>supplier_c/cari_supplier_by_id',
		data : {id:id},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();
			$('#id_supplier').val(result.ID);
			$('#nama_supplier_ed').val(result.NAMA_SUPPLIER);
			$('#npwp_ed').val(result.NPWP);
			$('#alamat_tagih_ed').val(result.ALAMAT_TAGIH);
			$('#no_telp_ed').val(result.NO_TELP);
			$('#no_hp_ed').val(result.NO_HP);
			$('#email_ed').val(result.EMAIL);

			if(result.TIPE == 'Perorangan'){
				$("#perorang_ed").prop("checked", true);
				$('#nama_usaha_ed').val('');
				$('#tdp_ed').val('');
				$('#siup_ed').val('');
			} else {
				$("#perusaha_ed").prop("checked", true);
				$('#nama_usaha_ed').val(result.NAMA_USAHA);
				$('#tdp_ed').val(result.TDP);
			    $('#siup_ed').val(result.SIUP);
			}

			isfilter_ed();



	        //$("#kategori_ed").chosen("destroy");

	        $('#view_data').hide();
	        $('#edit_data').fadeIn('slow');
		}
	});
}

function detail_supplier(id){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>supplier_c/cari_supplier_by_id',
		data : {id:id},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();
			$('#det_nama_pelanggan').html(result.NAMA_SUPPLIER);
			$('#det_npwp').html(result.NPWP);
			$('#det_no_telp').html(result.NO_TELP);
			$('#det_no_hp').html(result.NO_HP);
			$('#det_email').html(result.EMAIL);
			
			$('#det_alamat_tagih').html(result.ALAMAT_TAGIH);
			$('#det_waktu').html(result.WAKTU);
			$('#det_waktu_edit').html(result.WAKTU_EDIT);


		}
	});
}

function tambah_klik(){
	$('#view_data').hide();
	$('#add_data').fadeIn('slow');
}

function batal_klik(){
	$('#add_data').hide();
	$('#view_data').fadeIn('slow');
}

function batal_edit_klik(){
	$('#edit_data').hide();
	$('#view_data').fadeIn('slow');
}

function hapus_klik(id){
	$('#dialog-btn').click(); 
	$('#id_hapus').val(id);
}


function isfilter(){

	if($("#perorang").is(':checked')){
	    $('.orang_show').show(); 
	    $('.usaha_show').hide(); 
	} 

	if($("#perusaha").is(':checked')){
	    $('.orang_show').hide(); 
	    $('.usaha_show').show();  
	} 
}

function isfilter_ed(){
	if($("#perorang_ed").is(':checked')){
	    $('.orang_show_ed').show(); 
	    $('.usaha_show_ed').hide(); 
	} 

	if($("#perusaha_ed").is(':checked')){
	    $('.orang_show_ed').hide(); 
	    $('.usaha_show_ed').show();  
	} 
}

</script>