<!DOCTYPE html>
<?PHP
$base_url2 =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ?  "https" : "http");
$base_url2 .=  "://".$_SERVER['HTTP_HOST'];
$base_url2 .=  str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
?>
<html class="no-js">
<head>
   <meta charset="utf-8" />
   <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
   <!-- Mobile viewport optimized: h5bp.com/viewport -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   

   <title><?php echo $title; ?></title>

   <meta name="robots" content="noindex, nofollow">
   <meta name="description" content="BootMetro : Simple and complete web UI framework to create web apps with Windows 8 Metro user interface." />
   <meta name="keywords" content="bootmetro, modern ui, modern-ui, metro, metroui, metro-ui, metro ui, windows 8, metro style, bootstrap, framework, web framework, css, html" />
   <meta name="author" content="AozoraLabs by Marcello Palmitessa"/>
   
   <!-- remove or comment this line if you want to use the local fonts -->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>

   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro.css">
   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-responsive.css">
   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-icons.css">
   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/bootmetro-ui-light.css">
   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/datepicker.css">

   <!--  these two css are to use only for documentation -->
   <link rel="stylesheet" type="text/css" href="<?=$base_url2;?>assets/portal/css/demo.css">
   <link href="<?=$base_url2;?>assets/portal/css/bootstrap-responsive.css" rel="stylesheet">

   <!-- Le fav and touch icons -->
   <link rel="shortcut icon" href="<?php echo $base_url2; ?>picture/hospital.ico">
   <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-144-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-114-precomposed.png">
   <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-72-precomposed.png">
   <link rel="apple-touch-icon-precomposed" href="<?=$base_url2;?>assets/portal/ico/apple-touch-icon-57-precomposed.png">
  
   <!-- All JavaScript at the bottom, except for Modernizr and Respond.
      Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
      For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->
   <script src="<?=$base_url2;?>assets/portal/js/modernizr-2.6.2.min.js"></script>
</head>

<body>
   <div id="wrap">
   
      <!-- Header
      ================================================== -->
      <div id="nav-bar" class="">
         <div class="pull-left">
            <div id="header-container">
               <h5>SISTEM INFORMASI RUMAH SAKIT</h5>
               <div class="dropdown">
                  <a class="header-dropdown dropdown-toggle" href="javascript:;" style="color:#FFF;">
                     MY COMPANY
                  </a>
            </div>
            </div>
         </div>
         <div class="pull-right" style="margin-right: 40px;">
            <div id="top-info" class="pull-right">
            <a id="settings_old" href="javascript:;" class="win-command pull-left">
                <img class="win-commandicon win-commandring" src="<?=$base_url2;?>picture/hospital.ico">
            </a>
            <div class="pull-right">
               <h3><?=strtoupper('user');?></h3>
               <h4><?='Pasien';?></h4>
            </div>
         </div>
      </div>
      </div>
   
      <!--<div id="metro-container" class="-container">-->
         <!--<div class="row">-->
            <!--<div id="hub" class="metro">-->
               <br><br><br>
               <div class="metro panorama" style="margin: 0 15px; width: 90%;">
                  <div class="panorama-sections" style="width: auto !important;">   
                     <div class="panorama-section tile-span-12" style="margin-right: 20px;">
                        <a class="tile app bg-color-darken" href="<?php echo base_url(); ?>antrian/antrian_home_c">
                          <div class="image-wrapper" style="margin-top: -5px;">
                              <span class="icon icon-users"></span>
                          </div>
                          <div class="textover-wrapper transparent" style="padding: 2px 0; width: 100%;">
                             <div class="text2" style="text-align:center; margin-top: 12px;"><b>ANTRIAN</b></div>
                          </div>
                        </a>

                        <a class="tile app bg-color-red" href="<?php echo base_url(); ?>antrian/antrian_home_c">
                          <div class="image-wrapper" style="margin-top: -5px;">
                              <span class="icon icon-users"></span>
                          </div>
                          <div class="textover-wrapper transparent" style="padding: 2px 0; width: 100%;">
                             <div class="text2" style="text-align:center; margin-top: 12px;"><b>PROFIL DOKTER</b></div>
                          </div>
                        </a>
                     </div>
                  </div>
               </div>
            <!--</div>-->
         <!--</div>-->
      <!--</div>-->
   
   </div>

   <!-- Grab Google CDN's jQuery. fall back to local if necessary -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
   <script>window.jQuery || document.write("<script src='assets/js/jquery-1.8.3.min.js'>\x3C/script>")</script>

   <!--[if IE 7]>
   <script type="text/javascript" src="scripts/bootmetro-icons-ie7.js">
   <![endif]-->

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-panorama.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-pivot.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootmetro-charms.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/bootstrap-datepicker.js"></script>

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/jquery.mousewheel.min.js"></script>
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/jquery.touchSwipe.min.js"></script>

   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/holder.js"></script>
   <!--<script type="text/javascript" src="assets/portal/js/perfect-scrollbar.with-mousewheel.min.js"></script>-->
   <script type="text/javascript" src="<?=$base_url2;?>assets/portal/js/demo.js"></script>


   <script type="text/javascript">


   </script>
</body>
</html>