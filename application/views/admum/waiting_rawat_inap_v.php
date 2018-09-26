<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<style type="text/css">
#tombol_reset2 {
    display: none;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
    get_data_pasien_poli();

    setInterval(function () {
        get_data_pasien_poli();
    }, 5000);
});

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

                    res[i].JENIS_KELAMIN = res[i].JENIS_KELAMIN=='L'?"Laki - Laki":"Perempuan";
                    res[i].NAMA_IBU = res[i].NAMA_IBU==null?"-":res[i].NAMA_IBU;

                    var aksi = '<button type="button" class="btn btn-icon waves-effect waves-light btn-primary btn-sm"> <i class="fa fa-thumbs-o-up"></i> Proses</button>';

                    $tr +=  '<tr>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].TANGGAL_MASUK+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].KODE_PASIEN+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+res[i].TANGGAL_LAHIR+'</td>'+
                            '   <td style="vertical-align:middle; text-align:center;">'+umur+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_AYAH+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_IBU+'</td>'+
                            '   <td style="vertical-align:middle;">'+res[i].NAMA_POLI+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+aksi+'</td>'+
                            '</tr>';
                }
            }

            $('#tabel_pasien2 tbody').html($tr);
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
    <div class="col-lg-12" id="view_data">
        <div class="card-box">
        	<form class="form-horizontal" role="form" action="" target="_blank" method="post">
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
                            <div class="scroll-x">
                                <table id="tabel_pasien2" class="table table-bordered">
                                    <thead>
                                        <tr class="hijau">
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