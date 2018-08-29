<!DOCTYPE html>
<html style="background-color: #2b82ad;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes"> 

        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/favicon.png"> 

        <title> <?php echo $title; ?> </title>

        <!-- form Uploads -->
        <link href="<?php echo base_url(); ?>assets/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/morris/morris.css">

        <!-- Notification css (Toastr) -->
        <link href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

        <!-- Custom box css -->
        <link href="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- Plugins css-->
        <link href="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">

        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?=base_url();?>assets/dialog/css/reset.css"> <!-- CSS reset -->
        <link rel="stylesheet" href="<?=base_url();?>assets/dialog/css/style.css"> <!-- Resource style -->

        <link href="<?php echo base_url(); ?>css-devan/warna.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
    </head>

    <body onload="startTime()">
        <div class="wrapper" style="margin-top: 30px; background-color:#2B82AD;">
            <div class="container" style="margin-left: 0; width: 100%;">
                <div class="row">
                    <div class="col-sm-4" id="head_list_antrian">
                                                
                    </div>

                    <div class="col-sm-8" style="padding-left: 0;">
                        <div class="card-box" style="height: 100%; min-height: 642px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div style="float:left;">
                                        <img src="<?php echo base_url(); ?>picture/jtech-logo.png" style="max-width:280px; max-height:90px;">
                                    </div>

                                    <div style="float: right; margin-right: 20px;">
                                        <p style="text-align: right; font-size: 25px;"><?=date('d F Y');?></p>
                                        <div id="txtClock" style="color:#666; font-size: 30px; text-align:right;"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12" id="last_antrian_head">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script>
    jQuery(document).ready(function() {  
        getAntrian();

        setInterval(function () {
          getAntrian();
        }, 5000);
    });

    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('txtClock').innerHTML =
        h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }

    function getAntrian(){
        $.ajax({
            url : '<?php echo base_url(); ?>antrian/antrian_home_c/get_nomor_offline',
            data : {
                sts:"ok",
            },
            type : "POST",
            dataType : "json",
            success : function(result){
                var jml = result.length;
                // console.log(jml);
                if(result.length > 0){
                    var isi = "";
                    $.each(result,function(i,res){
                        isi += '<div class="col-lg-12 col-md-12">'+
                                    '<div class="card-box widget-user" style="background: #1ca0de; border: 2px solid #fff; border-bottom:30px solid #FFF;">'+
                                        '<table width="100%">'+
                                            '<tr>'+
                                                '<td style="color:#FFF; font-size: 30px;"> <b>No. Antrian</b> </td>'+
                                                '<td style="color:#FFF; font-size: 45px;" align="center"> <b>'+res.KODE_ANTRIAN+'-'+res.NOMOR_ANTRIAN+'</b> </td>'+
                                            '</tr>'+
                                            '<tr>'+
                                                '<td style="color:#FFF; font-size: 45px;"> <b>'+res.NAMA_POLI+'</b> </td>'+
                                            '</tr>'+
                                        '</table>'+
                                    '</div>'+
                                '</div>';
                    });

                } else {
                    var isi = '<div class="col-lg-12 col-md-12">'+
                                    '<div class="card-box widget-user" style="background: #FFF; border: 2px solid #fff;">'+
                                        '<center> <h2><b>Tidak ada Antrian</b></h2> </center>'+
                                    '</div>'+
                                '</div>';
                }

                $('#head_list_antrian').html(isi);                
                get_antrian_last(jml);   
            }
        });
    }

    function get_antrian_last(jml){
        if(jml > 0){
            $.ajax({
                url : '<?php echo base_url(); ?>antrian/antrian_home_c/get_nomor_offline',
                data : {
                    sts:"ok",
                },
                type : "POST",
                dataType : "json",
                success : function(res){
                    var isi = '';
                    for(var i=0; i<res.length; i++){
                        var isi =   '<center>'+
                                        '<p><font style="color: rgb(28, 160, 222); font-size: 55px; font-weight:bold;"> Antrian Nomor </font></p>'+
                                        '<p style="line-height: 0.8;"><font style="color: rgb(237, 28, 36); font-size: 165px; font-weight:bold;"> '+res[i].KODE_ANTRIAN+'-'+res[i].NOMOR_ANTRIAN+' </font></p>'+
                                        '<p><font style="color: rgb(28, 160, 222); font-size: 55px; font-weight:bold;"> Mohon Menuju Ke</font></p>'+
                                        '<p style="line-height: 0.8;">'+
                                            '<font style="color: rgb(237, 28, 36); font-size: 100px; font-weight:bold;"> '+res[i].NAMA_POLI+' </font>'+
                                        '</p>'+
                                    '</center>';
                    }
                            
                    $('#last_antrian_head').html(isi);
                }
            });

        } else {
            var isi =   '<center>'+
                            '<img src="<?=base_url();?>picture/forbidden.png" width="300"/> <br>'+
                            '<font style="color: rgb(237, 28, 36); font-size: 80px; font-weight:bold;"> TIDAK ADA ANTRIAN </font>'+
                       '</center>';
            $('#last_antrian_head').html(isi);
        }

        
    }

    </script>
</html>