<style type="text/css">
fieldset{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background: #fff none repeat scroll 0 0;
    border-color: #ddd -moz-use-text-color -moz-use-text-color;
    border-image: none;
    border-style: solid none none;
    border-width: 1px 0 0;
    padding: 10px;
} 

legend {
    color: #888;
    margin-left: 0;
    padding: 0 5px;
    font-size: 14px;
    width: 12%;
}
</style>
<?PHP 
$now = date('d-m-Y');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12">
                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label"> Nama Pegawai </label>
                            <div class="col-md-3">
                                <input readonly name="nip" id="nip" class="form-control" value="" type="text" placeholder="NIP">
                                <input name="id_pegawai" id="id_pegawai" class="form-control" value="" type="hidden">
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input readonly name="nama_pegawai" id="nama_pegawai" class="form-control" value="" type="text" placeholder="Nama Pegawai">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" onclick="show_pop_pegawai();">Cari Pegawai</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Nomor SK </label>
                            <div class="col-md-3">
                                <input name="sk_jabatan" class="form-control" value="" type="text" placeholder="">
                            </div>

                            <label class="col-md-1 control-label"> Tanggal SK </label>
                            <div class="col-md-3">
                                <input name="tgl_sk_jabatan" data-mask="99-99-9999" class="form-control" value="" type="text" placeholder="">
                            </div>
                        </div>

                        <br>
                        <div>
                            <fieldset><legend>Data Jabatan Lama</legend></fieldset>
                        </div>                 

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Jabatan Sekarang </label>
                            <div class="col-md-4">
                                <input name="jabatan_lama" id="jabatan_lama" class="form-control" value="" type="text" readonly>
                                <input name="sk_jabatan_lama" id="sk_jabatan_lama" class="form-control" value="" type="hidden" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Departemen</label>
                            <div class="col-md-4">
                                <input name="departemen_lama" id="departemen_lama" class="form-control" value="" type="text" readonly>
                            </div>

                            <label class="col-md-1 control-label"> Divisi </label>
                            <div class="col-md-4">
                                <input name="divisi_lama" id="divisi_lama" class="form-control" value="" type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Tanggal Akhir </label>
                            <div class="col-md-3">
                                <input name="tgl_akhir_jabatan" data-mask="99-99-9999" class="form-control" type="text" value="<?=date('d-m-Y');?>">
                            </div>
                        </div>

                        <br>
                        <div>
                            <fieldset><legend>Data Jabatan Baru</legend></fieldset>
                        </div> 

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Status Pegawai </label>
                            <div class="col-md-4">
                                <select class="form-control" name="status" id="status">
                                        <option value=""> -- Pilih </option>  
                                        <option value="-"> - </option>
                                        <option value="PNS PUSAT">PNS PUSAT</option>
                                        <option value="CPNS PUSAT">CPNS PUSAT</option>
                                        <option value="PNS DAERAH">PNS DAERAH</option>
                                        <option value="CPNS DAERAH">CPNS DAERAH</option>
                                        <option value="HONORER">HONORER</option>
                                        <option value="OUTSOURCING">OUTSOURCING</option>
                                        <option value="PENSIUN">PENSIUN</option>
                                        <option value="BERHENTI/PINDAH">BERHENTI/PINDAH</option>
                                        <option value="MENINGGAL DUNIA">MENINGGAL DUNIA</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Jabatan Baru </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="jabatan_baru" id="jabatan_baru">
                                        <?PHP 
                                            foreach ($get_jabatan as $key => $jab) { 
                                        ?>
                                            <option value="<?=$jab->ID;?>"><?=$jab->NAMA;?></option>

                                        <?PHP } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Departemen</label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="departemen_baru" id="departemen_baru" onchange="get_divisi(this.value);">
                                            <option value=""> -- Pilih Departemen </option>
                                        <?PHP 
                                            foreach ($get_departemen as $key => $dep) { 
                                        ?>
                                            <option value="<?=$dep->ID;?>"><?=$dep->NAMA_DEP;?></option>

                                        <?PHP } ?>
                                </select>
                            </div>

                            <label class="col-md-1 control-label"> Divisi </label>
                            <div class="col-md-4">
                                <select class="form-control select2" name="divisi_baru" id="divisi_baru">

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label"> Tanggal Awal </label>
                            <div class="col-md-3">
                                <input name="tgl_awal_jab_baru" data-mask="99-99-9999" class="form-control" type="text" value="<?=date('d-m-Y', strtotime($now . "+1 days"));?>">
                            </div>

                            <label class="col-md-2 control-label"> Tanggal Akhir </label>
                            <div class="col-md-3">
                                <input name="tgl_akhir_jab_baru" data-mask="99-99-9999" class="form-control" type="text" value="<?=date('d-m-Y', strtotime($now . "+4 years"));?>">
                            </div>
                        </div>

                        <br>
                        <hr>
                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-5 col-sm-10">
                              <input type="submit" class="btn btn-info" value="Simpan" name="simpan"/>
                              &nbsp;&nbsp;&nbsp;
                              <a href="<?=base_url();?>kepeg/mutasi_pegawai_c" class="btn btn-danger">Batal</a>
                            </div>
                        </div>

                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
function get_divisi (id_dep) {

    $.ajax({
        url : '<?php echo base_url(); ?>kepeg/add_pegawai_c/get_divisi',
        data : {id_dep:id_dep},
        type : "POST",
        dataType : "json",
        success : function(result){
            if(result.length > 0){
                var isine = "<option value='0'> Tanpa Divisi </option>";
                $.each(result,function(i,res){
                    isine += '<option value='+res.ID+'> '+res.NAMA_DIV+' </option>';
                });
            } else {
                var isine = "<option value='0'> Tanpa Divisi </option>";
            }

            $('#divisi_baru').html(isine);
            $('#divisi_baru').trigger('change');
        }
    });
}

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
                '                        <th style="text-align:left;"> NAMA </th>'+
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
        url : '<?php echo base_url(); ?>kepeg/mutasi_pegawai_c/get_data_pegawai', 
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#popup_load').show();
            $('#id_pegawai').val(id);
            $('#nip').val(res.NIP);
            $('#nama_pegawai').val(res.NAMA);
            $('#jabatan_lama').val(res.JABATAN);
            $('#departemen_lama').val(res.NAMA_DEP);
            $('#divisi_lama').val(res.NAMA_DIV);
            $('#status').val(res.STATUS);
            $('#sk_jabatan_lama').val(res.SK_JABATAN);
            $('#popup_koang').remove();
        }
    });

}

</script>