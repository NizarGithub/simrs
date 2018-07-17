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
			<h3 class="page-header"> <i class="icon-pencil"></i>  Profil Perusahaan </h3>

		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Pengaturan</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Profil Perusahaan </li>
		</ul>
	</div>
</div>

<div class="alert alert-info">
	<i class="icon-info-sign"></i><strong>Perhatian!</strong> Mohon isikan info perusahaan dengan benar 
</div>

<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>">
<div class="row-fluid" id="edit_data">
	<div class="span6">
		<div class="content-widgets light-gray">
			<div class="widget-head blue">
				<h3> Informasi Umum Perusahaan </h3>
			</div>
			<div class="widget-container">				
					<div class="control-group">
						<label class="control-label"> <b> Nama Perusahaan </b> </label>
						<div class="controls">
							<input required type="text" class="span12" value="<?=$dt->NAMA_PERUSAHAAN;?>" name="nama_perusahaan" id="nama_perusahaan" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Alamat Perusahaan </b> </label>
						<div class="controls">
							<input required type="text" class="span12" value="<?=$dt->ALAMAT;?>" name="alamat_perusahaan" id="alamat_perusahaan" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Telepon </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->TELEPON;?>" name="telepon" id="telepon" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> FAX </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->FAX;?>" name="fax" id="fax" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> NPWP </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->NPWP;?>" name="npwp" id="npwp" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Website </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->WEBSITE;?>" name="website" id="website" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Email </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->EMAIL;?>" name="email" id="email" >
						</div>
					</div>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="content-widgets light-gray">
			<div class="widget-head blue">
				<h3> Detail Akun Bank </h3>
			</div>
			<div class="widget-container">				
					<div class="control-group">
						<label class="control-label"> <b> Nama Bank </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->NAMA_BANK;?>" name="nama_bank" id="nama_bank" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Cabang Bank </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->CABANG_BANK;?>" name="cabang_bank" id="cabang_bank" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Nomor Akun Bank </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->NO_AKUN_BANK;?>" name="no_akun_bank" id="no_akun_bank" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Atas Nama </b> </label>
						<div class="controls">
							<input type="text" class="span12" value="<?=$dt->ATAS_NAMA;?>" name="atas_nama" id="atas_nama" >
						</div>
					</div>


			</div>
		</div>
	</div>

</div>

<div class="form-actions" style="padding-left: 0;">
	<center>
	<input type="submit" class="btn btn-success" name="simpan" value="Simpan Data Produk">
	<button type="button" onclick="window.location='<?=base_url();?>profil_perusahaan_c';" class="btn"> Batal </button>
	</center>
</div>

</form>





<script type="text/javascript">

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