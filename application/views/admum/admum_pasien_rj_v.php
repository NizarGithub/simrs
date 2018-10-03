<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_jk2, 
#view_pendidikan2, 
#view_agama2, 
#view_goldar2, 
#view_tgl_lahir2,
#view_kec2,
#view_kab_kota2,
#view_prov2,
.view_lab,
.view_rujukan,
.view_asr{
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

    get_barcode();
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
    //         $('#kecamatan').removeAttr('readonly');
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
    //         $('#id_pasien').val("");
    //         $('#kode_pasien').val("");
    //         $('#nama').val("");
    //         $('#alamat').val("");
    //         $('#tempat_lahir').val("");
    //         $('#tanggal_lahir').val("");
    //         $('#umur').val("");
    //         $('#kelurahan').val("");
    //         $('#kecamatan').val("");

    //         $('#jenis_kelamin_txt').val("");
    //         $('#jenis_kelamin_show').val("");
    //         $('#pendidikan_txt').val("");
    //         $('#agama_txt').val("");
    //         $('#goldar_txt').val("");
    //         $('#tanggal_txt').val("");
    //         $('#kecamatan_txt').val("");

    //         $('#nama').attr('readonly','readonly');
    //         $('#alamat').attr('readonly','readonly');
    //         $('#tempat_lahir').attr('readonly','readonly');
    //         $('#umur').attr('readonly','readonly');
    //         $('#kelurahan').attr('readonly','readonly');
    //         $('#kecamatan').attr('readonly','readonly');
    //         $('.btn_pasien').removeAttr('disabled');

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

    $('#btn_proses').click(function(){
        var rd = $("input[name='pilihan']:checked").val();
        $.ajax({
            url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/simpan',
            data : $('#form_pasien_baru').serialize(),
            type : "POST",
            dataType : "json",
            success : function(res){
                if(rd == '1'){
                    window.open('<?php echo base_url(); ?>admum/admum_pasien_rj_c/struk_antrian', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');
                    window.location = "<?php echo base_url(); ?>admum/admum_pasien_rj_c";
                }else{
                    notif_simpan();
                    window.location = "<?php echo base_url(); ?>admum/admum_pasien_rj_c";
                }
            }
        });
    });

    $('#batal').click(function(){
        window.location = "<?php echo base_url(); ?>admum/admum_pasien_rj_c";
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

    $('.btn_poli').click(function(){
        $('#popup_poli').click();
        load_poli();
    });

     $('.btn_dokter').click(function(){
        $('#popup_dokter').click();
        load_dokter();
    });

    $("input[name='pilihan']").click(function(){
        var rd = $("input[name='pilihan']:checked").val();
        if(rd == '1'){
            $('.view_poli').show();
            $('.view_lab').hide();
        }else{
            $('.view_poli').hide();
            $('.view_lab').show();

            $.ajax({
                url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/get_kode_lab',
                type : "POST",
                dataType : "json",
                success : function(kode){
                    $('#kode_lab').val(kode);
                }
            });

            $.ajax({
                url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/get_biaya_lab',
                data : {jenis:'Laborat'},
                type : "POST",
                dataType : "json",
                success : function(row){
                    $('#id_poli').val(row['ID']);
                    $('#id_peg_dokter').val(row['ID_PEG_DOKTER']);
                    $('#biaya_lab').val(formatNumber(row['BIAYA']));
                }
            });
        }
    });

    $('.btn_jenis_laborat').click(function(){
        $('#popup_laborat').click();
        load_laborat();
    });

    $('.btn_pemeriksaan').click(function(){
        $('#popup_pemeriksaan').click();
        load_pemeriksaan();
    });

    $('input[name="sistem_bayar"]').click(function(){
        var c = $('input[name="sistem_bayar"]:checked').val();
        if(c == 'Asuransi'){
            $('.view_asr').show();

        }else{
            $('.view_asr').hide();
        }
        get_biaya_adm();
    });

    $('.btn_asuransi').click(function(){
        $('#popup_asuransi').click();
        load_asuransi();
    });

    $('#asal_rujukan').click(function(){
        var cek = $('#asal_rujukan').val();
        if(cek == 'Bidan' || cek == 'Puskesmas' || cek == 'RS Swasta'){
            $('.view_rujukan').show();
        }else{
            $('.view_rujukan').hide();
        }
    });
});

function get_biaya_reg(status){
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/get_biaya_reg',
        data : {status:status},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#biaya_reg').val(formatNumber(row['reg']['BIAYA']));
        }
    });
}

function get_biaya_adm(){
    var sistem_bayar = $('input[name="sistem_bayar"]:checked').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/get_biaya_adm',
        data : {sistem_bayar:sistem_bayar},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#biaya_adm').val(formatNumber(row['BIAYA']));
        }
    });
}

function get_kode_pasien(){
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/kode_pasien',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_pasien').val(kode); 
        }
    });
}

function get_barcode(){
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/get_barcode',
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#barcode').val(res);
        }
    });
}

function data_provinsi(){
    var id_kota_kab = $('#kota').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/data_provinsi',
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
    $('.load_tabel').show();
    var keyword = $('#cari_pasien').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_data_pasien',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td style='text-align:center;' colspan='9'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++; 

                    result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=='L'?"Laki - Laki":'Perempuan';
                    result[i].TANGGAL_LAHIR = (result[i].TANGGAL_LAHIR==null || result[i].TANGGAL_LAHIR=='')?"-":result[i].TANGGAL_LAHIR;
                    result[i].NAMA_AYAH = result[i].NAMA_AYAH==null?"-":result[i].NAMA_AYAH;
                    result[i].NAMA_IBU = result[i].NAMA_IBU==null?"-":result[i].NAMA_IBU;
                    result[i].ALAMAT = (result[i].ALAMAT==null || result[i].ALAMAT=='')?"-":result[i].ALAMAT;

                    var umur = result[i].UMUR+' Tahun '+result[i].UMUR_BULAN+' Bulan';

                    $tr += "<tr style='cursor:pointer;' onclick='klik_pasien("+result[i].ID+");'>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+no+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].KODE_PASIEN+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].TANGGAL_LAHIR+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+umur+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA_AYAH+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA_IBU+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].ALAMAT+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_pasien tbody').html($tr);
            $('.load_tabel').hide();
        }
    });

    $('#cari_pasien').off('keyup').keyup(function(){
        load_data_pasien();
    });
}

function klik_pasien(id){
    $('#popup_load').show();
    $('#tutup_pasien').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_pasien',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_pasien').val(id);
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
            $('#tanggal_lahir').val(row['TANGGAL_LAHIR']);
            $('#umur').val(row['UMUR']);
            $('#umur_bulan').val(row['UMUR_BULAN']);
            $('#nama_ayah').val(row['NAMA_AYAH']);
            $('#nama_ibu').val(row['NAMA_IBU']);
            $('#telepon').val(row['TELEPON']);
            $('#kelurahan').val(row['KELURAHAN']);
            $('#kecamatan').val(row['KECAMATAN']);
            $('#kota').val(row['KOTA']);
            $('#provinsi').val(row['PROVINSI']);

            get_biaya_reg(row['JENIS_PASIEN']);

            $('#btn_proses').removeAttr('disabled');

            $('#popup_load').fadeOut();
        }
    });
}

function load_poli(){
    $('.load_tabel_poli').show();
    var keyword = $('#cari_poli').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_poli',
        data : {keyword:keyword},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='3' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;
                    var poli = result[i].NAMA_POLI+' - '+result[i].STATUS;

                    result[i].NAMA_DOKTER = result[i].NAMA_DOKTER==null?"-":result[i].NAMA_DOKTER;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_poli("+result[i].ID+","+result[i].WKT+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+poli+"</td>"+
                                "<td>"+result[i].NAMA_DOKTER+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].BIAYA)+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_poli tbody').html($tr);
            $('.load_tabel_poli').hide();
        }
    });

    $('#cari_poli').off('keyup').keyup(function(){
        load_poli();
    });
}

function klik_poli(id){
    $('#popup_load').show();
    $('#tutup_poli').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_poli',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_poli').val(id);
            $('#poli_tujuan').val(row['NAMA_POLI']);
            $('#id_dokter').val(row['ID_PEG_DOKTER']);
            $('#dokter').val(row['NAMA_DOKTER']);
            $('#biaya').val(formatNumber(row['BIAYA']));
            $('#popup_load').fadeOut();
        }
    });
}

function load_dokter(){
    $('.load_tabel_dokter').show();
    var keyword = $('#cari_dokter').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_dokter',
        data : {keyword:keyword},
        type : "POST",
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
            $('.load_tabel_dokter').hide();
        }
    });

    $('#cari_dokter').off('keyup').keyup(function(){
        load_dokter();
    });
}

function klik_dokter(id){
    $('#popup_load').show();
    $('#tutup_dokter').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_dokter',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_dokter').val(id);
            $('#dokter').val(row['NAMA']);
            $('#popup_load').fadeOut();
        }
    });
}

//LABORAT

function load_laborat(){
    var keyword = $('#cari_laborat').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_laborat',
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

                    $tr += "<tr style='cursor:pointer;' onclick='klik_laborat("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].JENIS_LABORAT+"</td>"+
                            "</tr>";
                }
            }

            $('#tb_laborat tbody').html($tr);
        }
    });

    $('#cari_laborat').off('keyup').keyup(function(){
        load_laborat();
    });
}

function klik_laborat(id){
    $('#tutup_laborat').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_laborat',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_laborat').val(id);
            $('#jenis_laborat').val(row['JENIS_LABORAT']);
            klik_pemeriksaan(id);
        }
    });
}

function klik_pemeriksaan(id){
    $('#tutup_pemeriksaan').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_pemeriksaan',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(result){
            $tr = "";

            for(var i=0; i<result.length; i++){
                var jumlah_data = $('#tr2_'+result[i].ID).length;

                var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow2(this);'><i class='fa fa-times'></i></button>";

                $tr += "<tr id='tr2_"+result[i].ID+"'>"+
                            "<input type='hidden' name='id_pemeriksaan[]' value='"+result[i].ID+"'>"+
                            "<input type='hidden' name='tarif_pemeriksaan[]' value='"+result[i].TARIF+"'>"+
                            "<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
                            "<td style='vertical-align:middle;'>"+result[i].NILAI_NORMAL+"</td>"+
                            "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
                            "<td align='center'>"+aksi+"</td>"+
                          "</tr>";
            }

            $('#tabel_tambah_pemeriksaan tbody').html($tr);
            hitung_pemeriksaan();
        }
    });
}

function load_pemeriksaan(){
    var keyword = $('#cari_pemeriksaan').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_pemeriksaan',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    $tr += "<tr style='cursor:pointer;' onclick='klik_pemeriksaan("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].KODE+"</td>"+
                                "<td>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
                            "</tr>";
                }
            }

            $('#tb_pemeriksaan tbody').html($tr);
        }
    });

    $('#cari_pemeriksaan').off('keyup').keyup(function(){
        load_pemeriksaan();
    });
}

function deleteRow2(btn){
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
    hitung_pemeriksaan();
}

function hitung_pemeriksaan(){
    var total = 0;
    $("input[name='tarif_pemeriksaan[]']").each(function(idx,elm){
        var tarif = elm.value;
        total += parseFloat(tarif);
    });
    $('#total_tarif_pemeriksaan').val(formatNumber(total));
}

function load_asuransi(){
    $('.load_tabel_asuransi').show();
    var keyword = $('#cari_asuransi').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_asuransi',
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
            $('.load_tabel_asuransi').hide();
        }
    });

    $('#cari_asuransi').off('keyup').keyup(function(){
        load_asuransi();
    });
}

function klik_asuransi(id){
    $('#tutup_asuransi').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/klik_asuransi',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_asuransi').val(id);
            $('#nama_asuransi').val(row['NAMA_ASURANSI']);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<input type="hidden" id="ord_tmp" value="" />
<div class="row">
    <div class="col-sm-12">
        <form class="form-horizontal" role="form" action="" method="post" id="form_pasien_baru">
            <input type="hidden" name="id_poli" id="id_poli" value="">
            <input type="hidden" name="id_peg_dokter" id="id_peg_dokter" value="">
            <input type="hidden" id="id_kode_antrian_off_now" name="id_kode_antrian" value="">
            <input type="hidden" id="kode_antrian_off_now" name="kode_antrian" value="">
            <input type="hidden" id="jml_antrian_off_now" name="jumlah_antrian" value="">
            <input type="hidden" id="id_loket_now" name="id_loket" value="">
            <input type="hidden" id="barcode" name="barcode" value="">
            <div class="card-box">
                <div class="row">
            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Lengkap</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control btn_pasien" name="nama" id="nama" value="" placeholder="klik disini..." readonly required>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn_pasien"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. RM</label>
                            <div class="col-md-5">
                                <input type="hidden" name="id_pasien" id="id_pasien" value="">
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
                            <label class="col-md-3 control-label">Tempat Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="" readonly>
                                </div>
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
                            <label class="col-md-3 control-label">Golongan Darah</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="goldar_txt" id="goldar_txt" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Telepon</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control num_only" name="telepon" id="telepon" value="" maxlength="13" readonly>
                            </div>
                        </div>
            		</div>

            		<div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" class="form-control" name="alamat" id="alamat" readonly></textarea>
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
                                <input type="text" class="form-control" name="kecamatan" id="kecamatan" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kabupaten / Kota</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="kota" id="kota" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Provinsi</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="provinsi" id="provinsi" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Ayah</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_ayah" id="nama_ayah" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Ibu</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_ibu" id="nama_ibu" value="" readonly>
                            </div>
                        </div>
            		</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-shield"></i>&nbsp;<strong>Pasien Rawat Jalan</strong>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Asal Rujukan</label>
                            <div class="col-md-9">
                                <select class="form-control select2" name="asal_rujukan" id="asal_rujukan">
                                    <option value="Sendiri">APS</option>
                                    <option value="Bidan">Bidan</option>
                                    <option value="Puskesmas">Puskesmas</option>
                                    <option value="RS Swasta">RS Swasta</option>
                                    <option value="Dokter Keluarga">Dokter Keluarga</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group view_rujukan">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_rujukan" value="" placeholder="Ketik disini...">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Datang</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tanggal" id="tanggal" value="<?php echo date('d-m-Y'); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tujuan</label>
                            <div class="col-md-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio1" value="1" name="pilihan" checked>
                                    <label for="inlineRadio1"> Poli </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio2" value="2" name="pilihan">
                                    <label for="inlineRadio2"> Laborat </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group view_poli">
                            <label class="col-md-2 control-label">Poli Tujuan</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="poli_tujuan" value="" required="required" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn_poli"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group view_poli">
                            <label class="col-md-2 control-label">Dokter</label>
                            <div class="col-md-10">
                                <div class="input-group">
                                    <input type="hidden" name="id_dokter" id="id_dokter" value="">
                                    <input type="text" class="form-control" id="dokter" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning btn_dokter"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group view_poli">
                            <label class="col-md-2 control-label">Biaya Poli</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="biaya" value="" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group view_lab">
                            <label class="col-md-2 control-label">Biaya Lab</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="biaya_lab" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group view_lab">
                            <label class="col-md-2 control-label">Kode Lab</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="kode_lab" id="kode_lab" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group view_lab">
                            <label class="col-md-2 control-label">Jenis Lab</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="hidden" name="id_laborat" id="id_laborat" value="">
                                    <input type="text" class="form-control" id="jenis_laborat" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary btn_jenis_laborat" style="cursor:cursor;"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="form-group view_lab">
                            <label class="col-md-2 control-label">Pemeriksaan</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="" readonly="readonly" required="required">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-inverse btn_pemeriksaan"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div> -->
                        <div class="form-group view_lab">
                            <label class="col-md-2 control-label">&nbsp;</label>
                            <div class="col-md-10">
                                <div class="table-responsive">
                                    <table id="tabel_tambah_pemeriksaan" class="table table-bordered">
                                        <thead>
                                            <tr class="kuning_tr">
                                                <th style="color:#fff; text-align:center;">Pemeriksaan</th>
                                                <th style="color:#fff; text-align:center;">Nilai Normal</th>
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
                        <div class="form-group view_lab">
                            <label class="col-md-2 control-label">Total Tarif</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="total_tarif_pemeriksaan" id="total_tarif_pemeriksaan" value="" readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Biaya Reg</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="biaya_reg" id="biaya_reg" value="" readonly>
                                <small><i>Biaya registrasi pasien Lama / Baru</i></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio3" value="Umum" name="sistem_bayar">
                                    <label for="inlineRadio3"> Umum </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio4" value="Asuransi" name="sistem_bayar">
                                    <label for="inlineRadio4"> Asuransi </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Biaya Adm</label>
                            <div class="col-md-10">
                                <input type="hidden" id="status_pasien" value="">
                                <input type="text" class="form-control" name="biaya_adm" id="biaya_adm" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group view_asr">
                            <label class="col-md-2 control-label">Asuransi</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="hidden" name="id_asuransi" id="id_asuransi" value="">
                                    <input type="text" class="form-control" name="nama_asuransi" id="nama_asuransi" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger btn_asuransi"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <center>
                    <button type="button" class="btn btn-success m-b-5" value="daftar" id="btn_proses" disabled="disabled">
                        <i class="fa fa-refresh"></i> <span><b>Proses</b></span>
                    </button>
                    <button type="button" class="btn btn-danger m-b-5" id="batal">
                        <i class="fa fa-times"></i> <span><b>Batal</b></span>
                    </button>
                </center>
            </div>
        </form>
    </div>
</div>

<!-- //LOAD PASIEN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" id="popup_pasien" style="display:none;">Standard Modal</button>
<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%;">
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
                            <span class="help-block"><small><b><i>*pencarian berdasarkan Nama Pasien, Nama Orang Tua, Alamat dan Tanggal Lahir</i></b></small></span>
                        </div>
                    </div>
                </form>
                <div class="load_tabel">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
                <div class="table-responsive">
                    <div class="scroll-xy">
                        <table class="table table-hover table-bordered" id="tabel_pasien">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">No. RM</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Pasien</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Jenis Kelamin</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Tanggal Lahir</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Umur</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Ayah</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Ibu</th>
                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Alamat</th>
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

<!-- //LOAD POLI -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal2" id="popup_poli" style="display:none;">Standard Modal</button>
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Poli</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_poli" placeholder="Cari..." value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-custom" style="cursor:default;">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="load_tabel_poli">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_poli">
                            <thead>
                                <tr class="hijau_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Nama Poli</th>
                                    <th style="text-align:center; color: #fff;">Dokter</th>
                                    <th style="text-align:center; color: #fff;">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_poli">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- // -->

<!-- //LOAD DOKTER -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal3" id="popup_dokter" style="display:none;">Standard Modal</button>
<div id="myModal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <div class="load_tabel_dokter">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
                <div class="table-responsive">
                    <div class="scroll-y">
                        <table class="table table-hover table-bordered" id="tabel_dokter">
                            <thead>
                                <tr class="kuning_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Dokter</th>
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

<!-- LABORAT -->
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Jenis Laborat</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_laborat" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tb_laborat">
                            <thead>
                                <tr class="hijau_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Jenis Laborat</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_laborat">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_pemeriksaan" id="popup_pemeriksaan" style="display:none;">Standard Modal</button>
<div id="myModal1_pemeriksaan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
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
                                <input type="text" class="form-control" id="cari_pemeriksaan" placeholder="Cari..." value="">
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
                        <table class="table table-hover table-bordered" id="tb_pemeriksaan">
                            <thead>
                                <tr class="hijau_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode</th>
                                    <th style="text-align:center; color: #fff;">Pemeriksaan</th>
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
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_pemeriksaan">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
                <div class="load_tabel_asuransi">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
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