<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}

#view_asuransi,
#view_dokter{
    display: none;
}
</style>

<script type="text/javascript">
var ajax = "";

$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php } ?>

    // get_kode_pasien();
    
    // $('#baru').click(function(){
    //     var cek = $('#baru').is(":checked");
    //     if(cek == true){
    //         get_kode_pasien();
    //         $('#btn_history').hide();
    //         $('.btn_pasien').attr('disabled','disabled');
    //         $('#nama').removeAttr('readonly');
    //         $('#alamat').removeAttr('readonly');
    //         $('#tempat_lahir').removeAttr('readonly');
    //         $('#umur').removeAttr('readonly');
    //         $('#kelurahan').removeAttr('readonly');
    //         $('#nama').focus();
            
    //         $('#id_pasien').val("");
    //         $('#nama').val("");
    //         $('#alamat').val("");
    //         $('#tempat_lahir').val("");
    //         $('#tanggal_lahir').val("");
    //         $('#umur').val("");
    //         $('#kelurahan').val("");
    //         $('#kecamatan').val("");
    //         var now = "<?php echo date('d-m-Y'); ?>";
    //         $('#tanggal').val(now);

    //         $('#view_jk1').show();
    //         $('#view_pendidikan1').show();
    //         $('#view_agama1').show();
    //         $('#view_goldar1').show();
    //         $('#view_tgl_lahir1').show();
    //         $('#view_kec1').show();
    //         $('#view_kab_kota1').show();
    //         $('#view_prov1').show();

    //         $('#view_jk2').hide();
    //         $('#view_pendidikan2').hide();
    //         $('#view_agama2').hide();
    //         $('#view_goldar2').hide();
    //         $('#view_tgl_lahir2').hide();
    //         $('#view_kec2').hide();
    //         $('#view_kab_kota2').hide();
    //         $('#view_prov2').hide();
    //     }else{
    //         $('#btn_history').show();
    //         $('.btn_pasien').removeAttr('disabled');
    //         $('#nama').attr('readonly','readonly');
    //         $('#alamat').attr('readonly','readonly');
    //         $('#tempat_lahir').attr('readonly','readonly');
    //         $('#umur').attr('readonly','readonly');
    //         $('#kelurahan').attr('readonly','readonly');

    //         $('#kode_pasien').val("");
    //         $('#nama').val("");
    //         $('#alamat').val("");
    //         $('#tempat_lahir').val("");
    //         $('#tanggal_lahir').val("");
    //         $('#umur').val("");
    //         $('#kelurahan').val("");
    //         $('#kecamatan').val("");
    //         var now = "<?php echo date('d-m-Y'); ?>";
    //         $('#tanggal').val(now);

    //         $('#jenis_kelamin_txt').val("");
    //         $('#jenis_kelamin_show').val("");
    //         $('#pendidikan_txt').val("");
    //         $('#agama_txt').val("");
    //         $('#goldar_txt').val("");
    //         $('#tanggal_txt').val("");
    //         $('#kecamatan_txt').val("");

    //         $('#view_jk1').hide();
    //         $('#view_pendidikan1').hide();
    //         $('#view_agama1').hide();
    //         $('#view_goldar1').hide();
    //         $('#view_tgl_lahir1').hide();
    //         $('#view_kec1').hide();
    //         $('#view_kab_kota1').hide();
    //         $('#view_prov1').hide();

    //         $('#view_jk2').show();
    //         $('#view_pendidikan2').show();
    //         $('#view_agama2').show();
    //         $('#view_goldar2').show();
    //         $('#view_tgl_lahir2').show();
    //         $('#view_kec2').show();
    //         $('#view_kab_kota2').show();
    //         $('#view_prov2').show();
    //     }
    // });

    $('#batal').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_pasien_ri_c";
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

    $("input[name='sistem_bayar']").click(function(){
        var rd = $("input[name='sistem_bayar']:checked").val();
        if(rd == '1'){
            $('#view_asuransi').hide();
        }else{
            $('#view_asuransi').show();
        }
    });

    $("input[name='rujukan_dari']").click(function(){
        var rd = $("input[name='rujukan_dari']:checked").val();
        if(rd == 'APS'){
            $('#view_dokter').hide();
        }else if(rd == 'Dokter'){
            $('#view_dokter').show();
        }else if(rd == 'Bidan'){
            $('#view_dokter').hide();
        }else if(rd == 'Lainnya'){
            $('#view_dokter').hide();
        }
    });

    $('.btn_pasien').click(function(){
        $('#popup_pasien').click();
        load_data_pasien();
    });

    $('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

    $('.btn_dokter').click(function(){
        $('#popup_dokter').click();
        load_dokter();
    });

    $('.btn_asuransi').click(function(){
        $('#popup_asuransi').click();
        load_asuransi();
    });
});

function get_kode_pasien(){
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/kode_pasien',
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
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/data_provinsi',
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
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/load_data_pasien',
        data : {keyword:keyword},
        type : "GET",
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

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/klik_pasien',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            // $('#view_jk1').hide();
            // $('#view_pendidikan1').hide(); 
            // $('#view_agama1').hide();
            // $('#view_goldar1').hide();
            // $('#view_tgl_lahir1').hide();
            // $('#view_kec1').hide();
            // $('#view_kab_kota1').hide();
            // $('#view_prov1').hide();

            // $('#view_jk2').show();
            // $('#view_pendidikan2').show();
            // $('#view_agama2').show();
            // $('#view_goldar2').show();
            // $('#view_tgl_lahir2').show();
            // $('#view_kec2').show();
            // $('#view_kab_kota2').show();
            // $('#view_prov2').show();

            $('#id_pasien').val(id);
            $('#id_pasien2').val(id);
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
            $('#umur_bulan').val(row['UMUR_BULAN']);
            $('#kelurahan').val(row['KELURAHAN']);
            $('#kecamatan_txt').val(row['KECAMATAN']);
            $('#kota_txt').val(row['KOTA']);
            $('#provinsi_txt').val(row['PROVINSI']);

            $('#nama').attr('readonly','readonly');
            $('#alamat').attr('readonly','readonly');
            $('#tempat_lahir').attr('readonly','readonly');
            $('#umur').attr('readonly','readonly');
            $('#kelurahan').attr('readonly','readonly');

            $('#btn_simpan').removeAttr('disabled');
        }
    });
}

function load_ruangan(){
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/load_kamar',
        data : {kelas:kelas,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='5' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].VISITE_DOKTER+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar tbody').html($tr);
        }
    });

    $('#cari_kamar').off('keyup').keyup(function(){
        load_ruangan();
    });
}

function klik_ruangan(id){
    $('#tutup_kamar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/klik_kamar',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['KELAS']+' - '+row['VISITE_DOKTER'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(NumberToMoney(row['BIAYA']));
        }
    });
}

function load_bed(){
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/load_bed',
        data : {id_kamar:id_kamar,keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                for(var i=0; i<result.length; i++){
                    var stt = "";
                    var warna = "";
                    var disabled = "";
                    var diklik = "";

                    if(result[i].STATUS_PAKAI == 0){
                        stt = '<span class="label label-success">KOSONG</span>';
                        warna = "";
                        disabled = "";
                        diklik = "style='cursor:pointer;' onclick='klik_bed("+result[i].ID+");'";
                    }else{
                        stt = '<span class="label label-danger">TERPAKAI</span>';
                        warna = "terpakai";
                        disabled = "disabled='disabled'";
                        diklik = "";
                    }

                    $tr += "<tr class='"+warna+"' "+disabled+" "+diklik+">"+
                                "<td style='text-align:center;'>"+result[i].NO+"</td>"+
                                "<td>"+result[i].NOMOR_BED+"</td>"+
                                "<td style='text-align:center;'>"+stt+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_bed tbody').html($tr);
        }
    });

    $('#cari_bed').off('keyup').keyup(function(){
        load_bed();
    });
}

function klik_bed(id){
    $('#tutup_bed').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}

function load_dokter(){
    var keyword = $('#cari_dokter').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/load_dokter',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_dokter("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].NAMA+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_dokter tbody').html($tr);
        }
    });

    $('#cari_dokter').off('keyup').keyup(function(){
        load_dokter();
    });
}

function klik_dokter(id){
    $('#tutup_dokter').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/klik_dokter',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_dokter').val(id);
            $('#nama_dokter').val(row['NAMA']);
        }
    });
}

function load_asuransi(){
    var keyword = $('#cari_asuransi').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/load_asuransi',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='2' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_asuransi("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].NAMA_ASURANSI+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_asuransi tbody').html($tr);
        }
    });

    $('#cari_asuransi').off('keyup').keyup(function(){
        load_asuransi();
    });
}

function klik_asuransi(id){
    $('#tutup_asuransi').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_ri_c/klik_asuransi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_kerjasama').val(id);
            $('#nama_kerjasama').val(row['NAMA_ASURANSI']);
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

<div class="row">
    <div class="col-sm-12">
        <form class="form-horizontal" role="form" action="<?php echo $url_simpan; ?>" method="post" id="form_pasien_baru">
            <input type="hidden" name="id_pasien" id="id_pasien" value="">
            <input type="hidden" name="id_pasien2" id="id_pasien2" value="">
            <div class="card-box">
                <div class="row">
            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama" id="nama" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn_pasien"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. RM</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="kode_pasien" id="kode_pasien" value="" readonly>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="checkbox checkbox-primary">
                                    <input id="baru" type="checkbox" name="baru" value="1">
                                    <label for="baru">
                                        Baru
                                    </label>
                                </div>
                            </div> -->
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <input type="hidden" class="form-control" name="jenis_kelamin_txt" id="jenis_kelamin_txt" value="">
                                <input type="text" class="form-control" id="jenis_kelamin_show" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pendidikan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="pendidikan_txt" id="pendidikan_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Agama</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="agama_txt" id="agama_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="alamat" id="alamat" readonly></textarea>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <button type="button" id="btn_history" onclick="get_history_medik();" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".bs-example-modal-lg">
                                   <i class="fa fa-history"></i> History Rekam Medik
                                </button>
                            </div>
                        </div> -->
            		</div>

            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Golongan Darah</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="goldar_txt" id="goldar_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tempat Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tanggal_txt" id="tanggal_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Umur</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control num_only" name="umur" id="umur" value="" maxlength="3" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Tahun</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control num_only" name="umur_bulan" id="umur_bulan" value="" maxlength="3" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Bulan</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kelurahan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kelurahan" id="kelurahan" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kecamatan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kecamatan_txt" id="kecamatan_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kabupaten / Kota</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kota_txt" id="kota_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Provinsi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="provinsi_txt" id="provinsi_txt" value="" readonly>
                            </div>
                        </div>
            		</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-bed"></i>&nbsp;<strong>Kelas Kamar</strong>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama P. Jawab</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_pjawab" id="nama_pjawab" value="" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telepon</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control num_only" name="telepon" id="telepon" value="" maxlength="13" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <div class="radio radio-danger radio-inline">
                                    <input type="radio" id="inlineRadio1" value="1" name="sistem_bayar">
                                    <label for="inlineRadio1"> Umum </label>
                                </div>
                                <div class="radio radio-danger radio-inline">
                                    <input type="radio" id="inlineRadio2" value="2" name="sistem_bayar">
                                    <label for="inlineRadio2"> Asuransi </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Rujukan Dari</label>
                            <div class="col-md-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio3" value="APS" name="rujukan_dari">
                                    <label for="inlineRadio3"> APS </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio4" value="Dokter" name="rujukan_dari">
                                    <label for="inlineRadio4"> Dokter </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio5" value="Bidan" name="rujukan_dari">
                                    <label for="inlineRadio5"> Bidan </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio6" value="Lainnya" name="rujukan_dari">
                                    <label for="inlineRadio6"> Lainnya </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="view_dokter">
                            <label class="col-md-3 control-label">Nama Dokter</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="hidden" name="id_dokter" id="id_dokter" value="">
                                    <input type="text" class="form-control" id="nama_dokter" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning btn_dokter"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kelas</label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="kelas_kamar" id="kelas_kamar">
                                    <option value="SVIP">SVIP</option>
                                    <option value="VIP">VIP</option>
                                    <option value="1A">I A</option>
                                    <option value="1B">I B</option>
                                    <option value="2A">II A</option>
                                    <option value="2B">II B</option>
                                    <option value="3">III</option>
                                    <option value="Neo">Ruang Neo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kamar</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="id_ruangan" id="id_ruangan" value="">
                                    <input type="text" class="form-control" id="ruang_tujuan" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger btn_ruangan"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <label class="col-md-1 control-label">No Bed</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="id_bed" id="id_bed" value="">
                                    <input type="text" class="form-control" id="bed" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn_bed"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Biaya Kamar</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="biaya" id="biaya" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" id="view_asuransi">
                        <!-- <div class="form-group">
                            <label class="col-md-3 control-label">Asal Rujukan</label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="asal_rujukan">
                                    <?php 
                                        // $dt = $this->db->query("SELECT * FROM master_rujukan")->result();
                                        // foreach ($dt as $key => $value) {  
                                    ?>
                                    <option value="<?php //echo $value->NAMA_SUPPLIER;?>"><?php //echo $value->NAMA_SUPPLIER;?></option>
                                    <?php 
                                        // } 
                                    ?>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="form-group">
                            <label class="col-md-3 control-label">Sistem Bayar</label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="sistem_bayar">
                                    <option value="Umum">Umum</option>
                                    <option value="BPJS">BPJS Kesehatan</option>
                                    <option value="PJKA">PJKA</option>
                                    <option value="JAMKESDA">JAMKESDA</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <h4 class="header-title">Kerjasama Asuransi</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kerjsasama RS</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="hidden" name="id_kerjasama" id="id_kerjasama" value="">
                                    <input type="text" class="form-control" id="nama_kerjasama" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn_asuransi"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Asuransi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_asuransi" id="nama_asuransi" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nomor KPA</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nomor_kpa" id="nomor_kpa" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama" id="nama" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Perusahaan</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="perusahaan" id="perusahaan" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">BP / Poli</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="bp_poli" id="bp_poli" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Asal Cabang</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="asal_cabang" id="asal_cabang" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Status Pasien</label>
                            <div class="col-md-9">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="inlineRadio7" value="Peserta" name="status_pasien">
                                    <label for="inlineRadio7"> Peserta </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="inlineRadio8" value="Suami" name="status_pasien">
                                    <label for="inlineRadio8"> Suami </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="inlineRadio9" value="Istri" name="status_pasien">
                                    <label for="inlineRadio9"> Istri </label>
                                </div>
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="inlineRadio10" value="Anak" name="status_pasien">
                                    <label for="inlineRadio10"> Anak </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Jumlah</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="jumlah_klaim" id="jumlah_klaim" value="" onkeyup="FormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <center>
                    <button type="submit" class="btn btn-success m-b-5" id="btn_simpan" disabled="disabled"><i class="fa fa-check"></i> <span><b>Daftar</b></span></button>
                    <button type="button" class="btn btn-danger m-b-5" id="batal"><i class="fa fa-times"></i> <span><b>Batal</b></span></button>
                </center>
            </div>
        </form>
    </div>
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

<!-- //LOAD RUANGAN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_ruangan" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_kamar" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_kamar">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nomor Kamar</th>
                                    <th style="text-align:center; color: #fff;">Kelas</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                    <th style="text-align:center; color: #fff;">Visite Dokter Sp.</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_kamar">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- //LOAD BED -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_bed" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bed Kamar</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_bed" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-warning" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_bed">
                            <thead>
                                <tr class="biru_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Bed</th>
                                    <th style="text-align:center; color: #fff;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_bed">Tutup</button>
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

<!-- //LOAD DOKTER -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal4" id="popup_dokter" style="display:none;">Standard Modal</button>
<div id="myModal4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Dokter</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_dokter" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_dokter">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nama Dokter</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_dokter">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- //LOAD ASURANSI -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal5" id="popup_asuransi" style="display:none;">Standard Modal</button>
<div id="myModal5" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Asuransi</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_asuransi" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tabel_asuransi">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nama Asuransi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_asuransi">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->