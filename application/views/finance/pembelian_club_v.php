<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<style type="text/css">
#view_ubah{
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

    get_history();

    $('#batal').click(function(){
        window.location = "<?php echo base_url(); ?>finance/pembelian_club_c";
    });

    $('#batal_ubah').click(function(){
        $('#id_ubah').val("");
        $('#view_data').show();
        $('#view_ubah').hide();
    });

    $('#li_input').click(function(){
        get_kode_pb();
    });

    $('.btn_barang').click(function(){
        $('#popup_barang').click();
        get_barang();
    });

    $('#btn_proses').click(function(){
        get_history();
    });

});

function get_kode_pb(){
    $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/get_kode_pb',
        type : "POST",
        dataType : "json",
        success : function(kode){
            $('#kode_pembelian').val(kode); 
        }
    });
}

function get_history(){
    $('#popup_load').show();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();

    $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/data_pembelian',
        data : {
            bulan:bulan,
            tahun:tahun
        },
        type : "POST",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ""){
                $tr = '<tr><td colspan="8" style="text-align:center;"><b>Data Tidak Ada</b></td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    var aksi =  '<button type="button" class="btn btn-success waves-effect waves-light btn-sm" onclick="ubah_history('+res[i].ID+');">'+
                                    '<i class="fa fa-pencil"></i>'+
                                '</button>&nbsp;'+
                                '<button type="button" class="btn btn-danger waves-effect waves-light btn-sm" onclick="hapus_history('+res[i].ID+');">'+
                                    '<i class="fa fa-trash"></i>'+
                                '</button>';

                    $tr += '<tr>'+
                                '<td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                                '<td style="vertical-align:middle; text-align:center;">'+res[i].KODE_PEMBELIAN+'</td>'+
                                '<td style="vertical-align:middle; text-align:center;">'+res[i].TANGGAL+'</td>'+
                                '<td style="vertical-align:middle;">'+res[i].NAMA_ALAT+'</td>'+
                                '<td style="vertical-align:middle; text-align:right;">'+formatNumber(res[i].HARGA)+'</td>'+
                                '<td style="vertical-align:middle; text-align:center;">'+res[i].JUMLAH+'</td>'+
                                '<td style="vertical-align:middle; text-align:right;">'+formatNumber(res[i].TOTAL)+'</td>'+
                                '<td align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_history tbody').html($tr);
            $('#popup_load').hide();
        }
    });
}

function ubah_history(id){
    $('#view_ubah').show();
    $('#view_data').hide();

    $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/data_pembelian_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_ubah').val(id);
            $('#kode_pembelian_ubah').val(row['KODE_PEMBELIAN']);
            $('#tanggal_ubah').val(row['TANGGAL']);
            $('#kode_barang_ubah').val(row['KODE_ALAT']);
            $('#nama_barang_ubah').val(row['NAMA_ALAT']);
            $('#harga_ubah').val(formatNumber(row['HARGA']));
            $('#jumlah_ubah').val(row['JUMLAH']);
            $('#total_harga_ubah').val(formatNumber(row['TOTAL']));
        }
    });
}

function hapus_history(id){
    $('#popup_hps').click();

    $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/data_pembelian_id',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_hapus').val(id);
            var kode = row['KODE_PEMBELIAN'];
            $('#msg').html('Apakah kode pembelian <b>'+kode+'</b> ini ingin dihapus?');
        }
    });
}

function paging($selector){
    var jumlah_tampil = 10;

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_barang tbody tr");
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

function get_barang(){
    var keyword = $('#cari_barang').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/data_barang',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(res){
            $tr = '';

            if(res == null || res == ""){
                $tr = '<tr><td colspan="4" style="text-align:center;"><b>Data Tidak Ada</b></td></tr>';
            }else{
                var no = 0;

                for(var i=0; i<res.length; i++){
                    no++;

                    $tr += '<tr style="cursor:pointer;" onclick="klik_barang('+res[i].ID+');">'+
                                '<td style="text-align:center;">'+no+'</td>'+
                                '<td style="text-align:center;">'+res[i].KODE_ALAT+'</td>'+
                                '<td>'+res[i].NAMA_ALAT+'</td>'+
                                '<td style="text-align:right;">'+formatNumber(res[i].HARGA_BELI)+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_barang tbody').html($tr);
            paging();
        }
    });

    $('#cari_barang').off('keyup').keyup(function(){
        get_barang();
    });
}

function klik_barang(id){
    $('#tutup_barang').click();

     $.ajax({
        url : '<?php echo base_url(); ?>finance/pembelian_club_c/klik_barang',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(row){
            $('#id_barang_gudang').val(id); 
            $('#kode_barang').val(row['KODE_ALAT']); 
            $('#nama_barang').val(row['NAMA_ALAT']); 
            $('#harga').val(formatNumber(row['HARGA_BELI'])); 
        }
    });
}

function hitung_total(){
    var id_ubah = $('#id_ubah').val();

    if(id_ubah == ""){
        var harga = $('#harga').val();
        var jumlah = $('#jumlah').val();

        harga = harga.split(',').join('');
        jumlah = jumlah.split(',').join('');

        if(harga == ""){
            harga = 0;
        }

        if(jumlah == ""){
            jumlah = 0;
        }

        var total = parseFloat(harga) * parseFloat(jumlah);
        $('#total_harga').val(formatNumber(total));
    }else{
        var harga = $('#harga_ubah').val();
        var jumlah = $('#jumlah_ubah').val();

        harga = harga.split(',').join('');
        jumlah = jumlah.split(',').join('');

        if(harga == ""){
            harga = 0;
        }

        if(jumlah == ""){
            jumlah = 0;
        }

        var total = parseFloat(harga) * parseFloat(jumlah);
        $('#total_harga_ubah').val(formatNumber(total));
    }
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-lg-12" id="view_data">
    	<div class="card-box">
            <ul class="nav nav-tabs">
                <li class="active" role="presentation">
                    <a data-toggle="tab" role="tab" href="#history_pemebelian1"><i class="fa fa-file-text"></i> History Pembelian</a>
                </li>
                <li role="presentation" id="li_input">
                    <a data-toggle="tab" role="tab" href="#input_pembelian1"><i class="fa fa-plus"></i> Input Pembelian</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="history_pemebelian1" class="tab-pane fade in active" role="tabpanel">
                    <div class="row">
                        <form role="form" action="<?php echo base_url(); ?>finance/pembelian_club_c/cetak" method="post" target="_blank">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label for="bulan">Bulan</label>
                                    <select class="form-control" name="bulan" id="bulan">
                                    <?php
                                        $bulan = array(
                                            0 => "",
                                            1 => "Januari",
                                            2 => "Februari",
                                            3 => "Maret",
                                            4 => "April",
                                            5 => "Mei",
                                            6 => "Juni",
                                            7 => "Juli",
                                            8 => "Agustus",
                                            9 => "September",
                                            10 => "Oktober",
                                            11 => "November",
                                            12 => "Desember"
                                        );
                                        $now = date('n');
                                        $selected = "";

                                        for ($i=0; $i < count($bulan); $i++) { 
                                            if($i == $now){
                                                $selected = "selected='selected'";
                                            }else{
                                                $selected = "";
                                            }
                                    ?>
                                        <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $bulan[$i]; ?></option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="tahun">Tahun</label>
                                    <select class="form-control" name="tahun" id="tahun">
                                    <?php
                                        $tahun = date('Y');
                                        $sel = "";
                                        for($i=$tahun-5; $i<$tahun+1; $i++){
                                            if($i == $tahun){
                                                $sel = "selected='selected'";
                                            }else{
                                                $sel = "";
                                            }
                                    ?>
                                        <option <?php echo $sel; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="proses">&nbsp;</label><br>
                                    <button class="btn btn-warning waves-effect w-md waves-light" id="btn_proses" type="button">Proses</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 m-t-15">
                                    <div class="table-responsive">
                                        <table id="tabel_history" class="table table-bordered">
                                            <thead>
                                                <tr class="biru">
                                                    <th style="color:#fff; text-align:center;" width="50">No</th>
                                                    <th style="color:#fff; text-align:center;">Kode Pembelian</th>
                                                    <th style="color:#fff; text-align:center;">Tanggal</th>
                                                    <th style="color:#fff; text-align:center;">Nama Barang</th>
                                                    <th style="color:#fff; text-align:center;">Harga</th>
                                                    <th style="color:#fff; text-align:center;">Jumlah</th>
                                                    <th style="color:#fff; text-align:center;">Total</th>
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
                                <label class="col-md-1 control-label">Jenis Laporan</label>
                                <div class="col-md-4">
                                    <div class="radio radio-inline radio-success">
                                        <input type="radio" id="inlineRadio1" value="excel" name="jenis_laporan">
                                        <label for="inlineRadio1"> Excel </label>
                                    </div>
                                    <div class="radio radio-inline radio-success">
                                        <input type="radio" id="inlineRadio2" value="pdf" name="jenis_laporan">
                                        <label for="inlineRadio2"> Pdf </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 m-t-10">
                                    <button class="btn btn-danger waves-effect w-md waves-light" type="submit"><i class="fa fa-print"></i> Cetak</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="input_pembelian1" class="tab-pane fade" role="tabpanel">
                    <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/pembelian_club_c/simpan" method="post">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Kode Pembelian</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="kode_pembelian" id="kode_pembelian" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Tanggal</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="tanggal" id="tanggal" value="<?php echo date('d-m-Y'); ?>" readonly>
                                        <span class="input-group-btn">
                                            <button class="btn waves-effect waves-light btn-primary" type="button" onclick="javascript:NewCssCal('tanggal');">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Kode Barang</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="hidden" name="id_barang_gudang" id="id_barang_gudang" value="">
                                        <input type="text" class="form-control" id="kode_barang" value="" required="required" readonly>
                                        <span class="input-group-btn">
                                            <button class="btn waves-effect waves-light btn-success btn_barang" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Nama Barang</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nama_barang" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Harga</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="harga" id="harga" value="" required="required" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Jumlah</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="jumlah" id="jumlah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Total Harga</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" class="form-control" name="total_harga" id="total_harga" value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2 control-label">&nbsp;</label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success waves-effect waves-light m-b-5"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                                    <button type="button" class="btn btn-danger waves-effect waves-light m-b-5" id="batal"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12" id="view_ubah">
        <div class="card-box">
            <form class="form-horizontal" role="form" action="<?php echo base_url(); ?>finance/pembelian_club_c/ubah" method="post">
                <input type="hidden" name="id_ubah" id="id_ubah" value="">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <h4 class="header-title">Ubah Pembelian</h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Pembelian</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kode_pembelian_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Tanggal</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="tanggal_ubah" id="tanggal_ubah" value="" readonly>
                                <span class="input-group-btn">
                                    <button class="btn waves-effect waves-light btn-primary" type="button" onclick="javascript:NewCssCal('tanggal_ubah');">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Kode Barang</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="kode_barang_ubah" value="" required="required" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Nama Barang</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="nama_barang_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Harga</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="harga_ubah" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Jumlah</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="jumlah_ubah" id="jumlah_ubah" value="" required="required" onkeyup="FormatCurrency(this); hitung_total();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Total Harga</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                <input type="text" class="form-control" name="total_harga_ubah" id="total_harga_ubah" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-md-2 control-label">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success waves-effect waves-light"> <i class="fa fa-save"></i> <span>Simpan</span> </button>
                            <button type="button" class="btn btn-danger waves-effect waves-light" id="batal_ubah"> <i class="fa fa-times"></i> <span>Batal</span> </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<button id="popup_barang" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModal" style="display:none;">Standard Modal</button>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Barang</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_barang" placeholder="Cari..." value="">
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
                    <table class="table table-hover" id="tabel_barang">
                        <thead>
                            <tr class="merah_popup">
                                <th style="text-align:center; color: #fff;" width="50">No</th>
                                <th style="text-align:center; color: #fff;">Kode Barang</th>
                                <th style="text-align:center; color: #fff;">Nama Barang</th>
                                <th style="text-align:center; color: #fff;">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <div id="tablePaging"> </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" id="tutup_barang">Tutup</button>
            </div>
        </div>
    </div>
</div>

<button id="popup_hps" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#custom-width-modal" style="display:none;">Custom width Modal</button>
<div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="custom-width-modalLabel">Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p id="msg"></p>
            </div>
            <div class="modal-footer">
                <form action="<?php echo base_url(); ?>finance/pembelian_aqua_c/hapus" method="post">
                    <input type="hidden" name="id_hapus" id="id_hapus" value="">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger waves-effect waves-light">Ya</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->