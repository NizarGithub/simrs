<style type="text/css">
.recent_add td{
	background: #CDE69C;
}
</style>

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-tag"></i> Daftar Kode Akuntansi </h3>

		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active">Daftar Kode Akun</li>
		</ul>
	</div>
</div>

<div class="row-fluid" id="view_data">
	<div class="span12">
		<div class="content-widgets light-gray">
			<div class="widget-head orange">
				<h3>List Kode Akuntansi </h3>
			</div>
			<div class="widget-container">
				<div class="control-group">
					<label class="control-label"> Cari Data Akun </label>
					<div class="controls input-icon">
						<i class="icon-search"></i>
						<input type="text" onkeyup="cari_kode(this.value);" class="span6" placeholder="Masukkan Kode Akun atau Nama Akun disini">

						<span style="float:right;">
							<button type="button" class="btn btn-success" onclick="tambah_klik();"> 
								<i class="icon-plus" style="color: #FFF; font-size: 16px; left: 0; position: relative; top: 2px;"></i> Tambah Kode Akun 
							</button>
						</span>
					</div>


				</div>

				<table class="stat-table table table-hover">
					<thead>
						<tr>
							<th align="center"> Kode Akun </th>
							<th align="center"> Nama Akun </th>
							<th align="center"> Kategori Akun</th>
							<th align="center"> Aksi </th>
						</tr>						
					</thead>
					<tbody id="tes">
						<?PHP 
						foreach ($dt as $key => $row) { ?>
						<tr>
							<td align="center" <?PHP if($nomor_akun == $row->KODE_AKUN){ echo "style='background: #CDE69C;'"; } ?> > <b> <?=$row->KODE_AKUN;?> </b> </td>
							<td <?PHP if($nomor_akun == $row->KODE_AKUN){ echo "style='background: #CDE69C;'"; } ?> > <b> <?=$row->NAMA_AKUN;?> </b> </td>
							<td <?PHP if($nomor_akun == $row->KODE_AKUN){ echo "style='background: #CDE69C;'"; } ?> >  <b> <?=$row->KATEGORI;?> </b> </td>
							<td align="center" <?PHP if($nomor_akun == $row->KODE_AKUN){ echo "style='background: #CDE69C;'"; } ?> > 
								<button onclick="ubah_data_akun(<?=$row->ID;?>);" type="button" class="btn btn-small btn-warning"> <i class="icon-edit"></i> Ubah Akun </button>
								<button onclick="$('#dialog-btn').click(); $('#id_hapus').val('<?=$row->ID;?>');" type="button" class="btn btn-small btn-danger"> <i class="icon-remove"></i> Hapus</button>
							</td>
						</tr>
						<?PHP } ?>
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
				<h3> <i class="icon-plus"></i> Tambah Kode Akun </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url();?>daftar_kode_akun_c">
					<div class="control-group">
						<label class="control-label"> Nama Akun </label>
						<div class="controls">
							<input required type="text"  class="span12" value="" name="nama_akun">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> Nomor Akun </label>
						<div class="controls">
							<input required type="text"  class="span12" value="" name="nomor_akun">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Deskripsi</label>
						<div class="controls">
							<textarea rows="3" class="span12" name="deskripsi"></textarea>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> Kategori Akun </label>
							<div class="controls">
								<select required data-placeholder="Pilih kategori..." class="chzn-select" tabindex="2" style="width:300px;" name="kategori">
									<option value=""></option>
									<option value="Kas & Bank">Kas & Bank</option>
									<option value="Akun Piutang">Akun Piutang</option>
									<option value="Aktiva Lancar Lainnya">Aktiva Lancar Lainnya</option>
									<option value="Persediaan">Persediaan</option>
									<option value="Aktiva Tetap">Aktiva Tetap</option>
									<option value="Depresiasi & Amortisasi">Depresiasi & Amortisasi</option>
									<option value="Akun Hutang">Akun Hutang</option>
									<option value="Kewajiban Lancar Lainnya">Kewajiban Lancar Lainnya</option>
									<option value="Ekuitas">Ekuitas</option>
									<option value="Pendapatan">Pendapatan</option>
									<option value="Harga Pokok Penjualan">Harga Pokok Penjualan</option>
									<option value="Beban">Beban</option>
									<option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
									<option value="Beban Lainnya">Beban Lainnya</option>
								</select>
							</div>
					</div>


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="simpan" value="Simpan">
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
				<h3> <i class="icon-edit"></i> Ubah Kode Akun </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url();?>daftar_kode_akun_c">
					<div class="control-group">
						<label class="control-label"> Nama Akun </label>
						<div class="controls">
							<input type="text" required class="span12" value="" id="nama_akun_ed" name="nama_akun_ed">
							<input type="hidden"  class="span12" value="" id="id_akun_ed" name="id_akun_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> Nomor Akun </label>
						<div class="controls">
							<input type="text" readonly class="span12" value="" id="nomor_akun_ed" name="nomor_akun_ed">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Deskripsi</label>
						<div class="controls">
							<textarea rows="3" class="span12" id="deskripsi_ed" name="deskripsi_ed"></textarea>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> Kategori Akun </label>
							<div class="controls">
								<select required data-placeholder="Pilih kategori..." class="chzn-select2" tabindex="2" style="width:300px;" id="kategori_ed" name="kategori_ed">
									<option value=""></option>
									<option value="Kas & Bank">Kas & Bank</option>
									<option value="Akun Piutang">Akun Piutang</option>
									<option value="Aktiva Lancar Lainnya">Aktiva Lancar Lainnya</option>
									<option value="Persediaan">Persediaan</option>
									<option value="Aktiva Tetap">Aktiva Tetap</option>
									<option value="Depresiasi & Amortisasi">Depresiasi & Amortisasi</option>
									<option value="Akun Hutang">Akun Hutang</option>
									<option value="Kewajiban Lancar Lainnya">Kewajiban Lancar Lainnya</option>
									<option value="Ekuitas">Ekuitas</option>
									<option value="Pendapatan">Pendapatan</option>
									<option value="Harga Pokok Penjualan">Harga Pokok Penjualan</option>
									<option value="Beban">Beban</option>
									<option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
									<option value="Beban Lainnya">Beban Lainnya</option>
								</select>
							</div>
					</div>


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="edit" value="Ubah Data Akun">
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

<script type="text/javascript">
function cari_kode(keyword) {
	$.ajax({
		url : '<?php echo base_url(); ?>daftar_kode_akun_c/cari_kode',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$isi = "";
			if(result.length == 0){
				$isi = "<tr><td colspan='4' style='text-align:center;'> <b> Tidak ada data yang ditampilkan </b> </td></tr>";
			} else {
				$.each(result, function(i, field){
				$isi += 
					"<tr>"+
						"<td style='text-align:center;'> <b> "+field.KODE_AKUN+" </b> </td>"+
						"<td><b>"+field.NAMA_AKUN+"</b></td>"+
						"<td><b>"+field.KATEGORI+"</b></td>"+
						"<td style='text-align:center;'>"+
							"<button onclick='ubah_data_akun("+field.ID+");' type='button' class='btn btn-small btn-warning'> <i class='icon-edit'></i> Ubah Akun </button> &nbsp;"+
							"<button onclick='hapus_klik("+field.ID+");' type='button' class='btn btn-small btn-danger'> <i class='icon-remove'></i> Hapus</button>"+
						"</td>"+
					"</tr>";
				});
			}

			$('#tes').html($isi);
		}
	});
}

function ubah_data_akun(id){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>daftar_kode_akun_c/cari_kode_by_id',
		data : {id:id},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();
			$('#id_akun_ed').val(result.ID);
			$('#nama_akun_ed').val(result.NAMA_AKUN);
			$('#nomor_akun_ed').val(result.KODE_AKUN);
			$('#deskripsi_ed').val(result.DESKRIPSI);
			$('#kategori_ed').val(result.KATEGORI);


	        //$("#kategori_ed").chosen("destroy");

	        $('#view_data').hide();
	        $('#edit_data').fadeIn('slow');
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

</script>