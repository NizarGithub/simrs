<!DOCTYPE html>
<?PHP
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="<?=$base_url2;?>material/icon-bill.png">

        <title><?php echo $title; ?></title> 

        <!-- form Uploads --> 
        <link href="<?=$base_url2;?>assets/plugins/fileuploads/css/dropify.min.css" rel="stylesheet" type="text/css" />

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="<?=$base_url2;?>assets/plugins/morris/morris.css">

        <!-- Notification css (Toastr) -->
        <link href="<?=$base_url2;?>assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

        <!-- Custom box css -->
        <link href="<?=$base_url2;?>assets/plugins/custombox/dist/custombox.min.css" rel="stylesheet">

        <!-- DataTables -->
        <link href="<?=$base_url2;?>assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- Plugins css-->
        <link href="<?=$base_url2;?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
        <link href="<?=$base_url2;?>assets/plugins/select2/dist/css/select2.css" rel="stylesheet" type="text/css">
        <link href="<?=$base_url2;?>assets/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">

        <link href="<?=$base_url2;?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?=$base_url2;?>assets/dialog/css/reset.css"> <!-- CSS reset -->
        <link rel="stylesheet" href="<?=$base_url2;?>assets/dialog/css/style.css"> <!-- Resource style -->
        <link href="<?=$base_url2;?>assets/custom/css/popup.css" rel="stylesheet">

        <link href="<?=$base_url2;?>dist/css/datepicker.css" rel="stylesheet" type="text/css">
        <link href="<?=$base_url2;?>assets/custom/css/jtree.css" rel="stylesheet" type="text/css" />

        <!--CSS Devan-->
        <link href="<?=$base_url2;?>css-devan/warna.css" rel="stylesheet" type="text/css" />
        <link href="<?=$base_url2;?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?=$base_url2;?>assets/js/modernizr.min.js"></script>

    </head>


    <body>

        <?PHP 
        $sess_user = $this->session->userdata('masuk_rs');
        $id_user = $sess_user['id'];
        $user = $this->master_model_m->get_user_info($id_user);

        $is_operator = $this->master_model_m->is_operator($id_user, 'billing');
        ?>

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main" style="background-color:#fdb94e; height:60px;">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="<?php echo base_url(); ?>kepeg/kepeg_home_c" class="logo">
                            <img src="<?=$base_url2;?>picture/jtech-logo.png" style="max-width:180px; max-height:50px; position: absolute;">
                        </a>
                    </div>
                    <!-- End Logo container-->

                    <!-- LOKET -->
                    <?PHP 
                    if(count($is_operator) > 0){ 
                        $get_loket = $this->master_model_m->getLoket($id_user, 'billing');
                        $get_jml_antrian = $this->master_model_m->getJmlAntrian($get_loket->KODE_ANTRIAN);
                    ?>
                    <center>
                    <div style="width: 87%; position: absolute; float: left; margin-top: 7px;">
                        <button type="button" class="btn btn-primary waves-effect waves-light w-md m-b-5" style="padding-top: 10px; padding-bottom: 10px;"> 
                            <b id="nama_loket_antrian_txt"> <?=strtoupper($get_loket->NAMA_LOKET);?> </b> 
                        </button>

                        <button type="button" class="btn btn-danger" style="margin-left: 100px; font-size: 32px; margin-top: -8px;"> 
                            <b><?=$get_loket->KODE;?>-<font id="jml_antrian_txt"><?=count($get_jml_antrian) + 0;?></font></b> 

                            <input type="hidden" id="kode_antrian_now" value="<?=$get_loket->KODE;?>" />
                            <input type="hidden" id="jml_antrian_now" value="<?=count($get_jml_antrian) + 0;?>" />
                            <input type="hidden" id="id_antrian_now" value="<?=$get_loket->KODE_ANTRIAN;?>" />
                        </button>

                        <button class="btn btn-purple waves-effect waves-light m-b-5" style="margin-left: 40px;" onclick="panggil_antrian();"> 
                           <i class="fa fa-bullhorn m-r-5"></i>   <span> Panggil </span> 
                        </button>

                        <button class="btn btn-info waves-effect waves-light m-b-5" style="margin-left: 10px;" data-toggle="modal" data-target="#next_antrian"> 
                            <span> Berikutnya </span> <i class="fa fa-chevron-circle-right m-r-5"></i>  
                        </button>

                        
                    </div>
                    </center>
                    <?PHP } ?>
                    <!-- END OF LOKET -->

                    <div class="menu-extras">

                        <ul class="nav navbar-nav navbar-right pull-right" style="background-color:#ed8b00;">
                            <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs" style="margin-right:0px;">
                                    <h5 style="color:#FFF; font-size: 14px; font-weight:bold;"><?=strtoupper($user->NAMA);?></h5>
                                    <h6 style="color:yellow; font-size: 13px; font-weight:bold;"> <?=$user->JABATAN;?> </h6>
                                </form>
                            </li>
                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="<?=$base_url2;?>files/foto_pegawai/<?=$user->FOTO;?>" alt="user-img" class="img-circle user-img">
                                    <div class="user-status away"><i class="zmdi zmdi-dot-circle"></i></div>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url(); ?>portal"><i class="fa fa-th m-r-5"></i> Portal Depan</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-user m-r-5"></i> Profile</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-settings m-r-5"></i> Settings</a></li>
                                    <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> Lock screen</a></li>
                                    <li><a href="<?=base_url();?>logout"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                </ul>
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
                                <a href="<?php echo base_url(); ?>billing/billing_home_c">
                                    <i class="fa fa-calculator"></i> <span> Billing </span> 
                                </a>
                            </li>

                            <?PHP 
                            $get_menu2 = $this->master_model_m->get_menu_2($id_user, 1);
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
                                                    <i class="fa fa-caret-right"></i> &nbsp; <?=$menu3->NAMA;?>
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
                <footer class="footer text-right" style="position: relative;">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6">
                                © 2016 Aplikasi Rumah Sakit
                            </div>
                            <div class="col-xs-6">
                                <ul class="pull-right list-inline m-b-0">
                                    <li>
                                        <a href="<?php echo base_url(); ?>portal" style="color:#887d59;"><i class="fa fa-th"></i> Portal Depan</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-info-circle"></i> Tentang</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-question-circle"></i> Bantuan</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#887d59;"><i class="fa fa-phone-square"></i> Kontak Kami</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer -->

            </div>
            <!-- end container -->



            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">T`H`E`M`E`L`O`C`K`.`C`O`M`</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">Michael Zenaty</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-info">
                                    <i class="zmdi zmdi-account"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Signup</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">5 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-pink">
                                    <i class="zmdi zmdi-comment"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Message received</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">James Anderson</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 days ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-warning">
                                    <i class="zmdi zmdi-settings"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">Settings</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

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

        <!-- jQuery  -->
        <script src="<?=$base_url2;?>assets/js/jquery.min.js"></script>
        <script src="<?=$base_url2;?>assets/js/bootstrap.min.js"></script>
        <script src="<?=$base_url2;?>assets/js/detect.js"></script>
        <script src="<?=$base_url2;?>assets/js/fastclick.js"></script>

        <script src="<?=$base_url2;?>assets/js/jquery.slimscroll.js"></script>
        <script src="<?=$base_url2;?>assets/js/jquery.blockUI.js"></script>
        <script src="<?=$base_url2;?>assets/js/waves.js"></script>
        <script src="<?=$base_url2;?>assets/js/wow.min.js"></script>
        <script src="<?=$base_url2;?>assets/js/jquery.nicescroll.js"></script>
        <script src="<?=$base_url2;?>assets/js/jquery.scrollTo.min.js"></script>

        <!-- Datatables-->
        <script src="<?=$base_url2;?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/buttons.bootstrap.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/jszip.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/pdfmake.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/vfs_fonts.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/buttons.html5.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/buttons.print.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.keyTable.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/responsive.bootstrap.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/datatables/dataTables.scroller.min.js"></script>

        <!-- Modal-Effect -->
        <script src="<?=$base_url2;?>assets/plugins/custombox/dist/custombox.min.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/custombox/dist/legacy.min.js"></script>

        <!-- Toastr js -->
        <script src="<?=$base_url2;?>assets/plugins/toastr/toastr.min.js"></script>

        <!--JS Devan-->
        <script src="<?=$base_url2;?>js-devan/alert.js"></script>
        <script src="<?=$base_url2;?>js-devan/js-form.js"></script>
        <script src="<?=$base_url2;?>js-devan/pagination.js"></script>

        <!-- Plugins Js -->
        <script src="<?=$base_url2;?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>
        <script src="<?=$base_url2;?>assets/plugins/moment/moment.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="<?=$base_url2;?>assets/plugins/select2/dist/js/select2.min.js" type="text/javascript"></script>

        <!-- KNOB JS -->
        <!--[if IE]>
        <script type="text/javascript" src="assets/plugins/jquery-knob/excanvas.js"></script>
        <![endif]-->
        <script src="<?=$base_url2;?>assets/plugins/jquery-knob/jquery.knob.js"></script>

        <!--Morris Chart
        <script src="<?php //echo base_url(); ?>assets/plugins/morris/morris.min.js"></script>
        <script src="<?php //echo base_url(); ?>assets/plugins/raphael/raphael-min.js"></script>
        -->
        <!-- Dashboard init -->
        <script src="<?=$base_url2;?>assets/pages/jquery.dashboard.js"></script>

        <!-- file uploads js -->
        <script src="<?=$base_url2;?>assets/plugins/fileuploads/js/dropify.min.js"></script>
 
 
        <!-- App js -->
        <script src="<?=$base_url2;?>assets/js/jquery.core.js"></script>
        <script src="<?=$base_url2;?>assets/js/jquery.app.js"></script>
        <script src="<?=$base_url2;?>assets/dialog/js/main.js"></script>
        <script src="<?=$base_url2;?>assets/custom/js/js-form.js"></script>
        <script src="<?=$base_url2;?>assets/custom/js/plugin.js"></script>
        <script src="http://code.responsivevoice.org/responsivevoice.js"></script>

        <!-- Include English language 
        <script src="<?php echo base_url(); ?>dist/js/datepicker.js"></script>
        <script src="<?php echo base_url(); ?>dist/js/i18n/datepicker.en.js"></script>
        -->

        <script type="text/javascript">
        jQuery(document).ready(function() {

            <?PHP if($msg == 1){?>
            notif_simpan();
            <?PHP } ?>

            <?PHP if($msg == 2){?>
            notif_ubah();
            <?PHP } ?>

            <?PHP if($msg == 3){?>
            notif_hapus();
            <?PHP } ?>

            $('#datatable').dataTable();
            $('.datatabel').dataTable();
            $('#datatable-keytable').DataTable( { keys: true } );
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable( { ajax: "<?php echo base_url(); ?>assets/plugins/datatables/json/scroller-demo.json", deferRender: true, scrollY: 380, scrollCollapse: true, scroller: true } );
            var table = $('#datatable-fixed-header').DataTable( { fixedHeader: true } );

            $(".select2").select2();

        });


        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop a file here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove',
                'error': 'Ooops, something wrong appended.'
            },
            error: {
                'fileSize': 'The file size is too big (1M max).'
            }
        });

        function next_antri(){
            var kode_antrian = $('#kode_antrian_now').val();
            var jml_antrian  = $('#jml_antrian_now').val();
            var id_antrian   = $('#id_antrian_now').val();
            var nama_loket_antrian_txt   = $('#nama_loket_antrian_txt').html();

            $.ajax({
                url : '<?php echo base_url(); ?>billing/billing_home_c/next_antri',
                data : {
                    kode_antrian:kode_antrian,
                    jml_antrian:parseInt(jml_antrian) + 1,
                    id_antrian:id_antrian,
                },
                type : "POST",
                dataType : "json",
                success : function(row){
                    if(row == 1){
                        var jml_antrian_next = parseInt(jml_antrian) + 1;
                        $('#jml_antrian_txt').html(jml_antrian_next);
                        $('#jml_antrian_now').val(jml_antrian_next);

                        $('#close_next_antrian').click();

                        responsiveVoice.speak(
                          "Pengunjung dengan nomor antrian, "+kode_antrian+","+jml_antrian_next+", silahkan menuju ke "+nama_loket_antrian_txt+". Terima kasih.",
                          "Indonesian Female",
                          {
                           pitch: 1, 
                           rate: 1, 
                           volume: 1
                          }
                         );

                    }
                }
            });
        }

        function panggil_antrian(){
            var kode_antrian = $('#kode_antrian_now').val();
            var jml_antrian  = $('#jml_antrian_now').val();
            var nama_loket_antrian_txt   = $('#nama_loket_antrian_txt').html();

            responsiveVoice.speak(
              "Pengunjung dengan nomor antrian, "+kode_antrian+","+jml_antrian+", silahkan menuju ke "+nama_loket_antrian_txt+". Terima kasih.",
              "Indonesian Female",
              {
               pitch: 1, 
               rate: 1, 
               volume: 1
              }
             );
        }

        </script>
    </body>
</html>