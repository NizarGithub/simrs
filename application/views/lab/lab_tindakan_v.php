<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#view_laborat_tambah, 
#view_laborat_ubah {
      display: none;
}
</style>

<script type="text/javascript">
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",

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

$(document).ready(function(){
      data_laborat();

      $('#btn_kembali').click(function(){
            window.location = "<?php echo base_url(); ?>lab/lab_home_c";
      });

      $('#btn_tambah_lab').click(function(){
            $('#view_laborat_tambah').show();
            $('#view_laborat').hide();
            $('#view_laborat_ubah').hide();

            $.ajax({
                  url : '<?php echo base_url(); ?>lab/lab_home_c/get_kode_lab',
                  type : "POST",
                  dataType : "json",
                  success : function(kode){
                        $('#kode_lab').val(kode);
                  }
            });
      });

      $('#batalLab').click(function(){
            $('#view_laborat_tambah').hide();
            $('#view_laborat').show();
      });

      $('.btn_jenis_laborat').click(function(){
            $('#popup_laborat').click();
            load_laborat();
      });

      $('.btn_pemeriksaan').click(function(){
            $('#popup_pemeriksaan').click();
            load_pemeriksaan();
      });

      $('#simpanLab').click(function(){
            var id_laborat = $('#id_laborat').val();
            var pemeriksaan = $('#tabel_tambah_pemeriksaan tbody tr').length;
            var total_tarif = $('#total_tarif_pemeriksaan').val();
            var cito = $("input[name='cito']").is(":checked");

            if(id_laborat == ""){
                toastr["error"]("Jenis Laborat harus diisi!", "Notifikasi");
            }else if(pemeriksaan == 0){
                  toastr["error"]("Pemeriksaan harus diisi!", "Notifikasi");
            }else if(total_tarif == "" || total_tarif == "0"){
                  toastr["error"]("Hitung total tarif dengan benar!", "Notifikasi");
            }else if(cito == false){
                  toastr["error"]("Cito belum dipilih!", "Notifikasi");
            }else{
                  $.ajax({
                        url : '<?php echo base_url(); ?>lab/lab_home_c/simpan_pemeriksaan',
                        data : $('#view_laborat_tambah').serialize(),
                        type : "POST",
                        dataType : "json",
                        success : function(result){
                              data_laborat();
                              notif_simpan();
                              $('#id_laborat').val("");
                              $('#jenis_laborat').val("");
                              $('#tabel_tambah_pemeriksaan tbody tr').remove();
                              $('#total_tarif_pemeriksaan').val("");
                              $('#inlineRadio1').removeAttr('checked');
                              $('#inlineRadio2').removeAttr('checked');
                              $('#view_laborat_tambah').hide();
                              $('#view_laborat').show();
                        }
                  });
            }
      });

      $('#ya_lab').click(function(){
            $.ajax({
                  url : '<?php echo base_url(); ?>lab/lab_home_c/hapus_laborat',
                  data : $('#form_hapus_lab').serialize(),
                  type : "POST",
                  dataType : "json",
                  success : function(result){
                        $('#tidak_lab').click();
                        data_laborat();
                        notif_hapus();
                  }
            });
      });
});

function data_laborat(){
      $('#popup_load').show();
      var id = "<?php echo $id; ?>";

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/data_laborat',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(result){
                  var id_pelayanan = "<?php echo $id; ?>";
                  $tr = "";
                  var total = 0;

                  if(result == "" || result == null){
                        $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
                  }else{
                        var no = 0;

                        for(var i=0; i<result.length; i++){
                              no++;

                              total += parseFloat(result[i].TOTAL_TARIF);

                              var aksi =  '<button type="button" class="btn btn-primary waves-effect waves-light btn-sm m-b-5" onclick="hasil_laborat('+id_pelayanan+');">'+
                                                      '<i class="fa fa-tint"></i>'+
                                                '</button>&nbsp;'+
                                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm m-b-5" onclick="hapus_laborat('+id_pelayanan+','+result[i].ID+');">'+
                                                      '<i class="fa fa-trash"></i>'+
                                                '</button>';

                              var cito = "";
                              if(result[i].CITO == '1'){
                                    cito = '<span class="label label-success">Aktif</span>';
                              }else{
                                    cito = '<span class="label label-danger">Tidak Aktif</span>';
                              }

                              var tanggal = formatTanggal(result[i].TANGGAL)+' - '+result[i].WAKTU;
                              var encodedString = Base64.encode(btoa(id_pelayanan));

                              $tr += "<tr>"+
                                                "<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
                                                "<td style='vertical-align:middle;'>"+tanggal+"</td>"+
                                                "<td style='vertical-align:middle;'>"+result[i].JENIS_LABORAT+"</td>"+
                                                "<td style='vertical-align:middle; text-align:center;'>"+cito+"</td>"+
                                                "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TOTAL_TARIF)+"</td>"+
                                                "<td align='center'>"+
                                                      "<a href='<?php echo base_url(); ?>lab/lab_home_c/cetak_laborat/"+encodedString+"' class='btn btn-inverse btn-sm' target='_blank'><i class='fa fa-print'></i></a>"+
                                                "</td>"+
                                                "<td align='center'>"+aksi+"</td>"+
                                          "</tr>";
                        }
                  }

                  $('#tabel_laborat tbody').html($tr);
                  $('#popup_load').fadeOut();
                  $('#grandtotal_laborat').html(formatNumber(total));
            }
      });
}

function hasil_laborat(id){
      $('#popup_hasil_lab').click();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/data_hasil_pemeriksaan',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(result){
                  $tr = "";
                  var total = 0;

                  if(result == "" || result == null){
                        $tr = "<tr><td colspan='4' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
                  }else{
                        var no = 0;

                        for(var i=0; i<result.length; i++){
                              no++;
                              
                              total += parseFloat(result[i].SUBTOTAL);

                              $tr += "<tr>"+
                                                "<td style='vertical-align:middle; text-align:center;'>"+no+"</td>"+
                                                "<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
                                                "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
                                                "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].SUBTOTAL)+"</td>"+
                                          "</tr>";
                        }
                  }

                  $('#tb_hasil_lab tbody').html($tr);
                  $('#total_laborat').html(formatNumber(total));
            }
      });
}

function hapus_laborat(id,idx){
      $('#popup_hapus_lab').click();
      
      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/data_laborat_id',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(row){
                  $('#id_hapus_lab').val(idx);
                  $('#msg_lab').html('Apakah data <b>'+row['JENIS_LABORAT']+'</b> ingin dihapus?');
            }
      });
}

function load_laborat(){
      var keyword = $('#cari_laborat').val();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/load_laborat',
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
            url : '<?php echo base_url(); ?>lab/lab_home_c/klik_laborat',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(row){
                  $('#id_laborat').val(id);
                  $('#jenis_laborat').val(row['JENIS_LABORAT']);
            }
      });
}

function load_pemeriksaan(){
      var keyword = $('#cari_pemeriksaan').val();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/load_pemeriksaan',
            data : {keyword:keyword},
            type : "POST",
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
}

function klik_pemeriksaan(id){
      $('#tutup_pemeriksaan').click();

      $.ajax({
            url : '<?php echo base_url(); ?>lab/lab_home_c/klik_pemeriksaan',
            data : {id:id},
            type : "POST",
            dataType : "json",
            success : function(result){
                  $tr = "";

                  for(var i=0; i<result.length; i++){
                        var jumlah_data = $('#tr2_'+result[i].ID).length;

                        var aksi = "<button type='button' class='btn waves-light btn-danger btn-sm' onclick='deleteRow2(this);'><i class='fa fa-times'></i></button>";

                        if(jumlah_data > 0){
                              var jumlah = $('#jumlah_pemeriksaan_'+result[i].ID).val();
                              $('#jumlah_pemeriksaan_'+result[i].ID).val(parseInt(jumlah)+1);
                        }else{
                              $tr = "<tr id='tr2_"+result[i].ID+"'>"+
                                          "<input type='hidden' name='id_pemeriksaan[]' value='"+result[i].ID+"'>"+
                                          "<input type='hidden' name='tarif_pemeriksaan[]' value='"+result[i].TARIF+"'>"+
                                          "<td style='vertical-align:middle;'>"+result[i].NAMA_PEMERIKSAAN+"</td>"+
                                          "<td align='center'><input type='text' class='form-control' name='hasil_periksa[]' value='' style='width:200px;'></td>"+
                                          "<td align='center'><input type='text' class='form-control' name='nilai_rujukan[]' value='' style='width:200px;'></td>"+
                                          "<td style='vertical-align:middle; text-align:right;'>"+formatNumber(result[i].TARIF)+"</td>"+
                                          "<td style='vertical-align:middle; text-align:right;'><b>"+formatNumber(result[i].TARIF)+"</b></td>"+
                                          "<td align='center'>"+aksi+"</td>"+
                                      "</tr>";
                        }
                  }

                  $('#tabel_tambah_pemeriksaan tbody').append($tr);
                  hitung_pemeriksaan();
            }
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
</script>

<div class="col-lg-12">
	<div class="row">
            <div class="card-box">
                  <h4><i class="fa fa-user"></i> Detail Pasien</h4><br>
                  <div class="row">
      	            <div class="col-md-6">
                        	<table class="table">
                              <?php
                                    $jk = "";
                                    if($dt->JENIS_KELAMIN=="L"){$jk="Laki - Laki";}else{$jk="Perempuan";}
                                    $kerja = "";
                                    if($dt->PEKERJAAN=="" || $dt->PEKERJAAN==null){$kerja="-";}else{$kerja=$dt->PEKERJAAN;}
                              ?>
                        		<tbody>
                        			<tr>
                        				<td>NO. RM</td>
                        				<td>:</td>
                        				<td><span style="color:#0066b2;"><?php echo $dt->KODE_PASIEN; ?></span></td>
                        				<td>JENIS KELAMIN</td>
                        				<td>:</td>
                        				<td>
                                                      <span style="color:#0066b2;"><?php echo $jk; ?></span>
                                                </td>
                        			</tr>
                        			<tr>
                        				<td>NAMA</td>
                        				<td>:</td>
                        				<td><span style="color:#0066b2;"><?php echo $dt->NAMA; ?></span></td>
                        				<td>UMUR</td>
                        				<td>:</td>
                        				<td><span style="color:#0066b2;"><?php echo $dt->UMUR; ?> Tahun</span></td>
                        			</tr>
                        			<tr>
                        				<td>GOL. DARAH</td>
                        				<td>:</td>
                        				<td>
                        					<span style="color:#0066b2;"><?php echo $dt->GOLONGAN_DARAH; ?></span>
                        				</td>
                        				<td>TGL LAHIR</td>
                        				<td>:</td>
                        				<td>
                                                      <span style="color:#0066b2;"><?php echo $dt->TANGGAL_LAHIR; ?></span>
                                                </td>
                        			</tr>
                        		</tbody>
                        	</table>
                        </div>
                        <div class="col-md-6">
                              <table class="table">
                                    <tbody>
                                          <tr>
                                                <td>PENDIDIKAN</td>
                                                <td>:</td>
                                                <td><span style="color:#0066b2;"><?php echo $dt->PENDIDIKAN; ?></span></td>
                                                <td>AGAMA</td>
                                                <td>:</td>
                                                <td><span style="color:#0066b2;"><?php echo $dt->AGAMA; ?></span></td>
                                          </tr>
                                          <tr>
                                                <td>PEKERJAAN</td>
                                                <td>:</td>
                                                <td><span style="color:#0066b2;"><?php echo $kerja; ?></span></td>
                                                <td>TEMPAT LAHIR</td>
                                                <td>:</td>
                                                <td><span style="color:#0066b2;"><?php echo $dt->TEMPAT_LAHIR; ?></span></td>
                                          </tr>
                                          <tr>
                                                <td>ALAMAT</td>
                                                <td>:</td>
                                                <td>
                                                      <span style="color:#0066b2;">
                                                            <?php echo $dt->ALAMAT; ?> Kec. <?php echo $dt->KECAMATAN; ?><br>
                                                            Kel. <?php echo $dt->KELURAHAN; ?> <br>
                                                            Kec. <?php echo $dt->KOTA; ?>
                                                      </span>
                                                </td>
                                                <td>NAMA ORTU</td>
                                                <td>:</td>
                                                <td><span style="color:#0066b2;"><?php echo $dt->NAMA_ORTU; ?></span></td>
                                          </tr>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
	</div>
</div>

<div class="col-lg-12">
      <div class="row">
            <div class="card-box">
                  <div class="row">
                        <ul class="nav nav-tabs">
                            <li role="presentation" class="active" id="dt_laborat">
                                <a href="#laborat1" role="tab" data-toggle="tab"><i class="fa fa-building"></i>&nbsp;Laboraturium</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                              <form class="form-horizontal" id="view_laborat">
                                    <div class="form-group">
                                          <div class="col-md-6">
                                                <h4 class="m-t-0"><i class="fa fa-table"></i>&nbsp;<b>Tabel Laborat</b></h4>
                                          </div>
                                          <div class="col-md-6">
                                          <button class="btn btn-primary m-b-5 pull-right" type="button" id="btn_tambah_lab">
                                                      <i class="fa fa-plus"></i>&nbsp;<b>Tambah Laborat</b>
                                                </button>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                          <div class="col-md-12">
                                          <div class="table-responsive">
                                                <table id="tabel_laborat" class="table table-bordered">
                                                    <thead>
                                                        <tr class="merah">
                                                            <th style="color:#fff; text-align:center;">No</th>
                                                            <th style="color:#fff; text-align:center;">Tanggal</th>
                                                            <th style="color:#fff; text-align:center;">Jenis Laborat</th>
                                                            <th style="color:#fff; text-align:center;">Cito</th>
                                                            <th style="color:#fff; text-align:center;">Total Tarif</th>
                                                            <th style="color:#fff; text-align:center;">Cetak</th>
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
                                          <div class="col-md-8">
                                                &nbsp;
                                          </div>
                                          <div class="col-md-4">
                                                <div class="card-box widget-user" style="background-color:#cee3f8;">
                                              <div>
                                                  <img alt="user" class="img-responsive img-circle" src="<?php echo base_url(); ?>picture/Money_44325.png">
                                                  <div class="wid-u-info">
                                                      <small class="text-primary"><b>Grand Total</b></small>
                                                      <h4 class="m-t-0 m-b-5 font-600 text-danger" id="grandtotal_laborat">0</h4>
                                                  </div>
                                              </div>
                                          </div>
                                          </div>
                                    </div>
                              </form>

                              <form class="form-horizontal" id="view_laborat_tambah" action="" method="post">
                                    <input type="hidden" name="id_rj" id="id_rj" value="<?php echo $id; ?>">
                                    <input type="hidden" name="id_pasien" value="<?php echo $dt->ID; ?>">
                                    <input type="hidden" name="id_poli" value="0">
                                    <input type="hidden" name="id_dokter" value="0">
                                    <h4><i class="fa fa-plus"></i> Tambah Laborat</h4>
                                    <hr>
                                    <div class="form-group">
                                          <label class="col-md-2 control-label">Kode</label>
                                          <div class="col-md-5">
                                                <input type="text" class="form-control" name="kode_lab" id="kode_lab" value="" readonly>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-2 control-label">Jenis Laborat</label>
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
                                  <div class="form-group">
                                      <label class="col-md-2 control-label">Pemeriksaan</label>
                                      <div class="col-md-5">
                                          <div class="input-group">
                                              <input type="text" class="form-control" value="" readonly="readonly" required="required">
                                              <span class="input-group-btn">
                                                  <button type="button" class="btn btn-inverse btn_pemeriksaan"><i class="fa fa-search"></i></button>
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-2 control-label">&nbsp;</label>
                                      <div class="col-md-10">
                                          <div class="table-responsive">
                                                <table id="tabel_tambah_pemeriksaan" class="table table-bordered">
                                                    <thead>
                                                        <tr class="kuning_tr">
                                                            <th style="color:#fff; text-align:center;">Pemeriksaan</th>
                                                            <th style="color:#fff; text-align:center;">Hasil</th>
                                                            <th style="color:#fff; text-align:center;">Nilai Rujukan</th>
                                                            <th style="color:#fff; text-align:center;">Tarif</th>
                                                            <th style="color:#fff; text-align:center;">Sub Total</th>
                                                            <th style="color:#fff; text-align:center;">#</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-2 control-label">Total Tarif</label>
                                      <div class="col-md-5">
                                          <input type="text" class="form-control" name="total_tarif_pemeriksaan" id="total_tarif_pemeriksaan" value="" readonly="readonly">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label">Cito</label>
                                    <div class="col-md-5">
                                          <div class="radio radio-primary radio-inline">
                                              <input type="radio" id="inlineRadio1" value="1" name="cito">
                                              <label for="inlineRadio1"> Aktif </label>
                                          </div>
                                          <div class="radio radio-primary radio-inline">
                                              <input type="radio" id="inlineRadio2" value="0" name="cito">
                                              <label for="inlineRadio2"> Tidak Aktif </label>
                                          </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <center>
                                      <button type="button" class="btn btn-success" id="simpanLab"><i class="fa fa-save"></i> <b>Simpan</b></button>
                                      <button type="button" class="btn btn-danger" id="batalLab"><i class="fa fa-times"></i> <b>Batal</b></button>
                                  </center>
                              </form>
                        </div>
                  </div>
                  <div class="row">
                        <form class="form-horizontal">
                              <div class="form-group">&nbsp;</div>
                              <div class="form-group">
                                    <div class="col-md-4">
                                          <button class="btn btn-purple btn-block m-b-5" type="button" id="btn_kembali">
                                                <i class="fa fa-arrow-circle-left"></i>&nbsp;<b>Kembali</b>
                                          </button>   
                                    </div>
                              </div>
                        </form>
                  </div>
            </div>
      </div>
</div>

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_laborat" id="popup_laborat" style="display:none;">Standard Modal</button>
<div id="myModal1_laborat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
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

<button class="btn btn-primary" data-toggle="modal" data-target="#myModal1_hasil_lab" id="popup_hasil_lab" style="display:none;">Standard Modal</button>
<div id="myModal1_hasil_lab" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Hasil Laborat</h4>
            </div>
            <div class="modal-body">
                  <div class="table-responsive">
                        <div class="scroll-y">
                            <table class="table table-bordered" id="tb_hasil_lab">
                                <thead>
                                    <tr class="hijau_popup">
                                        <th style="text-align:center; color: #fff;" width="50">No</th>
                                        <th style="text-align:center; color: #fff;">Pemeriksaan</th>
                                        <th style="text-align:center; color: #fff;">Tarif</th>
                                        <th style="text-align:center; color: #fff;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr class="active">
                                          <td colspan="2">&nbsp;</td>
                                          <td style="text-align:right;"><b>TOTAL :</b></td>
                                          <td style="text-align:right;"><b id="total_laborat" class="text-danger"></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_hasil_lab">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<button id="popup_hapus_lab" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal2" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg_lab"></p>
            </div>
            <div class="modal-footer">
                  <form action="" method="post" id="form_hapus_lab">
                        <input type="hidden" name="id_hapus_lab" id="id_hapus_lab" value="">
                        <input type="hidden" name="id_pelayanan_lab" value="<?php echo $id; ?>">
                        <input type="hidden" name="ket_hapus_lab" id="ket_hapus_lab" value="">
                      <button type="button" class="btn btn-default waves-effect" data-dismiss="modal" id="tidak_lab">Tidak</button>
                      <button type="button" class="btn btn-danger waves-effect waves-light" id="ya_lab">Ya</button>
                  </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->