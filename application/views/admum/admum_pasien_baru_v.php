<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_jk2, #view_pendidikan2, #view_agama2, #view_goldar2, #view_tgl_lahir2, #view_kab_kota2, #view_prov2{
    display: none;
}

.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
} 
</style>

<script type="text/javascript">
var ajax = "";

$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php } ?>

    // get_kode_pasien();
    
    $('#baru').click(function(){
        var cek = $('#baru').is(":checked");
        if(cek == true){
            get_kode_pasien();
            $('#nama').focus();
            $('#btn_history').hide();
            $('.btn_pasien').attr('disabled','disabled');

            $('#id_pasien').val("");
            $('#nama').val("");
            $('#alamat').val("");
            $('#tempat_lahir').val("");
            $('#tanggal_lahir').val("");
            $('#umur').val("");
            $('#kelurahan').val("");
            $('#kecamatan').val("");

            $('#nama').removeAttr('readonly');
            $('#alamat').removeAttr('readonly');
            $('#tempat_lahir').removeAttr('readonly');
            $('#tanggal_lahir').removeAttr('readonly');
            $('#umur').removeAttr('readonly');
            $('#kelurahan').removeAttr('readonly');
            $('#kecamatan').removeAttr('readonly');

            $('#view_jk2').hide();
            $('#view_pendidikan2').hide();
            $('#view_agama2').hide();
            $('#view_goldar2').hide();
            $('#view_tgl_lahir2').hide();
            $('#view_kab_kota2').hide();

            $('#view_jk1').show();
            $('#view_pendidikan1').show();
            $('#view_agama1').show();
            $('#view_goldar1').show();
            $('#view_tgl_lahir1').show();
            $('#view_kab_kota1').show();
        }else{
            $('#btn_history').show();
            $('.btn_pasien').removeAttr('disabled');

            $('#kode_pasien').val("");
            $('#nama').val("");
            $('#alamat').val("");
            $('#tempat_lahir').val("");
            $('#tanggal_lahir').val("");
            $('#umur').val("");
            $('#kelurahan').val("");
            $('#kecamatan').val("");

            $('#jenis_kelamin_txt').val("");
            $('#jenis_kelamin_show').val("");
            $('#pendidikan_txt').val("");
            $('#agama_txt').val("");
            $('#goldar_txt').val("");
            $('#tanggal_txt').val("");
            $('#kecamatan_txt').val("");

            $('#nama').attr('readonly','readonly');
            $('#alamat').attr('readonly','readonly');
            $('#tempat_lahir').attr('readonly','readonly');
            $('#tanggal_lahir').attr('readonly','readonly');
            $('#umur').attr('readonly','readonly');
            $('#kelurahan').attr('readonly','readonly');
            $('#kecamatan').attr('readonly','readonly');

            $('#view_jk2').show();
            $('#view_pendidikan2').show();
            $('#view_agama2').show();
            $('#view_goldar2').show();
            $('#view_tgl_lahir2').show();
            $('#view_kab_kota2').show();

            $('#view_jk1').hide();
            $('#view_pendidikan1').hide();
            $('#view_agama1').hide();
            $('#view_goldar1').hide();
            $('#view_tgl_lahir1').hide();
            $('#view_kab_kota1').hide();
        }
    });

    $('#batal').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_pasien_baru_c";
    });

    $(".num_only").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('.btn_pasien').click(function(){
        $('#popup_pasien').click();
        load_data_pasien();
    });
});

function get_kode_pasien(){
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/kode_pasien',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_pasien').val(kode);
        }
    });
}

function data_provinsi(){
    var id_kota_kab = $('#kota').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/data_provinsi',
        data : {id_kota_kab:id_kota_kab},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_provinsi').val(row['ID_PROV']);
            $('#provinsi').val(row['PROV']);
        }
    });
}

function load_data_pasien(){
    var keyword = $('#cari_pasien').val();
    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/load_data_pasien',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td style='text-align:center;' colspan='5'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++; 

                    var jk = "";
                    if(result[i].JENIS_KELAMIN == "L"){
                        jk = "Laki - Laki";
                    }else{
                        jk = "Perempuan";
                    }

                    $tr += "<tr style='cursor:pointer;' onclick='klik_pasien("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].KODE_PASIEN+"</td>"+
                                "<td>"+result[i].NAMA+"</td>"+
                                "<td style='text-align:center;'>"+jk+"</td>"+
                                "<td style='text-align:center;'>"+result[i].UMUR+" Tahun</td>"+
                            "</tr>";
                }
            }

            $('#tabel_pasien tbody').html($tr);
        }
    });

    $('#cari_pasien').off('keyup').keyup(function(){
        load_data_pasien();
    });
}

function klik_pasien(id){
    $('#tutup_pasien').click();
    $('#id_pasien2').val(id);
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/klik_pasien',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#view_jk1').hide();
            $('#view_pendidikan1').hide();
            $('#view_agama1').hide();
            $('#view_goldar1').hide();
            $('#view_tgl_lahir1').hide();
            $('#view_kab_kota1').hide();
            $('#view_prov1').hide();

            $('#view_jk2').show();
            $('#view_pendidikan2').show();
            $('#view_agama2').show();
            $('#view_goldar2').show();
            $('#view_tgl_lahir2').show();
            $('#view_kab_kota2').show();
            $('#view_prov2').show();

            $('#kode_pasien').val(row['KODE_PASIEN']);
            $('#nama').val(row['NAMA']);
            row['PENDIDIKAN'] = (row['PENDIDIKAN']==null?"-":row['PENDIDIKAN']);
            row['PROVINSI'] = (row['PROVINSI']==null?"-":row['PROVINSI']);

            var jk = "";
            if(row['JENIS_KELAMIN'] == 'L'){
                jk = "Laki - Laki";
            }else{
                jk = "Perempuan";
            }
            $('#jenis_kelamin_txt').val(row['JENIS_KELAMIN']);
            $('#jenis_kelamin_show').val(jk);
            $('#pendidikan_txt').val(row['PENDIDIKAN']);
            $('#agama_txt').val(row['AGAMA']);
            $('#alamat').val(row['ALAMAT']);
            $('#goldar_txt').val(row['GOLONGAN_DARAH']);
            $('#tempat_lahir').val(row['TEMPAT_LAHIR']);
            $('#tanggal_txt').val(row['TANGGAL_LAHIR']);
            $('#umur').val(row['UMUR']);
            $('#kelurahan').val(row['KELURAHAN']);
            $('#kecamatan').val(row['KECAMATAN']);
            $('#kota_txt').val(row['KOTA']);
            $('#provinsi_txt').val(row['PROVINSI']);

            $('#nama').attr('readonly','readonly');
            $('#alamat').attr('readonly','readonly');
            $('#tempat_lahir').attr('readonly','readonly');
            $('#umur').attr('readonly','readonly');
            $('#kelurahan').attr('readonly','readonly');
            $('#kecamatan').attr('readonly','readonly');
        }
    });
}

function get_history_medik(){
    $('.cari_tgl').val(''); 
    $('#ord_tmp').val(''); 
    $('#isi_history_rj').html(''); 
    $('#isi_history_igd').html(''); 
    $('#isi_history_ri').html(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/get_history_medik',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            var det_RJ = result['detail_RJ'];

            var det_IGD = result['detail_IGD'];

            var det_RI = result['detail_RI'];            
            var dataDetVisite_RI = result['dataDetVisite_RI'];
            var dataDetGizi_RI = result['dataDetGizi_RI'];
            var dataDetOksigen_RI = result['dataDetOksigen_RI'];
            var dataDetDiagnosa_RI = result['dataDetDiagnosa_RI'];
            var dataDetResep_RI = result['dataDetResep_RI'];

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

            // IGD
            if(det_IGD.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_IGD,function(i,IGD){                    
                    var ord  = IGD.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine_igd += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine_igd += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+IGD.TANGGAL+'</td>'+
                                '<td>'+IGD.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(IGD.ORD);     
                });
            } else {
                isine_igd = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }
            $('#isi_history_igd').html(isine_igd); 
            // END OF IGD

            // RAWAT INAP
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
            }

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
            } 

            $('#isi_history_ri').html(isine_ri); 
            // END OF RAWAT INAP
        }
    });

}

function Search_tgl_RJ(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/get_history_medik_by_search_rj',
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

function Search_tgl_IGD(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/get_history_medik_by_search_igd',
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
            var det_IGD = result['detail_IGD'];
            if(det_IGD.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_IGD,function(i,IGD){                    
                    var ord  = IGD.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine_igd += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine_igd += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+IGD.TANGGAL+'</td>'+
                                '<td>'+IGD.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(IGD.ORD);     
                });
            } else {
                isine_igd = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }
            $('#isi_history_igd').html(isine_igd);             
        }
    });
}

function Search_tgl_RI(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_baru_c/get_history_medik_by_search_ri',
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
            }

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
            } 

            $('#isi_history_ri').html(isine_ri); 
            // END OF RAWAT INAP

        }
    });
}
</script>
<input type="hidden" id="ord_tmp" value="" />
<div class="col-sm-12">
    <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" id="form_pasien_baru">
        <div class="card-box">
            <div class="row">
        		<div class="col-lg-6">
                    <div class="form-group">
                        <label class="col-md-3 control-label">No. RM</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="kode_pasien" id="kode_pasien" value="" readonly>
                        </div>
                        <div class="col-md-3">
                            <div class="checkbox checkbox-primary">
                                <input id="baru" type="checkbox" name="baru" value="1">
                                <label for="baru">
                                    Baru
                                </label>
                            </div>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Nama Lengkap</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="nama" id="nama" value="" required="required">
                                <input type="hidden" class="form-control" name="id_pasien2" id="id_pasien2" value="" readonly>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn_pasien"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="view_jk1">
                        <label class="col-md-3 control-label">Jenis Kelamin</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="jenis_kelamin">
                                <option value="L">Laki - Laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="view_jk2">
                        <label class="col-md-3 control-label">Jenis Kelamin</label>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control" name="jenis_kelamin_txt" id="jenis_kelamin_txt" value="">
                            <input type="text" class="form-control" id="jenis_kelamin_show" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group" id="view_pendidikan1">
                        <label class="col-md-3 control-label">Pendidikan</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="pendidikan">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMK/SMA">SMK / SMA</option>
                                <option value="Kuliah">Kuliah</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="view_pendidikan2">
                        <label class="col-md-3 control-label">Pendidikan</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="pendidikan_txt" id="pendidikan_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group" id="view_agama1">
                        <label class="col-md-3 control-label">Agama</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="agama" id="agama">
                                <option value="Islam">Islam</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="view_agama2">
                        <label class="col-md-3 control-label">Agama</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="agama_txt" id="agama_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Alamat</label>
                        <div class="col-md-9">
                            <textarea rows="5" class="form-control" name="alamat" id="alamat" required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-9">
                            <button type="button" id="btn_history" onclick="get_history_medik();" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">
                               <i class="fa fa-history"></i> History Rekam Medik
                            </button>
                        </div>
                    </div>
        		</div>

        		<div class="col-lg-6">
                    <div class="form-group" id="view_goldar1">
                        <label class="col-md-3 control-label">Golongan Darah</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="golongan_darah">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="O">O</option>
                                <option value="AB">AB</option>
                                <option value="Tidak Tahu">-</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="view_goldar2">
                        <label class="col-md-3 control-label">Golongan Darah</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="goldar_txt" id="goldar_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Tempat Lahir</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="" required="required">
                        </div>
                    </div>
                    <div class="form-group" id="view_tgl_lahir1">
                        <label class="col-md-3 control-label">Tanggal Lahir</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker-here" name="tanggal_lahir" id="tanggal_lahir" value="" data-language="en" data-date-format="dd-mm-yyyy" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="view_tgl_lahir2">
                        <label class="col-md-3 control-label">Tanggal Lahir</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="tanggal_txt" id="tanggal_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Umur</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control num_only" name="umur" id="umur" value="" required="required" maxlength="3">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-warning" style="cursor:default;">Tahun</button>
                                </span>
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
                    <div class="form-group" id="view_kab_kota1">
                        <label class="col-md-3 control-label">Kabupaten / Kota</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="kota" id="kota" onchange="data_provinsi();">
                            <?php
                            $dt = $this->model->default_lokasi();
                            $data_kota = $this->model->kota_kab();
                            $id_prov_default = "";
                            $prov_default = "";

                            if($dt == null || $dt == ""){

                            }else{
                                foreach ($dt as $value) {
                                    foreach ($data_kota as $val_kota) {
                                        $selected = "";
                                        if(($val_kota->lokasi_propinsi == $value->ID_PROVINSI) && ($val_kota->lokasi_kabupatenkota == $value->ID_KOTA_KAB)){
                                            $selected = "selected='selected'";
                                            $id_prov_default = $val_kota->lokasi_propinsi;
                                            $prov_default = $val_kota->PROV;
                                        }else{
                                            $selected = "";
                                        }
                            ?>
                                <option value="<?php echo $val_kota->KOTA; ?>" <?php echo $selected;?> ><?php echo $val_kota->KOTA; ?></option>
                            <?php
                                    }
                                }
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="view_kab_kota2">
                        <label class="col-md-3 control-label">Kabupaten / Kota</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="kota_txt" id="kota_txt" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group" id="view_prov1">
                        <label class="col-md-3 control-label">Provinsi</label>
                        <div class="col-md-9">
                            <input type="hidden" name="id_provinsi" id="id_provinsi" value="<?php echo $id_prov_default; ?>">
                            <input type="text" class="form-control" name="provinsi" id="provinsi" value="<?php echo $prov_default; ?>" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group" id="view_prov2">
                        <label class="col-md-3 control-label">Provinsi</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="provinsi_txt" id="provinsi_txt" value="" readonly>
                        </div>
                    </div>
        		</div>
            </div>
            <hr>
            <center>
                <button type="submit" class="btn btn-success m-b-5"><i class="fa fa-check"></i> <span><b>Daftar</b></span></button>
                <button type="button" class="btn btn-danger m-b-5" id="batal"><i class="fa fa-times"></i> <span><b>Batal</b></span></button>
            </center>
        </div>
    </form>
</div>

<!-- //LOAD PASIEN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_pasien" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Pasien</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_pasien" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_pasien">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Pasien</th>
                                    <th style="text-align:center; color: #fff;">Nama Pasien</th>
                                    <th style="text-align:center; color: #fff;">Jenis Kelamin</th>
                                    <th style="text-align:center; color: #fff;">Umur</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pasien">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!--  MODAL HISTORY REKAM MEDIK -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">History Rekam Medik</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box card-tabs" style="padding:0;">

                            <ul class="nav nav-tabs coba">
                                <li role="presentation" class="active">
                                    <a style="background:#f4f8fb;" href="#rj" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-thumb-tack"></i> Rawat Jalan</a>
                                </li> 
                                <li role="presentation" class="">
                                    <a style="background:#f4f8fb;" href="#ri" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> Rawat Inap </a>
                                </li>                       
                                <li role="presentation" class="">
                                    <a style="background:#f4f8fb;" href="#igd" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> IGD </a>
                                </li>
                            </ul> 
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="rj">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_RJ(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_rj">

                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="ri">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_RI(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_ri">

                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="igd">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_IGD(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_igd">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->