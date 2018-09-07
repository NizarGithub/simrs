<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?php echo base_url(); ?>picture/portal/checklist-clipart-1.jpg.ico">

        <title><?php echo $title; ?></title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/morris/morris.css">

        <!-- Notification css (Toastr) -->
        <link href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

        <!-- Custom box css -->
        <link href="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">

        <!-- Plugins css-->
        <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
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

        <!--CSS Devan-->
        <link href="<?php echo base_url(); ?>css-devan/warna.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>dist/css/datepicker.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>

        <style type="text/css">
        #ketap_ketip{
            display: none;
        }

        #popup_pasien_baru {
            width: 100%;
            height: 100%;
            position: fixed;
            background: rgba(0,0,0,.7);
            top: 0;
            left: 0;
            z-index: 9999;
            display: none;
        }
        .window_pasien_baru {
            width:50%;
            height:auto;
            position: relative;
            padding: 10px;
            margin: 2% auto;
            background-color: #fff;
        }
        </style>
    </head>


    <body>
        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main" style="background-color:#ff4c4c; height:60px;">
                <div class="container">
                    <?PHP 
                        $sess_user = $this->session->userdata('masuk_rs');
                        $id_user = $sess_user['id'];
                        $user = $this->master_model_m->get_user_info($id_user);
                        $is_operator = $this->master_model_m->is_operator($id_user, 'rekam_medik');
                        $level = $user->LEVEL;
                    ?>
                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?php echo base_url(); ?>rekam_medik/rk_home_c" class="logo" style="margin-top:4px;">
                            <img src="<?php echo base_url(); ?>picture/jtech-logo.png" style="max-width:150px; max-height:40px;">
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <!-- LOKET -->
                    <?PHP 
                    if(count($is_operator) > 0){ 
                        $get_loket = $this->master_model_m->getLoket($id_user, 'rekam_medik');
                        $get_jml_antrian = $this->master_model_m->getJmlAntrian($get_loket->KODE_ANTRIAN);
                    ?>
                    <center>
                    <div style="width: 87%; position: absolute; float: left; margin-top: 7px;">
                        <button type="button" class="btn btn-warning waves-effect waves-light w-md m-b-5" style="padding-top: 10px; padding-bottom: 10px;"> 
                            <b id="nama_loket_antrian_txt"> <?=strtoupper($get_loket->NAMA_LOKET);?> </b> 
                        </button>

                        <button type="button" class="btn btn-danger" style="margin-left: 100px; font-size: 32px; margin-top: -8px;"> 
                            <b><?=$get_loket->KODE;?>-<font id="jml_antrian_txt"><?=count($get_jml_antrian) + 1;?></font></b> 

                            <input type="hidden" id="kode_antrian_now" value="<?=$get_loket->KODE;?>" />
                            <input type="hidden" id="jml_antrian_now" value="<?=count($get_jml_antrian) + 1;?>" />
                            <input type="hidden" id="id_antrian_now" value="<?=$get_loket->KODE_ANTRIAN;?>" />
                        </button>

                        <button class="btn btn-purple waves-effect waves-light m-b-5" style="margin-left: 40px;" onclick="panggil_antrian();"> 
                           <i class="fa fa-bullhorn m-r-5"></i>   <span> Panggil </span> 
                        </button>

                        <button class="btn btn-primary waves-effect waves-light m-b-5" style="margin-left: 10px;" data-toggle="modal" data-target="#next_antrian"> 
                            <span> Berikutnya </span> <i class="fa fa-chevron-circle-right m-r-5"></i>  
                        </button>
                    </div>
                    </center>
                    <?PHP } ?>
                    <!-- END OF LOKET -->

                    <div class="menu-extras">
                        <?PHP 
                            $sess_user = $this->session->userdata('masuk_rs');
                            $id_user = $sess_user['id'];
                            $user = $this->master_model_m->get_user_info($id_user);
                        ?>
                        <ul class="nav navbar-nav navbar-right pull-right" style="background-color:#bb1e10; height:60px;">
                            <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs" style="margin-right:0px; margin-top:0px;">
                                    <h5 style="color:#fff;"><b><?php echo strtoupper($user->NAMA);?></b></h5>
                                    <h6 style="color:#fff;"><b><?php echo $user->JABATAN;?></b></h6>
                                </form>
                            </li>
                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="<?php echo base_url(); ?>files/foto_pegawai/<?php echo $user->FOTO;?>" alt="user-img" class="img-circle user-img">
                                </a>

                                <ul class="dropdown-menu">
                                    <?php if($level == null){ ?>
                                    <li><a href="<?php echo base_url(); ?>portal"><i class="fa fa-th m-r-5"></i> Portal Depan</a></li>
                                    <?php } ?>
                                    <li><a href="javascript:void(0)"><i class="ti-user m-r-5"></i> Profile</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
                                    <li><a href="<?php echo base_url(); ?>logout"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li id="li_notif">
                                            <a class="right-bar-toggle" href="javascript:void(0);">
                                                <i class="fa fa-bell-o"></i>
                                            </a>
                                            <span class="badge badge-success" id="tot_pasien">0</span>
                                            <div class="noti-dot">
                                                <span class="dot" id="ketap_ketip"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Notification bar -->
                            </li>
                        </ul>
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>

                </div>
            </div>

            <div class="navbar-custom">
                <div class="container">
                    <div id="navigation">
                        <!-- Navigation Menu-->
                        <ul class="navigation-menu">
                            <li <?php if($master_menu == 'home'){ echo 'class="active"';}else{echo '';} ?> >
                                <a href="<?php echo base_url(); ?>rekam_medik/rk_home_c">
                                    <i class="zmdi zmdi-view-dashboard"></i> <span> Dashboard </span> 
                                </a>
                            </li>
                            <?PHP 
                            $get_menu2 = $this->master_model_m->get_menu_2($id_user, 3);
                            foreach ($get_menu2 as $key => $menu2) {
                            ?>

                                <?PHP if($menu2->LINK == null || $menu2->LINK == ""){ ?>
                                    <li <?php if($master_menu == $menu2->MASTER){ echo 'class="has-submenu active"';}else{echo 'class="has-submenu"';} ?> >
                                        <a href="#"><i class="<?=$menu2->ICON;?>"></i><span> <?=$menu2->NAMA;?> </span> </a>
                                        <ul class="submenu">

                                            <?PHP 
                                                $get_menu3 = $this->master_model_m->get_menu_3($id_user, $menu2->ID);
                                                foreach ($get_menu3 as $key => $menu3) {
                                            ?>

                                            <li <?php if($view == $menu3->VIEW){ echo 'class="active"';}else{echo '';} ?> >
                                                <a href="<?php echo base_url().$menu3->LINK; ?>">
                                                    <i class="fa fa-caret-right"></i> <?=$menu3->NAMA;?>
                                                </a>
                                            </li>

                                            <?PHP } ?>

                                        </ul>
                                    </li>
                                <?PHP } else { ?>
                                    <li <?php if($view == $menu2->VIEW){ echo 'class="active"';}else{echo '';} ?> >
                                        <a href="<?php echo base_url().$menu2->LINK;?>">
                                            <i class="<?=$menu2->ICON;?>"></i> <span> <?=$menu2->NAMA;?> </span> 
                                        </a>
                                    </li>
                                <?PHP } ?>

                            <?PHP } ?>
                        </ul>
                        <!-- End navigation menu  -->
                    </div>
                </div>
            </div>
        </header>
        <!-- End Navigation Bar-->

        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title"><?php echo $subtitle; ?></h4>
                    </div>
                </div>

                <div class="row">
                    <?php $this->load->view($page); ?>
                </div>

                <!-- Footer -->
                <footer class="footer text-right">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6">
                                © 2016 Aplikasi Rumah Sakit
                            </div>
                            <div class="col-xs-6">
                                <ul class="pull-right list-inline m-b-0">
                                    <?php if($level == null){ ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>portal" style="color:#887d59;"><i class="fa fa-th"></i> Portal Depan</a>
                                    </li>
                                    <?php } ?>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-info-circle"></i> Tentang</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-question-circle"></i> Bantuan</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-phone-square"></i> Kontak Kami</a>
                                    </li
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer -->

            </div>
            <!-- end container -->

        </div>

        <!-- Next Antrian Modal -->
        <div id="next_antrian" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content p-0 b-0">
                    <div class="panel panel-color panel-primary">
                        <div class="panel-heading">
                            <button type="button" class="close m-t-5" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="panel-title"> Konfirmasi </h3>
                        </div>
                        <div class="panel-body">
                            <p>Apakah anda ingin melanjutkan ke antrian selanjutnya ?</p>
                        </div>
                        <div class="panel-footer">
                            <center>
                                <button type="button" class="btn btn-inverse " data-dismiss="modal" id="close_next_antrian">Tidak</button>
                                 &nbsp;&nbsp;&nbsp;&nbsp;
                                <button onclick="next_antri();" type="button" class="btn btn-danger"> Ya </button>
                            </center>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Of Next Antrian Modal -->

        <!-- Modal -->
        <div id="popup_pasien_baru">
            <div class="window_pasien_baru">
                <div class="row" id="view_data">
                    <div class="col-md-12">
                        <div class="card-box">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <h4 class="header-title m-t-0">Daftar Pasien Baru</h4>
                                </div>
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered tabel_pasien_home">
                                            <thead>
                                                <tr class="kuning_popup">
                                                    <!-- <th style="text-align:center; vertical-align: middle;"> -->
                                                        <!-- <div class="checkbox checkbox-primary">
                                                            <input id="checkboxAll" type="checkbox" name="centang_semua">
                                                            <label for="checkboxAll">
                                                                &nbsp;
                                                            </label>
                                                        </div> -->
                                                    </th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">No</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">No. RM</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Tgl / Waktu</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Nama Pasien</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">JK</th>
                                                    <th style="color:#fff; text-align:center; vertical-align: middle;">Umur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <center>
                                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal" id="tutup_pas">Tutup</button>
                                    </center>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery  -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/detect.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/fastclick.js"></script>

        <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.blockUI.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/wow.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.scrollTo.min.js"></script>

        <!-- Modal-Effect -->
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/custombox/dist/legacy.min.js"></script>

        <!-- Toastr js -->
        <script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>

        <!--JS Devan-->
        <script src="<?php echo base_url(); ?>js-devan/alert.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>
        <script src="<?php echo base_url(); ?>js-devan/pagination.js"></script>

        <!-- Plugins Js -->
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

        <!-- KNOB JS -->
        <!--[if IE]>
        <script type="text/javascript" src="assets/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <script src="<?php echo base_url(); ?>assets/plugins/jquery-knob/jquery.knob.js"></script>

        <!--Morris Chart
		<script src="<?php //echo base_url(); ?>assets/plugins/morris/morris.min.js"></script>
		<script src="<?php //echo base_url(); ?>assets/plugins/raphael/raphael-min.js"></script>
        -->
        <!-- Dashboard init -->
        <script src="<?php echo base_url(); ?>assets/pages/jquery.dashboard.js"></script>

        <!-- App js -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.core.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery.app.js"></script>

        <script src="<?php echo base_url(); ?>dist/js/datepicker.js"></script>

        <!-- Include English language -->
        <script src="<?php echo base_url(); ?>dist/js/i18n/datepicker.en.js"></script>
        <script src="http://code.responsivevoice.org/responsivevoice.js"></script>

        <script type="text/javascript">
            var snd = new Audio("<?php echo base_url(); ?>sound/nokia_tune_new.mp3"); // buffers automatically when created
        var timer = 0;
        var level = "<?php echo $level; ?>";

        jQuery(document).ready(function() {
            $(".select2").select2();

            get_notif_pasien();

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

            if(level == null || level == ""){
                
            }else{
                timer = setInterval(function () {
                    get_notif_pasien();
                }, 5000);
            }

            $('#li_notif').click(function(){
                snd.pause();
                $('#popup_pasien_baru').show();
                data_pasien_baru();

                $.ajax({
                    url : '<?php echo base_url(); ?>rekam_medik/rk_home_c/ubah_sts_lihat',
                    type : "POST",
                    dataType : "json",
                    success : function(res){
                        
                    }
                });
            });

            $('#tutup_pas').click(function(){
                $('#popup_pasien_baru').fadeOut();
                get_data_rm();
            });

        });

        function get_notif_pasien(){
            $.ajax({
                url : '<?php echo base_url(); ?>rekam_medik/rk_home_c/notif_pasien_baru',
                type : "POST",
                dataType : "json",
                success : function(res){
                    var total = 0;
                    if(res['rj']['TOTAL'] == 0){
                        $('#ketap_ketip').hide();
                        snd.pause();
                    }else{
                        $('#ketap_ketip').show();
                        snd.play();
                        total = res['rj']['TOTAL'];
                        toastr["success"]("Ada pasien baru!");
                    }

                    $('#tot_pasien').html(total);
                }
            });
        }

        function data_pasien_baru(){
            $.ajax({
                url : '<?php echo base_url(); ?>rekam_medik/rk_home_c/data_pasien_baru',
                type : "POST",
                dataType : "json",
                success : function(result){
                    $tr = "";

                    if(result['rj'] == "" || result['rj'] == null){
                        $tr = "<tr><td colspan='7' style='text-align:center;'><b>Data Tidak Ada</b></td></tr>";
                    }else{
                        var no = 0;

                        for(var i=0; i<result['rj'].length; i++){
                            no++;

                            result['rj'][i].WAKTU = result['rj'][i].WAKTU==null?"00:00":result['rj'][i].WAKTU;
                            result['rj'][i].JENIS_KELAMIN = result['rj'][i].JENIS_KELAMIN=="L"?"Laki - Laki":"Perempuan";

                            $tr +=  '<tr>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+no+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result['rj'][i].KODE_PASIEN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result['rj'][i].TANGGAL+' - '+result['rj'][i].WAKTU+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle;">'+result['rj'][i].NAMA+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result['rj'][i].JENIS_KELAMIN+'</td>'+
                                    '   <td style="cursor:pointer; vertical-align:middle; text-align:center;">'+result['rj'][i].UMUR+' Tahun</td>'+
                                    '</tr>';
                        }
                    }

                    $('.tabel_pasien_home tbody').html($tr);
                }
            });
        }
        </script>
    </body>
</html>