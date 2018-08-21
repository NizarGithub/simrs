
<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_tambah, #view_stok, #tombol_reset, #view_gambar, #view_input_pemakaian, #view_ubah{
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

    data_peralatan();

    $("input[name='urutkan']").click(function(){
    	var urutkan = $("input[name='urutkan']:checked").val();
    	if(urutkan == 'Stok'){
    		$('#view_stok').show();
    	}else{
    		$('#view_stok').hide();
    		data_peralatan();
    	}
    });

    $('#urutkan_stok').change(function(){
    	data_peralatan();
    });

    $('#jumlah_tampil').change(function(){
    	data_peralatan();
    });

	$('#btn_tambah').click(function(){
		$('#view_tambah').show();
		$('#view_data').hide();
		$('#ket').val('Tambah');
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>logistik/log_peralatan_medis_c";
	});

	$('#batal_ubah').click(function(){
		window.location = "<?php echo base_url(); ?>logistik/log_peralatan_medis_c";
	});

	$('#kode_alat').click(function(){
		$('#popup_nama_alat').click();
		load_nama_alat();
	});

	$('.btn_nama_alat').click(function(){
		$('#popup_nama_alat').click();
		load_nama_alat();
	});

	$('#satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});

	$('.btn_satuan').click(function(){
		$('#popup_satuan').click();
		get_satuan();
	});

	$('#golongan').click(function(){
		$('#popup_golongan').click();
	});

	$('.btn_golongan').click(function(){
		$('#popup_golongan').click();
	});

	$('#departemen').click(function(){
		$('#popup_departemen').click();
		get_departemen();
	});

	$('.btn_departemen').click(function(){
		$('#popup_departemen').click();
		get_departemen();
	});

	$('#divisi').click(function(){
		$('#popup_divisi').click();
		get_divisi();
	});

	$('.btn_divisi').click(function(){
		$('#popup_divisi').click();
		get_divisi();
	});
});
function load_nama_alat(){
	var keyword = $('#cari_nama_alat').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url()?>logistik/log_peralatan_medis_c/load_nama_alat',
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

					$tr += "<tr style='cursor:pointer;' onclick='klik_nama_alat("+result[i].ID+");'>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td style='text-align:center;'>"+result[i].KODE_ALAT+"</td>"+
								"<td style='text-align:center;'>"+result[i].BARCODE+"</td>"+
								"<td>"+result[i].NAMA_ALAT+"</td>"+
								"<td>"+result[i].MERK+"</td>"+
							"</tr>";
				}
			}

			$('#tabel_nama_alat tbody').html($tr);
		}
	});

	$('#cari_nama_alat').off('keyup').keyup(function(){
		load_nama_alat();
	});
}

function klik_nama_alat(id){
	$('#tutup_nama_alat').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_nama_alat',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_nama_alat').val(id);

			if(row['ID_ALAT'] == null){
				$('#kode_alat').val(row['KODE_ALAT']);
				$('#nama_alat').val(row['NAMA_ALAT']);
				$('#merk').val(row['MERK']);
				$('#jenis_alat').val(row['JENIS_ALAT']);
				$('#id_satuan').val("");
				$('#satuan').val("");
				$('#id_golongan').val("");
				$('#golongan').val("");
				$('#view_input_pemakaian').hide();
				$('#jumlah').val("");
				$('#isi').val("");
				$('#total').val("");
				$('#harga_beli').val("");
				$('#view_gambar').hide();
			}else{
				$('#kode_alat').val(row['KODE_ALAT']);
				$('#nama_alat').val(row['NAMA_ALAT']);
				$('#merk').val(row['MERK']);
				$('#jenis_alat').val(row['JENIS_ALAT']);
				$('#id_satuan').val(row['ID_SATUAN']);
				$('#satuan').val(row['NAMA_SATUAN']);
				$('#id_golongan').val("");
				$('#golongan').val("");
				$('#view_input_pemakaian').show();
				$('#jumlah').val(NumberToMoney(row['JUMLAH']));
				$('#isi').val(NumberToMoney(row['ISI']));
				$('#total').val(NumberToMoney(row['TOTAL']));
				$('#harga_beli').val(NumberToMoney(row['HARGA_BELI']));

				var link_gambar = "<?php echo base_url(); ?>files/foto_alat/"+row['GAMBAR'];
				$('#view_gambar').show();
				$('#gambar').attr('src',link_gambar);
				$('#file_hidden').val(row['GAMBAR']);
			}
		}
	});
}

function get_satuan(){
	var keyword = $('#cari_satuan').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_satuan',
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

function get_departemen(){
	var keyword = $('#cari_departemen').val();
	if(ajax){
		ajax.abort();
	}
	ajax = $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_departemen',
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
	             $tr += '<tr style="cursor:pointer;" onclick="klik_departemen('+result[i].ID+');">'+
	            					'<td style="text-align:center;">'+no+'</td>'+
	            					'<td style="text-align:center;">'+result[i].NAMA_DEP+'</td>'+
	            				'</tr>';
	            }
            }
            $('#tabel_departemen tbody').html($tr);
        }
    });

    $('#cari_departemen').off('keyup').keyup(function(){
    	get_departemen();
    });
}

function get_divisi(){
	var keyword = $('#cari_divisi').val();
	var keterangan = $('#ket').val();
	if(ajax){
		ajax.abort();
	}
	if(ket == 'Tambah'){
			var id_departemen = $('#id_departemen').val();
			ajax = $.ajax({
		        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_divisi',
		        data : {
										keyword:keyword,
										id_departemen:id_departemen
									 },
		        type : "POST",
		        dataType : "json",
		        success : function(result){
		            $tr = "";
		            if(result == "" || result == null){
		            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
		            }else{
			            var no = 0;
			            for(var i=0; i<result.length; i++){
			            	no++;
			             $tr += '<tr style="cursor:pointer;" onclick="klik_divisi('+result[i].ID+');">'+
			            					'<td style="text-align:center;">'+no+'</td>'+
			            					'<td style="text-align:center;">'+result[i].NAMA_DIV+'</td>'+
			            				'</tr>';
			            }
		            }
		            $('#tabel_divisi tbody').html($tr);
		        }
		    });
	}else{
			var id_departemen = $('#id_departemen_ubah').val();
			ajax = $.ajax({
		        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_divisi',
		        data : {
										keyword:keyword,
										id_departemen:id_departemen
									 },
		        type : "POST",
		        dataType : "json",
		        success : function(result){
		            $tr = "";
		            if(result == "" || result == null){
		            	$tr = "<tr><td colspan='2' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
		            }else{
			            var no = 0;
			            for(var i=0; i<result.length; i++){
			            	no++;
			             $tr += '<tr style="cursor:pointer;" onclick="klik_divisi('+result[i].ID+');">'+
			            					'<td style="text-align:center;">'+no+'</td>'+
			            					'<td style="text-align:center;">'+result[i].NAMA_DIV+'</td>'+
			            				'</tr>';
			            }
		            }
		            $('#tabel_divisi tbody').html($tr);
		        }
		    });
	}
    $('#cari_divisi').off('keyup').keyup(function(){
    	get_divisi();
    });
}

function klik_satuan(id_satuan){
	$('#tutup_satuan').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_satuan',
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

function klik_departemen(id_departemen){
	$('#tutup_departemen').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_departemen',
		data : {id_departemen:id_departemen},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_departemen').val(id_departemen);
                $('#departemen').val(row['NAMA_DEP']);
            }else{
                $('#id_departemen_ubah').val(id_departemen);
                $('#departemen_ubah').val(row['NAMA_DEP']);
            }
		}
	});
}

function klik_divisi(id_divisi){
	$('#tutup_divisi').click();

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/klik_divisi',
		data : {id_divisi:id_divisi},
		type : "POST",
		dataType : "json",
		success : function(row){
            var ket = $('#ket').val();

            if(ket == 'Tambah'){
                $('#id_divisi').val(id_divisi);
                $('#divisi').val(row['NAMA_DIV']);
            }else{
                $('#id_divisi_ubah').val(id_divisi);
                $('#divisi_ubah').val(row['NAMA_DIV']);
            }
		}
	});
}

function klik_golongan(no_golongan){
	$('#tutup_golongan').click();
	var ket = $('#ket').val();

	var golongan = {
										1  : 'Alat Kardiologi',
										2  : 'Alat Farmasi',
										3  : 'Alat Sterilisasi Medis',
										4  : 'Alat THT',
										5  : 'Alat Kedokteran Umum',
										6  : 'Alat Kesehatan Paru',
										7  : 'Alat Medis',
										8  : 'Alat Bedah',
										9  : 'Furniture Rumah Sakit',
										10  : 'Breathalyzer',
										11 : 'Alat Ginekologi',
										12 : 'Alat Neurologi',
										13 : 'Alat Forensik'
			           }
								 console.log(golongan[0]);
	 if(ket == 'Tambah'){
 			$('#golongan').val(golongan[no_golongan]);
 	}else{
 			$('#golongan_ubah').val(golongan[no_golongan]);
 	}
}

function paging($selector){
	var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_alat tbody tr");
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

function data_peralatan(){
	$('#popup_load').show();
	var keyword = $('#cari_alat').val();
	var urutkan = $("input[name='urutkan']:checked").val();
	var urutkan_stok = $('#urutkan_stok').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_peralatan',
		data : {
			keyword:keyword,
			urutkan:urutkan,
			urutkan_stok:urutkan_stok
		},
		type : "POST",
		dataType : "json",
		success : function(result){
			$tr = "";

			if(result == "" || result == null){
				$tr = "<tr><td colspan='9' style='text-align:center;'><b>Data tidak ditemukan</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;

					var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm m-b-5" onclick="ubah_alat('+result[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_alat('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

					$tr += "<tr>"+
								"<td style='text-align:center;'>"+no+"</td>"+
								"<td>"+
									"<b>"+result[i].NAMA_ALAT+"</b><br>"+
									"<small>"+result[i].BARCODE+"</small>"+
								"</td>"+
								"<td style='text-align:center;'>"+result[i].URUT_BARANG+"</td>"+
								"<td>"+result[i].JENIS_ALAT+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].HARGA_BELI)+"</td>"+
								"<td style='text-align:right;'>"+NumberToMoney(result[i].TOTAL)+"</td>"+
								"<td style='text-align:center;'>"+formatTanggal(result[i].TANGGAL_MASUK)+"</td>"+
								"<td style='text-align:center;'>"+result[i].WAKTU_MASUK+"</td>"+
								"<td align='center'>"+aksi+"</td>"+
							"</tr>";
				}
			}
			$('#tabel_alat tbody').html($tr);
			paging();
			$('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
        data_peralatan();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_alat').val("");
        data_peralatan();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_peralatan();
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
function hitung_total_harga(){
    var ket = $('#ket').val();

    if(ket == 'Tambah'){
        var total_barang = $('#total').val();
				var harga_beli = $('#harga_beli').val();

        total_barang = total_barang.split(',').join('');
        harga_beli = harga_beli.split(',').join('');

        if(total_barang == ""){
            total_barang = 0;
        }

        if(harga_beli == ""){
            harga_beli = 0;
        }

        var total = parseFloat(total_barang) * parseFloat(harga_beli);
        $('#total_harga').val(NumberToMoney(total));
    }else{
			var total_barang = $('#total_ubah').val();
			var harga_beli = $('#harga_beli_ubah').val();

			total_barang = total_barang.split(',').join('');
			harga_beli = harga_beli.split(',').join('');

			if(total_barang == ""){
					total_barang = 0;
			}

			if(harga_beli == ""){
					harga_beli = 0;
			}

			var total = parseFloat(total_barang) * parseFloat(harga_beli);
			$('#total_harga_ubah').val(NumberToMoney(total));
    }
}

function ubah_alat(id){
	$('#view_ubah').show();
	$('#view_data').hide();
	$('#ket').val('Ubah');

	$.ajax({
		url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/get_edit_data',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(row['ID_LOG']);
			$('#id_departemen_ubah').val(row['ID_DEPARTEMEN']);
			$('#departemen_ubah').val(row['NAMA_DEP']);

			$('#id_divisi_ubah').val(row['ID_DIVISI']);
			$('#divisi_ubah').val(row['NAMA_DIV']);

			$('#id_nama_alat_ubah').val(row['ID_SETUP_NAMA_ALAT']);
			$('#kode_alat_ubah').val(row['KODE_ALAT']);
			$('#nama_alat_ubah').val(row['NAMA_ALAT']);
			$('#merk_ubah').val(row['MERK']);
			$('#jenis_alat_ubah').val(row['JENIS_ALAT']);
			$('#jumlah_ubah').val(row['JUMLAH']);
			$('#isi_ubah').val(row['ISI']);
			$('#total_ubah').val(row['TOTAL']);
			$('#harga_beli_ubah').val(NumberToMoney(row['HARGA_BELI']));
			$('#total_harga_ubah').val(NumberToMoney(row['TOTAL_HARGA']));

			if (row['GAMBAR'] == '' || row['GAMBAR'] == null) {
			  // $('#view_gambar_ubah').hide();
				$('#gambar_ubah').attr('style','display:none;');
			}else {
			  var link_gambar = "<?php echo base_url(); ?>files/foto_alat/"+row['GAMBAR'];
	 		  $('#view_gambar_ubah').show();
	 			$('#gambar_ubah').attr('src',link_gambar);
	 			$('#file_hidden_ubah').val(row['GAMBAR']);
			}

				$('#id_satuan_ubah').val(row['ID_SATUAN_ALAT']);
				$('#satuan_ubah').val(row['NAMA_SATUAN']);

				$('#golongan_ubah').val(row['GOLONGAN']);
		}
	})
}
function hapus_alat(id){
    $('#popup_hapus').click();
    $.ajax({
        url : '<?php echo base_url(); ?>logistik/log_peralatan_medis_c/data_peralatan_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_hapus').val(id);
            var txt = row['KODE_ALAT']+' - '+row['NAMA_ALAT'];
            $('#msg').html('Apakah data logistik <b>'+txt+'</b> ingin dihapus?');
        }
    });
}
</script>

<input type="hidden" id="ket" value="">

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="col-lg-12">
    <div class="card-box" id="view_data">
    	<form class="form-horizontal" role="form">
            <div class="form-group">
                <div class="col-md-7">
                    <button type="button" class="btn btn-purple waves-effect w-md waves-light" id="btn_tambah">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
								<div class="col-md-2 pull-right">
                    <button type="button" class="btn btn-success waves-effect w-md waves-light col-md-12" id="btn_tambah">
                        <i class="fa fa-plus"></i> Input Excel
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
                        <input type="radio" name="urutkan" value="Nama Alat" id="urut_nama_alat">
                        <label for="urut_nama_alat"> Nama Alat </label>
                    </div>
                    <div class="radio radio-purple radio-inline">
                        <input type="radio" name="urutkan" value="Stok" id="urut_stok">
                        <label for="urut_stok"> Stok </label>
                    </div>
                </div>
                <div class="col-md-4 pull-right">
	                <div class="input-group">
	                    <input type="text" class="form-control" id="cari_alat" placeholder="Cari..." value="" onkeypress="return onEnterText(event);">
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
            <table id="tabel_alat" class="table table-bordered">
                <thead>
                    <tr class="biru">
                        <th style="color:#fff; text-align:center;" width="50">No</th>
                        <th style="color:#fff; text-align:center;">Nama Alat</th>
                        <th style="color:#fff; text-align:center;">No. FIFO</th>
                        <th style="color:#fff; text-align:center;">Jenis Alat</th>
                        <th style="color:#fff; text-align:center;">Harga Beli</th>
                        <th style="color:#fff; text-align:center;">Stok</th>
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

    <div class="card-box" id="view_tambah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" enctype="multipart/form-data">
            <h4 class="header-title m-t-0 m-b-30">Tambah Alat</h4>
            <hr/>
            <div class="row">
                <div class="col-lg-6">
									<div class="form-group">
											<label class="col-md-2 control-label">Departemen</label>
											<div class="col-md-8">
													<div class="input-group">
															<input type="hidden" name="id_departemen" id="id_departemen" value="">
															<input type="text" class="form-control" id="departemen" value="" required="required" readonly>
															<span class="input-group-btn">
																	<button class="btn waves-effect waves-light btn-default btn_departemen" type="button">
																			<i class="fa fa-search"></i>
																	</button>
															</span>
													</div>
											</div>
									</div>
									<div class="form-group">
											<label class="col-md-2 control-label">Divisi</label>
											<div class="col-md-8">
													<div class="input-group">
															<input type="hidden" name="id_divisi" id="id_divisi" value="">
															<input type="text" class="form-control" id="divisi" value="" required="required" readonly>
															<span class="input-group-btn">
																	<button class="btn waves-effect waves-light btn-default btn_divisi" type="button">
																			<i class="fa fa-search"></i>
																	</button>
															</span>
													</div>
											</div>
									</div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Alat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_alat" id="id_nama_alat" value="">
                                <input type="text" class="form-control" id="kode_alat" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_alat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Alat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_alat" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="merk" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Barang</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="jenis_alat" value="" required="required" readonly>
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
                        <label class="col-md-2 control-label">Golongan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="golongan" id="golongan" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_golongan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-2 control-label">Pemakaian</label>
                        <div class="col-md-6" id="view_select_pemakaian">
		                    <select name="pemakaian" id="pemakaian" class="form-control">
		                    	<option value="Sekali Pakai">Sekali Pakai</option>
		                    	<option value="Dipakai Berulang">Dipakai Berulang</option>
		                    </select>
		                </div>
		                <div class="col-md-8" id="view_input_pemakaian">
                            <input type="text" class="form-control" name="pemakaian_txt" id="pemakaian_txt" value="" readonly>
                        </div>
                    </div> -->
                    <div class="form-group" id="view_gambar">
                        <label class="col-md-2 control-label">View Gambar</label>
                        <div class="col-md-6">
                        	<input type="hidden" name="file_hidden" id="file_hidden" value="">
		                    <img src="" id="gambar" style="max-width:250px; max-height:125px;">
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
                                	<button class="btn waves-effect waves-light btn-pink" type="button" style="cursor:default;">buah</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total Barang</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total" id="total" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli" id="harga_beli" value="" required="required" onkeyup="FormatCurrency(this); hitung_total_harga();">
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Total Harga</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="total_harga" id="total_harga" value="" required="required" onkeyup="FormatCurrency(this);" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Gambar</label>
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
    	</form>
    </div>
		<div class="card-box" id="view_ubah">
    	<form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post" enctype="multipart/form-data">
            <h4 class="header-title m-t-0 m-b-30">Ubah Alat</h4>
            <hr/>
            <div class="row">
                <div class="col-lg-6">
									<input type="hidden" name="id_log" id="id_ubah" value="">
									<div class="form-group">
											<label class="col-md-2 control-label">Departemen</label>
											<div class="col-md-8">
													<div class="input-group">
															<input type="hidden" name="id_departemen" id="id_departemen_ubah" value="">
															<input type="text" class="form-control" id="departemen_ubah" value="" required="required" readonly>
															<span class="input-group-btn">
																	<button class="btn waves-effect waves-light btn-default btn_departemen" type="button">
																			<i class="fa fa-search"></i>
																	</button>
															</span>
													</div>
											</div>
									</div>
									<div class="form-group">
											<label class="col-md-2 control-label">Divisi</label>
											<div class="col-md-8">
													<div class="input-group">
															<input type="hidden" name="id_divisi" id="id_divisi_ubah" value="">
															<input type="text" class="form-control" id="divisi_ubah" value="" required="required" readonly>
															<span class="input-group-btn">
																	<button class="btn waves-effect waves-light btn-default btn_divisi" type="button">
																			<i class="fa fa-search"></i>
																	</button>
															</span>
													</div>
											</div>
									</div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Alat</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="hidden" name="id_nama_alat" id="id_nama_alat_ubah" value="">
                                <input type="text" class="form-control" id="kode_alat_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-default btn_nama_alat" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Alat</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_alat_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Merk</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="merk_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jenis Barang</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="jenis_alat_ubah" value="" required="required" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Satuan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                            	<input type="hidden" name="id_satuan" id="id_satuan_ubah" value="">
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
                        <label class="col-md-2 control-label">Golongan</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="golongan" id="golongan_ubah" value="" required="required" readonly>
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-default btn_golongan" type="button">
                                		<i class="fa fa-search"></i>
                                	</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-2 control-label">Pemakaian</label>
                        <div class="col-md-6" id="view_select_pemakaian">
		                    <select name="pemakaian" id="pemakaian" class="form-control">
		                    	<option value="Sekali Pakai">Sekali Pakai</option>
		                    	<option value="Dipakai Berulang">Dipakai Berulang</option>
		                    </select>
		                </div>
		                <div class="col-md-8" id="view_input_pemakaian">
                            <input type="text" class="form-control" name="pemakaian_txt" id="pemakaian_txt" value="" readonly>
                        </div>
                    </div> -->
                    <div class="form-group" id="view_gambar_ubah">
                        <label class="col-md-2 control-label">View Gambar</label>
                        <div class="col-md-6">
                        	<input type="hidden" name="file_hidden" id="file_hidden_ubah" value="">
		                    <img src="" id="gambar_ubah" style="max-width:250px; max-height:125px;">
		                </div>
                    </div>
                </div>

                <div class="col-lg-6">
                	<div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="jumlah" id="jumlah_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Isi</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="isi" id="isi_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                                <span class="input-group-btn">
                                	<button class="btn waves-effect waves-light btn-pink" type="button" style="cursor:default;">buah</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total Barang</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="total" id="total_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga Beli</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="harga_beli" id="harga_beli_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total_harga();">
                            </div>
                        </div>
                    </div>
										<div class="form-group">
                        <label class="col-md-2 control-label">Total Harga</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="total_harga" id="total_harga_ubah" value="" required="required" onkeyup="FormatCurrency(this);" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Gambar</label>
                        <div class="col-md-8">
                            <input type="file" class="dropify" name="userfile">
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_nama_alat" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Nama Alat Medis</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_nama_alat" placeholder="Cari..." value="">
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
                        <table class="table table-hover" id="tabel_nama_alat">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Alat</th>
                                    <th style="text-align:center; color: #fff;">Barcode</th>
                                    <th style="text-align:center; color: #fff;">Nama Alat</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_nama_alat">Tutup</button>
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
                <h4 class="modal-title" id="myModalLabel">Data Jenis Barang</h4>
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
		                            <th style="text-align:center; color: #fff;">Satuan Barang</th>
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

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" id="popup_golongan" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Golongan</h4>
            </div>
            <div class="modal-body">
            	<div class="table-responsive">
            		<div class="scroll-y">
		                <table class="table table-hover">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Jenis Golongan</th>
		                        </tr>
		                    </thead>
		                    <tbody>
													<?php
													$array = array(
														0 => 'Alat Kardiologi',
														1 => 'Alat Farmasi',
														2 => 'Alat Sterilisasi Medis',
														3 => 'Alat THT',
														4 => 'Alat Kedokteran Umum',
														5 => 'Alat Kesehatan Paru',
														6 => 'Alat Medis',
														7 => 'Alat Bedah',
														8 => 'Furniture Rumah Sakit',
														9 => 'Breathalyzer',
														10 => 'Alat Ginekologi',
														11 => 'Alat Neurologi',
														12 => 'Alat Forensik'
													);
													$no = 0;

													for ($i=0; $i < count($array); $i++) {
													$no++;
													 ?>
													<tr style="cursor:pointer;" onclick="klik_golongan(<?php echo $no; ?>);">
														<td><?php echo $no; ?></td>
														<td><?php echo $array[$i]; ?></td>
													</tr>
													<?php } ?>
		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_golongan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" id="popup_departemen" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Departemen</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_departemen" placeholder="Cari..." value="">
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
		                <table class="table table-hover" id="tabel_departemen">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Departemen</th>
		                        </tr>
		                    </thead>
		                    <tbody>

		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_departemen">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal6" id="popup_divisi" style="display:none;">Standard Modal</button>
<div id="myModal6" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Divisi</h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" role="form">
		            <div class="form-group">
		                <div class="col-md-12">
			                <div class="input-group">
			                    <input type="text" class="form-control" id="cari_divisi" placeholder="Cari..." value="">
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
		                <table class="table table-hover" id="tabel_divisi">
		                    <thead>
		                        <tr class="merah_popup">
		                            <th style="text-align:center; color: #fff;" width="50">No</th>
		                            <th style="text-align:center; color: #fff;">Divisi</th>
		                        </tr>
		                    </thead>
		                    <tbody>

		                    </tbody>
		                </table>
            		</div>
            	</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_divisi">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
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
