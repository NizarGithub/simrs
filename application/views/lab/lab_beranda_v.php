<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<?php
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$user = $this->master_model_m->get_user_info($id_user);
$level = $user->LEVEL;
?>
<script type="text/javascript">
var ajax = '';

$(document).ready(function(){
    data_pasien();

    get_barcode();

    $('#jumlah_tampil').change(function(){
        data_pasien();
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

function data_pasien(){
    $('#popup_load').show();
    var keyword = $('#cari_pasien').val();
    var urutkan = $("input[name='urutkan']:checked").val();
    var pilih_umur = $('#pilih_umur').val();
    var pilih_status = $('#pilih_status').val();

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>lab/lab_home_c/data_pasien_terima',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            pilih_umur:pilih_umur,
            pilih_status:pilih_status
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

                    var detail = '<button type="button" id="btn_history" onclick="detail_pasien('+result[i].ID+');" class="btn btn-danger waves-effect waves-light btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">'+
                                    '<i class="fa fa-eye"></i>';
                                 '</button>';
                    var aksi =  '<a href="<?php echo base_url(); ?>lab/lab_home_c/tindakan/'+result[i].ID_RJ+'" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-user-md"></i></a>';

                    $tr +=  '<tr>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].TANGGAL_DAFTAR+' - '+result[i].WAKTU_DAFTAR+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].ALAMAT+'</td>'+
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
                    </ul>
                    <div class="tab-content">
                        <div id="home1" class="tab-pane fade in active" role="tabpanel">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <div class="col-md-4">
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