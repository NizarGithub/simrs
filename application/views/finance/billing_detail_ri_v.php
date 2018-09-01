<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="<?php echo base_url(); ?>material/icon-bill.png">

    <title><?php echo $title; ?></title> 

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
    <link href="<?=base_url();?>assets/custom/css/popup.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>dist/css/datepicker.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/custom/css/jtree.css" rel="stylesheet" type="text/css" />

    <!--CSS Devan-->
    <link href="<?php echo base_url(); ?>css-devan/warna.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>css-devan/style-devan.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/modernizr.min.js"></script>
    <style type="text/css">
    .table tbody tr td {
    	border: 1px solid #666;
    }
    </style>
</head>
<body>
<div class="wrapper" style="margin-top:0px;">	
	<div class="container">

	    <div class="row">
	        <div class="col-sm-12">
	            <h4 class="page-title"> <i class="zmdi zmdi-print"></i> <?php echo $subtitle; ?></h4>
	        </div>
	    </div>

		<div class="row">
			<div class="col-lg-12">
		        <div class="card-box" style="border-top: 3px solid #59a9f8;">
		            <h4 class="header-title m-t-0 m-b-15" style="border-bottom: 1px solid #eee; padding-bottom: 15px;"> 
		            	<i class="fa fa-user"></i> DETAIL PASIEN (<?php echo $ket; ?>)
		            </h4>

		            <div class="row">
				        <div class="col-sm-6">
				            <table style="font-size: 13px;">
				            	<tr>
				            		<td> <b> NO. TRANSAKSI </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> TR-<?=$nomor_bill->NOMOR;?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> TGL. PELAYANAN </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?=date('d F Y', strtotime($dt->TANGGAL_DAFTAR));?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> SISTEM BAYAR </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?=strtoupper($dt->SISTEM_BAYAR);?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> STATUS </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> 
				            			<?PHP if($dt->STS_BAYAR == 0){
				            				echo '<font style="color:red; font-weight:bold;"> BELUM LUNAS </font> ';
				            			} else {
				            				echo '<font style="color:green; font-weight:bold;"> LUNAS </font> ';
				            			} ?>				            			
				            		</td>
				            	</tr>
				            </table>
				        </div>

				        <div class="col-sm-6">
				            <table style="font-size: 13px;">
				            	<tr>
				            		<td> <b> NO. PASIEN </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?=$dt->KODE_PASIEN;?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> NAMA </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?=strtoupper($dt->NAMA);?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> ALAMAT </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?=$dt->ALAMAT;?> </td>
				            	</tr>
				            	<tr> <td>&nbsp;</td> </tr>
				            	<tr>
				            		<td> <b> UMUR </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> <?PHP echo getOld($dt->TANGGAL_LAHIR); ?> TAHUN </td>
				            	</tr>
				            </table>
				        </div>
				    </div>

		        </div>
		    </div>
		</div>

		<div class="row">
			<form action="<?php echo $url_cetak; ?>" method="post" target="_blank">
				<input type="hidden" name="id_pasien_ri" value="<?php echo $id_pasien; ?>">
			    <div class="col-lg-12">
			    	<div class="card-box" style="border-top: 3px solid #34bf49;">
			            <h4 class="header-title m-t-0 m-b-15" style="border-bottom: 1px solid #eee; padding-bottom: 15px;"> <i class="fa fa-tag"></i> DETAIL PELAYANAN </h4>
			            <div class="row">
					        <div class="col-sm-12">
					        	<div class="table-responsive">
								    <table class="table table-bordered" style="border: 2px solid #666;">
								        <thead>
								            <tr class="success">
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">NO.</th>
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">PERAWATAN</th>
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">KAMAR</th>
								                <th colspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">TANGGAL</th>
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">LAMA<br>RAWAT</th>
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">/HARI KAMAR</th>
								                <th rowspan="2" style="border: 2px solid #666; text-align:center; vertical-align:middle;">AKUMULASI BIAYA<br>(Rp)</th>
								            </tr>
								            <tr class="success">
								            	<th style="border: 2px solid #666; text-align:center; vertical-align:middle;">MRS</th>
								            	<th style="border: 2px solid #666; text-align:center; vertical-align:middle;">KRS</th>
								            </tr>
								        </thead>
								        <tbody>
								        	<tr>
								        		<td colspan="7"><b>Kamar Rawat Inap</b></td>
								        	</tr>
									        <tr>
									        	<td style="text-align:center;">1</td>
									        	<td>Perawatan Pasien</td>
									        	<td style="white-space:nowrap;">
									        		Nama Kamar : <?php echo $dt2->NAMA_KAMAR; ?><br>
									        		Kelas : <?php echo $dt2->KELAS; ?>
									        	</td>
									        	<td style="text-align:center;"><?php echo $dt2->TANGGAL_MASUK; ?></td>
									        	<td style="text-align:center;">
									        	<?php
									        		$i = $dt2->DIRAWAT_SELAMA;
									        		$tgl = $dt2->TANGGAL_MASUK_BALIK;
									        		$date = strtotime("+".$i." days", strtotime($tgl));
									        		echo date("d-m-Y", $date);
									        	?>
									        	</td>
									        	<td style="text-align:center;"><?php echo $i; ?> Hari</td>
									        	<td style="text-align:right;"><?php echo number_format($dt2->BIAYA,0,'.',','); ?></td>
									        	<td style="text-align:right;">
									        	<?php
									        		$biaya = $dt2->BIAYA;
									        		$hari = $dt2->DIRAWAT_SELAMA;
									        		$total = $biaya * $hari;
									        		echo number_format($total,0,'.',',');
									        	?>
									        	</td>
									        </tr>

									        <tr>
								        		<td colspan="7"><b>Tindakan</b></td>
								        	</tr>
									        <?php
									        	$dt_detail = $this->model->getDetailLayananRI($id_pasien);

										        $no = 0;
									        	$total_td = 0;
									        	foreach ($dt_detail as $key => $row) {
									        		$no++;
									        		$total_td += $row->TARIF;
									        ?>
								            <tr>
								                <td style="text-align:center;"> <?=$no;?> </td>
								                <td colspan="6"> <?=$row->KET;?> </td>
								                <td align="right"> <?=number_format($row->TARIF,0,'.',',');?> </td>
								            </tr> 
								            <?PHP } ?>

								            <tr>
								        		<td colspan="7"><b>Visite</b></td>
								        	</tr>
								        	<?php
								        		$dt_visite = $this->model->dataDetVisite_RI($id_pasien);

									        	$no = 0;
									        	$total_vt = 0;
									        	foreach ($dt_visite as $key => $val_vt) {
									        		$no++;
									        		$total_vt += $val_vt->TARIF;
									        ?>
									        <tr>
									        	<td style="text-align:center;"><?php echo $no; ?></td>
									        	<td><?php echo $val_vt->NAMA_VISITE; ?></td>
									        	<td colspan="5">
									        		Dokter : <?php echo $val_vt->NAMA_DOKTER; ?>
									        	</td>
									        	<td align="right"> <?=number_format($val_vt->TARIF,0,'.',',');?> </td>
								        	</tr>
									        <?php
									        	}
								        	?>

								        	<tr>
								        		<td colspan="7"><b>Gizi</b></td>
								        	</tr>
								        	<?php
								        		$dt_gizi = $this->model->dataDetGizi_RI($id_pasien);

									        	$no = 0;
									        	$total_gz = 0;
									        	foreach ($dt_gizi as $val_gz) {
									        		$no++;
									        		$total_gz += $val_gz->TARIF;
									        ?>
									        	<tr>
									        	<td style="text-align:center;"><?php echo $no; ?></td>
									        	<td colspan="6"><?php echo $val_gz->NAMA_GIZI; ?></td>
									        	<td align="right"> <?php echo number_format($val_gz->TARIF,0,'.',',');?> </td>
								        	</tr>
									        <?php
									        	}
									        ?>

									        <tr>
								        		<td colspan="7"><b>Oksigen</b></td>
								        	</tr>
								        	<?php
								        		$dt_oksigen = $this->model->dataDetOksigen_RI($id_pasien);

									        	$no = 0;
									        	$total_ok = 0;
									        	foreach ($dt_oksigen as $val_ok) {
									        		$no++;
									        		$total_ok += $val_ok->TOTAL;
									        ?>
									        	<tr>
									        	<td style="text-align:center;"><?php echo $no; ?></td>
									        	<td><?php echo $val_ok->KETERANGAN; ?></td>
									        	<td colspan="5">
									        		Jumlah : <?php echo $val_ok->JUMLAH; ?> Tabung x <?php echo number_format($val_ok->TARIF,0,'.',','); ?>
									        	</td>
									        	<td align="right"> <?php echo number_format($val_ok->TOTAL,0,'.',',');?> </td>
								        	</tr>
									        <?php
									        	}
									        ?>
									        <tr>
								        		<td colspan="7"><b>Diagnosa</b></td>
								        	</tr>
								        	<?php
								        		$dt_diagnosa = $this->model->dataDetDiagnosa_RI($id_pasien);

									        	$no = 0;
									        	foreach ($dt_diagnosa as $val_dg) {
									        		$no++;
									        ?>
									        	<tr>
									        	<td style="text-align:center;"><?php echo $no; ?></td>
									        	<td colspan="6"><?php echo $val_dg->TINDAKAN; ?></td>
									        	<td align="right">-</td>
								        	</tr>
									        <?php
									        	}
									        ?>
									        <tr>
								        		<td colspan="7"><b>Resep</b></td>
								        	</tr>
								        	<?php
								        		$dt_resep = $this->model->dataDetResep_RI($id_pasien);

									        	$no = 0;
									        	$total_rs = 0;
									        	foreach ($dt_resep as $val_rs) {
									        		$no++;
									        		$total_rs += $val_rs->SUBTOTAL;
									        ?>
									        	<tr>
									        	<td style="text-align:center;"><?php echo $no; ?></td>
									        	<td><?php echo $val_rs->NAMA_OBAT; ?></td>
									        	<td colspan="5">
									        		Jumlah : <?php echo $val_rs->JUMLAH_BELI." ".$val_rs->NAMA_SATUAN; ?>
									        	</td>
									        	<td align="right"><?php echo number_format($val_rs->SUBTOTAL,0,'.',',');?></td>
								        	</tr>
									        <?php
									        	}
									        ?>
								        </tbody>
								        <tfoot>
								        	<tr class="success">
								        		<td style="border: 2px solid #666;" colspan="7"><b>TOTAL</b></td>
								        		<td style="text-align:right; border: 2px solid #666;">
									        		<b>
									        		<?php
									        			$grandtotal = $total + $total_td + $total_vt + $total_gz + $total_ok + $total_rs;
									        			echo number_format($grandtotal,0,'.',',');
									        		?>
									        		</b>
								        		</td>
								        	</tr>
								        </tfoot>
								    </table>
								</div>
							</div>
						</div>
					</div>
			    </div>

			    <div class="col-lg-12">
			    	<div class="card-box">
			    		<div class="row">
					        <div class="col-sm-12">
						        <center>
						        	<button class="btn btn-danger waves-effect w-md waves-light" type="submit" style="width:20%;"><i class="fa fa-print"></i> Cetak</button>
						        </center>
					        </div>
					    </div>
			    	</div>
			    </div>
			</form>
		</div>
	</div>

</div>

<?PHP 
function getOld($birthDate){
	$birthDate = explode("-", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
      ? ((date("Y") - $birthDate[2]) - 1)
      : (date("Y") - $birthDate[2]));

    return $age;
}

?>

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>js-devan/alert.js"></script>
<script src="<?php echo base_url(); ?>js-devan/js-form.js"></script>
<script src="<?php echo base_url(); ?>js-devan/pagination.js"></script>

</body>
</html>
