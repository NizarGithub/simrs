<style type="text/css">
.recent_add td{
	background: #CDE69C;
}

#tes td {
	vertical-align: middle;
}
</style>

<?PHP 
$kode_kas_full = "";
if($last_kas_bank->KODE_AKUN != "" || $last_kas_bank->KODE_AKUN != null ){
	$kode_kas_last = $last_kas_bank->KODE_AKUN;
	$kode_kas = explode("-", $kode_kas_last);
	$kode_kas1 = $kode_kas[0];
	$kode_kas2 = $kode_kas[1];
	$kode_kas_res = intval($kode_kas2) + 1;

	$kode_kas_full = $kode_kas1."-".$kode_kas_res;
} else {
	$kode_kas_full = "1-1000";
}


$kode_cc_full = "";

if($last_cc->KODE_AKUN != "" || $last_cc->KODE_AKUN != null ){
	$kode_cc_last = $last_cc->KODE_AKUN;
	$kode_cc = explode("-", $kode_cc_last);
	$kode_cc1 = $kode_cc[0];
	$kode_cc2 = $kode_cc[1];
	$kode_cc_res = intval($kode_cc2) + 1;

	$kode_cc_full = $kode_cc1."-".$kode_cc_res;
} else {
	$kode_cc_full = "2-2101";
}


?>


<input type="hidden" class="span12" value="<?=$kode_kas_full;?>" id="last_kode_kas">
<input type="hidden" class="span12" value="<?=$kode_cc_full;?>" id="last_kode_cc">

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-money"></i>  Kas & Bank </h3>


		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Master Data</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Kas & Bank </li>
		</ul>
	</div>
</div>

<!-- <div class="row-fluid ">
	<div class="span4">
		<div class="board-widgets green" style="background:#6ec06e;">
			<div class="board-widgets-head clearfix">
				<h4 class="pull-left" style="font-size: 18px;"> Saldo Kas & Bank </h4>
			</div>
			<div class="board-widgets-content" style="background:#FFF; color:#666;">
				<span class="n-counter">Rp 7,500,000</span><span class="n-sources">Total saldo saat ini</span>
			</div>
		</div>
	</div>
</div>
 -->

<button onclick="tambah_klik();" style="float:left; margin-bottom: 10px;" type="button" class="btn btn-info opt_btn"> <i class="icon-plus"></i> Buat Akun Baru </button>
<button onclick='window.location="<?=base_url();?>kas_bank_c/transfer_uang";' style="float:right; margin-bottom: 10px; margin-right:5px;" type="button" class="btn btn-warning opt_btn"> Transfer Uang </button>
<!-- <button onclick='window.location="<?=base_url();?>kas_bank_c/terima_uang";' style="float:left; margin-bottom: 10px; margin-right:5px;" type="button" class="btn btn-success opt_btn"> Terima Uang </button>
<button onclick='window.location="<?=base_url();?>kas_bank_c/kirim_uang";' style="float:left; margin-bottom: 10px; margin-right:5px;" type="button" class="btn btn-danger opt_btn"> Kirim Uang </button> -->
<br>

<div class="row-fluid" id="view_data">
	<div class="span12">
		<div class="content-widgets light-gray">

			<div class="widget-container">


				<table class="stat-table table table-hover">
					<thead>
						<tr>
							<th style="background:#e0f7ff; color:#666;" align="center"> Kode Akun </th>
							<th style="background:#e0f7ff; color:#666;" align="center"> Nama Akun </th>
							<th style="background:#e0f7ff; color:#666;" align="center"> Saldo (Rp) </th>

						</tr>						
					</thead>
					<tbody id="tes">

						<?PHP
						if(count($dt) > 0){
						  echo "<tr><td colspan='3' align='left'> <b style='font-size: 15px; color: #337ab7;'> - Kas & Bank </b> </td></tr>";
						}
						?>

						<?PHP 
						$desk1 = "";
						foreach ($dt as $key => $row) { 
							if($row->DESKRIPSI != "" || $row->DESKRIPSI != null){
								$desk1 = "<br> <i style='font-size:12px;'>".$row->DESKRIPSI."</i>";
							} else {
								$desk1 = "";
							}
						?>
						<tr>
							<td style="font-size:14px;" align="center">  <?=$row->KODE_AKUN;?>  </td>
							<td style="font-size:14px;" align="left">  <a href="#"><b><?=$row->NAMA_AKUN;?> </b> </a> <?=$desk1;?> </td>
							<td style="font-size:14px;" align="right">  <?=number_format($row->TOTAL);?>  </td>
						</tr>
						<?PHP } ?>


						<?PHP
						if(count($dt2) > 0){
						  echo "<tr><td colspan='3' align='left'> <b style='font-size: 15px; color: #337ab7;'> - Credit Card </b> </td></tr>";
						}
						?>

						<?PHP 
						$desk2 = "";
						foreach ($dt2 as $key => $row2) { 
							if($row2->DESKRIPSI != "" || $row2->DESKRIPSI != null){
								$desk2 = "<br> <i style='font-size:12px;'>".$row2->DESKRIPSI."</i>";
							} else {
								$desk2 = "";
							}
						?>
						<tr>
							<td style="font-size:14px;" align="center"> <?=$row2->KODE_AKUN;?> </td>
							<td style="font-size:14px;" align="left"> <a href="#"><b><?=$row2->NAMA_AKUN;?> </b> </a> <?=$desk2;?> </td>
							<td style="font-size:14px;" align="right"> <?=number_format($row2->TOTAL);?> </td>
						</tr>
						<?PHP } ?>

						<?PHP if(count($dt) == 0 && count($dt2) == 0 ){
							echo "<tr> <td colspan='3' align='center'> <b> Tidak ada kode akun yang dapat ditampilkan </b>  </td> </tr>";
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
				<h3> <i class="icon-plus"></i> Tambah Data Akun Kas & Bank </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>">

					<div class="control-group">
						<label class="control-label"> <b> Kategori Akun </b> </label>
							<div class="controls">
								<select onchange="get_kode_akun(this.value);" required data-placeholder="Pilih kategori..." class="chzn-select" tabindex="2" style="width:300px;" name="kategori">
									<option selected value="Kas & Bank">Kas & Bank</option>
									<option value="Credit Card"> Credit Card</option>
								</select>
							</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Nama Akun </b> </label>
						<div class="controls">
							<input required type="text" class="span12" value="" name="nama_akun">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Kode Akun </b> </label>
						<div class="controls">
							<input required type="text" class="span12" value="<?=$kode_kas_full;?>" name="kode_akun" id="kode_akun">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Saldo Awal </b> </label>
						<div class="controls">
							<input required onkeyup="FormatCurrency(this);" type="text" class="span12" value="0" name="saldo_awal" id="saldo_awal">
							<span class="help-inline" style="color:red;"> Penting!! Isikan saldo awal pada akun baru ini. </span>
						</div>
					</div>					

					<div class="control-group">
						<label class="control-label"> <b> Deskripsi </b> </label>
						<div class="controls">
							<textarea rows="3" class="span12" name="deskripsi"></textarea>
						</div>
					</div>					


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="simpan" value="Simpan Data Akun">
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
				<h3> <i class="icon-edit"></i> Ubah Data Produk </h3>
			</div>
			<div class="widget-container">
				<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>">
					<div class="control-group">
						<label class="control-label"> <b> Kode Produk </b> </label>
						<div class="controls">
							<input readonly type="text" class="span12" value="" name="kode_produk_ed" id="kode_produk_ed" >
							<input type="hidden" class="span12" value="" name="id_produk" id="id_produk" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Nama Produk </b> </label>
						<div class="controls">
							<input required type="text" class="span12" value="" name="nama_produk_ed" id="nama_produk_ed" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b>Satuan</b> </label>
						<div class="controls">
							<input type="text"  class="span12" value="" name="satuan_ed" id="satuan_ed" >
						</div>
					</div>

					<div class="control-group">
						<label class="control-label"> <b> Deskripsi Produk</b> </label>
						<div class="controls">
							<textarea rows="3" class="span12" name="deskripsi_ed" id="deskripsi_ed" ></textarea>
						</div>
					</div>


					<div class="form-actions">
						<input type="submit" class="btn btn-success" name="edit" value="Ubah Data Produk">
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

function get_kode_akun(val){

	var last_kode_kas = $('#last_kode_kas').val(); 
	var last_kode_cc = $('#last_kode_cc').val();

	if(val == "Kas & Bank"){
		$('#kode_akun').val(last_kode_kas);
	} else if(val == "Credit Card"){
		$('#kode_akun').val(last_kode_cc);
	}
} 

function cari_kas_bank(keyword) {
	$.ajax({
		url : '<?php echo base_url(); ?>kas_bank_c/cari_kas_bank',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$isi = "";
			if(result.length == 0){
				$isi = "<tr><td colspan='3' style='text-align:center;'> <b> Tidak ada kode akun yang dapat ditampilkan </b> </td></tr>";
			} else {
				$.each(result, function(i, field){
				$isi += 
					"<tr>"+
						"<td style='text-align:center;'> <b> "+field.KODE_AKUN+" </b> </td>"+
						"<td style='text-align:left;'> <b> "+field.NAMA_AKUN+" </b> </td>"+
						"<td style='text-align:right;'> <b> 0 </b> </td>"+
					"</tr>";
				});
			}

			$('#tes').html($isi);
		}
	});
}

function ubah_data_produk(id){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>kas_bank_c/cari_produk_by_id',
		data : {id:id},
		type : "GET",
		dataType : "json",
		success : function(result){
			$('#popup_load').hide();
			$('#id_produk').val(result.ID);
			$('#kode_produk_ed').val(result.KODE_PRODUK);
			$('#nama_produk_ed').val(result.NAMA_PRODUK);
			$('#satuan_ed').val(result.SATUAN);
			$('#deskripsi_ed').val(result.DESKRIPSI);



	        //$("#kategori_ed").chosen("destroy");

	        $('#view_data').hide();
	        $('#edit_data').fadeIn('slow');
		}
	});
}

function detail_supplier(id){
	$('#popup_load').show();
	$.ajax({
		url : '<?php echo base_url(); ?>kas_bank_c/cari_supplier_by_id',
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
	$('.opt_btn').hide();
	$('#view_data').hide();
	$('#add_data').fadeIn('slow');
}

function batal_klik(){
	$('#add_data').hide();
	$('.opt_btn').show();
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