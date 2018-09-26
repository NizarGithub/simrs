<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_ubah, 
#view_umur, 
#view_kota, 
#view_status,
#tombol_reset, 
#tombol_reset2, 
#tombol_reset3, 
#view_prov_ubah{
	display: none;
}

.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
} 
</style>

<script type="text/javascript">
var timer = 0
var timer2 = 0;
var timer3 = 0;

$(document).ready(function(){
	<?php if($this->session->flashdata('ubah')){?>
        notif_ubah();
    <?php }else if($this->session->flashdata('hapus')){ ?>
    	notif_hapus();
    <?php }else if($this->session->flashdata('kirim')){ ?>
        notif_kirim();
    <?php }else if($this->session->flashdata('sukses')){ ?>
        notif_simpan();
    <?php } ?>

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-bottom-left",
      "preventDuplicates": false,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }

	get_pasien_per_poli();

    var timer = setInterval(function () {
        get_pasien_per_poli();
        toastr["success"]("Sinkronisasi data...");
    }, 20000);

    $('#jumlah_tampil').change(function(){
        get_pasien_per_poli();
    });

    $('#jumlah_tampil2').change(function(){
        $('#popup_load').show();
        get_data_pasien();
    });

    $('#jumlah_tampil3').change(function(){
        $('#popup_load').show();
        get_data_pasien_ri();
    });

    $('#li_poli').click(function(){
        get_pasien_per_poli();

        clearInterval(timer2);
        clearInterval(timer3);

        timer = setInterval(function () {
            get_pasien_per_poli();
            toastr["success"]("Sinkronisasi data...");
        }, 20000);
    });

    $('#li_rj').click(function(){
        get_data_pasien();

        clearInterval(timer);
        clearInterval(timer3);

        timer2 = setInterval(function () {
            get_data_pasien();
            toastr["success"]("Sinkronisasi data...");
        }, 20000);
    });

    $('#li_ri').click(function(){
        get_data_pasien_ri();

        clearInterval(timer);
        clearInterval(timer2);

        timer3 = setInterval(function () {
            get_data_pasien_ri();
            toastr["success"]("Sinkronisasi data...");
        }, 20000);
    });

    $('#checkbox2').click(function(){
        var cek = $('#checkbox2').is(":checked");
        if(cek == true){
            $('#view_kota').show();
        }else{
            $('#view_kota').hide();
        }
    });

    $('#btn_pasien_umum').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_data_pasien_c";
    });

    $('#btn_pasien_rj').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_data_pasien_c/pasien_rj";
    });

    $('#btn_pasien_ri').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_data_pasien_c/pasien_ri";
    });

    $('#btn_pasien_igd').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_data_pasien_c/pasien_igd";
    });

});

var ajax = "";

function pagingPerPoli($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien tbody tr"); 
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

function get_pasien_per_poli(){
	// $('#popup_load').show();
    var keyword = $('#cari_pasien').val();

	if(ajax){
		ajax.abort();
	}

	ajax = $.ajax({
		url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_per_poli',
		data : {keyword:keyword},
		type : "GET",
		dataType : "json",
		success : function(result){
			$tr = "";
            var total_pasien = 0;

			if(result == "" || result == null){
				$tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
			}else{
				var no = 0;

				for(var i=0; i<result.length; i++){
					no++;
                    total_pasien += parseInt(result[i].JUMLAH_PASIEN);

					var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="klik_det_pasien_poli('+result[i].ID+');">'+
                                   '<i class="fa fa-eye"></i>'+
                                '</button>&nbsp;';

					$tr +=  '<tr>'+
		                    '	<td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
		                    '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA_POLI+'</td>'+
		                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JUMLAH_PASIEN+'</td>'+
		                    '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
		                    '</tr>';
				}
			}

			$('#tabel_pasien tbody').html($tr);
            $('#total_pasien').html(parseInt(total_pasien));
			pagingPerPoli();
			// $('#popup_load').fadeOut();
		}
	});

	$('#tombol_cari').click(function(){
		get_pasien_per_poli();
		$('#tombol_reset').show();
		$('#tombol_cari').hide();
	});

	$('#tombol_reset').click(function(){
		$('#cari_pasien').val("");
		get_pasien_per_poli();
		$('#tombol_reset').hide();
		$('#tombol_cari').show();
	});
}

function onEnterText(e){
    if (e.keyCode == 13) {
        get_pasien_per_poli();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function klik_det_pasien_poli(id){
    $('#popup_detail_poli').click();
    $('#popup_tabel').show();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_per_poli_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";
            $('#nama_poli_judul').html(result['pl'].NAMA);

            if(result['ps'] == "" || result['ps'] == null){
                $tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result['ps'].length; i++){
                    no++;

                    result['ps'][i].JENIS_KELAMIN = result['ps'][i].JENIS_KELAMIN=='L'?"Laki - Laki":"Perempuan";
                    result['ps'][i].NAMA_AYAH = result['ps'][i].NAMA_AYAH==null?"-":result['ps'][i].NAMA_AYAH;
                    result['ps'][i].NAMA_IBU = result['ps'][i].NAMA_IBU==null?"-":result['ps'][i].NAMA_IBU;
                  
                    $tr +=  '<tr>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result['ps'][i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle;">'+result['ps'][i].NAMA_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result['ps'][i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result['ps'][i].TANGGAL_LAHIR+'</td>'+
                            '   <td style="vertical-align:middle;">'+result['ps'][i].UMUR+' Tahun</td>'+
                            '   <td style="vertical-align:middle;">'+result['ps'][i].NAMA_AYAH+'</td>'+
                            '   <td style="vertical-align:middle;">'+result['ps'][i].NAMA_IBU+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_detail_poli tbody').html($tr);
            $('#popup_tabel').hide();
        }
    });
}

function paging($selector){
    var jumlah_tampil = $('#jumlah_tampil2').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien2 tbody tr"); 
    }

    window.tp = new Pagination('#tablePaging2', {
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

function get_data_pasien(){
   // $('#popup_load').show();
    var keyword = $('#cari_pasien2').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_rj',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr class='active'><td colspan='13' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="detail_pasien('+result[i].ID_PASIEN+');">'+
                                   '<i class="fa fa-eye"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_pasien('+result[i].ID_PASIEN+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_pasien('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    var rekam_medik = '<button type="button" class="btn btn-purple waves-effect waves-light btn-sm" onclick="get_history_medik_rj('+result[i].ID_PASIEN+');">'+
                                        '<i class="fa fa-file-text"></i>&nbsp;Detail'+
                                      '</button>';

                    result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";
                    result[i].ALAMAT = (result[i].ALAMAT==null || result[i].ALAMAT=='')?"-":result[i].ALAMAT;
                    result[i].NAMA_AYAH = result[i].NAMA_AYAH==null?"-":result[i].NAMA_AYAH;
                    result[i].NAMA_IBU = result[i].NAMA_IBU==null?"-":result[i].NAMA_IBU;

                    var warna = '';
                    if(result[i].STS_APPROVE_RM == '1'){
                        warna = 'class="kuning"';
                    }else{
                        warna = "";
                    }

                    $tr +=  '<tr '+warna+'>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].TANGGAL_LAHIR+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].UMUR+' Tahun</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].ALAMAT+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA_AYAH+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA_IBU+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA_POLI+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].INVOICE+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+rekam_medik+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien2 tbody').html($tr);
            $('#total_pasien2').html(parseInt(result.length));
            paging();
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari2').click(function(){
        get_data_pasien();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
    });

    $('#tombol_reset2').click(function(){
        $('#cari_pasien2').val("");
        get_data_pasien();
        $('#tombol_reset2').hide();
        $('#tombol_cari2').show();
    });
}

function onEnterText2(e){
    if (e.keyCode == 13) {
        get_data_pasien();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
        return false;
    }
}

function get_history_medik_rj(id_pasien){
    $('#popup_histori_rj').click();
    $('#id_pasien2').val(id_pasien); 
    $('.cari_tgl').val(''); 
    $('#ord_tmp').val(''); 
    $('#isi_history_rj').html('');

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/get_history_medik_rj',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;

            var det_RJ = result['detail_RJ'];
            $('#nama_pasien_rj').html(result['ps']['NAMA']);

            // RAWAT JALAN
            if(det_RJ.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_RJ,function(i,RJ){                    
                    var ord  = RJ.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;

                    isine += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+RJ.TANGGAL+'</td>'+
                                '<td>'+RJ.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(RJ.ORD);     
                });
            } else {
                isine = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            } 

            $('#isi_history_rj').html(isine);             
            // END OF RAWAT JALAN
        }
    });
}

function get_data_pasien_ri(){
    // $('#popup_load').show();
    var keyword = $('#cari_pasien3').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_ri',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='11' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" onclick="detail_pasien('+result[i].ID_PASIEN+');">'+
                                   '<i class="fa fa-eye"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_pasien('+result[i].ID_PASIEN+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_pasien('+result[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    var rekam_medik = '<button type="button" class="btn btn-purple waves-effect waves-light btn-sm" onclick="get_history_medik_ri('+result[i].ID_PASIEN+');">'+
                                        '<i class="fa fa-file-text"></i>&nbsp;Detail'+
                                      '</button>';

                    result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";
                    result[i].TANGGAL_KELUAR = result[i].TANGGAL_KELUAR==null?"-":result[i].TANGGAL_KELUAR;

                    var warna = '';
                    if(result[i].STS_APPROVE_RM == '1'){
                        warna = 'class="kuning"';
                    }else{
                        warna = "";
                    }

                    $tr +=  '<tr '+warna+'>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].TANGGAL_MASUK+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].TANGGAL_KELUAR+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].KODE_KAMAR+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].KELAS+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].NOMOR_BED+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+rekam_medik+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien3 tbody').html($tr);
            $('#total_pasien3').html(parseInt(result.length));
            paging();
            $('#popup_load').fadeOut();
        }
    });

    $('#tombol_cari3').click(function(){
        get_data_pasien_ri();
        $('#tombol_reset3').show();
        $('#tombol_cari3').hide();
    });

    $('#tombol_reset3').click(function(){
        $('#cari_pasien3').val("");
        get_data_pasien_ri();
        $('#tombol_reset3').hide();
        $('#tombol_cari3').show();
    });
}

function onEnterText3(e){
    if (e.keyCode == 13) {
        get_data_pasien_ri();
        $('#tombol_reset3').show();
        $('#tombol_cari3').hide();
        return false;
    }
}

function get_history_medik_ri(id_pasien){
    $('#popup_histori_ri').click();
    $('#id_pasien2').val(id_pasien); 
    $('.cari_tgl_ri').val(''); 
    $('#ord_tmp').val(''); 
    $('#isi_history_ri').html('');

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/get_history_medik_ri',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            
            $('#nama_pasien_ri').html(result['ps']['NAMA']);

            var det_RI = result['detail_RI'];            
            var dataDetVisite_RI = result['dataDetVisite_RI'];
            var dataDetGizi_RI = result['dataDetGizi_RI'];
            var dataDetOksigen_RI = result['dataDetOksigen_RI'];
            var dataDetDiagnosa_RI = result['dataDetDiagnosa_RI'];
            var dataDetResep_RI = result['dataDetResep_RI'];
            
            // RAWAT INAP
            if(det_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>TINDAKAN</b> </td></tr>';
                no = 0;
                $.each(det_RI,function(i,RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+RI.TANGGAL+'</td>'+
                                    '<td>'+RI.KET+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetDiagnosa_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>DIAGNOSA</b> </td></tr>';
                no = 0;
                $.each(dataDetDiagnosa_RI,function(i,D_RI){                    
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+D_RI.TANGGAL+'</td>'+
                                    '<td>'+D_RI.DIAGNOSA+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetVisite_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>VISITE</b> </td></tr>';
                no = 0;
                $.each(dataDetVisite_RI,function(i,V_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+V_RI.TANGGAL+'</td>'+
                                    '<td>'+V_RI.NAMA_VISITE+' <br> <b>(Dokter : '+V_RI.NAMA_DOKTER+')</b> </td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetGizi_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>GIZI</b> </td></tr>';
                no = 0;
                $.each(dataDetGizi_RI,function(i,Gizi_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Gizi_RI.TANGGAL+'</td>'+
                                    '<td>'+Gizi_RI.NAMA_GIZI+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetOksigen_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OKSIGEN</b> </td></tr>';
                no = 0;
                $.each(dataDetOksigen_RI,function(i,Oks_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Oks_RI.TANGGAL+'</td>'+
                                    '<td>'+Oks_RI.KETERANGAN+' <br> <b>('+Oks_RI.JUMLAH+' Tabung)</b> </td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }             

            if(dataDetResep_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OBAT / RESEP</b> </td></tr>';
                no = 0;
                $.each(dataDetResep_RI,function(i,Resep_RI){
                    no++;
                    var obat = Resep_RI.NAMA_OBAT+' x '+Resep_RI.JUMLAH_BELI;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Resep_RI.TANGGAL+'</td>'+
                                    '<td>'+obat+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            $('#isi_history_ri').html(isine_ri); 
            // END OF RAWAT INAP

        }
    });
}

function detail_pasien(id){
    $('#popup_detail').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            row['JENIS_KELAMIN'] = row['JENIS_KELAMIN']=='L'?'Laki - Laki':'Perempuan';
            row['NAMA_ORTU'] = row['NAMA_ORTU']==null?'-':row['NAMA_ORTU'];
            row['TELEPON'] = row['TELEPON']==null?'-':row['TELEPON'];

            $tr = "<tr>"+
                    "<td><b>No. RM</b></td>"+
                    "<td>"+row['KODE_PASIEN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Nama Lengkap</b></td>"+
                    "<td>"+row['NAMA']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Jenis Kelamin</b></td>"+
                    "<td>"+row['JENIS_KELAMIN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Pendidikan</b></td>"+
                    "<td>"+row['PENDIDIKAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Agama</b></td>"+
                    "<td>"+row['AGAMA']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Alamat</b></td>"+
                    "<td>"+row['ALAMAT']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Nama Orang Tua</b></td>"+
                    "<td>"+row['NAMA_ORTU']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Telepon</b></td>"+
                    "<td>"+row['TELEPON']+"</td>"+
                  "</tr>";

            $('#tabel_detail tbody').html($tr);
            var umur = row['UMUR']+' Tahun '+row['UMUR_BULAN']+' Bulan';

            $tr2 = "<tr>"+
                    "<td><b>Tempat Lahir</b></td>"+
                    "<td>"+row['TEMPAT_LAHIR']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Tanggal Lahir</b></td>"+
                    "<td>"+formatTanggal(row['TANGGAL_LAHIR'])+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Umur</b></td>"+
                    "<td>"+umur+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Golongan Darah</b></td>"+
                    "<td>"+row['GOLONGAN_DARAH']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Kelurahan</b></td>"+
                    "<td>"+row['KELURAHAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Kecamatan</b></td>"+
                    "<td>"+row['KECAMATAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Provinsi</b></td>"+
                    "<td>"+row['PROVINSI']+"</td>"+
                  "</tr>";

            $('#tabel_detail2 tbody').html($tr2);
        }
    });
}

function ubah_pasien(id){
	$('#view_ubah').show();
	$('#view_data').hide();

	$.ajax({
		url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_pasien_id',
		data : {id:id},
		type : "POST",
		dataType : "json",
		success : function(row){
			$('#id_ubah').val(id);
			$('#kode_pasien').val(row['KODE_PASIEN']);
			$('#nama').val(row['NAMA']);
            
            if(row['JENIS_KELAMIN'] == "L"){
                $('#jenis_kelamin option[value="L"]').attr('selected','selected');
            }else{
                $('#jenis_kelamin option[value="P"]').attr('selected','selected');
            }

            if(row['PENDIDIKAN'] == "SD"){
                $('#pendidikan option[value="SD"]').attr('selected','selected');
            }else if(row['PENDIDIKAN'] == "SMP"){
                $('#pendidikan option[value="SMP"]').attr('selected','selected');
            }else if(row['PENDIDIKAN'] == "SMK/SMA"){
                $('#pendidikan option[value="SMK / SMA"]').attr('selected','selected');
            }else if(row['PENDIDIKAN'] == "Kuliah"){
                $('#pendidikan option[value="Kuliah').attr('selected','selected');
            }

            if(row['AGAMA'] == "Islam"){
                $('#agama option[value="Islam"]').attr('selected','selected');
            }else if(row['AGAMA'] == "Kristen Katolik"){
                $('#agama option[value="Kristen Katolik"]').attr('selected','selected');
            }else if(row['AGAMA'] == "Kristen Protestan"){
                $('#agama option[value="Kristen Protestan"]').attr('selected','selected');
            }else if(row['AGAMA'] == "Hindu"){
                $('#agama option[value="Hindu"]').attr('selected','selected');
            }else if(row['AGAMA'] == "Budha"){
                $('#agama option[value="Budha"]').attr('selected','selected');
            }else if(row['AGAMA'] == "Lain"){
                $('#agama option[value="Lain"]').attr('selected','selected');
            }

            $('#alamat').val(row['ALAMAT']);

            if(row['GOLONGAN_DARAH'] == "A"){
                $('#golongan_darah option[value="A"]').attr('selected','selected');
            }else if(row['GOLONGAN_DARAH'] == "B"){
                $('#golongan_darah option[value="B"]').attr('selected','selected');
            }else if(row['GOLONGAN_DARAH'] == "O"){
                $('#golongan_darah option[value="O"]').attr('selected','selected');
            }else if(row['GOLONGAN_DARAH'] == "AB"){
                $('#golongan_darah option[value="AB"]').attr('selected','selected');
            }

			$('#tempat_lahir').val(row['TEMPAT_LAHIR']);
			$('#tanggal_lahir').val(row['TANGGAL_LAHIR']);
			$('#umur').val(row['UMUR']);
            $('#kelurahan').val(row['KELURAHAN']);
            $('#kecamatan').val(row['KECAMATAN']);
            $('#kota').val(row['KOTA']);
            $('#kota_txt').val(row['KOTA']);
            $('#provinsi').val(row['PROVINSI']);
		}
	});

	$('#batal').click(function(){
		window.location = "<?php echo base_url(); ?>admum/admum_data_pasien_c";
	});
}

function hapus_pasien(id){
	$('#popup_konfirmasi').click();
    $('#id_pasien_kirim').val(id);
}

function data_provinsi(){
    var id_kota_kab = $('#kota').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/data_provinsi',
        data : {id_kota_kab:id_kota_kab},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_provinsi').val(row['ID_PROV']);
            $('#provinsi').val(row['PROV']);
        }
    });
}

function Search_tgl_RJ(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/get_history_medik_by_search_rj',
        data : {
            id_pasien:id_pasien,
            tgl : tgl
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            var det_RJ = result['detail_RJ'];
            // RAWAT JALAN
            if(det_RJ.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_RJ,function(i,RJ){                    
                    var ord  = RJ.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+RJ.TANGGAL+'</td>'+
                                '<td>'+RJ.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(RJ.ORD);     
                });
            } else {
                isine = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            } 
            $('#isi_history_rj').html(isine);             
            // END OF RAWAT JALAN 

            
        }
    });
}

function Search_tgl_RI(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_data_pasien_c/get_history_medik_by_search_ri',
        data : {
            id_pasien:id_pasien,
            tgl : tgl
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            
            var det_RI = result['detail_RI'];            
            var dataDetVisite_RI = result['dataDetVisite_RI'];
            var dataDetGizi_RI = result['dataDetGizi_RI'];
            var dataDetOksigen_RI = result['dataDetOksigen_RI'];
            var dataDetDiagnosa_RI = result['dataDetDiagnosa_RI'];
            var dataDetResep_RI = result['dataDetResep_RI'];
            
            // RAWAT INAP
            if(det_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>TINDAKAN</b> </td></tr>';
                no = 0;
                $.each(det_RI,function(i,RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+RI.TANGGAL+'</td>'+
                                    '<td>'+RI.KET+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetDiagnosa_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>DIAGNOSA</b> </td></tr>';
                no = 0;
                $.each(dataDetDiagnosa_RI,function(i,D_RI){                    
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+D_RI.TANGGAL+'</td>'+
                                    '<td>'+D_RI.DIAGNOSA+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetVisite_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>VISITE</b> </td></tr>';
                no = 0;
                $.each(dataDetVisite_RI,function(i,V_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+V_RI.TANGGAL+'</td>'+
                                    '<td>'+V_RI.NAMA_VISITE+' <br> <b>(Dokter : '+V_RI.NAMA_DOKTER+')</b> </td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetGizi_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>GIZI</b> </td></tr>';
                no = 0;
                $.each(dataDetGizi_RI,function(i,Gizi_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Gizi_RI.TANGGAL+'</td>'+
                                    '<td>'+Gizi_RI.NAMA_GIZI+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            if(dataDetOksigen_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OKSIGEN</b> </td></tr>';
                no = 0;
                $.each(dataDetOksigen_RI,function(i,Oks_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Oks_RI.TANGGAL+'</td>'+
                                    '<td>'+Oks_RI.KETERANGAN+' <br> <b>('+Oks_RI.JUMLAH+' Tabung)</b> </td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }             

            if(dataDetResep_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OBAT / RESEP</b> </td></tr>';
                no = 0;
                $.each(dataDetResep_RI,function(i,Resep_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Resep_RI.TANGGAL+'</td>'+
                                    '<td>'+Resep_RI.NAMA_OBAT+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }

            $('#isi_history_ri').html(isine_ri); 
        }
    });
}
</script>
<?php
    $sess_user = $this->session->userdata('masuk_rs');
    $id_pegawai = $sess_user['id'];
?>
<input type="hidden" id="ord_tmp" value="" />

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation" id="li_poli">
                    <a data-toggle="tab" role="tab" href="#poli1"><i class="fa fa-home"></i> Pasien Per Poli</a>
                </li>
                <li role="presentation" id="li_rj">
                    <a data-toggle="tab" role="tab" href="#rj1"><i class="fa fa-stethoscope"></i> Data Pasien Rawat Jalan</a>
                </li>
                <li role="presentation" id="li_ri">
                    <a data-toggle="tab" role="tab" href="#ri1"><i class="fa fa-hospital-o"></i> Data Pasien Rawat Inap</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="poli1" class="tab-pane fade in active" role="tabpanel">
                    <form class="form-horizontal" role="form" action="<?php echo $url_cetak; ?>" target="_blank" method="post">
                        <!-- <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5" id="btn_pasien_umum"><i class="fa fa-users"></i> <b>Pasien Umum</b></button>
                                <button type="button" class="btn btn-success waves-effect w-md waves-light m-b-5" id="btn_pasien_rj"><i class="fa fa-h-square"></i> <b>Pasien Rawat Jalan</b></button>
                                <button type="button" class="btn btn-warning waves-effect w-md waves-light m-b-5" id="btn_pasien_ri"><i class="fa fa-bed"></i> <b>Pasien Rawat Inap</b></button>
                                <button type="button" class="btn btn-danger waves-effect w-md waves-light m-b-5" id="btn_pasien_igd"><i class="fa fa-ambulance"></i> <b>Pasien IGD</b></button>
                            </div>
                        </div>
                        <hr> -->
                        <div class="form-group">
                            <label class="col-md-1 control-label" style="text-align:left; width: 5%;">Cetak</label>
                            <div class="col-md-2" style="width: 10%;">
                                <div class="radio radio-purple radio-inline">
                                    <input type="radio" name="pilihan_cetak" value="Excel" id="excel">
                                    <label for="excel"> Excel </label>
                                </div>
                                <div class="radio radio-purple radio-inline">
                                    <input type="radio" name="pilihan_cetak" value="PDF" id="pdf">
                                    <label for="pdf"> PDF </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn waves-effect waves-light btn-success">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cari_pasien" id="cari_pasien" placeholder="Cari poli..." value="" onkeypress="return onEnterText(event);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" style="margin-bottom: 0px;">
                                    <small><i>*ketikkan nama poli untuk pencarian data, lalu tekan Enter</i></small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabel_pasien" class="table table-hover table-bordered">
                                        <thead>
                                            <tr class="biru">
                                                <th style="color:#fff; text-align:center;">No</th>
                                                <th style="color:#fff; text-align:center;">Nama Poli</th>
                                                <th style="color:#fff; text-align:center;">Jumlah Pasien</th>
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
                                <h4 class="header-title">Total Pasien : <b id="total_pasien"></b></h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9">
                                
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

                <div id="rj1" class="tab-pane fade" role="tabpanel">
                    <form class="form-horizontal" role="form" action="" target="_blank" method="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cari_pasien2" id="cari_pasien2" placeholder="Cari pasien..." value="" onkeypress="return onEnterText2(event);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari2">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset2" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" style="margin-bottom: 0px;">
                                    <small><i>*ketikkan nama pasien untuk pencarian data, lalu tekan Enter</i></small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <div class="scroll-x">
                                        <table id="tabel_pasien2" class="table table-bordered">
                                            <thead>
                                                <tr class="hijau">
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">No</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Kode Pasien</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Pasien</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Jenis Kelamin</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Tanggal Lahir</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Umur</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Alamat</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Ayah</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Ibu</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Poli Tujuan</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Nota</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">RM</th>
                                                    <th style="color:#fff; text-align:center; white-space: nowrap;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10">
                                <div id="tablePaging2"> </div>
                            </div>
                            <div class="col-md-2">
                                <h4 class="header-title">Total Pasien : <b id="total_pasien2"></b></h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9">
                                
                            </div>
                            <label class="col-md-2 control-label">Jumlah Tampil</label>
                            <div class="col-md-1 pull-right">
                                <select class="form-control" id="jumlah_tampil2">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th style="text-align:center;">Warna</th>
                                            <th style="text-align:center;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="kuning">Kuning</td>
                                            <td>Status Pasien telah di Approve oleh Rekam Medik</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="ri1" class="tab-pane fade" role="tabpanel">
                    <form class="form-horizontal" role="form" action="" target="_blank" method="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cari_pasien3" id="cari_pasien3" placeholder="Cari pasien..." value="" onkeypress="return onEnterText3(event);">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari3">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset3" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </span>
                                </div>
                                <span class="help-block" style="margin-bottom: 0px;">
                                    <small><i>*ketikkan nama pasien untuk pencarian data, lalu tekan Enter</i></small>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabel_pasien3" class="table table-bordered">
                                        <thead>
                                            <tr class="merah">
                                                <th style="color:#fff; text-align:center;">No</th>
                                                <th style="color:#fff; text-align:center;">Kode Pasien</th>
                                                <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                                <th style="color:#fff; text-align:center;">Jenis Kelamin</th>
                                                <th style="color:#fff; text-align:center;">Tanggal MRS</th>
                                                <th style="color:#fff; text-align:center;">Tanggal KRS</th>
                                                <th style="color:#fff; text-align:center;">Nomor Kamar</th>
                                                <th style="color:#fff; text-align:center;">Kelas</th>
                                                <th style="color:#fff; text-align:center;">Nomor Bed</th>
                                                <th style="color:#fff; text-align:center;">RM</th>
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
                                <div id="tablePaging3"> </div>
                            </div>
                            <div class="col-md-2">
                                <h4 class="header-title">Total Pasien : <b id="total_pasien3"></b></h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9">
                                
                            </div>
                            <label class="col-md-2 control-label">Jumlah Tampil</label>
                            <div class="col-md-1 pull-right">
                                <select class="form-control" id="jumlah_tampil3">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="active">
                                            <th style="text-align:center;">Warna</th>
                                            <th style="text-align:center;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="kuning">Kuning</td>
                                            <td>Status Pasien telah di Approve oleh Rekam Medik</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    	</div>
    </div>

    <div class="col-lg-12" id="view_ubah">
    	<div class="card-box">
            <form class="form-horizontal" role="form" action="<?php echo $url_ubah; ?>" method="post">
                <h4 class="header-title m-t-0 m-b-30">Ubah Data Pasien</h4>
                <hr/>
                <div class="row">
            		<input type="hidden" name="id_ubah" id="id_ubah" value="">
            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kode Pasien</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kode_pasien" id="kode_pasien" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama" id="nama" value="" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required="required">
                                    <option value="L">Laki - Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pendidikan</label>
                            <div class="col-md-9">
                                <select class="form-control" name="pendidikan" id="pendidikan">
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMK/SMA">SMK / SMA</option>
                                    <option value="Kuliah">Kuliah</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Agama</label>
                            <div class="col-md-9">
                                <select class="form-control" name="agama" id="agama">
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Katolik">Kristen Katolik</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Budha">Budha</option>
                                    <option value="Lain">Lain - Lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="alamat" id="alamat" required="required"></textarea>
                            </div>
                        </div>
            		</div>

            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Golongan Darah</label>
                            <div class="col-md-9">
                                <select class="form-control" name="golongan_darah" id="golongan_darah">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="O">O</option>
                                    <option value="AB">AB</option>
                                </select>
                            </div>
                        </div>
            			<div class="form-group">
                            <label class="col-md-3 control-label">Tempat Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="" data-mask="99-99-9999" placeholder="__-__-____" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Umur</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="umur" id="umur" value="" required="required">
                                    <span class="input-group-addon">Tahun</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kelurahan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kelurahan" id="kelurahan" value="" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kecamatan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kecamatan" id="kecamatan" value="" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kabupaten / Kota</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="kota_txt" value="" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <div class="checkbox checkbox-primary">
                                    <input name="ubah_kota" id="checkbox2" type="checkbox" value="1">
                                    <label for="checkbox2">Ubah</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="view_kota">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="kota" id="kota" onchange="data_provinsi();">
                                <?php
                                    $data_kota = $this->model->kota_kab();
                                    foreach ($data_kota as $val_kota) {
                                ?>
                                    <option value="<?php echo $val_kota->KOTA; ?>"><?php echo $val_kota->KOTA; ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-prov">
                            <label class="col-md-3 control-label">Provinsi</label>
                            <div class="col-md-9">
                                <input type="hidden" name="id_provinsi" id="id_provinsi" value="">
                                <input type="text" class="form-control" name="provinsi" id="provinsi" value="" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group" id="view_prov_ubah">
                            <label class="col-md-3 control-label">Provinsi</label>
                            <div class="col-md-9">
                                <input type="hidden" name="id_provinsi_ubah" id="id_provinsi_ubah" value="">
                                <input type="text" class="form-control" name="provinsi_ubah" id="provinsi_ubah" value="" readonly="readonly">
                            </div>
                        </div>
            		</div>
                </div>
                <hr>
                <center>
                    <button type="submit" class="btn btn-success waves-effect w-md waves-light m-b-5"><i class="fa fa-save"></i> <span>Simpan</span></button>
                    <button type="button" class="btn btn-danger waves-effect w-md waves-light m-b-5" id="batal"><i class="fa fa-times"></i> <span>Batal</span></button>
                </center>
            </form>
    	</div>
    </div>
</div>

<button id="popup_konfirmasi" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Permintaan Hapus</h4>
            </div>
            <div class="modal-body">
                <p>Sebelum melakukan <b>Hapus Data</b>, Anda harus mengirim permintaan Hapus kepada <b>Admin</b>.</p>
                <p>Ingin kirim permintaan <b>Hapus kepada Admin?</b> Tekan <b>Ya</b> jika ingin dikirim.</p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo base_url(); ?>admum/admum_data_pasien_c/kirim_permintaan" method="post">
                    <input type="hidden" name="id_pegawai" id="id_pegawai" value="<?php echo $id_pegawai; ?>">
                    <input type="hidden" name="id_pasien_kirim" id="id_pasien_kirim" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Ya</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" id="popup_detail" data-toggle="modal" data-target="#full-width-modal-det" style="display:none;">Full width Modal</button>
<div id="full-width-modal-det" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="full-width-modalLabel">Detail Pasien</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-6">
                            <table id="tabel_detail" class="table table-bordered">
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6">
                            <table id="tabel_detail2" class="table table-bordered">
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  MODAL HISTORY RJ -->
<button type="button" id="popup_histori_rj" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalRJ" style="display: none;"></button>
<div class="modal fade bs-example-modal-lg" id="myModalRJ" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">
                    History Rekam Medik <button class="btn btn-danger waves-effect w-md waves-light m-l-10" type="button" id="nama_pasien_rj" style="cursor: default;"></button>
                </h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_pasien2"/>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box card-tabs" style="padding:0;">
                            <ul class="nav nav-tabs coba">
                                <li role="presentation" class="active">
                                    <a style="background:#f4f8fb;" href="#rj" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-stethoscope"></i> Rawat Jalan</a>
                                </li>
                            </ul> 
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="rj">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" type="text" value="" data-mask="99-99-9999" onkeyup="Search_tgl_RJ(this.value);" placeholder="ex: <?php echo date('d-m-Y'); ?>">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr class="hijau">
                                                <th style="text-align:center; color: #fff;">#</th>
                                                <th style="text-align:center; color: #fff;">Tanggal</th>
                                                <th style="text-align:center; color: #fff;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_rj">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>

<!--  MODAL HISTORY RI -->
<button type="button" id="popup_histori_ri" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalRI" style="display: none;"></button>
<div class="modal fade bs-example-modal-lg" id="myModalRI" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">
                    History Rekam Medik <button class="btn btn-success waves-effect w-md waves-light m-l-10" type="button" id="nama_pasien_ri" style="cursor: default;"></button>
                </h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_pasien2"/>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box card-tabs" style="padding:0;">
                            <ul class="nav nav-tabs coba">
                                <li role="presentation" class="active">
                                    <a style="background:#f4f8fb;" href="#ri" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-hospital-o"></i> Rawat Inap</a>
                                </li>
                            </ul> 
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="ri">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl_ri" type="text" value="" onkeyup="Search_tgl_RI(this.value);" placeholder="ex: <?php echo date('d-m-Y'); ?>">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr class="merah">
                                                <th style="text-align:center; color: #fff;">#</th>
                                                <th style="text-align:center; color: #fff;">Tanggal</th>
                                                <th style="text-align:center; color: #fff;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_ri">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary" id="popup_detail_poli" data-toggle="modal" data-target="#full-width-modal-det-poli" style="display:none;">Full width Modal</button>
<div id="full-width-modal-det-poli" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="full-width-modalLabel">Detail Pasien <b id="nama_poli_judul"></b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="popup_tabel">
                                <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                            </div>
                            <div class="table-responsive">
                                <table id="tabel_detail_poli" class="table table-bordered">
                                    <thead>
                                        <tr class="biru_popup">
                                            <th style="color: #fff; text-align: center;">No</th>
                                            <th style="color: #fff; text-align: center;">No. RM</th>
                                            <th style="color: #fff; text-align: center;">Nama Pasien</th>
                                            <th style="color: #fff; text-align: center;">Jenis Kelamin</th>
                                            <th style="color: #fff; text-align: center;">Tanggal Lahir</th>
                                            <th style="color: #fff; text-align: center;">Umur</th>
                                            <th style="color: #fff; text-align: center;">Nama Ayah</th>
                                            <th style="color: #fff; text-align: center;">Nama Ibu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->