<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$id_divisi = $sess_user['id_divisi']; //ID POLI
$user = $this->master_model_m->get_user_info($id_user);
$level = $user->LEVEL;
?>
<style type="text/css">
#loading_tindakan,
#loading_diagnosa,
#loading_resep,
.view_poli,
#view_tanggal,
#view_bulan{
    display: none;
}
</style>
<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}

var ajax = '';
var level = "<?php echo $level; ?>";

$(document).ready(function(){
    data_pasien();

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

    $('#jumlah_tampil').change(function(){
        data_pasien();
    });

    $('#dt_diagnosa').click(function(){
        get_diagnosa();
    });

    $('#dt_resep').click(function(){
        get_resep();
    });

    $('#cek_tampil_semua').click(function(){
        data_pasien();
        $('#view_tanggal').hide();
        $('#view_bulan').hide();

        $('#tanggal_awal').val("");
        $('#tanggal_akhir').val("");
        $('#tahun').val("");
    });

    $("input[name='filter']").click(function(){
        var filter = $("input[name='filter']:checked").val();

        if(filter == 'Semua'){
            data_pasien();
            $('.view_poli').hide();
            $('#view_tanggal').hide();
            $('#view_bulan').hide();

            $('#tanggal_awal').val("");
            $('#tanggal_akhir').val("");
            $('#tahun').val("");
        }else if(filter == 'Per Tanggal'){
            $('.view_poli').hide();
            $('#view_tanggal').show();
            $('#view_bulan').hide();

            $('#tahun').val("");
        }else if(filter == 'Per Bulan'){
            $('.view_poli').hide();
            $('#view_tanggal').hide();
            $('#view_bulan').show();

            $('#tanggal_awal').val("");
            $('#tanggal_akhir').val("");
            $('#tahun').val("");
        }else if(filter == 'Poli'){
            $('.view_poli').show();
            $('#view_tanggal').hide();
            $('#view_bulan').hide();

            $('#tanggal_awal').val("");
            $('#tanggal_akhir').val("");
            $('#tahun').val("");
        }
    });
});

function paging($selector){
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

function cek_tanggal(){
    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    //01-02-2018
    var daw = tanggal_awal.substr(0,2);
    var maw = tanggal_awal.substr(3,2);
    var yaw = tanggal_awal.substr(6);
    var tglaw_balik = yaw+'-'+maw+'-'+daw;

    var dak = tanggal_akhir.substr(0,2);
    var mak = tanggal_akhir.substr(3,2);
    var yak = tanggal_akhir.substr(6);
    var tglak_balik = yak+'-'+mak+'-'+dak;

    // Here are the two dates to compare
    var date1 = tglaw_balik;
    var date2 = tglak_balik;

    // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
    date1 = date1.split('-');
    date2 = date2.split('-');

    // Now we convert the array to a Date object, which has several helpful methods
    date1 = new Date(date1[0], date1[1], date1[2]);
    date2 = new Date(date2[0], date2[1], date2[2]);

    if(tanggal_awal == ""){
        notif_tanggal_awal_kosong();
        $('#tanggal_awal').focus();
    }else if(tanggal_akhir == ""){
        notif_tanggal_akhir_kosong();
        $('#tanggal_akhir').focus();
    }else if(date2 < date1){
        notif_tanggal_kurang();
        $('#tanggal_akhir').focus();
    }else{
        data_pasien();
    }
}

function data_pasien(){
    $('#popup_load').show();
    var keyword = $('#cari_pasien').val();
    var now = "<?php echo date('d-m-Y'); ?>";
    var id_divisi = "<?php echo $id_divisi; ?>";
    var level = "<?php echo $level; ?>";

    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();
    var poli = $('#poli').val();

    var cek = $('#cek_tampil_semua').is(":checked");
    var hasil_cek = '';

    if(cek == true){
        hasil_cek = 'True';
    }else{
        hasil_cek = 'False';
    }

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/data_pasien_terima',
        data : {
            keyword:keyword,
            now:now,
            id_divisi:id_divisi,
            level:level,
            hasil_cek:hasil_cek,
            tanggal_awal:tanggal_awal,
            tanggal_akhir:tanggal_akhir,
            bulan:bulan,
            tahun:tahun,
            poli:poli
        },
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='8' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;
                    var nomor = result[i].KODE_ANTRIAN+'-'+result[i].NOMOR_ANTRIAN;
                    var encodedString = Base64.encode(result[i].ID_RJ);
                    var panggil = '';
                    var aksi = '';

                    if(result[i].STATUS_SUDAH == '1'){
                        panggil = '<span class="label label-success">Selesai Ditangani</span>';
                        aksi = '<span class="label label-success">Selesai Ditangani</span>';
                    }else{
                        panggil = '<button type="button" onclick="panggil_pasien('+result[i].ID_RJ+');" class="btn btn-purple waves-effect waves-light btn-sm">'+
                                    nomor+' <i class="fa fa-bullhorn"></i>'+
                                  '</button>';
                        if(level == null || level == ""){
                            aksi =  '<button type="button" onclick="detail_rm('+result[i].ID+');" class="btn btn-success waves-effect waves-light btn-sm">'+
                                        '<i class="fa fa-book"></i>';
                                    '</button>';
                        }else{
                            aksi = '<a href="<?php echo base_url(); ?>poli/rk_pelayanan_rj_c/tindakan_rj/'+encodedString+'" class="btn btn-success waves-effect waves-light btn-sm m-r-5" onclick="klik_tindakan('+result[i].ID+');">'+
                                        '<i class="fa fa-user-md"></i> Tindakan'+
                                    '</a>'+
                                   '<button type="button" onclick="detail_pasien('+result[i].ID+');" class="btn btn-primary waves-effect waves-light btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">'+
                                        '<i class="fa fa-eye"></i> Detail'+
                                   '</button>';
                        }
                    }

                    result[i].WAKTU_DAFTAR = result[i].WAKTU_DAFTAR==null?"00:00":result[i].WAKTU_DAFTAR;
                    result[i].ALAMAT = (result[i].ALAMAT=="" || result[i].ALAMAT==null)?"-":result[i].ALAMAT;

                    $tr +=  '<tr>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].TANGGAL+' - '+result[i].WAKTU_DAFTAR+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].ALAMAT+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+panggil+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien tbody').html($tr);
            var total_pasien = result.length;
            $('#total_pasien').html(parseInt(total_pasien));
            paging();
            $('#popup_load').fadeOut();
        }
    });

    $('#cari_pasien').off('keyup').keyup(function(){
        data_pasien();
    });
}

function detail_pasien(id){
    $('#popup_detail').click();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/data_pasien_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            row['JENIS_KELAMIN'] = row['JENIS_KELAMIN']=='L'?'Laki - Laki':'Perempuan';
            row['NAMA_ORTU'] = row['NAMA_ORTU']==null?'-':row['NAMA_ORTU'];
            row['TELEPON'] = row['TELEPON']==null?'-':row['TELEPON'];

            $tr = "<tr>"+
                    "<td><b>No. RM</b></td>"+
                    "<td class='success'>"+row['KODE_PASIEN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Nama Lengkap</b></td>"+
                    "<td class='success'>"+row['NAMA']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Jenis Kelamin</b></td>"+
                    "<td class='success'>"+row['JENIS_KELAMIN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Pendidikan</b></td>"+
                    "<td class='success'>"+row['PENDIDIKAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Agama</b></td>"+
                    "<td class='success'>"+row['AGAMA']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Alamat</b></td>"+
                    "<td class='success'>"+row['ALAMAT']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Nama Orang Tua</b></td>"+
                    "<td class='success'>"+row['NAMA_ORTU']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Telepon</b></td>"+
                    "<td class='success'>"+row['TELEPON']+"</td>"+
                  "</tr>";

            $('#tabel_detail tbody').html($tr);
            var umur = row['UMUR']+' Tahun '+row['UMUR_BULAN']+' Bulan';

            $tr2 = "<tr>"+
                    "<td><b>Tempat Lahir</b></td>"+
                    "<td class='success'>"+row['TEMPAT_LAHIR']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Tanggal Lahir</b></td>"+
                    "<td class='success'>"+formatTanggal(row['TANGGAL_LAHIR'])+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Umur</b></td>"+
                    "<td class='success'>"+umur+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Golongan Darah</b></td>"+
                    "<td class='success'>"+row['GOLONGAN_DARAH']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Kelurahan</b></td>"+
                    "<td class='success'>"+row['KELURAHAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Kecamatan</b></td>"+
                    "<td class='success'>"+row['KECAMATAN']+"</td>"+
                  "</tr>"+
                  "<tr>"+
                    "<td><b>Provinsi</b></td>"+
                    "<td class='success'>"+row['PROVINSI']+"</td>"+
                  "</tr>";

            $('#tabel_detail2 tbody').html($tr2);
        }
    });
}

function panggil_pasien(id){
    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/panggil_pasien',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            var kode_antrian = row['KODE_ANTRIAN'];
            var jml_antrian = row['NOMOR_ANTRIAN'];
            var poli = row['NAMA_POLI'];

            responsiveVoice.speak(
              "Pengunjung dengan nomor antrian, "+kode_antrian+","+jml_antrian+", silahkan menuju ke "+poli+". Terima kasih. ",
              "Indonesian Female",
              {
               pitch: 1, 
               rate: 1, 
               volume: 1
              }
            );
        }
    });
}

function detail_rm(id_pasien){
    $('#popup_rekam_medik').click();
    $('#id_pasien').val(id_pasien);

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/get_rekam_medik',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#nama_pasien_txt').html(row['ps']['NAMA']);
            row['rk']['NAMA_DOKTER'] = row['rk']['NAMA_DOKTER']==null?"-":row['rk']['NAMA_DOKTER'];

            $tr = '<tr>'+
                      '<td style="font-weight:bold;">NAMA POLI</td>'+
                      '<td>: '+row['rk']['NAMA_POLI']+'</td>'+
                      '<td style="font-weight:bold;">NAMA DOKTER</td>'+
                      '<td>: '+row['rk']['NAMA_DOKTER']+'</td>'+
                      '<td style="font-weight:bold;">BIAYA</td>'+
                      '<td>: '+formatNumber(row['rk']['BIAYA'])+'</td>'+
                  '</tr>';

            $('#tabel_poli tbody').html($tr);
            get_tindakan();
        }
    });
}

function klik_tindakan(id_pasien){
    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/ubah_jenis_pasien',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            console.log(res);
        }
    });
}

function get_tindakan(){
    var id_pasien = $('#id_pasien').val();
    $('#loading_tindakan').show();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/get_tindakan',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ""){
                $tr = '<tr><td colspan="3" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr +=  '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+res[i].NAMA_TINDAKAN+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res[i].TARIF)+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_tindakan tbody').html($tr);
            $('#loading_tindakan').hide();
        }
    });
}

function get_diagnosa(){
    var id_pasien = $('#id_pasien').val();
    $('#loading_diagnosa').show();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/get_diagnosa',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr +=  '<tr>'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td>'+res[i].DIAGNOSA+'</td>'+
                                '<td>'+res[i].TINDAKAN+'</td>'+
                                '<td>'+res[i].NAMA_PENYAKIT+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_diagnosa tbody').html($tr);
            $('#loading_diagnosa').hide();
        }
    });
}

function get_resep(){
    var id_pasien = $('#id_pasien').val();
    $('#loading_resep').show();

    $.ajax({
        url : '<?php echo base_url(); ?>poli/poli_home_c/get_resep',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            $tr2 = '';

            if(res['ind'] == null || res['ind'] == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;">Data Tidak Ada</td></tr>';
            }else{
                $tr = '<tr>'+
                        '<td>'+res['ind']['KODE_RESEP']+'</td>'+
                        '<td style="text-align:center;">'+res['ind']['TANGGAL']+'</td>'+
                        '<td style="text-align:right;">'+formatNumber(res['ind']['TOTAL'])+'</td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td style="text-align:center; font-weight:bold;">NAMA OBAT</td>'+
                        '<td style="text-align:center; font-weight:bold;">ATURAN MINUM</td>'+
                        '<td style="text-align:center;">&nbsp;</td>'+
                      '</tr>';

                for(var i=0; i<res['det'].length; i++){
                    $tr2 += '<tr>'+
                                '<td>'+res['det'][i].NAMA_OBAT+'</td>'+
                                '<td style="text-align:center;">'+res['det'][i].ATURAN_MINUM+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res['det'][i].SUBTOTAL)+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_resep tbody').html($tr+$tr2);
            $('#loading_resep').hide();
        }
    });
}
</script>

<?PHP 
    $sess_user = $this->session->userdata('masuk_rs');
    $id_user = $sess_user['id'];
    $user = $this->master_model_m->get_user_info($id_user);
?>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row" id="view_data">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">
                <?php 
                    $h4 = "";
                    if($level == null){
                        $h4 = "Data Pasien Per Poli";
                    }else{
                        $h4 = "Pasien Baru";
                    }
                    echo $h4; 
                ?>
            </h4>
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-md-1 control-label" style="width: 4%; text-align: left;">Filter :</label>
                    <?php if(count($level) != 0){ ?>
                    <div class="col-md-2" style="width: 10%;">
                        <div class="checkbox checkbox-inline checkbox-success">
                            <input type="checkbox" id="cek_tampil_semua" value="Semua">
                            <label for="cek_tampil_semua"> Tampilkan Semua </label>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-4">
                        <?php if(count($level) == 0){ ?>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_semua" value="Semua" name="filter">
                            <label for="rd_semua"> Semua </label>
                        </div>
                        <?php } ?>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_per_tgl" value="Per Tanggal" name="filter">
                            <label for="rd_per_tgl"> Per Tanggal </label>
                        </div>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_per_bln" value="Per Bulan" name="filter">
                            <label for="rd_per_bln"> Per Bulan </label>
                        </div>
                        <?php if(count($level) == 0){ ?>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="rd_poli" value="Poli" name="filter">
                            <label for="rd_poli"> Poli </label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php if(count($level) == 0){ ?>
                    <label class="col-md-1 control-label view_poli" style="text-align: left; width: 6%;">Pilih Poli</label>
                    <div class="col-md-4">
                        <select class="form-control select2 view_poli" id="poli" onchange="data_pasien();">
                            <option value="Semua">Semua</option>
                            <?php
                                $poli = $this->model->get_poli();
                                foreach ($poli as $key => $value) {
                            ?>
                            <option value="<?php echo $value->ID; ?>"><?php echo $value->NAMA; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                </div>
                <div class="form-group" id="view_tanggal">
                    <label class="control-label col-sm-1" style="text-align: left; width: 5%;">Tanggal</label>
                    <div class="col-sm-4">
                        <div class="input-daterange input-group" id="date-range">
                            <input type="text" class="form-control" id="tanggal_awal" data-mask="99-99-9999" value="">
                            <span class="input-group-addon bg-primary b-0 text-white">s/d</span>
                            <div class="input-group">
                                <input type="text" class="form-control" id="tanggal_akhir" data-mask="99-99-9999" value="">
                                <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-success" onclick="cek_tanggal();">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="view_bulan">
                    <label class="control-label col-sm-1" style="text-align: left; width: 5%;">Bulan</label>
                    <div class="col-sm-2">
                        <select class="form-control" id="bulan">
                        <?php
                            $bulan = date('m');
                            $bln_arr = array('Pilih','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                            for($i=0; $i<13; $i++){
                                $select = "";
                                if($i == $bulan){
                                    $select = "selected='selected'";
                                }else{
                                    $select = $select;
                                }
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo $select; ?>><?php echo $bln_arr[$i]; ?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" class="form-control num_only" id="tahun" value="" maxlength="4" placeholder="Tahun">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-success" onclick="data_pasien();">
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" class="form-control" name="cari_pasien" id="cari_pasien" placeholder="Cari pasien..." value="">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tabel_pasien" class="table table-hover table-bordered">
                                <thead>
                                    <tr class="merah">
                                        <th style="color:#fff; text-align:center;">No</th>
                                        <th style="color:#fff; text-align:center;">No. RM</th>
                                        <th style="color:#fff; text-align:center;">Tgl / Waktu</th>
                                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                        <th style="color:#fff; text-align:center;">JK</th>
                                        <th style="color:#fff; text-align:center;">Alamat</th>
                                        <th style="color:#fff; text-align:center;">Panggil</th>
                                        <th style="color:#fff; text-align:center;">
                                            <?php 
                                                echo $level=$level==null?"Rekam Medik":"Aksi"; 
                                            ?>  
                                        </th>
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
    </div>
</div>

<button class="btn btn-primary" id="popup_detail" data-toggle="modal" data-target="#full-width-modal" style="display:none;">Full width Modal</button>
<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
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
</div>

<button class="btn btn-primary" id="popup_rekam_medik" data-toggle="modal" data-target="#full-width-modal2" style="display:none;">Full width Modal</button>
<div id="full-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="full-width-modalLabel">Rekam Medik Pasien <b class="text-info" id="nama_pasien_txt">-</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="col-md-6">
                                <table id="tabel_poli" class="table">
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="id_pasien" id="id_pasien" value="">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="active">
                                <a href="#tindakan1" role="tab" data-toggle="tab">Tindakan</a>
                            </li>
                            <li role="presentation" id="dt_diagnosa">
                                <a href="#diagnosa1" role="tab" data-toggle="tab">Diagnosa</a>
                            </li>
                            <li role="presentation" id="dt_resep">
                                <a href="#resep1" role="tab" data-toggle="tab">Resep</a>
                            </li>
                            <li role="presentation" id="dt_lab">
                                <a href="#lab1" role="tab" data-toggle="tab">Laborat</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tindakan1">
                                <form class="form-horizontal" id="loading_tindakan">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table id="tabel_tindakan" class="table table-bordered">
                                    <thead>
                                        <tr class="success">
                                            <th style="text-align: center;">NO</th>
                                            <th>NAMA TINDAKAN</th>
                                            <th>BIAYA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="diagnosa1">
                                <form class="form-horizontal" id="loading_diagnosa">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table id="tabel_diagnosa" class="table table-bordered">
                                    <thead>
                                        <tr class="info">
                                            <th>NO</th>
                                            <th>DIAGNOSA</th>
                                            <th>TINDAKAN</th>
                                            <th>PENYAKIT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="resep1">
                                <form class="form-horizontal" id="loading_resep">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                                <img src="<?php echo base_url(); ?>picture/loading.gif" style="height: 35px; width: 40px;">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table id="tabel_resep" class="table table-bordered">
                                    <thead>
                                        <tr class="success">
                                            <th style="text-align: center;">KODE RESEP</th>
                                            <th style="text-align: center;">TANGGAL</th>
                                            <th style="text-align: center;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="lab1">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>