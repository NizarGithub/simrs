<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset{
    display: none;
}    
</style>

<script type="text/javascript">
$(document).ready(function(){
    get_data_rm();

    $('#jumlah_tampil').change(function(){
        get_data_rm();
    });

    $('#tombol_cari').click(function(){
        get_data_rm();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_pasien').val("");
        get_data_rm();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });

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

});

function pagingData($selector){
    var jumlah_tampil = $('#jumlah_tampil').val();

    if(typeof $selector == 'undefined')
    {
        $selector = $("#tabel_pasien_rm tbody tr"); 
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

function get_data_rm(){
    $('#popup_load').show();
    var keyword = $('#cari_pasien').val();

    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_home_c/get_data_rm',
        data : {keyword:keyword},
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td colspan='6' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++;

                    var aksi = '';

                    result[i].WAKTU = result[i].WAKTU==null?"00:00":result[i].WAKTU;
                    result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";

                    if(result[i].STS_APPROVE_RM == '0'){
                        aksi = '<button type="button" class="btn btn-primary waves-effect btn-sm" onclick="klik_approve('+result[i].ID+','+result[i].STS+');">Approve</button>';
                    }else{
                        aksi = '<span class="label label-success"><i class="fa fa-check"></i> Sudah Approve</span>';
                    }

                    $tr +=  '<tr>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].TANGGAL+' - '+result[i].WAKTU+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle;">'+result[i].NAMA_POLI+'</td>'+
                            '   <td align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien_rm tbody').html($tr);
            pagingData();
            $('#total_pasien').html(formatNumber(result.length));
            $('#popup_load').fadeOut();
        }
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        get_data_rm();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function klik_approve(id,sts){
    $.ajax({
        url : '<?php echo base_url(); ?>rekam_medik/rk_home_c/klik_approve',
        data : {
            id:id,
            sts:sts
        },
        type : "POST",
        dataType : "json",
        success : function(res){
            toastr["success"]("Rekam Medik Telah Diterima!", "Berhasil");
            get_data_rm();
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">Daftar Pasien</h4>
            <br>
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" class="form-control" name="cari_pasien" id="cari_pasien" placeholder="Cari pasien..." value="" onkeypress="return onEnterText(event);">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-default" id="tombol_cari">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset" data-original-title="Reset Pencarian" title="" data-placement="top" data-toggle="tooltip">
                                    <i class="fa fa-refresh"></i>
                                </button>
                            </span>
                        </div>
                        <span class="help-block"><small><i>*ketikkan Nomor RM / Nama Pasien untuk pencarian data, lalu tekan Enter</i></small></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="tabel_pasien_rm">
                                <thead>
                                    <tr class="biru_popup">
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">No</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">No. RM</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">Tgl / Waktu</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">Nama Pasien</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">Jenis Kelamin</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">Poli Tujuan</th>
                                        <th style="color:#fff; text-align:center; vertical-align: middle;">Aksi</th>
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
                        <h4 class="header-title pull-right">Total Pasien : <b id="total_pasien"></b></h4>
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