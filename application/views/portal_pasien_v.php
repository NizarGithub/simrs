<?php
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta content="Admin dashboard html template" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="<?php echo $base_url2; ?>front_pasien/favicon.png" rel="shortcut icon">
    <link href="<?php echo $base_url2; ?>front_pasien/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="//fast.fonts.net/cssapi/175a63a1-3f26-476a-ab32-4e21cbdb8be2.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/dropzone/dist/dropzone.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="<?php echo $base_url2; ?>front_pasien/css/main.css?version=2.6" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url2; ?>slider/css/style.css">
  </head>
  <body onload="startTime();">
  <?php
    $bulan = array(
        1 =>    "Januari", 2  =>"Februari", 3  =>"Maret", 4 =>"April",
        5 =>    "Mei", 6  =>"Juni", 7  =>"Juli", 8 =>"Agustus",
        9 =>    "September", 10 =>"Oktober", 11 =>"November", 12 =>"Desember"
    );
  ?>
    <div class="all-wrapper menu-side with-side-panel">
      <div class="layout-w">
        <div class="menu-mobile menu-activated-on-click color-scheme-dark">
          <div class="mm-logo-buttons-w">
            <a class="mm-logo" href="<?php echo base_url(); ?>"><img src="<?php echo $base_url2; ?>front_pasien/img/logo.png"><span>SIMRS</span></a>
            <div class="mm-buttons">
              <div class="content-panel-open">
                <div class="os-icon os-icon-grid-circles"></div>
              </div>
              <div class="mobile-menu-trigger">
                <div class="os-icon os-icon-hamburger-menu-1"></div>
              </div>
            </div>
          </div>
          <div class="menu-and-user">
            <div class="logged-user-w">
              <div class="avatar-w">
                <img alt="" src="<?php echo $base_url2; ?>front_pasien/img/avatar1.jpg">
              </div>
              <div class="logged-user-info-w">
                <div class="logged-user-name">
                  SIMRS
                </div>
              </div>
            </div>
            <ul class="main-menu">
              <li>
                <a href="<?php echo base_url();?>">
                  <div class="icon-w">
                    <div class="os-icon os-icon-window-content"></div>
                  </div>
                  <?php if($master_menu == 'dashboard'){echo '<b>Dashboard</b>';}else{echo 'Dashboard';}?>
                </a>
              </li>
            </ul>
            <div class="mobile-menu-magic">
              <h4>
                Bla Bla
              </h4>
              <p>
                Apa Aja
              </p>
              <div class="btn-w">
                <a href="javascript:;" class="btn btn-white btn-rounded">Klik Disini</a>
              </div>
            </div>

          </div>
        </div>
        <div class="desktop-menu menu-side-w menu-activated-on-click">
          <div class="logo-w">
            <a class="logo" href="<?php echo base_url(); ?>"><img src="<?php echo $base_url2; ?>front_pasien/img/logo.png"><span><b>SIMRS</b></span></a>
          </div>
          <div class="menu-and-user">
            <div class="logged-user-w">
              <div class="avatar-w">
                <img alt="" src="<?php echo $base_url2; ?>picture/hospital_red_98257.jpg">
              </div>
              
              <div class="logged-user-menu">
                <div class="avatar-w">
                  <img alt="" src="<?php echo $base_url2; ?>picture/hospital_red_98257.jpg">
                </div>
                <div class="logged-user-info-w">
                  <div class="logged-user-name">
                    SIMRS
                  </div>
                </div>
                <div class="bg-icon">
                  <i class="os-icon os-icon-wallet-loaded"></i>
                </div>
                <ul>
                  <li>
                    <a href="#"><i class="os-icon os-icon-mail-01"></i><span>Incoming Mail</span></a>
                  </li>
                  <li>
                    <a href="users_profile_big.html"><i class="os-icon os-icon-user-male-circle2"></i><span>Profile Details</span></a>
                  </li>
                  <li>
                    <a href="#"><i class="os-icon os-icon-others-43"></i><span>Notifications</span></a>
                  </li>
                </ul>
              </div>
            </div>

            <ul class="main-menu">
              <li>
                <a href="<?php echo base_url(); ?>">
                  <div class="icon-w">
                    <div class="os-icon os-icon-window-content"></div>
                  </div>
                  <?php if($master_menu == 'dashboard'){echo '<b>Dashboard</b>';}else{echo 'Dashboard';}?>
                </a>
              </li>
            </ul>

            <div class="side-menu-magic">
              <h4 id="waktu_txt">
                00:00:00
              </h4>
              <div class="btn-w">
                <a href="javascript:;" class="btn btn-white btn-rounded"><?php echo date('d');?> <?php echo $bulan[date('n')];?> <?php echo date('Y');?></a>
              </div>
            </div>

            <div class="side-menu-magic">
              <h4>
                Bla Bla
              </h4>
              <p>
                Apa Aja
              </p>
              <div class="btn-w">
                <a href="javascript:;" class="btn btn-white btn-rounded">Klik Disini</a>
              </div>
            </div>

          </div>
        </div>

        <div class="content-w">
          <ul class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span><?php echo $subtitle; ?></span>
            </li>
          </ul>
          <div class="content-panel-toggler">
            <i class="os-icon os-icon-grid-squares-22"></i><span>Sidebar</span>
          </div>
          <div class="content-i">
            <div class="content-box">
              <div class="row">
                <div class="col-sm-12">
                  <div class="element-wrapper" style="padding-bottom: 0px;">
                    <h6 class="element-header">
                      Slider
                    </h6>
                    <div class="element-content" style="height: 375px;">
                      <div class="slider">
                        <div class="slide_viewer">
                          <div class="slide_group">
                            <div class="slide">
                              <img src="<?php echo base_url(); ?>picture/slider/hospital-wallpaper-12.jpg" style="max-height: 350px; width: 100%;">
                            </div>
                            <div class="slide">
                              <img src="<?php echo base_url(); ?>picture/slider/blog-guest-fox-susannah-2017-03-09-shutterstock_189632216-banner-edit.jpg" style="max-height: 350px; width: 100%;">
                            </div>
                            <div class="slide">
                              <img src="<?php echo base_url(); ?>picture/slider/h1.jpg" style="max-height: 350px; width: 100%;">
                            </div>
                            <div class="slide">
                              <img src="<?php echo base_url(); ?>picture/slider/hospital-wallpaper-12.jpg" style="max-height: 350px; width: 100%;">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="directional_nav">
                        <div class="previous_btn" title="Previous">
                          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="65px" height="65px" viewBox="-11 -11.5 65 66">
                            <g>
                              <g>
                                <path fill="#474544" d="M-10.5,22.118C-10.5,4.132,4.133-10.5,22.118-10.5S54.736,4.132,54.736,22.118
                            c0,17.985-14.633,32.618-32.618,32.618S-10.5,40.103-10.5,22.118z M-8.288,22.118c0,16.766,13.639,30.406,30.406,30.406 c16.765,0,30.405-13.641,30.405-30.406c0-16.766-13.641-30.406-30.405-30.406C5.35-8.288-8.288,5.352-8.288,22.118z"/>
                                <path fill="#474544" d="M25.43,33.243L14.628,22.429c-0.433-0.432-0.433-1.132,0-1.564L25.43,10.051c0.432-0.432,1.132-0.432,1.563,0 c0.431,0.431,0.431,1.132,0,1.564L16.972,21.647l10.021,10.035c0.432,0.433,0.432,1.134,0,1.564  c-0.215,0.218-0.498,0.323-0.78,0.323C25.929,33.569,25.646,33.464,25.43,33.243z"/>
                              </g>
                            </g>
                          </svg>
                        </div>
                        <div class="next_btn" title="Next">
                          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="65px" height="65px" viewBox="-11 -11.5 65 66">
                            <g>
                              <g>
                                <path fill="#474544" d="M22.118,54.736C4.132,54.736-10.5,40.103-10.5,22.118C-10.5,4.132,4.132-10.5,22.118-10.5  c17.985,0,32.618,14.632,32.618,32.618C54.736,40.103,40.103,54.736,22.118,54.736z M22.118-8.288  c-16.765,0-30.406,13.64-30.406,30.406c0,16.766,13.641,30.406,30.406,30.406c16.768,0,30.406-13.641,30.406-30.406 C52.524,5.352,38.885-8.288,22.118-8.288z"/>
                                <path fill="#474544" d="M18.022,33.569c 0.282,0-0.566-0.105-0.781-0.323c-0.432-0.431-0.432-1.132,0-1.564l10.022-10.035      L17.241,11.615c 0.431-0.432-0.431-1.133,0-1.564c0.432-0.432,1.132-0.432,1.564,0l10.803,10.814c0.433,0.432,0.433,1.132,0,1.564 L18.805,33.243C18.59,33.464,18.306,33.569,18.022,33.569z"/>
                              </g>
                            </g>
                          </svg>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <?php $this->load->view($page); ?>
              
              <!-- <div class="floated-chat-btn">
                <i class="os-icon os-icon-mail-07"></i><span>Demo Chat</span>
              </div>
              <div class="floated-chat-w">
                <div class="floated-chat-i">
                  <div class="chat-close">
                    <i class="os-icon os-icon-close"></i>
                  </div>
                  <div class="chat-head">
                    <div class="user-w with-status status-green">
                      <div class="user-avatar-w">
                        <div class="user-avatar">
                          <img alt="" src="img/avatar1.jpg">
                        </div>
                      </div>
                      <div class="user-name">
                        <h6 class="user-title">
                          John Mayers
                        </h6>
                        <div class="user-role">
                          Account Manager
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="chat-messages">
                    <div class="message">
                      <div class="message-content">
                        Hi, how can I help you?
                      </div>
                    </div>
                    <div class="date-break">
                      Mon 10:20am
                    </div>
                    <div class="message">
                      <div class="message-content">
                        Hi, my name is Mike, I will be happy to assist you
                      </div>
                    </div>
                    <div class="message self">
                      <div class="message-content">
                        Hi, I tried ordering this product and it keeps showing me error code.
                      </div>
                    </div>
                  </div>
                  <div class="chat-controls">
                    <input class="message-input" placeholder="Type your message here..." type="text">
                    <div class="chat-extra">
                      <a href="#"><span class="extra-tooltip">Attach Document</span><i class="os-icon os-icon-documents-07"></i></a><a href="#"><span class="extra-tooltip">Insert Photo</span><i class="os-icon os-icon-others-29"></i></a><a href="#"><span class="extra-tooltip">Upload Video</span><i class="os-icon os-icon-ui-51"></i></a>
                    </div>
                  </div>
                </div>
              </div> -->

            </div>
            <div class="content-panel">
              <div class="content-panel-close">
                <i class="os-icon os-icon-close"></i>
              </div>

              <div class="element-wrapper" style="padding-bottom: 0px;">
                <?php
                  $dete = $this->master_model_m->get_akses_antrian('pasien');
                  if($dete == null || $dete == ""){

                  }else{
                    foreach ($dete as $key => $value) {
                ?>
                <div class="element-box">
                  <div class="padded m-b">
                    <div class="centered-header">
                      <h6><?php echo $value->NAMA_LOKET; ?></h6>
                    </div>
                    <div class="row">
                      <div class="col-6 b-r b-b">
                        <div class="el-tablo centered padded-v-big highlight bigger">
                          <div class="label">
                            Kode
                          </div>
                          <div class="value"><?php echo $value->KODE; ?></div>
                        </div>
                      </div>
                      <div class="col-6 b-b">
                        <div class="el-tablo centered padded-v-big highlight bigger">
                          <div class="label">
                            Antrian
                          </div>
                          <div class="value" id="nomor_<?php echo $value->STS_LOKET; ?>">
                            -
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="centered-header">
                    <h6>Antrian Sekarang</h6>
                  </div>
                </div>
                <?php
                    }
                ?>
                <input type="hidden" id="id_offline" value="<?php echo $value->ID; ?>">
                <input type="hidden" id="status_offline" value="<?php echo $value->STS_LOKET; ?>">
                <?php
                  }
                ?>
              </div>

              <div class="element-wrapper" style="padding-bottom: 0px;">
                <h6 class="element-header">
                  Aktivitas Hari Ini
                </h6>
                <div class="element-box-tp">
                  <div class="activity-boxes-w" id="tracking_pasien">
                    
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="display-type"></div>
    </div>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/moment/moment.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/chart.js/dist/Chart.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/ckeditor/ckeditor.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/bootstrap-validator/dist/validator.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/dropzone/dist/dropzone.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/editable-table/mindmup-editabletable.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/bootstrap/js/dist/util.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/bower_components/bootstrap/js/dist/tab.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo $base_url2; ?>front_pasien/js/main.js?version=2.6"></script>
    <script src="<?php echo $base_url2; ?>slider/js/index.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        // get_antri_on();
        get_antri_off();

        // setInterval(function () {
        //     get_antri_on();
        // }, 3000);

        setInterval(function () {
            get_antri_off();
        }, 4000);

        setInterval(function () {
            get_tracking_pasien();
        }, 5000);
    });

    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('waktu_txt').innerHTML = h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }

    function get_antri_off(){
        var id_kode_antrian = $('#id_offline').val();
        var status = $('#status_offline').val();
        
        $.ajax({
            url : '<?php echo base_url(); ?>portal_pasien/get_antrian_off',
            data : {
              id_kode_antrian:id_kode_antrian,
              status:status
            },
            type : "POST",
            dataType : "json",
            success : function(res){
                if(res == null || res == ""){
                    $('#nomor_'+status).html('1');
                }else{
                  for(var i=0; i<res.length; i++){
                    $('#nomor_'+status).html(res[i].NOMOR_ANTRIAN);
                  }
                }
            }
        });
    }

    function get_tracking_pasien(){
        $.ajax({
            url : '<?php echo base_url(); ?>portal_pasien/get_tracking_pasien',
            type : "GET",
            dataType : "json",
            success : function(res){
                $div = '';

                if(res == null || res == ""){
                    $div = '<div class="pt-btn">'+
                            '     <a class="btn btn-info" href="javascript:;">Belum Ada Aktivitas</a>'+
                            '</div>';
                }else{
                  for(var i=0; i<res.length; i++){
                      var stt = '';
                      var foto = '';

                      if(res[i].STS_POSISI == '1'){
                          stt = 'Posisi di : '+res[i].NAMA_POLI;
                      }else if(res[i].STS_POSISI == '2'){
                          stt = 'Posisi di : Laborat';
                      }

                      if(res[i].JENIS_KELAMIN == 'L'){
                          foto = '<?php echo base_url(); ?>picture/icons8-circundado-usuario-hombre-tipo-de-piel-1-2-50.png';
                      }else{
                          foto = '<?php echo base_url(); ?>picture/icons8-circled-user-female-skin-type-1-2-50.png';
                      }

                      //10-08-2018
                      var tanggal = res[i].TANGGAL;
                      var d = tanggal.substr(0,2);
                      var m = tanggal.substr(3,2);
                      var y = tanggal.substr(6);
                      var dmy = m+'/'+d+'/'+y;
                      var strtdatetime = dmy+' '+res[i].WAKTU;
                      var datetime = new Date(strtdatetime).getTime();
                      var now = new Date().getTime();

                      if (isNaN(datetime)) {
                          return "";
                      }

                      //console.log(datetime + " " + now);

                      if (datetime < now) {
                          var milisec_diff = now - datetime;
                      } else {
                          var milisec_diff = datetime - now;
                      }

                      var days = Math.floor(milisec_diff / 1000 / 60 / (60 * 24));
                      var date_diff = new Date(milisec_diff);

                      var msec = milisec_diff;
                      var hh = Math.floor(msec / 1000 / 60 / 60);
                      msec -= hh * 1000 * 60 * 60;
                      var mm = Math.floor(msec / 1000 / 60);
                      msec -= mm * 1000 * 60;
                      var ss = Math.floor(msec / 1000);
                      msec -= ss * 1000;

                      var daylabel = "";
                      if (days > 0) {
                          var grammar = " ";
                          if (days > 1) grammar = "s " 
                          var hrreset = days * 24;
                          hh = hh - hrreset;
                          daylabel = days + " Day" + grammar ;
                      }


                      //  Format Hours
                      var hourtext = '00';
                      hourtext = String(hh);
                      if (hourtext.length == 1) { hourtext = '0' + hourtext };

                      //  Format Minutes
                      var mintext = '00';
                      mintext = String(mm); 
                      if (mintext.length == 1) { mintext = '0' + mintext };

                      $div += '<div class="activity-box-w">'+
                              '  <div class="activity-time">'+mintext+' Min'+'</div>'+
                              '  <div class="activity-box">'+
                              '    <div class="activity-avatar">'+
                              '      <img alt="" src="'+foto+'">'+
                              '    </div>'+
                              '    <div class="activity-info">'+
                              '      <div class="activity-role">'+res[i].NAMA_PASIEN+'</div>'+
                              '      <strong class="activity-title">'+stt+'</strong>'+
                              '    </div>'+
                              '  </div>'+
                              '</div>';
                  }
                }

                $('#tracking_pasien').html($div);
            }
        });
    }
    </script>
  </body>
</html>