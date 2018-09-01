<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>

<script type="text/javascript">
var ajax = "";

$(document).ready(function(){
    data_pasien();

    $('#jumlah_tampil').change(function(){
        data_pasien();
    });

    $("input[name='urutkan']").click(function(){
        var urutkan = $("input[name='urutkan']:checked").val();
        if(urutkan == 'Umur'){
            $('#view_umur').show();
            $('#view_status').hide();
        }else if(urutkan == 'Status'){
            $('#view_umur').hide();
            $('#view_status').show();
        }else{
            $('#view_umur').hide();
            $('#view_status').hide();
            data_pasien();
        }
    });

    $('#pilih_umur').change(function(){
        data_pasien();
    });

    $('#pilih_status').change(function(){
        data_pasien();
    });

    $('#checkbox_pasien').click(function(){
        data_pasien();
    });
});

function klik_menu(link){
    window.location = link;
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

function data_pasien(){
    $('#popup_load').show();
    var cek = $('input[name="cek_pasien_kemarin"]').is(":checked");
    var keyword = $('#cari_pasien').val();
    var urutkan = $("input[name='urutkan']:checked").val();
    var pilih_umur = $('#pilih_umur').val();
    var pilih_status = $('#pilih_status').val();
    var now = "";

    if(cek == true){
        now = now;
    }else{
        now = "<?php echo date('d-m-Y'); ?>";
    }

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_home_c/data_pasien',
        data : {
            keyword:keyword,
            urutkan:urutkan,
            pilih_umur:pilih_umur,
            pilih_status:pilih_status,
            now:now
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

                    var rekam_medik = '<button type="button" class="btn btn-purple waves-effect waves-light btn-sm" onclick="get_history_medik('+result[i].ID+');">'+
                                        '<i class="fa fa-file-text"></i>'+
                                      '</button>';

                    var sts = '';
                    var warna = '';

                    if(result[i].STS_BAYAR == '0'){
                        warna = 'class="danger"';
                        sts = '<span class="label label-danger">Belum Lunas</span>';
                    }else{
                        warna = warna;
                        sts = '<span class="label label-success">Lunas</span>';
                    }

                    var tanggal = result[i].TANGGAL_DAFTAR+' - '+result[i].WAKTU_DAFTAR;

                    $tr +=  '<tr>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+tanggal+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].KODE_PASIEN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].NAMA+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result[i].JENIS_KELAMIN+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle;">'+result[i].ALAMAT+'</td>'+
                            '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+sts+'</td>'+
                            '   <td style="vertical-align:middle;" align="center">'+rekam_medik+'</td>'+
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

    $('#tombol_cari').click(function(){
        data_pasien();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
    });

    $('#tombol_reset').click(function(){
        $('#cari_pasien').val("");
        data_pasien();
        $('#tombol_reset').hide();
        $('#tombol_cari').show();
    });
}

function onEnterText(e){
    if (e.keyCode == 13) {
        data_pasien();
        $('#tombol_reset').show();
        $('#tombol_cari').hide();
        return false;
    }
}

function get_history_medik(id_pasien){
    $('#popup_histori_rm').click();
    $('#id_pasien2').val(id_pasien); 
    $('.cari_tgl').val(''); 
    $('#ord_tmp').val(''); 
    $('#isi_history_rj').html(''); 
    $('#isi_history_igd').html(''); 
    $('#isi_history_ri').html(''); 

    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_home_c/get_history_medik',
        data : {id_pasien:id_pasien},
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;

            var det_RJ = result['detail_RJ'];
            var det_IGD = result['detail_IGD'];
            var det_RI = result['detail_RI'];   

            var dataDetVisite_RI = result['dataDetVisite_RI'];
            var dataDetGizi_RI = result['dataDetGizi_RI'];
            var dataDetOksigen_RI = result['dataDetOksigen_RI'];
            var dataDetDiagnosa_RI = result['dataDetDiagnosa_RI'];
            var dataDetResep_RI = result['dataDetResep_RI'];

            // RAWAT JALAN
            if(det_RJ.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_RJ,function(i,RJ){                    
                    var ord  = RJ.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+RJ.TANGGAL+'</td>'+
                                '<td>'+RJ.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(RJ.ORD);     
                });
            } else {
                isine = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            } 

            $('#isi_history_rj').html(isine);             
            // END OF RAWAT JALAN 

            // RAWAT INAP
            if(dataDetDiagnosa_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>DIAGNOSA</b> </td></tr>';
                no = 0;
                $.each(dataDetDiagnosa_RI,function(i,D_RI){                    
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+D_RI.TANGGAL+'</td>'+
                                    '<td>'+D_RI.DIAGNOSA+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }

            if(det_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>TINDAKAN</b> </td></tr>';
                no = 0;
                $.each(det_RI,function(i,RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+RI.TANGGAL+'</td>'+
                                    '<td>'+RI.KET+'</td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }

            if(dataDetVisite_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>VISITE</b> </td></tr>';
                no = 0;
                $.each(dataDetVisite_RI,function(i,V_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+V_RI.TANGGAL+'</td>'+
                                    '<td>'+V_RI.NAMA_VISITE+' <br> <b>(Dokter : '+V_RI.NAMA_DOKTER+')</b> </td>'+
                                '</tr>';
    
                });
            }else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }

            if(dataDetGizi_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>GIZI</b> </td></tr>';
                no = 0;
                $.each(dataDetGizi_RI,function(i,Gizi_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Gizi_RI.TANGGAL+'</td>'+
                                    '<td>'+Gizi_RI.NAMA_GIZI+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }

            if(dataDetOksigen_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OKSIGEN</b> </td></tr>';
                no = 0;
                $.each(dataDetOksigen_RI,function(i,Oks_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Oks_RI.TANGGAL+'</td>'+
                                    '<td>'+Oks_RI.KETERANGAN+' <br> <b>('+Oks_RI.JUMLAH+' Tabung)</b> </td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }             

            if(dataDetResep_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OBAT / RESEP</b> </td></tr>';
                no = 0;
                $.each(dataDetResep_RI,function(i,Resep_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Resep_RI.TANGGAL+'</td>'+
                                    '<td>'+Resep_RI.NAMA_OBAT+'</td>'+
                                '</tr>';
    
                });
            } else{
                isine_ri = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }

            $('#isi_history_ri').html(isine_ri); 
            // END OF RAWAT INAP

            // IGD
            if(det_IGD.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_IGD,function(i,IGD){                    
                    var ord  = IGD.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine_igd += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine_igd += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+IGD.TANGGAL+'</td>'+
                                '<td>'+IGD.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(IGD.ORD);     
                });
            } else {
                isine_igd = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }
            $('#isi_history_igd').html(isine_igd); 
            // END OF IGD
        }
    });

}

function Search_tgl_RJ(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_home_c/get_history_medik_by_search_rj',
        data : {
            id_pasien:id_pasien,
            tgl : tgl
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            var det_RJ = result['detail_RJ'];
            // RAWAT JALAN
            if(det_RJ.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_RJ,function(i,RJ){                    
                    var ord  = RJ.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+RJ.TANGGAL+'</td>'+
                                '<td>'+RJ.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(RJ.ORD);     
                });
            } else {
                isine = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            } 
            $('#isi_history_rj').html(isine);             
            // END OF RAWAT JALAN 

            
        }
    });
}

function Search_tgl_RI(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_home_c/get_history_medik_by_search_ri',
        data : {
            id_pasien:id_pasien,
            tgl : tgl
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            
            var det_RI = result['detail_RI'];            
            var dataDetVisite_RI = result['dataDetVisite_RI'];
            var dataDetGizi_RI = result['dataDetGizi_RI'];
            var dataDetOksigen_RI = result['dataDetOksigen_RI'];
            var dataDetDiagnosa_RI = result['dataDetDiagnosa_RI'];
            var dataDetResep_RI = result['dataDetResep_RI'];
            
            // RAWAT INAP
            if(dataDetDiagnosa_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>DIAGNOSA</b> </td></tr>';
                no = 0;
                $.each(dataDetDiagnosa_RI,function(i,D_RI){                    
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+D_RI.TANGGAL+'</td>'+
                                    '<td>'+D_RI.DIAGNOSA+'</td>'+
                                '</tr>';
    
                });
            }

            if(det_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>TINDAKAN</b> </td></tr>';
                no = 0;
                $.each(det_RI,function(i,RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+RI.TANGGAL+'</td>'+
                                    '<td>'+RI.KET+'</td>'+
                                '</tr>';
    
                });
            }

            if(dataDetVisite_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>VISITE</b> </td></tr>';
                no = 0;
                $.each(dataDetVisite_RI,function(i,V_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+V_RI.TANGGAL+'</td>'+
                                    '<td>'+V_RI.NAMA_VISITE+' <br> <b>(Dokter : '+V_RI.NAMA_DOKTER+')</b> </td>'+
                                '</tr>';
    
                });
            }

            if(dataDetGizi_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>GIZI</b> </td></tr>';
                no = 0;
                $.each(dataDetGizi_RI,function(i,Gizi_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Gizi_RI.TANGGAL+'</td>'+
                                    '<td>'+Gizi_RI.NAMA_GIZI+'</td>'+
                                '</tr>';
    
                });
            } 

            if(dataDetOksigen_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OKSIGEN</b> </td></tr>';
                no = 0;
                $.each(dataDetOksigen_RI,function(i,Oks_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Oks_RI.TANGGAL+'</td>'+
                                    '<td>'+Oks_RI.KETERANGAN+' <br> <b>('+Oks_RI.JUMLAH+' Tabung)</b> </td>'+
                                '</tr>';
    
                });
            }              

            if(dataDetResep_RI.length > 0){
                isine_ri += '<tr><td colspan="3"> <b>OBAT / RESEP</b> </td></tr>';
                no = 0;
                $.each(dataDetResep_RI,function(i,Resep_RI){
                    no++;
                    isine_ri += '<tr>'+
                                    '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                    '<td>'+Resep_RI.TANGGAL+'</td>'+
                                    '<td>'+Resep_RI.NAMA_OBAT+'</td>'+
                                '</tr>';
    
                });
            } 

            $('#isi_history_ri').html(isine_ri); 
            // END OF RAWAT INAP

        }
    });
}

function Search_tgl_IGD(tgl){
    $('#ord_tmp').val(''); 
    var id_pasien = $('#id_pasien2').val();
    $.ajax({
        url : '<?php echo base_url(); ?>admum/admum_home_c/get_history_medik_by_search_igd',
        data : {
            id_pasien:id_pasien,
            tgl : tgl
        },
        type : "POST",
        dataType : "json",
        success : function(result){
            var isine = "";
            var isine_igd = "";
            var isine_ri = "";
            var no = 0;
            var det_IGD = result['detail_IGD'];
            if(det_IGD.length > 0){
                var ord2 = $('#ord_tmp').val(); 
                $.each(det_IGD,function(i,IGD){                    
                    var ord  = IGD.ORD;
                    var ord2 = $('#ord_tmp').val(); 

                    if(ord != ord2){
                        isine_igd += '<tr><td colspan="3"> <b>'+ord+'</b> </td></tr>';
                        no = 0;
                    }

                    no++;
                    isine_igd += '<tr>'+
                                '<th scope="row" align="center" style="text-align:center;">'+no+'</th>'+
                                '<td>'+IGD.TANGGAL+'</td>'+
                                '<td>'+IGD.KET+'</td>'+
                             '</tr>';

                    $('#ord_tmp').val(IGD.ORD);     
                });
            } else {
                isine_igd = '<tr><td colspan="3" align="center" style="text-align:center;"> <b>Tidak ada history / info medik pada pasien ini </b> </td></tr>';
            }
            $('#isi_history_igd').html(isine_igd);             
        }
    });
}
</script>

<style type="text/css">
a {
    color: #36C;
    text-decoration: none;
}

#dashboard-depan {
    width: 100%;
    height: 700px;
    /*background-color: #fff;*/
    border-radius: 5px;
} 
.tile-depan {
    text-align: center;
    float: left;
    margin: 10px 0;
    color: #07A;
    font: bold 12px tahoma;
    height: 150px;
    width: 148px;
}
.tile-depan img {
    width: 120px;
    height: 120px;
    padding: 10px;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    border-radius: 10px;
    margin-bottom: 5px;
}
.tile-depan a:hover {
    color: #770;
}
.tile-depan a:hover img {
    border: 2px solid #00a0f0; 
    background: #c4dff6;
}

#view_ubah, 
#view_umur, 
#view_kota, 
#view_status,
#tombol_reset, 
#view_prov_ubah{
    display: none;
}
</style>

<?PHP 
    $sess_user = $this->session->userdata('masuk_rs');
    $id_user = $sess_user['id'];
    $user = $this->master_model_m->get_user_info($id_user);
?>

<div class="row">
<?PHP 
    $get_menu2 = $this->master_model_m->get_menu_2($id_user, 1);
    foreach ($get_menu2 as $key => $menu2) {
        $link = base_url().$menu2->LINK;
        $icon = base_url().$menu2->GAMBAR_ICON;
        if($menu2->LINK != null || $menu2->LINK != ""){
            $id1 = $menu2->ID;
            $text1 = "";
            if($id1 % 2 == 0){
                $text1 = "text-custom";
            }else{
                $text1 = "text-success";
            }
?>
    <div class="col-lg-2 col-md-6" onclick="klik_menu('<?php echo $link; ?>');" style="cursor: pointer;">
        <div class="card-box widget-user">
            <div>
                <img alt="user" class="img-responsive img-circle" src="<?php echo $icon; ?>">
                <div class="wid-u-info">
                    <h4 class="m-t-0 m-b-5"><?php echo $menu2->NAMA; ?></h4>
                </div>
                <small class="<?php echo $text1; ?>" style="margin-left: 20px;"><b><?php echo str_replace('_', ' ', strtoupper($menu2->VIEW)); ?></b></small>
            </div>
        </div>
    </div>
<?php
        }else{
            $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                foreach ($get_menu3 as $key => $menu3) {
                    $link3 = base_url().$menu3->LINK;
                    $icon3 = base_url().$menu3->GAMBAR_ICON;
                    $id = $menu3->ID;
                    $text = "";
                    if($id % 2 == 0){
                        $text = "text-success";
                    }else{
                        $text = "text-custom";
                    }
?>
    <div class="col-lg-2 col-md-6" onclick="klik_menu('<?php echo $link3; ?>');" style="cursor: pointer;">
        <div class="card-box widget-user">
            <div>
                <img alt="user" class="img-responsive img-circle" src="<?php echo $icon3; ?>">
                <div class="wid-u-info">
                    <h4 class="m-t-0 m-b-5"><?php echo $menu3->NAMA; ?></h4>
                    <small class="<?php echo $text; ?>"><b><?php echo str_replace('_', ' ', strtoupper($menu3->VIEW)); ?></b></small>
                </div>
            </div>
        </div>
    </div>
<?php
                }
        }
    }
?>
</div>

<div class="row">
    <div class="col-lg-12" id="view_data">
        <div class="card-box">
            <form class="form-horizontal" role="form" action="" method="post">
                <input type="hidden" id="ord_tmp" value="" />
                <!-- <div class="form-group">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary waves-effect w-md waves-light m-b-5" id="btn_pasien_umum"><i class="fa fa-users"></i> <b>Pasien Umum</b></button>
                        <button type="button" class="btn btn-success waves-effect w-md waves-light m-b-5" id="btn_pasien_rj"><i class="fa fa-h-square"></i> <b>Pasien Rawat Jalan</b></button>
                        <button type="button" class="btn btn-warning waves-effect w-md waves-light m-b-5" id="btn_pasien_ri"><i class="fa fa-bed"></i> <b>Pasien Rawat Inap</b></button>
                        <button type="button" class="btn btn-danger waves-effect w-md waves-light m-b-5" id="btn_pasien_igd"><i class="fa fa-ambulance"></i> <b>Pasien IGD</b></button>
                    </div>
                </div>
                <hr> -->
                <!-- <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success waves-effect w-md waves-light m-b-5 pull-right"><i class="fa fa-file-text-o"></i> <b>Cetak Excel</b></button>
                    </div>
                </div> -->
                <div class="form-group">
                    <label class="col-md-1 control-label" style="text-align:left; width: 5%;">Urutkan</label>
                    <div class="col-md-11">
                        <div class="radio radio-purple radio-inline">
                            <input type="radio" name="urutkan" value="Default" id="default" checked="checked">
                            <label for="default"> Default </label>
                        </div>
                        <div class="radio radio-purple radio-inline">
                            <input type="radio" name="urutkan" value="Nama Pasien" id="cari_nama_pasien">
                            <label for="nama_poli"> Nama Pasien </label>
                        </div>
                        <div class="radio radio-purple radio-inline">
                            <input type="radio" name="urutkan" value="Umur" id="cari_umur">
                            <label for="jenis"> Umur </label>
                        </div>
                        <div class="radio radio-purple radio-inline">
                            <input type="radio" name="urutkan" value="Status" id="cari_status">
                            <label for="jenis"> Status Pembayaran </label>
                        </div>
                        <div class="checkbox checkbox-inline checkbox-primary" style="margin-left: 10px;">
                            <input type="checkbox" id="checkbox_pasien" name="cek_pasien_kemarin">
                            <label for="checkbox_pasien">
                                Tampilkan Semua Pasien
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="view_umur">
                    <label class="col-md-1 control-label" style="text-align:left; width: 5%;">&nbsp;</label>
                    <div class="col-md-3">
                        <select class="form-control" name="pilih_umur" id="pilih_umur">
                            <option value="Balita">0 - 5 Tahun</option>
                            <option value="Anak">6 - 16 Tahun</option>
                            <option value="Remaja">17 - 25 Tahun</option>
                            <option value="Dewasa">26 - 50 Tahun</option>
                            <option value="Tua"> > 50 Tahun</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="view_status">
                    <label class="col-md-1 control-label" style="text-align:left; width: 5%;">&nbsp;</label>
                    <div class="col-md-3">
                        <select class="form-control" name="pilih_status" id="pilih_status">
                            <option value="0">Belum Lunas</option>
                            <option value="1">Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" class="form-control" name="cari_pasien" id="cari_pasien" placeholder="Cari pasien..." value="" onkeypress="return onEnterText(event);">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_cari">
                                    <i class="fa fa-search"></i>
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-warning" id="tombol_reset">
                                    <i class="fa fa-refresh"></i>
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
                                        <th style="color:#fff; text-align:center;">Tgl Rawat / Waktu</th>
                                        <th style="color:#fff; text-align:center;">No. RM</th>
                                        <th style="color:#fff; text-align:center;">Nama Pasien</th>
                                        <th style="color:#fff; text-align:center;">JK</th>
                                        <th style="color:#fff; text-align:center;">Alamat</th>
                                        <th style="color:#fff; text-align:center;">Status Bayar</th>
                                        <th style="color:#fff; text-align:center;">Rekam Medik</th>
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
    </div>
</div>

<!--  MODAL HISTORY REKAM MEDIK -->
<button type="button" id="popup_histori_rm" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#myModalRM" style="display: none;"></button>
<div class="modal fade bs-example-modal-lg" id="myModalRM" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">History Rekam Medik</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_pasien2"/>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box card-tabs" style="padding:0;">

                            <ul class="nav nav-tabs coba">
                                <li role="presentation" class="active">
                                    <a style="background:#f4f8fb;" href="#rj" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-thumb-tack"></i> Rawat Jalan</a>
                                </li> 
                                <li role="presentation" class="">
                                    <a style="background:#f4f8fb;" href="#ri" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> Rawat Inap </a>
                                </li>                       
                                <li role="presentation" class="">
                                    <a style="background:#f4f8fb;" href="#igd" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-thumb-tack"></i> IGD </a>
                                </li>
                            </ul> 
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="rj">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_RJ(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_rj">

                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="ri">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_RI(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_ri">

                                        </tbody>
                                    </table>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="igd">
                                    <div class="col-md-4 pull-right" style="margin: 10px -10px 10px 0;">
                                        <label class="control-label"> Cari berdasarkan tanggal </label>
                                        <div class="input-group">                                            
                                            <input class="form-control cari_tgl" placeholder="ex: 01-01-2016" value="" onkeyup="Search_tgl_IGD(this.value);" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-warning">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">#</th>
                                                <th style="text-align:center;">Tanggal</th>
                                                <th style="text-align:center;">Informasi Medik</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_history_igd">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>