<?PHP if($warning == 1){ ?>
<div class="alert alert-danger alert-dismissable" style="color: #b96463; font-size: 15px;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    Maaf, pengulangan password tidak cocok. Mohon ulangi password dengan benar.
</div>
<?PHP } ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>" onsubmit="return cek_username_submit();">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5; margin-top: 10px;"> Nama Pegawai </label>
                            <div class="col-md-6">
                                <div class="input-group m-t-10">
                                    <input id="nama_pegawai" name="nama_pegawai" class="form-control" type="text" readonly value="">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_pegawai();">Cari Pegawai</button>
                                    </span>
                                </div>
                                <input name="id_pegawai" id="id_pegawai" class="form-control" value="" type="hidden">
                                <input name="sts_pass_awal" id="sts_pass_awal" class="form-control" value="0" type="hidden">
                                <input name="sts_pass_edit" id="sts_pass_edit" class="form-control" value="0" type="hidden">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5;"> Status Akun </label>
                            <div class="col-md-6">
                                <div class="radio radio-primary radio-inline">
                                    <input type="radio" id="inlineRadio1" value="1" name="status" checked>
                                    <label for="inlineRadio1"> Aktif </label>
                                </div>
                                <div class="radio radio-danger radio-inline">
                                    <input type="radio" id="inlineRadio2" value="0" name="status">
                                    <label for="inlineRadio2"> Tidak Aktif </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Foto </label>
                            <div class="col-md-6" id="foto_head">
                                <img width="120" src="<?=base_url();?>files/foto_pegawai/default_pics_of_rs_jt.png">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Username </label>
                            <div class="col-md-6">
                                <input name="username" id="username" required class="form-control" value="" type="text" onkeyup="cek_username();">
                                <span class="help-block" id="warning_username" style="display:none;">
                                    <small style="color: red; font-weight: bold; font-size: 13px;"> 
                                        <i class="fa fa-warning"></i> Perhatian!! Username tersebut telah terpakai oleh pegawai lain. 
                                    </small>
                                </span>
                            </div>
                        </div>

                        <div class="form-group pass_gk_ada">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Password </label>
                            <div class="col-md-6">
                                <input name="pass1" id="pass1" required class="form-control" value="" type="password">
                            </div>
                        </div>

                        <div class="form-group pass_gk_ada">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Ulangi Password </label>
                            <div class="col-md-6">
                                <input name="pass2" id="pass2" required class="form-control" value="" type="password">
                            </div>
                        </div>

                        <div class="form-group pass_ada" style="display:none;">
                        <label class="col-md-2 control-label" style="color: #0099e5;"> Password </label>
                            <div class="col-md-6">
                                <a style="margin-top: 5px; position: absolute;" id="link_ganti" href="javascript:;" onclick="$('#link_ganti').hide(); $('.pass_ubah').show(); $('#batal_link_ganti').show(); $('#sts_pass_edit').val(1); cek_req(1);"> Ganti Password </a>
                                <input name="new_pass1" id="new_pass1" class="form-control pass_ubah" value="" type="password" style="display:none; margin-top: 13px;" placeholder="Masukkan Password Baru">
                                <input name="new_pass2" id="new_pass2" class="form-control pass_ubah" value="" type="password" style="display:none; margin-top: 13px;" placeholder="Ulangi Password Baru">

                                <a style="display:none; margin-top: 5px; position: absolute;" id="batal_link_ganti" href="javascript:;" onclick="$('#batal_link_ganti').hide(); $('.pass_ubah').hide(); $('#link_ganti').show(); $('#sts_pass_edit').val(0); cek_req(0);"> Batal Ubah </a>
                                <br><br>
                            </div>
                        </div>

                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" class="btn btn-info" value="Simpan Akun" name="simpan"/>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

<input type="hidden" id="sts_username" value="" />

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){

   <?PHP if($warning == 1){ ?>
    get_data_pegawai(<?=$id_pegawai;?>);
   <?PHP } ?>
});

function show_pop_pegawai(){
    get_popup_pegawai();
    ajax_pegawai();
}

function get_popup_pegawai(){
    var base_url = '<?php echo base_url(); ?>';
    var $isi = '<div id="popup_koang">'+
                '<div class="window_koang">'+
                '    <a href="javascript:void(0);"><img src="'+base_url+'assets/custom/ico/cancel.gif" id="pojok_koang"></a>'+
                '    <div class="panel-body">'+
                '    <input style="width: 95%;" type="text" name="search_koang" id="search_koang" class="form-control" value="" placeholder="Cari Pegawai...">'+
                '    <div class="table-responsive">'+
                '            <table class="table table-hover2" id="tes5">'+
                '                <thead>'+
                '                    <tr>'+
                '                        <th style="text-align:center;">NO</th>'+
                '                        <th style="text-align:center;" style="white-space:nowrap;"> NIP </th>'+
                '                        <th style="text-align:center;"> NAMA </th>'+
                '                        <th style="text-align:center;"> USERNAME </th>'+
                '                    </tr>'+
                '                </thead>'+
                '                <tbody>'+
            
                '                </tbody>'+
                '            </table>'+
                '        </div>'+
                '    </div>'+
                '</div>'+
            '</div>';
    $('body').append($isi);

    $('#pojok_koang').click(function(){
        $('#popup_koang').css('display','none');
        $('#popup_koang').hide();
    });

    $('#popup_koang').css('display','block');
    $('#popup_koang').show();
}

function ajax_pegawai(){
    var keyword = $('#search_koang').val();
    $.ajax({
        url : '<?php echo base_url(); ?>setting/login_pengguna_c/get_pegawai',
        type : "POST",
        dataType : "json",
        data : {
            keyword : keyword,
        },
        success : function(result){
            var isine = '';
            var no = 0;
            var tipe_data = "";
            $.each(result,function(i,res){
                no++;
                var username = res.USERNAME;
                if(username == "" || username == null){
                    username = "(Tanpa username)";
                }

                isine += '<tr onclick="get_data_pegawai('+res.ID+');" style="cursor:pointer;">'+
                            '<td align="center">'+no+'</td>'+
                            '<td align="center">'+res.NIP+'</td>'+
                            '<td align="left">'+res.NAMA+'</td>'+
                            '<td align="center">'+username+'</td>'+
                        '</tr>';
            });

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }

            $('#tes5 tbody').html(isine); 
            $('#search_koang').off('keyup').keyup(function(){
                ajax_pegawai();
            });
        }
    });
}

function get_data_pegawai(id){
    $.ajax({
        url : '<?php echo base_url(); ?>setting/login_pengguna_c/get_data_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#nama_pegawai').val(res.NAMA);
            $('#username').val(res.USERNAME);
            $('#id_pegawai').val(id);

            if(res.STS_AKUN == 1){
                $('#inlineRadio1').prop('checked', true);
                $('#inlineRadio2').prop('checked', false);
            } else {
                $('#inlineRadio1').prop('checked', false);
                $('#inlineRadio2').prop('checked', true);
            }

            $('#foto_head').html('<img width="120" src="<?=base_url();?>files/foto_pegawai/'+res.FOTO+'">');

            if(res.PASSWORD == null || res.PASSWORD == ""){
                $("#pass1").prop('required',true); 
                $("#pass2").prop('required',true);

                $('.pass_ada').hide();
                $('.pass_gk_ada').show();

                $('#sts_pass_awal').val(0);
            } else {
                $('.pass_gk_ada').hide();
                $('.pass_ada').show();               

                $("#pass1").prop('required',false);
                $("#pass2").prop('required',false);

                $('#sts_pass_awal').val(1);
            }

            $('#popup_koang').remove();
        }
    });

}

function cek_req(sts){
    if(sts == 1){
        $("#new_pass1").prop('required',true);
        $("#new_pass2").prop('required',true);
    } else {
        $("#new_pass1").prop('required',false);
        $("#new_pass2").prop('required',false);
    }
}

function cek_username(){
    var id_peg = $('#id_pegawai').val();
    var username = $('#username').val();    
    $.ajax({
        url : '<?php echo base_url(); ?>setting/login_pengguna_c/cek_username',
        data : {
            id_peg:id_peg,
            username:username,
        },
        type : "POST",
        dataType : "json",
        success : function(result){  

            $('#sts_username').val(result);

            if(result > 0){
                $('#warning_username').show();
            } else {
                $('#warning_username').hide();
            }        
            
        }
    });

}

function cek_username_submit(){
   var a = true;   
   var res = $('#sts_username').val();

    if(res > 0){
        a = false;
        alert("Username tersebut telah terpakai oleh pegawai lain !");
    } else {
        a = true;
    }  

    return a;
}
</script>