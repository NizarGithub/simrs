<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$user = $this->master_model_m->get_user_info($id_user);
$level = $user->LEVEL;
?>
<style type="text/css">
#view_tanggal,
#view_bulan,
#view_data_poli_det{
    display: none;
}

#view_jk2, #view_pendidikan2, #view_agama2, #view_goldar2, #view_tgl_lahir2, #view_kab_kota2, #view_prov2{
    display: none;
}
</style>

<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/-",

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
    data_pasien_dr_poli();

    // get_barcode();

    $('#btn_history').show();
    $('.btn_pasien').removeAttr('disabled');

    $('#kode_pasien').val("");
    $('#nama').val("");
    $('#alamat').val("");
    $('#tempat_lahir').val("");
    $('#nama_ortu').val("");
    $('#telepon').val("");
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
    $('#nama_ortu').attr('readonly','readonly');
    $('#telepon').attr('readonly','readonly');
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

    $('.btn_pasien').click(function(){
        $('#popup_pasien').click();
        load_data_pasien();
    });

    $('#btn_tambah_lab_snd').click(function(){
        $('#form_pasien_baru').show();
        $('#form_data').hide();
    });

    $('#batal_snd').click(function(){
    $('#btn_history').show();

    $('.btn_pasien').removeAttr('disabled');
        $('#kode_pasien').val("");
        $('#nama').val("");
        $('#alamat').val("");
        $('#tempat_lahir').val("");
        $('#nama_ortu').val("");
        $('#telepon').val("");
        $('#tanggal_lahir').val("");
        $('#umur').val("");
        $('#umur_bulan').val("");
        $('#kelurahan').val("");
        $('#kecamatan').val("");

        $('#jenis_kelamin_txt').val("");
        $('#jenis_kelamin_show').val("");
        $('#pendidikan_txt').val("");
        $('#agama_txt').val("");
        $('#goldar_txt').val("");
        $('#tanggal_txt').val("");
        $('#kecamatan_txt').val("");
        $('#kota_txt').val("");
    });

    $('#jumlah_tampil').change(function(){
        data_pasien();
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
            $('#view_tanggal').hide();
            $('#view_bulan').hide();

            $('#tanggal_awal').val("");
            $('#tanggal_akhir').val("");
            $('#tahun').val("");
        }else if(filter == 'Per Tanggal'){
            $('#view_tanggal').show();
            $('#view_bulan').hide();

            $('#tahun').val("");
        }else if(filter == 'Per Bulan'){
            $('#view_tanggal').hide();
            $('#view_bulan').show();

            $('#tanggal_awal').val("");
            $('#tanggal_akhir').val("");
            $('#tahun').val("");
        }
    });

    $('#dt_dari_poli').click(function(){
        // data_pasien_dr_poli();

        $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/ubah_status_lihat',
            type : "POST",
            dataType : "json",
            success : function(res){
                console.log(res);
                data_pasien_dr_poli();
            }
        });
    });

    $('#btn_simpan_poli').click(function(){
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-bottom-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }

        var id = $('#id_rj_det').val();
        var hasil = $('input[name="hasil[]"]').val();
        var nilai_rujukan = $('input[name="nilai_rujukan[]"]').val();

        if(hasil.length == 0){
            toastr["error"]("Masukkan hasil laboratnya!", "Notifikasi");
        }else if(nilai_rujukan.length == 0){
            toastr["error"]("Masukkan nilai rujukan laboratnya!", "Notifikasi");
        }else{
            $.ajax({
                url : '<?php echo base_url(); ?>lab/lab_home_c/ubah_laborat_detail',
                data : $('#view_data_poli_det').serialize(),
                type : "POST",
                dataType : "json",
                success : function(res){
                    toastr["success"]("Data berhasil disimpan!", "Notifikasi");
                    // detail_pasien_poli(id);
                    $('#view_data_poli').show();
                    $('#view_data_poli_det').hide();
                    data_pasien_dr_poli();
                }
            });
        }
    });

    $('#btn_proses').click(function(){
        toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-bottom-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }

        var id = $('#id_pasien2').val();

        if(id == ""){
            toastr["error"]("Masukkan data pasien terlebih dahulu!", "Notifikasi");
        }else{
            // $.ajax({
            //     url : '<?php //echo base_url(); ?>lab/lab_home_c/ubah_laborat_detail',
            //     data : $('#form_pasien_baru').serialize(),
            //     type : "POST",
            //     dataType : "json",
            //     success : function(res){
            //         toastr["success"]("Data berhasil disimpan!", "Notifikasi");
            //         $('#form_data').show();
            //         $('#form_pasien_baru').hide();
            //     }
            // });
        }
    });

});

function get_kode_pasien(){
    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/kode_pasien',
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
        url : '<?php echo base_url(); ?>lab/lab_home_c/data_provinsi',
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
        url : '<?php echo base_url(); ?>lab/lab_home_c/load_data_pasien',
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

                    $tr += "<tr style='cursor:pointer;' onclick='klik_pasien("+result[i].ID+");'>"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td>"+result[i].KODE_PASIEN+"</td>"+
                                "<td>"+result[i].NAMA+"</td>"+
                                "<td style='text-align:center;'>"+result[i].UMUR+" Tahun "+result[i].UMUR_BULAN+" Bulan</td>"+
                                "<td>"+result[i].NAMA_ORTU+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_pasien_ds tbody').html($tr);
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
        url : '<?php echo base_url(); ?>lab/lab_home_c/klik_pasien',
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
            $('#nama_ortu').val(row['NAMA_ORTU']);
            $('#telepon').val(row['TELEPON']);
            $('#tanggal_txt').val(row['TANGGAL_LAHIR']);
            $('#umur').val(row['UMUR']);
            $('#umur_bulan').val(row['UMUR_BULAN']);
            $('#kelurahan').val(row['KELURAHAN']);
            $('#kecamatan').val(row['KECAMATAN']);
            $('#kota_txt').val(row['KOTA']);
            $('#provinsi_txt').val(row['PROVINSI']);

            $('#nama').attr('readonly','readonly');
            $('#alamat').attr('readonly','readonly');
            $('#tempat_lahir').attr('readonly','readonly');
            $('#nama_ortu').attr('readonly','readonly');
            $('#telepon').attr('readonly','readonly');
            $('#umur').attr('readonly','readonly');
            $('#kelurahan').attr('readonly','readonly');
            $('#kecamatan').attr('readonly','readonly');
        }
    });
}

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
    var level = "<?php echo $level; ?>";

    var tanggal_awal = $('#tanggal_awal').val();
    var tanggal_akhir = $('#tanggal_akhir').val();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();

    var cek = $('#cek_tampil_semua').is(":checked");
    var hasil_cek = '';

    if(cek == true){
        hasil_cek = 'True';
    }else{
        hasil_cek = 'False';
    }

    // if(ajax){
    //     ajax.abort();
    // }

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/data_pasien_terima',
        data : {
            keyword:keyword,
            now:now,
            level:level,
            hasil_cek:hasil_cek,
            tanggal_awal:tanggal_awal,
            tanggal_akhir:tanggal_akhir,
            bulan:bulan,
            tahun:tahun
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

                    var detail = '<button type="button" id="btn_history" onclick="detail_pasien('+result[i].ID+');" class="btn btn-danger waves-effect waves-light btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">'+
                                    '<i class="fa fa-eye"></i>';
                                 '</button>';
                    var aksi =  '';

                    if(level == null || level == ""){

                    }else{
                        var encodedString = Base64.encode(result[i].ID_RJ);
                        aksi =  '<a href="<?php echo base_url(); ?>lab/lab_home_c/tindakan/'+btoa(encodedString)+'" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-user-md"></i></a>';
                    }

                    $tr +=  '<tr>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].TANGGAL+' - '+result[i].WAKTU_RJ+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].ALAMAT+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA_ORTU+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+detail+'</td>'+
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
        url : '<?php echo base_url(); ?>lab/lab_home_c/data_pasien_id',
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

function data_pasien_dr_poli(){
    $('#popup_load').show();
    var tanggal = "<?php echo date('d-m-Y'); ?>";
    var keyword = $('#cari_pasien_poli').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_pasien_dr_poli',
        data : {
            tanggal:tanggal,
            keyword:keyword
        },
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";
            var tot = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    if(result[i].STATUS_LIHAT == '1'){
                        tot = '0';
                    }else{
                        tot = result.length;
                    }
                    $('#tot_dari_poli').html(parseInt(tot));

                    var detail = '<button type="button" id="btn_history" onclick="detail_pasien_poli('+result[i].ID_LAB+');" class="btn btn-primary waves-effect waves-light btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">'+
                                    '<i class="fa fa-eye"></i>';
                                 '</button>';

                    $tr +=  '<tr>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].TANGGAL+' - '+result[i].WAKTU+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].JENIS_LABORAT+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:right;">'+formatNumber(result[i].TOTAL_TARIF)+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+detail+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien_poli tbody').html($tr);
            var total_pasien = result.length;
            $('#total_pasien_poli').html(parseInt(total_pasien));
            // paging();
            $('#popup_load').fadeOut();
        }
    });

    $('#cari_pasien_poli').off('keyup').keyup(function(){
        data_pasien_dr_poli();
    });
}

function detail_pasien_poli(id){
    $('#view_data_poli_det').show();
    $('#view_data_poli').hide();

    $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/get_pasien_poli_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';
            var no = 0;

            $('#id_rj_det').val(id);
            $('#no_rm_poli').val(res['ind']['KODE_PASIEN']);
            $('#nama_pasien_poli').val(res['ind']['NAMA']);
            $('#jenis_lab_poli').val(res['ind']['JENIS_LABORAT']);

            for(var i=0; i<res['det'].length; i++){
                no++;

                res['det'][i].HASIL = res['det'][i].HASIL==null?" ":res['det'][i].HASIL;
                res['det'][i].NILAI_RUJUKAN = res['det'][i].NILAI_RUJUKAN==null?" ":res['det'][i].NILAI_RUJUKAN;

                $tr += '<tr>'+
                            '<td style="text-align:center;">'+no+'</td>'+
                            '<td>'+res['det'][i].NAMA_PEMERIKSAAN+'</td>'+
                            '<td>'+
                                '<input type="hidden" name="id_detail[]" value="'+res['det'][i].ID+'">'+
                                '<input type="text" class="form-control" name="hasil[]" value="'+res['det'][i].HASIL+'">'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" class="form-control" name="nilai_rujukan[]" value="'+res['det'][i].NILAI_RUJUKAN+'">'+
                            '</td>'+
                            '<td style="text-align:right;">'+formatNumber(res['det'][i].SUBTOTAL)+'</td>'+
                        '</tr>';
            }

            $('#tabel_pasien_poli_det tbody').html($tr);
        }
    });

    $('#btn_batal_poli').click(function(){
        $('#view_data_poli_det').hide();
        $('#view_data_poli').show();
        $("input[name='hasil[]']").val("");
        $("input[name='nilai_rujukan[]']").val("");
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="header-title m-t-0 m-b-30">
            <?php 
                $h4 = "";
                if($level == null){
                    $h4 = "Data Pasien Laborat";
                }else{
                    $h4 = "Pasien Baru";
                }
                echo $h4; 
            ?>
            </h4>

            <div class="row">
                <div class="col-lg-12">

                    <ul class="nav nav-tabs">
                        <li class="active" role="presentation">
                            <a data-toggle="tab" role="tab" href="#home1"><i class="fa fa-book"></i> Dari Admission</a>
                        </li>
                        <li role="presentation">
                            <a data-toggle="tab" role="tab" href="#profile1"><i class="fa fa-user"></i> Datang Sendiri</a>
                        </li>
                        <li role="presentation" id="dt_dari_poli">
                            <a data-toggle="tab" role="tab" href="#profile2">
                                <i class="fa fa-home"></i> Dari Poli <span class="badge badge-success" id="tot_dari_poli">0</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="home1" class="tab-pane fade in active" role="tabpanel">
                            <form class="form-horizontal" role="form">
                                <input type="hidden" name="nomor_antrian" id="nomor_antrian" value="">
                                <div class="form-group">
                                    <label class="col-md-1 control-label" style="width: 5%; text-align: left;">Filter :</label>
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
                                    </div>
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
                                            <input type="text" class="form-control" name="cari_pasien" id="cari_pasien" placeholder="Cari..." value="">
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
                                                    <tr class="biru">
                                                        <th style="color:#fff; text-align:center;">No</th>
                                                        <th style="color:#fff; text-align:center;">No. RM</th>
                                                        <th style="color:#fff; text-align:center;">Tgl / Waktu</th>
                                                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                                        <th style="color:#fff; text-align:center;">JK</th>
                                                        <th style="color:#fff; text-align:center;">Alamat</th>
                                                        <th style="color:#fff; text-align:center;">Nama Org Tua</th>
                                                        <th style="color:#fff; text-align:center;">Detail</th>
                                                        <th style="color:#fff; text-align:center;">Tindakan</th>
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

                        <div id="profile1" class="tab-pane fade" role="tabpanel">
                            <form class="form-horizontal" role="form" action="" method="post" id="form_pasien_baru">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-6">
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
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">No. RM</label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="kode_pasien" id="kode_pasien" value="" readonly>
                                                </div>
                                               <!--  <div class="col-md-3">
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="baru" type="checkbox" name="baru" value="1">
                                                        <label for="baru">
                                                            Baru
                                                        </label>
                                                    </div>
                                                </div> --> 
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
                                            <div class="form-group" id="view_tgl_lahir1">
                                                <label class="col-md-3 control-label">Tanggal Lahir</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir" data-mask="99-99-9999" value="" placeholder="dd-mm-yyyy" onchange="hitung_umur();">
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
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Nama Orang Tua</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="nama_ortu" id="nama_ortu" value="" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Telepon</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control num_only" name="telepon" id="telepon" value="" maxlength="13" required="required">
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
                                        <button type="button" class="btn btn-success m-b-5" id="btn_proses"><i class="fa fa-refresh"></i> <span><b>Proses</b></span></button>
                                        <button type="button" class="btn btn-danger m-b-5" id="batal_snd"><i class="fa fa-times"></i> <span><b>Batal</b></span></button>
                                    </center>
                                </div>
                            </form>

                            <form class="form-horizontal" role="form" id="form_data">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <button class="btn btn-primary m-b-5" type="button" id="btn_tambah_lab_snd">
                                            <i class="fa fa-plus"></i>&nbsp;<b>Tambah Data</b>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tabel_pasien_snd" class="table table-hover table-bordered">
                                                <thead>
                                                    <tr class="merah">
                                                        <th style="color:#fff; text-align:center;">No</th>
                                                        <th style="color:#fff; text-align:center;">No. RM</th>
                                                        <th style="color:#fff; text-align:center;">Tgl / Waktu</th>
                                                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                                        <th style="color:#fff; text-align:center;">JK</th>
                                                        <th style="color:#fff; text-align:center;">Alamat</th>
                                                        <th style="color:#fff; text-align:center;">Detail</th>
                                                        <th style="color:#fff; text-align:center;">Tindakan</th>
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
                                        <div id="tablePaging_snd"> </div>
                                    </div>
                                    <div class="col-md-2">
                                        <h4 class="header-title">Total Pasien : <b id="total_pasien_snd"></b></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-9">
                                        
                                    </div>
                                    <label class="col-md-2 control-label">Jumlah Tampil</label>
                                    <div class="col-md-1 pull-right">
                                        <select class="form-control" id="jumlah_tampil_snd">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="profile2" class="tab-pane fade" role="tabpanel">
                            <form class="form-horizontal" role="form" id="view_data_poli">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="cari_pasien_poli" placeholder="Cari..." value="">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tabel_pasien_poli" class="table table-hover table-bordered">
                                                <thead>
                                                    <tr class="merah">
                                                        <th style="color:#fff; text-align:center;">No</th>
                                                        <th style="color:#fff; text-align:center;">Tgl / Waktu</th>
                                                        <th style="color:#fff; text-align:center;">No. RM</th>
                                                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                                        <th style="color:#fff; text-align:center;">Jenis Laborat</th>
                                                        <th style="color:#fff; text-align:center;">Tarif</th>
                                                        <th style="color:#fff; text-align:center;">Detail</th>
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
                                        <div id="tablePagingPoli"> </div>
                                    </div>
                                    <div class="col-md-2">
                                        <h4 class="header-title">Total Pasien : <b id="total_pasien_poli"></b></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-9">
                                        
                                    </div>
                                    <label class="col-md-2 control-label">Jumlah Tampil</label>
                                    <div class="col-md-1 pull-right">
                                        <select class="form-control" id="jumlah_tampil_poli">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <form class="form-horizontal" role="form" id="view_data_poli_det">
                                <input type="hidden" id="id_rj_det" name="id_rj_det" value="">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="no_rm_poli">No. RM</label>
                                        <input type="text" id="no_rm_poli" class="form-control" value="" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="no_rm_poli">Nama Pasien</label>
                                        <input type="text" id="nama_pasien_poli" class="form-control" value="" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="no_rm_poli">Jenis Laborat</label>
                                        <input type="text" id="jenis_lab_poli" class="form-control" value="" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tabel_pasien_poli_det" class="table table-hover table-bordered">
                                                <thead>
                                                    <tr class="biru">
                                                        <th style="color:#fff; text-align:center;">No</th>
                                                        <th style="color:#fff; text-align:center;">Nama Pemeriksaan</th>
                                                        <th style="color:#fff; text-align:center;">Hasil</th>
                                                        <th style="color:#fff; text-align:center;">Nilai Rujukan</th>
                                                        <th style="color:#fff; text-align:center;">Biaya</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <center>
                                            <button type="button" class="btn btn-primary" id="btn_simpan_poli">Simpan</button>
                                            <button type="button" class="btn btn-danger" id="btn_batal_poli">Batal</button>
                                        </center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div><!-- end col -->
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

<!-- //LOAD PASIEN -->
<button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal1" id="popup_pasien" style="display:none;">Standard Modal</button>
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                        <table class="table table-hover table-bordered" id="tabel_pasien_ds">
                            <thead>
                                <tr class="merah_popup">
                                    <th style="text-align:center; color: #fff;" width="50">No</th>
                                    <th style="text-align:center; color: #fff;">Kode Pasien</th>
                                    <th style="text-align:center; color: #fff;">Nama Pasien</th>
                                    <th style="text-align:center; color: #fff;">Umur</th>
                                    <th style="text-align:center; color: #fff;">Nama Org Tua</th>
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