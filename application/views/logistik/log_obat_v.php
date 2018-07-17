<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_ubah, #tombol_reset, #view_stok{
	display: none;
}
</style>

<script type="text/javascript">
var ajax = "";
$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php }else if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
        notif_hapus();
    <?php } ?>

    data_obat();

    $("input[name='urutkan']").click(function(){
        var urut = $("input[name='urutkan']:checked").val();

        if(urut == 'Stok'){
            $('#view_stok').show();
        }else{
            $('#view_stok').hide();
            data_obat();
        }
    });

    $('#urutkan_stok').change(function(){
        data_obat();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
        $('#ket').val('Tambah');
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>logistik/log_obat_c";
	});

    $('.btn_nama_obat').click(function(){
        $('#popup_nama_obat').click();
        get_nama_obat();
    });

	$('#jenis_obat').click(function(){
		$('#popup_jenis').click();
		get_jenis_obat();
	});

	$('.btn_jenis').click(function(){
		$('#popup_jenis').click();
		get_jenis_obat();
	});

	$('#satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});

	$('.btn_satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});

    $('#jumlah_tampil').change(function(){
        data_obat();
    });
});

//NAMA OBAT

function get_nama_obat(){
    var keyword = $('#cari_nama_obat').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/data_nama_obat',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
                var no = 0;
                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += '<tr style="cursor:pointer;" onclick="klik_nama_obat('+result[i].ID+');">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+result[i].KODE_OBAT+'</td>'+
                                '<td>'+result[i].BARCODE+'</td>'+
                                '<td>'+result[i].NAMA_OBAT+'</td>'+
                                '<td>'+result[i].MERK+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_nama_obat tbody').html($tr);
        }
    });

    $('#cari_nama_obat').off('keyup').keyup(function(){
        get_nama_obat();
    });
}

function klik_nama_obat(id){
    $('#tutup_nama_obat').click();

    $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/klik_nama_obat',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_nama_obat').val(id);
                $('#kode_obat').val(row['KODE_OBAT']);
                $('#nama_obat').val(row['NAMA_OBAT']);
                $('#id_merk').val(row['ID_MERK']);
                $('#merk').val(row['MERK']);

                if(row['ID_GUDANG'] != null){
                    $('#id_jenis').val(row['ID_JENIS_OBAT']);
                    $('#jenis_obat').val(row['NAMA_JENIS']);
                    $('#id_satuan').val(row['ID_SATUAN_OBAT']);
                    $('#satuan').val(row['NAMA_SATUAN']);
                    $('#tanggal_expired').val(row['KADALUARSA']);
                    $('#jumlah').val(NumberToMoney(row['JUMLAH']));
                    $('#isi').val(NumberToMoney(row['ISI']));
                    $('#total').val(NumberToMoney(row['TOTAL']));
                    $('#jumlah_butir').val(NumberToMoney(row['JUMLAH_BUTIR']));
                    $('#harga_beli').val(NumberToMoney(row['HARGA_BELI']));
                    $('#harga_jual').val(NumberToMoney(row['HARGA_JUAL']));
                }else{
                    $('#id_jenis').val("");
                    $('#jenis_obat').val("");
                    $('#id_satuan').val("");
                    $('#satuan').val("");
                    $('#tanggal_expired').val("");
                    $('#jumlah').val("");
                    $('#isi').val("");
                    $('#total').val("");
                    $('#jumlah_butir').val("");
                    $('#harga_beli').val("");
                    $('#harga_jual').val("");
                }
            }else{
                $('#id_nama_obat_ubah').val(id);
                $('#kode_obat_ubah').val(row['KODE_OBAT']);
                $('#nama_obat_ubah').val(row['NAMA_OBAT']);
                $('#id_merk_ubah').val(row['ID_MERK']);
                $('#merk_ubah').val(row['MERK']);
            }
        }
    });
}
//------------

//JENIS OBAT

function get_jenis_obat(){
	var keyword = $('#cari_jenis').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/data_jenis_obat',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
	            var no = 0;
	            for(var i=0; i<result.length; i++){
	            	no++;

	            	$tr += '<tr style="cursor:pointer;" onclick="klik_jenis('+result[i].ID+');">'+
	            				'<td style="text-align:center;">'+no+'</td>'+
	            				'<td>'+result[i].NAMA_JENIS+'</td>'+
	            			'</tr>';
	            }
            }

            $('#tabel_jenis tbody').html($tr);
        }
    });

    $('#cari_jenis').off('keyup').keyup(function(){
    	get_jenis_obat();
    });
}

function klik_jenis(id_jenis){
	$('#tutup_jenis').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_obat_c/klik_jenis',
		data : {id_jenis:id_jenis},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
    			$('#id_jenis').val(id_jenis);
    			$('#jenis_obat').val(row['NAMA_JENIS']);
            }else{
                $('#id_jenis_ubah').val(id_jenis);
                $('#jenis_obat_ubah').val(row['NAMA_JENIS']);
            }
		}
	});
}

//--------------

//SATUAN OBAT

function get_satuan(){
	var keyword = $('#cari_satuan').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/data_satuan',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
            }else{
	            var no = 0;

	            for(var i=0; i<result.length; i++){
	            	no++;

	            	$tr += '<tr style="cursor:pointer;" onclick="klik_satuan('+result[i].ID+');">'+
	            				'<td style="text-align:center;">'+no+'</td>'+
	            				'<td style="text-align:center;">'+result[i].NAMA_SATUAN+'</td>'+
	            			'</tr>';
	            }
            }

            $('#tabel_satuan tbody').html($tr);
        }
    });

    $('#cari_satuan').off('keyup').keyup(function(){
    	get_satuan();
    });
}

function klik_satuan(id_satuan){
	$('#tutup_satuan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_obat_c/klik_satuan',
		data : {id_satuan:id_satuan},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_satuan').val(id_satuan);
                $('#satuan').val(row['NAMA_SATUAN']);
            }else{
                $('#id_satuan_ubah').val(id_satuan);
                $('#satuan_ubah').val(row['NAMA_SATUAN']);
            }
		}
	});
}
//-------------

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_obat tbody tr");
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

function data_obat(){
	$('#popup_load').show();

	var keyword = $('#cari_obat').val();
    var urutkan = $("input[name='urutkan']:checked").val();
    var urutkan_stok = $('#urutkan_stok').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>logistik/log_obat_c/get_data_obat',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            urutkan_stok:urutkan_stok,
        },
        type : "GET",
        dataType : "json",
        success : function(result){
        	$tr = "";

        	if(result == "" || result == null){
        		$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
        	}else{
        		var no = 0;

        		for(var i=0; i<result.length; i++){
        			no++;

                    var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_obat('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    var warna = "";

                    if(result[i].AKTIF == 0){
                        warna = "merah_tr";
                    }else{
                        warna = "";
                    }

        			$tr += "<tr class='"+warna+"'>"+
        						"<td style='text-align:center;'>"+no+"</td>"+
        						"<td>"+
        							"<b>"+result[i].NAMA_OBAT+"</b><br/>"+
                                    "<small>"+result[i].KODE_OBAT+"</small>"+
        						"</td>"+
                                "<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
                                "<td>"+result[i].NAMA_JENIS+"</td>"+
                                "<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
        						"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_JUAL)+"</td>"+
        						"<td>"+NumberToMoney(result[i].TOTAL)+"&nbsp;"+result[i].SATUAN_ISI+"</td>"+
        						"<td>"+formatTanggal(result[i].KADALUARSA)+"</td>"+
                                "<td>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
                                "<td align='center'>"+aksi+"</td>"+
        					"</tr>";
        		}
        	}

        	$('#tabel_obat tbody').html($tr);
        	paging();
        	$('#popup_load').fadeOut();
        }
	});

    $('#tombol_cari').click(function(){
        data_obat();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_obat').val("");
        data_obat();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_obat();
        $('#tombol_reset').show();
		$('#tombol_cari').hide();
        return false;
    }
}

function hitung_total(){
    var ket = $('#ket').val();

    if(ket == 'Tambah'){
        var jumlah = $('#jumlah').val();
        var isi = $('#isi').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total').val(NumberToMoney(total));
    }else{
        var jumlah = $('#jumlah_ubah').val();
        var isi = $('#isi_ubah').val();

        jumlah = jumlah.split(',').join('');
        isi = isi.split(',').join('');

        if(jumlah == ""){
            jumlah = 0;
        }

        if(isi == ""){
            isi = 0;
        }

        var total = parseFloat(jumlah) * parseFloat(isi);
        $('#total_ubah').val(NumberToMoney(total));
    }
}

function ubah_obat(id){
    $('#view_ubah').show();
    $('#view_data').hide();
    $('#ket').val('Ubah');

    $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ubah').val(id);
            $('#id_nama_obat_ubah').val(row['ID_SETUP_NAMA_OBAT']);
            $('#kode_obat_ubah').val(row['KODE_OBAT']);
            $('#nama_obat_ubah').val(row['NAMA_OBAT']);
            $('#id_merk_ubah').val(row['ID_MERK']);
            $('#merk_ubah').val(row['MERK']);
            $('#id_jenis_ubah').val(row['ID_JENIS_OBAT']);
            $('#jenis_obat_ubah').val(row['NAMA_JENIS']);
            $('#id_satuan_ubah').val(row['ID_SATUAN_OBAT']);
            $('#satuan_ubah').val(row['NAMA_SATUAN']);
            $('#tanggal_expired_ubah').val(row['KADALUARSA']);
            $('#jumlah_ubah').val(NumberToMoney(row['JUMLAH']));
            $('#isi_ubah').val(NumberToMoney(row['ISI']));
            $('#total_ubah').val(NumberToMoney(row['TOTAL']));
            $('#jumlah_butir_ubah').val(NumberToMoney(row['JUMLAH_BUTIR']));
            $('#harga_beli_ubah').val(NumberToMoney(row['HARGA_BELI']));
            $('#harga_jual_ubah').val(NumberToMoney(row['HARGA_JUAL']));
            $('#file_hidden').val(row['GAMBAR']);
            var link_gambar = "";
            if(row['GAMBAR'] == null){
                link_gambar = "<?php echo base_url(); ?>picture/noImageAvailable.jpg";
            }else{
                link_gambar = "<?php echo base_url(); ?>files/foto_obat/"+row['GAMBAR'];
            }
            $('#gambar_ubah').attr('src',link_gambar);
        }
    });

    $('#batal_ubah').click(function(){
        $('#view_ubah').hide();
        $('#view_data').show();
        $('#ket').val("");
    });
}

function hapus_obat(id){
    $('#popup_hps').click();

    $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_obat_c/data_obat_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_hapus').val(id);
            var txt = row['KODE_OBAT']+' - '+row['NAMA_OBAT'];
            $('#msg').html('Apakah data obat <b>'+txt+'</b> ingin dihapus?');
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<input type="hidden" id="ket" value="">

<div class="col-lg-12">
    <div class="card-box card-tabs" id="view_data">
    	<form class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-7">
                    <button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
                        <i class="fa fa-plus"></i> Tambah Obat
                    </button>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" style="text-align:left;">Urutkan</label>
                <div class="col-md-6">
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                        <label for="default"> Default </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Nama Obat" id="urut_nama_obat">
                        <label for="nama_poli"> Nama Obat </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Stok" id="urut_stok">
                        <label for="jenis"> Stok </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Expired" id="urut_expired">
                        <label for="jenis"> Expired </label>
                    </div>
                </div>
                <div class="col-md-4 pull-right">
	                <div class="input-group">
	                    <input type="text" class="form-control" id="cari_obat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
            </div>
            <div class="form-group" id="view_stok">
                <label class="col-md-1 control-label">&nbsp;</label>
                <div class="col-md-2">
                    <select id="urutkan_stok" class="form-control">
                        <option value="Rendah">Terendah</option>
                        <option value="Tinggi">Tertinggi</option>
                    </select>
                </div>
            </div>
        </form>

    	<div class="table-responsive">
            <table id="tabel_obat" class="table table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Nama Obat</th>
                        <th style="color:#fff; text-align:center;">No. FIFO</th>
                        <th style="color:#fff; text-align:center;">Jenis Obat</th>
                        <th style="color:#fff; text-align:center;">Harga Beli</th>
                        <th style="color:#fff; text-align:center;">Harga Jual</th>
                        <th style="color:#fff; text-align:center;">Stok</th>
                        <th style="color:#fff; text-align:center;">Tanggal Expired</th>
                        <th style="color:#fff; text-align:center;">Tanggal Masuk</th>
                        <th style="color:#fff; text-align:center;">Waktu</th>
                        <th style="color:#fff; text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </div>
        <form class="form-horizontal" role="form">
        	<div class="form-group">
        		<div class="col-md-10">
        			<div id="tablePaging"> </div>
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
            <div class="form-group">
                <table>
                    <tr>
                        <td><div class="merah_tr" style="width:15px; height:15px;"></div></td>
                        <td>&nbsp;</td>
                        <td>Keterangan warna tabel</td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" id="view_tambah" enctype="multipart/form-data">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">Tambah Obat</h4>
            <hr/>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_obat" id="id_nama_obat" value="">
                                <input type="text" class="form-control" id="kode_obat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_obat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Obat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_obat" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                        	<input type="hidden" name="id_merk" id="id_merk" value="">
                            <input type="text" class="form-control" id="merk" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_jenis" id="id_jenis" value="">
                                <input type="text" class="form-control" id="jenis_obat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_jenis" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Satuan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_satuan" id="id_satuan" value="">
                                <input type="text" class="form-control" id="satuan" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_satuan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expired</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal_expired" id="tanggal_expired" value="" required="required" data-language="en" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-8">
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat" value="Obat Racik" id="radio_obat1">
                                <label for="radio_obat1"> Obat Racik </label>
                            </div>
                            <div class="radio radio-purple radio-inline">
                                <input type="radio" name="status_obat" value="Obat Umum" id="radio_obat2">
                                <label for="radio_obat2"> Obat Umum </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="jumlah" id="jumlah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="isi" id="isi" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-pink" type="button" style="cursor:default;">kaplet</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total" id="total" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi 1 Kaplet</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlah_butir" id="jumlah_butir" value="" required="required" onkeyup="FormatCurrency(this);">
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-purple" type="button" style="cursor:default;">butir</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli" id="harga_beli" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Jual</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_jual" id="harga_jual" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Gambar Obat</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="userfile">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <center>
                <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </div>
    </form>
</div>

<div class="col-md-12" id="view_ubah">
    <div class="card-box card-tabs">
        <form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post" enctype="multipart/form-data">
            <h4 class="header-title m-t-0 m-b-30">Ubah Data Obat</h4>
            <hr/>
            <div class="row">
        		<input type="hidden" name="id_ubah" id="id_ubah" value="">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_obat_ubah" id="id_nama_obat_ubah" value="">
                                <input type="text" class="form-control" id="kode_obat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_obat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Obat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_obat_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                            <input type="hidden" name="id_merk_ubah" id="id_merk_ubah" value="">
                            <input type="text" class="form-control" id="merk_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Obat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_jenis_ubah" id="id_jenis_ubah" value="">
                                <input type="text" class="form-control" id="jenis_obat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_jenis" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Satuan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_satuan_ubah" id="id_satuan_ubah" value="">
                                <input type="text" class="form-control" id="satuan_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_satuan" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Expired</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal_expired_ubah" id="tanggal_expired_ubah" value="" required="required" data-language="en" data-date-format="dd-mm-yyyy">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">View Gambar</label>
                        <div class="col-md-8">
                            <img id="gambar_ubah" style="max-width:200px; max-height:130px;">
                            <input type="hidden" name="file_hidden" id="file_hidden" value="">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="isi_ubah" id="isi_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-pink" type="button" style="cursor:default;">kaplet</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total_ubah" id="total_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi 1 Kaplet</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="jumlah_butir_ubah" id="jumlah_butir_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-purple" type="button" style="cursor:default;">butir</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli_ubah" id="harga_beli_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Jual</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_jual_ubah" id="harga_jual_ubah" value="" required="required" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Ubah Gambar</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="fileuser">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <center>
                <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal_ubah"> <i class="fa fa-times"></i> <span>Batal</span> </button>
            </center>
        </form>
    </div>
</div>

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_nama_obat" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Nama Obat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_nama_obat" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover" id="tabel_nama_obat">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Obat</th>
                                    <th style="text-align:center; color: #fff;">Barcode</th>
                                    <th style="text-align:center; color: #fff;">Nama Obat</th>
                                    <th style="text-align:center; color: #fff;">Merk</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_nama_obat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_jenis" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_jenis" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover" id="tabel_jenis">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_jenis">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_satuan" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Obat</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_satuan" placeholder="Cari..." value="">
			                    <span class="input-group-btn">
			                    	<button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
			                    		<i class="fa fa-search"></i>
			                    	</button>
			                    </span>
			                </div>
		                </div>
		            </div>
		        </form>
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover" id="tabel_satuan">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Satuan Obat</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_satuan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo $url_hapus; ?>" method="post">
                    <input type="hidden" name="id_hapus" id="id_hapus" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>