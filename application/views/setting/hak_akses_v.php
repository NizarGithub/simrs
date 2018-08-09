<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}
</style> 
  
<?PHP if($dt_pegawai != ""){ ?> 
<div id="popup_load" style="display:block;"> 
    <div class="window_load"> 
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>   
</div>
<?PHP } else { ?> 
<div id="popup_load" style="display:none;">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div> 
<?PHP } ?> 

<div class="row"> 
    <div class="col-sm-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-12"> 
                    <form id="cari_pegawai_frm" class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5; margin-top: 10px;"> &nbsp; </label>
                            <div class="col-md-6" id="foto_head">
                                <?PHP if($dt_pegawai == ""){ ?>
                                    <img width="120" src="<?=base_url();?>files/foto_pegawai/default_pics_of_rs_jt.png">
                                <?PHP } else { ?>
                                    <img width="120" src="<?=base_url();?>files/foto_pegawai/<?=$dt_pegawai->FOTO;?>">
                                <?PHP } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label" style="color: #0099e5; margin-top: 10px;"> Nama Pegawai </label>
                            <div class="col-md-6">
                                <div class="input-group m-t-10">

                                    <?PHP if($dt_pegawai == ""){ ?>
                                        <input id="nama_pegawai" name="nama_pegawai" class="form-control" type="text" readonly value="">
                                    <?PHP } else { ?>
                                        <input id="nama_pegawai" name="nama_pegawai" class="form-control" type="text" readonly value="<?=$dt_pegawai->NAMA;?>">
                                    <?PHP } ?>
                                    
                                    <span class="input-group-btn">
                                    <button type="button" class="btn waves-effect waves-light btn-primary" id="cari_peg">Cari Pegawai</button>
                                    </span>
                                </div>

                                <?PHP if($dt_pegawai == ""){ ?>
                                    <input name="id_pegawai" id="id_pegawai" class="form-control" value="" type="hidden">
                                <?PHP } else { ?>
                                    <input name="id_pegawai" id="id_pegawai" class="form-control" value="<?=$dt_pegawai->ID;?>" type="hidden">
                                <?PHP } ?>
                                
                            </div>
                        </div>
                    </form>
                </div><!-- end col -->

            </div><!-- end row -->
        </div>
    </div><!-- end col -->
</div>

<form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                   <div class="col-lg-12">
                        <ul class="nav nav-tabs coba">
                            <?PHP 
                            $no_1 = 0;
                            foreach ($get_menu_1 as $key => $menu_1) { 
                                $no_1++;
                            ?>
                                <li role="presentation" class="<?PHP if($no_1 == 1){ echo "active"; }?>">
                                    <a style="background:#f4f8fb;" href="#data_menu<?=$menu_1->ID;?>" role="tab" data-toggle="tab"> <?=$menu_1->NAMA;?> </a>
                                </li>
                            <?PHP } ?>
                        </ul>
                        <div class="tab-content">
                            <?PHP 
                                $no = 0;
                                $check_1 = "";
                                foreach ($get_menu_1 as $key => $menu_1) { 
                                    if($menu_1->STS == 0){
                                        $check_1 = "";
                                    } else {
                                        $check_1 = "checked"; 
                                    }

                                    $no++;
                            ?>
                                <div role="tabpanel" class="tab-pane fade <?PHP if($no == 1){ echo "in active"; }?>" id="data_menu<?=$menu_1->ID;?>">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <center>
                                                <div class="checkbox checkbox-success">
                                                    <input id="ck_portal_<?=$menu_1->ID;?>" name="menu_portal[]" value="<?=$menu_1->ID;?>" type="checkbox" <?=$check_1;?> >
                                                    <label for="ck_portal_<?=$menu_1->ID;?>">
                                                       <b> Menu Portal <?=strtoupper($menu_1->NAMA);?> </b>
                                                    </label>
                                                </div>
                                            </center>

                                            <ul class="treeview">
                                                <?PHP 
                                                $get_menu_2 = $this->model->get_data_menu_2($menu_1->ID, $id_pegawai);
                                                $check_2 = "";
                                                $check_2_cus = "";
                                                foreach ($get_menu_2 as $key => $menu_2) {
                                                    if($menu_2->STS == 0){
                                                        $check_2 = "";
                                                        $check_2_cus = "custom-unchecked";
                                                    } else {
                                                        $check_2 = "checked";
                                                        $check_2_cus = "custom-checked";
                                                    }
                                                ?>
                                                <div class="col-lg-3" style="margin-top: 35px;">
                                                    <?PHP if($menu_2->LINK != "" || $menu_2->LINK != null){ ?>
                                                        
                                                            <li>
                                                                <input type="checkbox" name="ch_menu2[]" id="<?=$menu_2->NAMA;?>" value="<?=$menu_2->ID;?>" <?=$check_2;?> >
                                                                <label for="<?=$menu_2->NAMA;?>" class="<?=$check_2_cus;?>"> <i class="<?=$menu_2->ICON;?>"></i> <?=$menu_2->NAMA;?> </label>
                                                            </li>
                                                        <?PHP } else { ?>
                                                            <li>
                                                                <input type="checkbox" name="ch_menu2[]" id="menu2-<?=$menu_2->ID;?>" value="<?=$menu_2->ID;?>" <?=$check_2;?> >
                                                                <label for="<?=$menu_2->NAMA;?>" class="<?=$check_2_cus;?>"> <i class="<?=$menu_2->ICON;?>"></i> <?=$menu_2->NAMA;?> </label>
                                                                
                                                                <ul>
                                                                    <?PHP 
                                                                        $check_3 = "";
                                                                        $check_3_cus = "";
                                                                        $get_menu_3 = $this->model->get_data_menu_3($menu_2->ID, $id_pegawai);
                                                                        foreach ($get_menu_3 as $key => $menu_3) {
                                                                            if($menu_3->STS == 0){
                                                                                $check_3 = "";
                                                                                $check_3_cus = "custom-unchecked";
                                                                            } else {
                                                                                $check_3 = "checked";
                                                                                $check_3_cus = "custom-checked";
                                                                            }
                                                                    ?>
                                                                    <li>
                                                                        <input  type="checkbox" name="ch_menu3[]" id="menu3-<?=$menu_3->ID;?>" value="<?=$menu_3->ID;?>" <?=$check_3;?>>
                                                                        <label for="tall-1" class="<?=$check_3_cus;?>"> <i class="fa fa-caret-right"></i> <?=$menu_3->NAMA;?> </label>
                                                                    </li>
                                                                    <?PHP } ?>
                                                                </ul>
                                                            </li>
                                                        
                                                    <?PHP } ?>
                                                </div>


                                                <?PHP } ?>                                            
                                            </ul>
                                        </div>


                                    </div>
                                </div>
                            <?PHP } ?>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-lg-12">
                        <center>
                            <div class="form-group m-b-0">
                                  <input type="submit" class="btn btn-info" value="Simpan Hak Akses" name="simpan"/>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?PHP if($dt_pegawai == ""){ ?>
        <input name="id_pegawai2" id="id_pegawai2" class="form-control" value="" type="hidden">
    <?PHP } else { ?>
        <input name="id_pegawai2" id="id_pegawai2" class="form-control" value="<?=$dt_pegawai->ID;?>" type="hidden">
    <?PHP } ?>
</form>

<button class="btn btn-primary waves-effect waves-light" id="popup_pegawai" data-toggle="modal" data-target=".bs-example-modal-lg" style="display: none;">Large modal</button>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input class="form-control" type="text" id="search_koang" value="" placeholder="Cari...">
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover2" id="tes5">
                        <thead>
                            <tr>
                                <th style="text-align:center;">NO</th>
                                <th style="text-align:center;" style="white-space:nowrap;"> NIP </th>
                                <th style="text-align:center;"> NAMA </th>
                                <th style="text-align:center;"> USERNAME </th>
                            </tr>
                        </thead>
                        <tbody>
    
                        </tbody>
                    </table>
                </div>
                <div id="tablePaging"> </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
$(function() {

  $('input[type="checkbox"]').change(checkboxChanged); 

  function checkboxChanged() {
    var $this = $(this),
        checked = $this.prop("checked"),
        container = $this.parent(),
        siblings = container.siblings();

    container.find('input[type="checkbox"]')
    .prop({
        indeterminate: false,
        checked: checked
    })
    .siblings('label')
    .removeClass('custom-checked custom-unchecked custom-indeterminate')
    .addClass(checked ? 'custom-checked' : 'custom-unchecked');

    checkSiblings(container, checked);
  }

  function checkSiblings($el, checked) {
    var parent = $el.parent().parent(),
        all = true,
        indeterminate = false;

    $el.siblings().each(function() {
      return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
    });

    if (all && checked) {
      parent.children('input[type="checkbox"]')
      .prop({
          indeterminate: false,
          checked: checked
      })
      .siblings('label')
      .removeClass('custom-checked custom-unchecked custom-indeterminate')
      .addClass(checked ? 'custom-checked' : 'custom-unchecked');

      checkSiblings(parent, checked);
    } 
    
  }
});

$(document).ready(function(){
    <?PHP if($warning == 1){ ?>
    get_data_pegawai(<?=$id_pegawai;?>);
    <?PHP } ?>

    $('#popup_load').hide();

    $('#cari_peg').click(function(){
        $('#popup_pegawai').click();
        ajax_pegawai();
    });

});

function paging($selector){
    if(typeof $selector == 'undefined')
    {
        $selector = $("#tes5 tbody tr");
    }

    window.tp = new Pagination('#tablePaging', {
        itemsCount:$selector.length,
        pageSize : 10,
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

            if(result.length == 0){
                isine = "<tr><td colspan='4' style='text-align:center'><b style='font-size: 15px;'> Data tidak tersedia </b></td></tr>";
            }else{
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
            }

            $('#tes5 tbody').html(isine);
            paging();
        }
    });

    $('#search_koang').off('keyup').keyup(function(){
        ajax_pegawai();
    });
}

function get_data_pegawai(id){
    $.ajax({
        url : '<?php echo base_url(); ?>setting/login_pengguna_c/get_data_pegawai',
        data : {id:id},
        type : "POST",
        dataType : "json",
        success : function(res){
            $('#popup_load').show();
            $('#nama_pegawai').val(res.NAMA);
            $('#id_pegawai').val(id);
            $('#foto_head').html('<img width="120" src="<?=base_url();?>files/foto_pegawai/'+res.FOTO+'">');
            $('#popup_koang').remove();


            $('#cari_pegawai_frm').submit();
        }
    });

}

</script>