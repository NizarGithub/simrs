<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset2,
#view_asuransi,
#view_proses {
    display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
    <?php if($this->session->flashdata('sukses')){?>
        notif_simpan();
    <?php } ?>

    get_data_pasien_poli();

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

    setInterval(function () {
        get_data_pasien_poli();
        toastr["success"]("Sinkronisasi data...");
    }, 20000);

    $('#batal').click(function(){
        window.location = "<?php echo base_url(); ?>admum/waiting_rawat_inap_c";
    });

    $('#jumlah_tampil2').change(function(){
        get_data_pasien_poli();
    });

    $('.btn_ruangan').click(function(){
        $('#popup_ruangan').click();
        load_ruangan();
    });

    $('.btn_bed').click(function(){
        $('#popup_bed').click();
        load_bed();
    });

    $("input[name='sistem_bayar']").click(function(){
        var rd = $("input[name='sistem_bayar']:checked").val();
        if(rd == '1'){
            $('#view_asuransi').hide();
        }else{
            $('#view_asuransi').show();
        }
    });
});

function get_biaya_adm(){
    var sistem_bayar = 'Admin Ranap';

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/get_biaya_adm',
        data : {sistem_bayar:sistem_bayar},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#biaya_adm').val(formatNumber(row['BIAYA']));
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

function get_data_pasien_poli() {
    var keyword = $('#cari_pasien2').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/get_data_pasien_poli',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ""){
                $tr = '';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;
                    var umur = res[i].UMUR+' Tahun '+res[i].UMUR_BULAN+' Bulan';

                    res[i].TANGGAL_MRS = res[i].TANGGAL_MRS==null?"-":res[i].TANGGAL_MRS;
                    res[i].JENIS_KELAMIN = res[i].JENIS_KELAMIN=='L'?"Laki - Laki":"Perempuan";
                    res[i].NAMA_IBU = res[i].NAMA_IBU==null?"-":res[i].NAMA_IBU;
                    res[i].KODE_SURAT_PENGANTAR_RI = res[i].KODE_SURAT_PENGANTAR_RI==null?"-":res[i].KODE_SURAT_PENGANTAR_RI;

                    var aksi = '';

                    if(res[i].STS_WAITING == '1'){
                        aksi = '<button type="button" class="btn btn-icon btn-success btn-sm" style="cursor:default;"><i class="fa fa-thumbs-o-up"></i> Sudah Proses</button>';
                    }else{
                        aksi = '<button type="button" class="btn btn-icon waves-effect waves-light btn-primary btn-sm" onclick="klik_pasien_poli('+res[i].ID+');"><i class="fa fa-refresh"></i> Proses</button>';
                    }

                    $tr +=  '<tr>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].TANGGAL_MRS+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].TANGGAL_LAHIR+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+umur+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_AYAH+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_IBU+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_POLI+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].KODE_SURAT_PENGANTAR_RI+'</td>'+
                            '   <td align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien2 tbody').html($tr);
            $('#total_pasien2').html(res.length);
            paging();
        }
    });

    $('#tombol_cari2').click(function(){
        get_data_pasien_poli();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
    });

    $('#tombol_reset2').click(function(){
        $('#cari_pasien2').val("");
        get_data_pasien_poli();
        $('#tombol_reset2').hide();
        $('#tombol_cari2').show();
    });
}

function onEnterText2(e){
    if (e.keyCode == 13) {
        get_data_pasien_poli();
        $('#tombol_reset2').show();
        $('#tombol_cari2').hide();
        return false;
    }
}

function klik_pasien_poli(id){
    $('#view_data').hide();
    $('#view_proses').show();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/klik_pasien_poli',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            row['JENIS_KELAMIN'] = row['JENIS_KELAMIN']=='L'?'Laki - Laki':'Perempuan';
            row['NAMA_IBU'] = row['NAMA_IBU']==null?'-':row['NAMA_IBU'];

            $('#id_ri').val(id);
            $('#id_pasien').val(row['ID_PASIEN']);
            $('#kode_pasien').val(row['KODE_PASIEN']);
            $('#tanggal_periksa').val(row['TANGGAL_MASUK']);
            $('#nama').val(row['NAMA']);
            $('#jenis_kelamin').val(row['JENIS_KELAMIN']);
            $('#tanggal_lahir').val(row['TANGGAL_LAHIR']);
            $('#umur').val(row['UMUR']);
            $('#umur_bulan').val(row['UMUR_BULAN']);
            $('#nama_ayah').val(row['NAMA_AYAH']);
            $('#nama_ibu').val(row['NAMA_IBU']);
            $('#kode_pengantar').val(row['KODE_SURAT_PENGANTAR_RI']);

            var pjawab = '';
            if(row['NAMA'] != null || row['NAMA'] != ""){
                pjawab = row['NAMA'];
            }else if(row['NAMA_AYAH'] != null || row['NAMA_AYAH'] != ""){
                pjawab = row['NAMA_AYAH'];
            }else if(row['NAMA_IBU'] != null || row['NAMA_IBU'] != ""){
                pjawab = row['NAMA_IBU'];
            }

            $('#nama_pjawab').val(pjawab);
            $('#telepon').val(row['TELEPON']);
            $('#nama_pjawab').focus();
            get_biaya_adm();
            $('#btn_simpan').removeAttr('disabled');
        }
    });
}

function load_ruangan(){
    $('.loading_tabel_kmr').show();
    var kelas = $('#kelas_kamar').val();
    var keyword = $('#cari_kamar').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/load_kamar',
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

                    var delapan = new Date('<?php echo date('d/m/Y'); ?> 08:00:00').toLocaleTimeString();
                    var duabelas = new Date('<?php echo date('d/m/Y'); ?> 11:59:00').toLocaleTimeString();
                    var now = new Date().toLocaleTimeString();

                    var cash = 0;
                    var biaya_kamar = result[i].BIAYA;

                    if((parseInt(now) >= parseInt(delapan)) && (parseInt(now) <= parseInt(duabelas))){
                        cash = (15 * parseFloat(biaya_kamar)) / 100;
                    }else{
                        cash = cash;
                    }

                    var klik = '';
                    if(result[i].STATUS_PENUH == '0'){
                        klik = "style='cursor:pointer;' onclick='klik_ruangan("+result[i].ID+","+cash+");'";
                    }else{
                        klik = "class='active'";
                    }

                    $tr += "<tr "+klik+">"+
                                "<td style='text-align:center;'>"+no+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KODE_KAMAR+"</td>"+
                                "<td style='text-align:center;'>"+result[i].KELAS+"</td>"+
                                "<td style='text-align:right;'>"+formatNumber(biaya_kamar)+"</td>"+
                                "<td style='text-align:center;'>"+result[i].VISITE_DOKTER+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_kamar tbody').html($tr);
            $('.loading_tabel_kmr').hide();
        }
    });

    $('#cari_kamar').off('keyup').keyup(function(){
        load_ruangan();
    });
}

function klik_ruangan(id,cash){
    $('#tutup_kamar').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/klik_kamar',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ruangan').val(id);
            var txt = row['KODE_KAMAR']+' - '+row['KELAS']+' - '+row['VISITE_DOKTER'];
            $('#ruang_tujuan').val(txt);
            $('#biaya').val(formatNumber(row['BIAYA']));
            $('#biaya_charge_kamar').val(formatNumber(cash));
        }
    });
}

function load_bed(){
    $('.loading_tabel_bed').show();
    var id_kamar = $('#id_ruangan').val();
    var keyword = $('#cari_bed').val();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/load_bed',
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
            $('.loading_tabel_bed').hide();
        }
    });

    $('#cari_bed').off('keyup').keyup(function(){
        load_bed();
    });
}

function klik_bed(id){
    $('#tutup_bed').click();

    $.ajax({
        url : '<?php echo base_url(); ?>admum/waiting_rawat_inap_c/klik_bed',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_bed').val(id);
            $('#bed').val(row['NOMOR_BED']);
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row" id="view_data">
    <div class="col-lg-12">
        <div class="card-box">
        	<form class="form-horizontal" role="form" action="" method="post">
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
                            <div class="scroll-xy">
                                <table id="tabel_pasien2" class="table table-bordered">
                                    <thead>
                                        <tr class="merah">
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">No</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Tanggal MRS</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">No.RM</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Pasien</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Jenis Kelamin</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Tanggal Lahir</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Umur</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Ayah</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Nama Ibu</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Dari Poli</th>
                                            <th style="color:#fff; text-align:center; white-space: nowrap;">Kode Pengantar</th>
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
            </form>
        </div>
    </div>
</div>

<div class="row" id="view_proses">
    <div class="col-sm-12">
        <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>admum/waiting_rawat_inap_c/ubah" method="post">
            <input type="hidden" name="id_ri" id="id_ri" value="">
            <input type="hidden" name="id_pasien" id="id_pasien" value="">
            <div class="card-box">
                <h4 class="header-title m-t-0 m-b-30">Detail Pasien</h4>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. RM</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" id="kode_pasien" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Periksa</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="tanggal_periksa" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Pasien</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nama" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Jenis Kelamin</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="jenis_kelamin" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal Lahir</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="tanggal_lahir" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Umur</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="umur" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Tahun</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="umur_bulan" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning" style="cursor:default;">Bulan</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Ayah</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nama_ayah" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama Ibu</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="nama_ibu" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fa fa-bed"></i>&nbsp;<strong>Pemilihan Kamar Rawat Inap</strong>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kode Pengantar</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="kode_pengantar" id="kode_pengantar" value="" readonly>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" data-original-title="Kode Pengantar berisi sesuai dengan Kode Surat Pengantar Rawat Inap" title="" data-placement="right" data-toggle="tooltip">
                                            <i class="fa fa-info"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tanggal MRS</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="tanggal_mrs" id="tanggal_mrs" value="<?php echo date('d-m-Y'); ?>" readonly>
                            </div>
                        </div>
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
                                    <option value="NEO">Ruang Neo</option>
                                    <option value="Ruang Isolasi">Ruang Isolasi</option>
                                    <option value="UGD">UGD</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Kamar</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="id_ruangan" id="id_ruangan" value="">
                                    <input type="hidden" name="biaya_charge_kamar" id="biaya_charge_kamar" value="">
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
                        <div class="form-group">
                            <label class="col-md-3 control-label">Biaya Adm</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="biaya_adm" id="biaya_adm" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pembayaran</label>
                            <div class="col-md-9">
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio1" value="1" name="sistem_bayar">
                                    <label for="inlineRadio1"> Umum </label>
                                </div>
                                <div class="radio radio-success radio-inline">
                                    <input type="radio" id="inlineRadio2" value="2" name="sistem_bayar">
                                    <label for="inlineRadio2"> Asuransi </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" id="view_asuransi">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-9">
                                <h4 class="header-title">Kerjasama Asuransi</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Asuransi</label>
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
                            <label class="col-md-3 control-label">No. Polis</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nomor_polis" id="nomor_polis" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">No. Peserta</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nomor_peserta" id="nomor_peserta" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama" id="nama" value="">
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
                    </div>
                </div>
                <hr>
                <center>
                    <button type="submit" class="btn btn-success m-b-5" id="btn_simpan" disabled="disabled"><i class="fa fa-check"></i> <span><b>Simpan</b></span></button>
                    <button type="button" class="btn btn-danger m-b-5" id="batal"><i class="fa fa-times"></i> <span><b>Batal</b></span></button>
                </center>
            </div>
        </form>
    </div>
</div>

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
                <div class="loading_tabel_kmr">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
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
                <div class="loading_tabel_bed">
                    <img src="<?php echo base_url(); ?>picture/processando.gif" style="width: 90px; height: 90px;">
                </div>
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