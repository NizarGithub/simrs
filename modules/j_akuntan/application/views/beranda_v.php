<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title> Akuntansi | Sistem Informasi Rumah Sakit </title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- styles -->
<link href="<?=base_url();?>material/css/bootstrap.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/jquery.gritter.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url();?>material/css/font-awesome.css">
<!--[if IE 7]>
<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
<![endif]-->
<link href="<?=base_url();?>material/css/tablecloth.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/styles.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/theme-default.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/chosen.css" rel="stylesheet">
<link href="<?=base_url();?>material/css/style-devan.css" rel="stylesheet">
<link rel="stylesheet" href="<?=base_url();?>material/dialog/css/reset.css"> <!-- CSS reset -->
<link rel="stylesheet" href="<?=base_url();?>material/dialog/css/style.css"> <!-- Resource style -->
<link rel="stylesheet" href="<?=base_url();?>jgrowl/jquery.jgrowl.css" type="text/css"/>

<script src="<?=base_url();?>material/dialog/js/modernizr.js"></script>


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'>
<!--fav and touch icons -->
<link rel="shortcut icon" href="<?=base_url();?>material/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=base_url();?>material/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=base_url();?>material/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=base_url();?>material/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?=base_url();?>material/ico/apple-touch-icon-57-precomposed.png">

<!--============ javascript ===========-->
<script src="<?=base_url();?>material/js/jquery.js"></script>
<script src="<?=base_url();?>material/js/jquery-ui-1.10.1.custom.min.js"></script>
<script src="<?=base_url();?>material/js/bootstrap.js"></script>
<script src="<?=base_url();?>material/js/jquery.sparkline.js"></script>
<script src="<?=base_url();?>material/js/bootstrap-fileupload.js"></script>
<script src="<?=base_url();?>material/js/jquery.metadata.js"></script>
<script src="<?=base_url();?>material/js/jquery.tablesorter.min.js"></script>
<script src="<?=base_url();?>material/js/jquery.tablecloth.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.selection.js"></script>
<script src="<?=base_url();?>material/js/excanvas.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.pie.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.stack.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.time.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.tooltip.js"></script>
<script src="<?=base_url();?>material/js/jquery.flot.resize.js"></script>
<script src="<?=base_url();?>material/js/jquery.collapsible.js"></script>
<script src="<?=base_url();?>material/js/accordion.nav.js"></script>
<script src="<?=base_url();?>material/js/jquery.gritter.js"></script>
<script src="<?=base_url();?>material/js/tiny_mce/jquery.tinymce.js"></script>
<script src="<?=base_url();?>material/js/custom.js"></script>
<script src="<?=base_url();?>material/js/respond.min.js"></script>
<script src="<?=base_url();?>material/js/ios-orientationchange-fix.js"></script>
<script src="<?=base_url();?>material/js/chosen.jquery.js"></script>
<script src="<?=base_url();?>material/dialog/js/main.js"></script>
<script src="<?=base_url();?>material/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?=base_url();?>material/js/date.js"></script>
<script src="<?=base_url();?>material/js/daterangepicker.js"></script>
<script src="<?=base_url();?>material/js/js-form.js"></script>
<script src="<?=base_url();?>material/js/plugin.js"></script>
<script type="text/javascript" src="<?=base_url();?>jgrowl/alert.js"></script>
<script type="text/javascript" src="<?=base_url();?>jgrowl/jquery.jgrowl.js"></script>
<script src="<?=base_url();?>material/canvas/canvasjs.min.js"></script>
<script>

<?PHP if($page == 'edit_transaksi_penjualan_v'){ ?>
$(document).ready(function() {
  cek_islunas();

  var id_pajak = $('#id_pajak_sel').val();
  hitung_pajak(id_pajak);

  var id_pel = $('#pelanggan_sel').val();
  get_pelanggan_det(id_pel);

});
<?PHP } ?>

<?PHP if($page == 'edit_transaksi_pembelian_v'){ ?>
$(document).ready(function() {
  cek_islunas();

  var id_pajak = $('#id_pajak_sel').val();
  hitung_pajak(id_pajak);

  var id_pel = $('#pelanggan_sel').val();
  get_supplier_detail(id_pel);

});
<?PHP } ?>

<?PHP if($page == ''){ ?>
$(document).ready(function() {
    transaksi_grafik();
    laba_rugi_grafik_harian();
    laba_rugi_grafik_bulanan();

});
<?PHP } ?>




/*===============================================
TEXT EDITOR
==================================================*/

        $(function() {
        $('textarea.chat-inputbox').tinymce({
            script_url : 'js/tiny_mce/tiny_mce.js',
            theme : "simple"
            });
        });

/*===============================================
TBALE THEMES
==================================================*/
$(function() {
        $(".paper-table").tablecloth({
          theme: "paper",
          striped: true,
          sortable: true,
          condensed: false
        });
      });
$(function() {
        $(".dark-table").tablecloth({
          theme: "dark",
          striped: true,
          sortable: true,
          condensed: false
        });
      });
      $(function() {
        $(".stat-table").tablecloth({
          theme: "stats",
          striped: false,
          sortable: false,
          condensed: false
        });
      });
      $(function() {
        $("table").tablecloth({
          theme: "default",
          striped: true,
          bordered: true
                  });
      });

      /*====Select Box====*/
    $(function () {
        $(".chzn-select").chosen();
        $(".chzn-select-deselect").chosen({
            allow_single_deselect: true
        });
    });

     /*====DATE Time Picker====*/
    $(function () {
        $('#datetimepicker1').datetimepicker({
            language: 'pt-BR',
            pickTime: false
        });
    });

    $(function () {
        $('#datetimepicker2').datetimepicker({
            language: 'pt-BR',
            pickTime: false
        });
    });

    /*DATE RANGE PICKER*/

    $(function () {
        $('#reservation').daterangepicker();
    });

      
$(function(){
        // global setting override
        
        $.extend($.gritter.options, {
            class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
            position: 'bottom-right', // possibilities: bottom-left, bottom-right, top-left, top-right
            fade_in_speed: 100, // how fast notifications fade in (string or int)
            fade_out_speed: 100, // how fast the notices fade out
            time: 3000 // hang on the screen for...
        });
        
/**=========================
ONLOAD NOTIFICATION 
==============================**/
    <?PHP if($msg == 1){ ?>
        pesan_sukses();
    <?PHP } ?>

    <?PHP if($msg == 2){ ?>
        pesan_hapus();
    <?PHP } ?>
});
/**=========================
SPARKLINE MINI CHART
==============================**/
$(function () {
    $(".line-min-chart").sparkline([50, 10, 2, 3, 40, 5, 26, 10, 15, 20, 40, 60], {
        type: 'line',
        width: '80',
        height: '40',
        lineColor: '#2b2b2b',
        fillColor: '#e5e5e5',
        lineWidth: 2,
        highlightSpotColor: '#0e8e0e',
        spotRadius: 3,
        drawNormalOnTop: true,
        disableTooltips : true
    });
    $(".bar-min-chart").sparkline([50, 10, 2, 3, 40, 5, 26, 10, -15, 20, 40, 60], {
        type: 'bar',
        height: '40',
        barWidth: 4,
        barSpacing: 1,
        barColor: '#007f00',
        disableTooltips : true
    });
    $(".pie-min-chart").sparkline([3, 5, 2, 10, 8], {
        type: 'pie',
        width: '40',
        height: '40',
        disableTooltips : true
    });
    $(".tristate-min-chart").sparkline([1, 1, 0, 1, -1, -1, 1, -1, 0, 0, 1, 1], {
        type: 'tristate',
        height: '40',
        posBarColor: '#bf005f',
        negBarColor: '#ff7f00',
        zeroBarColor: '#545454',
        barWidth: 4,
        barSpacing: 1,
        disableTooltips : true
    });
});
/**=========================
LEFT NAV ICON ANIMATION 
==============================**/
$(function () {
    $(".left-primary-nav a").hover(function () {
        $(this).stop().animate({
            fontSize: "30px"
        }, 200);
    }, function () {
        $(this).stop().animate({
            fontSize: "24px"
        }, 100);
    });
});
</script>
<script type="text/javascript">
/*===============================================
FLOT BAR CHART
==================================================*/

    var data7_1 = [
        [1354586000000, 153],
        [1354587000000, 658],
        [1354588000000, 198],
        [1354589000000, 663],
        [1354590000000, 801],
        [1354591000000, 1080],
        [1354592000000, 353],
        [1354593000000, 749],
        [1354594000000, 523],
        [1354595000000, 258],
        [1354596000000, 688],
        [1354597000000, 364]
    ];
    var data7_2 = [
        [1354586000000, 53],
        [1354587000000, 65],
        [1354588000000, 98],
        [1354589000000, 83],
        [1354590000000, 80],
        [1354591000000, 108],
        [1354592000000, 120],
        [1354593000000, 74],
        [1354594000000, 23],
        [1354595000000, 79],
        [1354596000000, 88],
        [1354597000000, 36]
    ];
    $(function () {
        $.plot($("#visitors-chart #visitors-container"), [{
            data: data7_1,
            label: "Page View",
            lines: {
                fill: true
            }
        }, {
            data: data7_2,
            label: "Online User",
            points: {
                show: true
            },
            lines: {
                show: true
            },
            yaxis: 2
        }
        ],
        {
            series: {
                lines: {
                    show: true,
                    fill: false
                },
                points: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: "#ffffff",
                    symbol: "circle",
                    radius: 5,
                },
                shadowSize: 0,
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#f9f9f9",
                borderWidth: 1
            },
            colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
                  shifts: { 
                      x: -100                     //10
                  },
                defaultTheme: false
            },
            xaxis: {
                mode: "time",
                timeformat: "%0m/%0d %0H:%0M"
            },
            yaxes: [{
                /* First y axis */
            }, {
                /* Second y axis */
                position: "right" /* left or right */
            }]
        }
        );
    });
</script>
<script type="text/javascript">
/*===============================================
FLOT PIE CHART
==================================================*/

    $(function () {
        var data = [{
            label: "Page View",
            data: 70
        }, {
            label: "Online User",
            data: 30
        }];
        var options = {
            series: {
                pie: {
                    show: true,
                    innerRadius: 0.5,
            show: true
                }
            },
            legend: {
                show: true
            },
            grid: {
                hoverable: true,
                clickable: true
            },
             colors: ["#b086c3", "#ea701b"],
            tooltip: true,
            tooltipOpts: {
                shifts: { 
                      x: -100                     //10
                  },
                defaultTheme: false
            }
        };
        $.plot($("#pie-chart-donut #pie-donutContainer"), data, options);
    });
</script>

<script type="text/javascript">
    

function transaksi_grafik(){
        var chart = new CanvasJS.Chart("chartContainer2",
        {
            animationEnabled: true,
            axisX:{

                gridColor: "Silver",
                tickColor: "silver",
                valueFormatString: "DD/MMM"

            },                        
                        toolTip:{
                          shared:true
                        },
            theme: "theme2",
            axisY: {
                gridColor: "Silver",
                tickColor: "silver"
            },
            legend:{
                verticalAlign: "center",
                horizontalAlign: "right"
            },
            data: [
            {        
                type: "line",
                showInLegend: true,
                lineThickness: 2,
                name: "Penjualan",
                markerType: "square",
                color: "#F08080",
                dataPoints: [
                  { label: '<?=$penjualan_grafik_harian_5->TGL;?>', y: <?PHP echo $penjualan_grafik_harian_5->TOTAL; ?> },
                  { label: '<?=$penjualan_grafik_harian_4->TGL;?>', y: <?PHP echo $penjualan_grafik_harian_4->TOTAL; ?> },
                  { label: '<?=$penjualan_grafik_harian_3->TGL;?>', y: <?PHP echo $penjualan_grafik_harian_3->TOTAL; ?> },
                  { label: '<?=$penjualan_grafik_harian_2->TGL;?>', y: <?PHP echo $penjualan_grafik_harian_2->TOTAL; ?> },
                  { label: '<?=$penjualan_grafik_harian_1->TGL;?>', y: <?PHP echo $penjualan_grafik_harian_1->TOTAL; ?> }
                ]
            },
            {        
                type: "line",
                showInLegend: true,
                name: "Pembelian / Cost",
                color: "#20B2AA",
                lineThickness: 2,

                dataPoints: [
                  { label: '<?=$pembelian_grafik_harian_5->TGL;?>', y: <?PHP echo $pembelian_grafik_harian_5->TOTAL;?> },
                  { label: '<?=$pembelian_grafik_harian_4->TGL;?>', y: <?PHP echo $pembelian_grafik_harian_4->TOTAL;?> },
                  { label: '<?=$pembelian_grafik_harian_3->TGL;?>', y: <?PHP echo $pembelian_grafik_harian_3->TOTAL;?> },
                  { label: '<?=$pembelian_grafik_harian_2->TGL;?>', y: <?PHP echo $pembelian_grafik_harian_2->TOTAL;?> },
                  { label: '<?=$pembelian_grafik_harian_1->TGL;?>', y: <?PHP echo $pembelian_grafik_harian_1->TOTAL;?> }
                ]
            }

            
            ],
          legend:{
            cursor:"pointer",
            itemclick:function(e){
              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
              }
              else{
                e.dataSeries.visible = true;
              }
              chart.render();
            }
          }
        });

chart.render();
}

function laba_rugi_grafik_harian(){
        var chart = new CanvasJS.Chart("chartContainer_labarugi_harian", {
            data: [{
                type: "line",
                dataPoints: [
                  { label: '<?=$laba_rugi_harian_5->TGL;?>', y: <?PHP echo $laba_rugi_harian_5->TOTAL;?> },
                  { label: '<?=$laba_rugi_harian_4->TGL;?>', y: <?PHP echo $laba_rugi_harian_4->TOTAL;?> },
                  { label: '<?=$laba_rugi_harian_3->TGL;?>', y: <?PHP echo $laba_rugi_harian_3->TOTAL;?> },
                  { label: '<?=$laba_rugi_harian_2->TGL;?>', y: <?PHP echo $laba_rugi_harian_2->TOTAL;?> },
                  { label: '<?=$laba_rugi_harian_1->TGL;?>', y: <?PHP echo $laba_rugi_harian_1->TOTAL;?> }
                ]
            }]
        });
        chart.render();
}

function laba_rugi_grafik_bulanan(){
        var chart = new CanvasJS.Chart("chartContainer_labarugi_bulanan", {
            data: [{
                type: "line",
                dataPoints: [
                  { label: '<?=$laba_rugi_bulanan_5->TGL;?>', y: <?PHP echo $laba_rugi_bulanan_5->TOTAL;?> },
                  { label: '<?=$laba_rugi_bulanan_4->TGL;?>', y: <?PHP echo $laba_rugi_bulanan_4->TOTAL;?> },
                  { label: '<?=$laba_rugi_bulanan_3->TGL;?>', y: <?PHP echo $laba_rugi_bulanan_3->TOTAL;?> },
                  { label: '<?=$laba_rugi_bulanan_2->TGL;?>', y: <?PHP echo $laba_rugi_bulanan_2->TOTAL;?> },
                  { label: '<?=$laba_rugi_bulanan_1->TGL;?>', y: <?PHP echo $laba_rugi_bulanan_1->TOTAL;?> }
                ]
            }]
        });
        chart.render();
}

</script>

<style type="text/css">
.stat-table tbody tr:hover{
    background: #F5F5F5;
    cursor: pointer;
}

#popup_load {
    width: 100%;
    height: 100%;
    position: fixed;
    background: #fff;
    z-index: 9999;
    opacity:0.8;
    filter:alpha(opacity=80); /* For IE8 and earlier */
    visibility:visible;
    top: 0;
    left: 0;
}

.window_load {
    border-radius: 10px;
    height: auto;
    margin-left: 20%;
    margin-top: 20%;
    padding: 10px;
    position: relative;
    text-align: center;
    width: 60%;
}
</style>

<?PHP 
$sess_user = $this->session->userdata('masuk_rs');
$id_user = $sess_user['id'];
$user = $this->master_model_m->get_user_info($id_user);
?>
</head>
<body>
<div id="popup_load" style="display:none;">
    <div class="window_load">
        <img src="<?=base_url();?>/external/loading.gif" height="100" width="100">
    </div>
</div>

<div class="layout">
	<!-- Navbar
    ================================================== -->
	<div class="navbar navbar-inverse top-nav" style="position: fixed; width: 100%; z-index: 9999;">
		<div class="navbar" style="background:#1CA0DE;"> 
			<div class="container">

                    <a class="brand" href="<?=base_url();?>beranda_c"><img src="<?=base_url();?>material/images/logo_akun.png" width="125" height="50" alt="Logo Akun" style="margin-left: 14px; margin-top: 10px;"></a>
				<div class="nav-collapse">
					<ul class="nav">

                        <li>
                            <a style="color:#FFF;" href="<?=base_url();?>transfer_data_c"><i class="icon-random"></i> <b> Transfer Data Akuntansi </b> </a> 
                        </li>

                        <li class="dropdown"><a style="color:#FFF;" data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-globe"></i> <b> Master Data </b> <b class="icon-angle-down"></b></a>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="<?=base_url();?>daftar_kategori_akun_c"><i class="icon-bookmark"></i> Kategori Akun </a></li>
                                <li><a href="<?=base_url();?>daftar_kode_akun_c"><i class="icon-tag"></i> Daftar Kode Akun </a></li>
                                <!-- <li><a href="<?=base_url();?>pelanggan_c"><i class="icon-group"></i> Pelanggan </a></li>
                                <li><a href="<?=base_url();?>supplier_c"><i class="icon-truck"></i> Supplier </a></li> -->
                                <li><a href="<?=base_url();?>produk_c"><i class="icon-hdd"></i> Produk / Aset</a></li>
                            </ul>
                        </div>
                        </li>

						<li class="dropdown"><a style="color:#FFF;" data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-th-large"></i> <b> Input Data </b> <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="<?=base_url();?>kas_bank_c"><i class="icon-money"></i> Kas & Bank </a></li>
                                <li><a href="<?=base_url();?>transaksi_pembelian_c"><i class="icon-shopping-cart"></i> Belanja Harian </a></li>
                                <!-- <li><a href="<?=base_url();?>transaksi_penjualan_c"><i class="icon-random"></i> Transaksi Penjualan </a></li> -->
							</ul>
						</div>
						</li>

                        <li class="dropdown"><a style="color:#FFF;" data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-briefcase"></i> <b> Input Akuntansi  </b> <b class="icon-angle-down"></b></a>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="<?=base_url();?>input_transaksi_akuntansi_c"><i class="icon-plus-sign"></i> Transaksi Akuntansi</a></li>
                                <li><a href="<?=base_url();?>input_jurnal_bayar_kas_c"><i class="icon-plus-sign"></i> Jurnal Bayar Kas Bank </a></li>
                                <li><a href="<?=base_url();?>kas_bank_c"><i class="icon-plus-sign"></i> Jurnal Penyesuaian </a></li>
                            </ul>
                        </div>
                        </li>
						
						<li class="dropdown"><a style="color:#FFF;" data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file-alt"></i> <b> Laporan Akuntansi </b> <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
                                <li><a href="<?=base_url();?>lap_buku_besar_c"><i class=" icon-book"></i> Laporan Buku Besar </a></li>
                                <li><a href="<?=base_url();?>lap_laba_rugi_c"><i class=" icon-book"></i> Laporan Laba Rugi  </a> </li>
                                <li><a href="<?=base_url();?>lap_jurnal_umum_c"><i class=" icon-book"></i> Laporan Jurnal Umum  </a></li>
                                <li><a href="<?=base_url();?>lap_arus_kas_c"><i class=" icon-book"></i> Laporan Arus Kas </a></li>
                                <li><a href="<?=base_url();?>lap_jurnal_bayar_kas_c"><i class=" icon-book"></i> Jurnal Bayar Kas Bank  </a></li>
                                <li><a href="<?=base_url();?>lap_jurnal_penyesuaian_c"><i class=" icon-book"></i> Jurnal Penyesuaian  </a></li>
                                <li><a href="<?=base_url();?>lap_neraca_c"><i class=" icon-book"></i> Laporan Neraca  </a></li>
							</ul>
						</div>
						</li>
						<!-- <li class="dropdown"><a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-bar-chart"></i> Grafik <b class="icon-angle-down"></b></a>
						<div class="dropdown-menu">
							<ul>
								<li><a href="#"><i class="icon-bar-chart"></i> Coming Soon </a></li>
							</ul>
						</div>
						</li> -->

                        <li class="dropdown"><a style="color:#FFF;" data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-cog"></i> <b> Pengaturan </b> <b class="icon-angle-down"></b></a>
                        <div class="dropdown-menu">
                            <ul>
                                <!-- <li><a href="<?=base_url();?>profil_perusahaan_c"><i class="icon-pencil"></i> Profil Perusahaan </a></li> -->
                                <!-- <li><a href="<?=base_url();?>pengaturan_akun_c"><i class="icon-user"></i> Pengaturan Akun </a></li>  -->
                                <li><a href="<?=base_url();?>setting_laporan_c"><i class="icon-list-alt"></i> Pengaturan Laporan </a></li> 
                            </ul>
                        </div>
                        </li>

                        <li>
                            <a style="color:#FFF;" href="../../portal"><i class="icon-home"></i> <b> Portal Depan </b> </a> 
                        </li>

                        
					</ul>
				</div>

			</div>
		</div>
	</div>
	<div class="leftbar leftbar-close clearfix" style="margin-top: 50px; position:fixed;">
		<div class="admin-info clearfix">
			<div class="admin-thumb">
                <?PHP if($user->FOTO == "" || $user->FOTO== null){ ?>
				    <i class="icon-user"></i>
                <?PHP } else { ?>
                    <?PHP if(
                        $page == "kas_bank_trf_uang_v" || $page == "buat_transaksi_pembelian_new_v" || $page == "buat_transaksi_penjualan_new_v" || $page == "ubah_transaksi_akuntansi_v"
                    ){ ?>
                        <img src="../../../files/foto_pegawai/<?=$user->FOTO;?>" style="padding-bottom: 5px;" />

                    <?PHP } else if ($page == "edit_transaksi_penjualan_v" || $page == "detail_transaksi_penjualan_v" || $page == "edit_transaksi_pembelian_v" || $page == "detail_transaksi_pembelian_v") { ?>

                        <img src="../../../../files/foto_pegawai/<?=$user->FOTO;?>" style="padding-bottom: 5px;" />

                    <?PHP } else { ?>

                        <img src="../../files/foto_pegawai/<?=$user->FOTO;?>" style="padding-bottom: 5px;" />

                    <?PHP } ?>
                <?PHP } ?>
			</div>
			<div class="admin-meta">
				<ul>
                    <li class="admin-username"> <?=$user->NAMA;?> </li>
					<li class="admin-username" style="color:#1CA0DE;"> <?=$user->JABATAN;?> </li>

					<li>
                        <!-- <a href="<?=base_url();?>pengaturan_akun_c"> Edit Profil </a> -->
                        <a href="<?=base_url();?>beranda_c/sign_out"><i class="icon-lock"></i> Logout</a>
                    </li>
				</ul>
			</div>
		</div>
		<div class="left-nav clearfix">
			<div class="left-primary-nav">
				<ul id="myTab">
					<li <?PHP if($master == ""){ echo "class='active'"; } ?> ><a href="#main" class="icon-desktop" title="Dashboard"></a></li>
					<li <?PHP if($master == "master_data"){ echo "class='active'"; } ?> ><a href="#features" class="icon-globe" title="Master Data"></a></li>
                    <li <?PHP if($master == "input_data"){ echo "class='active'"; } ?> ><a href="#forms" class="icon-th-large" title="Input Data "></a></li>
                    <li <?PHP if($master == "input_akuntansi"){ echo "class='active'"; } ?> ><a href="#input_akun" class="icon-briefcase" title="Input Akuntansi "></a></li>
					<li <?PHP if($master == "laporan"){ echo "class='active'"; } ?> ><a href="#pages" class="icon-file-alt" title="Laporan"></a></li>
                    <!-- <li <?PHP if($master == "3"){ echo "class='active'"; } ?> ><a href="#chart" class="icon-bar-chart" title="Grafik"></a></li> -->
					<li <?PHP if($master == "setting"){ echo "class='active'"; } ?> ><a href="#pengaturan" class="icon-cog" title="Pengaturan"></a></li>
				</ul>
			</div>
			<div class="responsive-leftbar">
				<i class="icon-list"></i>
			</div>
			<div class="left-secondary-nav tab-content">
				<div class="tab-pane <?PHP if($master == ""){ echo "active"; } ?>" id="main">
					<h4 class="side-head">Dashboard</h4>
                    					
					<ul class="metro-sidenav clearfix">
                        <li style="width: 180px;" ><a style="width: 180px;" class="green" href="<?=base_url();?>transfer_data_c"><i class="icon-random"></i><span> Transfer Data Akuntansi </span></a></li>
                        <!-- <li><a class="green" href="<?=base_url();?>transaksi_penjualan_c"><i class="icon-random"></i><span> Penjualan </span></a></li> -->
                        <li><a class="brown" href="<?=base_url();?>transaksi_pembelian_c"><i class="icon-shopping-cart"></i><span>Input Belanja </span></a></li>
                        <li><a class="orange" href="<?=base_url();?>kas_bank_c"><i class="icon-money"></i><span> Kas & Bank </span></a></li>
                        <li><a class=" blue-violate" href="<?=base_url();?>daftar_kode_akun_c"><i class="icon-tag"></i><span> Kode Akun </span></a></li>
                        <li><a class=" bondi-blue" href="<?=base_url();?>produk_c"><i class="icon-hdd"></i><span> Produk / Aset </span></a></li>
                        <!-- <li><a class=" dark-yellow" href="<?=base_url();?>pelanggan_c"><i class="icon-group"></i><span> Pelanggan </span></a></li>
                        <li><a class=" blue" href="<?=base_url();?>supplier_c"><i class="icon-truck"></i><span> Supplier </span></a></li> -->
                        <!-- <li><a class=" magenta" href="<?=base_url();?>profil_perusahaan_c"><i class="icon-pencil"></i><span>Profil Usaha</span></a></li> -->
                    </ul>
				</div>

                <div class="tab-pane <?PHP if($master == "master_data"){ echo "active"; } ?>" id="features">
                    <h4 class="side-head">Master Data</h4>
                    <ul class="accordion-nav">
                        <li <?PHP if($view == "daftar_kat_akun"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>daftar_kategori_akun_c"><i class="icon-bookmark"></i> Daftar Kategori Akun <span> Daftar Kategori Kode Akun Anda </span> </a></li>
                        <li <?PHP if($view == "daftar_akun"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>daftar_kode_akun_c"><i class="icon-tag"></i> Daftar Kode Akun <span> Mengelola Kode Akun Anda </span> </a></li>
                        <!-- <li <?PHP if($view == "daftar_pelanggan"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>pelanggan_c"><i class="icon-group"></i> Pelanggan <span> Daftar Pelanggan Anda  </span>  </a></li> -->
                        <!-- <li <?PHP if($view == "daftar_supplier"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>supplier_c"><i class="icon-truck"></i> Supplier <span> Kelola Daftar Supplier  </span>  </a></li> -->
                        <li <?PHP if($view == "daftar_produk"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>produk_c"><i class="icon-hdd"></i> Produk / Aset <span> Daftar Produk dan Aset untuk Transaksi Anda  </span> </a></li>

                    </ul>
                </div>

				<div class="tab-pane <?PHP if($master == "input_data"){ echo "active"; } ?>" id="forms">
					<h4 class="side-head">Input Data</h4>
					<ul id="nav" class="accordion-nav">
                        <li <?PHP if($view == "kas_bank"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>kas_bank_c"><i class="icon-money"></i> Kas & Bank <span> Tambahkan akun bank, catat transaksi kas & bank </span></a></li>
                        <li <?PHP if($view == "transaksi_pembelian"){ echo "class='active'"; } ?>><a href="<?=base_url();?>transaksi_pembelian_c"><i class="icon-shopping-cart"></i> Belanja Harian <span> Membuat daftar belanja harian anda dengan mudah. </span></a></li>
                        <!-- <li <?PHP if($view == "transaksi_penjualan"){ echo "class='active'"; } ?>><a href="<?=base_url();?>transaksi_penjualan_c"><i class="icon-random"></i> Transaksi Penjualan <span> Membuat faktur & retur penjualan secara detil. </span></a></li> -->
					</ul>
				</div>

                <div class="tab-pane <?PHP if($master == "input_akuntansi"){ echo "active"; } ?>" id="input_akun">
                    <h4 class="side-head">Input Akuntansi</h4>
                    <ul id="nav" class="accordion-nav">
                        <li <?PHP if($view == "input_transaksi_akuntansi"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>input_transaksi_akuntansi_c"><i class="icon-plus-sign"></i> Transaksi Akuntansi </a></li>
                        <li <?PHP if($view == "jurnal_bayar_kas"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>input_jurnal_bayar_kas_c"><i class="icon-plus-sign"></i> Jurnal Bayar Kas Bank </a></li>
                        <li <?PHP if($view == "jp"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>input_jurnal_penyesuaian_c"><i class="icon-plus-sign"></i> Jurnal Penyesuaian </a></li>
                        <li <?PHP if($view == "hapus_trx_akun"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>hapus_transaksi_akun_c"><i class="icon-remove-sign"></i> Hapus Transaksi Akun </a></li>
                    </ul>
                </div>
				
				<div class="tab-pane" id="ui-elements">
					<h4 class="side-head">UI Elements</h4>
					<ul class="accordion-nav">
						<li><a href="components-widgets.html"><i class=" icon-list-alt"></i> UI Components</a></li>
						<li><a href="buttons-icons.html"><i class=" icon-th-large"></i> Buttons &amp; Icons</a></li>
					</ul>
				</div>
				<div class="tab-pane <?PHP if($master == "laporan"){ echo "active"; } ?>" id="pages">
					<h4 class="side-head">Laporan Akuntansi</h4>
					<ul class="accordion-nav">
						<!-- <li><a href="#"><i class="icon-minus-sign"></i> Error Pages</a>
						<ul>
							<li><a href="page-403.html"><i class=" icon-file-alt"></i> 403 Error Page</a></li>
							<li><a href="page-404.html"><i class=" icon-file-alt"></i> 404 Error Page</a></li>
							<li><a href="page-405.html"><i class=" icon-file-alt"></i> 405 Error Page</a></li>
							<li><a href="page-500.html"><i class=" icon-file-alt"></i> 500 Error Page</a></li>
							<li><a href="page-503.html"><i class=" icon-file-alt"></i> 503 Error Page</a></li>
						</ul>
						</li> -->
                        <li <?PHP if($view == "buku_besar"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_buku_besar_c"><i class=" icon-book"></i> Laporan Buku Besar </a></li>
                        <li <?PHP if($view == "laba_rugi"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_laba_rugi_c"><i class=" icon-book"></i> Laporan Laba Rugi  </a> </li>
                        <li <?PHP if($view == "jurnal_umum"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_jurnal_umum_c"><i class=" icon-book"></i> Laporan Jurnal Umum  </a></li>
                        <li <?PHP if($view == "arus_kas"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_arus_kas_c"><i class=" icon-book"></i> Laporan Arus Kas </a></li>
                        <li <?PHP if($view == "jbk"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_jurnal_bayar_kas_c"><i class=" icon-book"></i> Jurnal Bayar Kas Bank  </a></li>
                        <li <?PHP if($view == "jp"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_jurnal_penyesuaian_c"><i class=" icon-book"></i> Jurnal Penyesuaian </a></li>
						<li <?PHP if($view == "neraca"){ echo "class='active'"; } ?> ><a href="<?=base_url();?>lap_neraca_c"><i class=" icon-book"></i> Laporan Neraca </a></li>
					</ul>
				</div>
				<!-- <div class="tab-pane" id="chart">
					<h4 class="side-head">Charts</h4>
					<ul class="accordion-nav">
						<li><a href="#"><i class=" icon-unlock"></i> Coming Soon </a></li>
					</ul>
				</div> -->

                <div class="tab-pane <?PHP if($master == "setting"){ echo "active"; } ?>" id="pengaturan">
                    <h4 class="side-head"> Pengaturan </h4>
                    <ul class="accordion-nav">
                        <!-- <li <?PHP if($view == "profil_usaha"){ echo "class='active'"; } ?> > <a href="<?=base_url();?>profil_perusahaan_c"><i class="icon-pencil"></i> Profil Perusahaan <span> Kelola profil perusahaan anda. </span> </a></li> -->
                        <!-- <li <?PHP if($view == "pengaturan_akun"){ echo "class='active'"; } ?> > <a href="<?=base_url();?>pengaturan_akun_c"><i class="icon-user"></i> Pengaturan Akun <span> Mengatur detail akun anda </span></a></li> -->
                        <li <?PHP if($view == "setting_laporan"){ echo "class='active'"; } ?> > <a href="<?=base_url();?>setting_laporan_c"><i class="icon-list-alt"></i> Pengaturan Laporan <span> Mengatur format laporan anda </span></a></li>
                    </ul>
                </div>
			</div>
		</div>
	</div>
	<div class="main-wrapper">        
		<div class="container-fluid" style="margin-top: 50px;">
        <?PHP if($page == ""){ ?>
		
        <div class="row-fluid ">
            <div class="span4">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left">Penjualan Bulan Ini</h4>
                        <a href="#" class="widget-settings"><i class="icon-random"></i></a>
                    </div>
                    <div class="board-widgets-content">
                        <div class="line-min-chart min-chart"><canvas style="display: inline-block; width: 80px; height: 40px; vertical-align: top;" width="80" height="40"></canvas></div>
                        <div class="stat-text">
                             <i class="up icon-sort-up"></i> Rp <?=number_format($penjualan_bulan_ini->TOTAL);?> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="span4">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left"> Pembelian / Cost Bulan Ini </h4>
                        <a href="#" class="widget-settings"><i class="icon-shopping-cart"></i></a>
                    </div>
                    <div class="board-widgets-content">
                        <div class="bar-min-chart min-chart"><canvas style="display: inline-block; width: 59px; height: 40px; vertical-align: top;" width="59" height="40"></canvas></div>
                        <div class="stat-text">
                             <i class="down icon-sort-down"></i> Rp <?=number_format($pembelian_bulan_ini->TOTAL);?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span4">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left"> Laba dan Rugi </h4>
                        <a href="#" class="widget-settings"><i class="icon-money"></i></a>
                    </div>
                    <div class="board-widgets-content">
                        <div class="tristate-min-chart min-chart"><canvas style="display: inline-block; width: 59px; height: 40px; vertical-align: top;" width="59" height="40"></canvas></div>
                        <div class="stat-text">
                            <?PHP if($laba_rugi_bulan_ini->JML > 0){ ?>
                                <i class="up icon-sort-up"></i> Rp <?=number_format($laba_rugi_bulan_ini->JML);?>
                            <?PHP } else { ?>
                                <i class="down icon-sort-down"></i> Rp <?=number_format($laba_rugi_bulan_ini->JML);?>
                            <?PHP } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>	

        <div class="row-fluid ">
            <div class="span6">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left">Grafik Laba Rugi Harian</h4>
                        <a href="#" class="widget-settings"><i class="icon-money"></i></a>
                    </div>
                    <div class="board-widgets-content" style="padding: 0;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="widget-container">
                                    <div id="visitors-chart">
                                        <div id="chartContainer_labarugi_harian" style="height: 400px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-text">
                             Data update pada <?=date('d-m-Y')." ".date('H:i');?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="span6">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left">Grafik Laba Rugi Bulanan</h4>
                        <a href="#" class="widget-settings"><i class="icon-money"></i></a>
                    </div>
                    <div class="board-widgets-content" style="padding: 0;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="widget-container">
                                    <div id="visitors-chart">
                                        <div id="chartContainer_labarugi_bulanan" style="height: 400px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-text">
                             Data update pada <?=date('d-m-Y')." ".date('H:i');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>    


        <div class="row-fluid ">
            <div class="span12">
                <div class="board-widgets gray">
                    <div class="board-widgets-head clearfix">
                        <h4 class="pull-left">Grafik Data Penjualan dan Pembelian / Cost Terakhir</h4>
                        <a href="#" class="widget-settings"><i class="icon-random"></i></a>
                    </div>
                    <div class="board-widgets-content" style="padding: 0;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="widget-container">
                                    <div id="visitors-chart">
                                        <div id="chartContainer2" style="height: 400px; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="stat-text">
                             Data update pada <?=date('d-m-Y')." ".date('H:i');?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

		<?PHP } else { $this->load->view($page); } ?>
        </div>
	</div>
	<div class="copyright">
		<p>
			 &copy; 2016 SIMRS
		</p>
	</div>
	<div class="scroll-top">
		<a href="#" class="tip-top" title="Go Top"><i class="icon-double-angle-up"></i></a>
	</div>
</div>
</body>
</html>

