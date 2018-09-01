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
		            <h4 class="header-title m-t-0 m-b-15" style="border-bottom: 1px solid #eee; padding-bottom: 15px;"> <i class="fa fa-user"></i> DETAIL PASIEN </h4>

		            <div class="row">
				        <div class="col-sm-6">
				            <table style="font-size: 13px;">
				            	<tr>
				            		<td> <b> NO. TRANSAKSI </b> </td>
				            		<td style="text-align: center; width: 50px;"> : </td>
				            		<td> TR-<?=$nomor_bill->NOMOR;?>  (<?=$ket;?>) </td>
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
			<input type="hidden" name="id_pasien_igd" value="<?php echo $id_pasien; ?>">
			<div class="col-lg-12">
		        <div class="card-box" style="border-top: 3px solid #34bf49;">
		            <h4 class="header-title m-t-0 m-b-15" style="border-bottom: 1px solid #eee; padding-bottom: 15px;"> <i class="fa fa-tag"></i> DETAIL PELAYANAN </h4>
		            <div class="row">
				        <div class="col-sm-12">
				        	<div class="table-responsives">
							    <table class="table table-bordered" style="font-size:15px; border: 2px solid #666;">
							        <thead>
							            <tr class="success">
							                <th style="border: 2px solid #666; text-align:center;">NO.</th>
							                <th style="border: 2px solid #666; text-align:center;">PERAWATAN</th>
							                <th style="border: 2px solid #666; text-align:center;">AKUMULASI BIAYA (Rp) </th>
							            </tr>
							        </thead>
							        <tbody>
							        	<?PHP 
							        	$dt_detail = $this->model->getDetailLayananIGD($id_pasien);
							        	$no = 0;
							        	$total = 0;							        	
							        	?>
							        	<tr>
							        		<td colspan="3"><b>Tindakan</b></td>
							        	</tr>
							            
						            	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "TINDAKAN"){
							        		$no++;
							        		$total += $row->TARIF;
						            	?>
						            	<tr>
							                <td align="center"> <?=$no;?> </td>
							                <td> <?=$row->KET;?> </td>
							                <td align="right"> <?=number_format($row->TARIF);?> </td>
							            </tr>      
							            <?PHP } } ?>

							            <tr>
							        		<td colspan="3"><b>Laborat</b></td>
							        	</tr>
							        	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "LABORAT"){
							        		$no++;
							        		$total += $row->TARIF;
						            	?>
						            	<tr>
							                <td align="center"> <?=$no;?> </td>
							                <td> <?=$row->KET;?> </td>
							                <td align="right"> <?=number_format($row->TARIF);?> </td>
							            </tr>      
							            <?PHP } } ?>

							            <tr>
							        		<td colspan="3"><b>Obat</b></td>
							        	</tr>
							        	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "OBAT"){
							        		$no++;
							        		$total += $row->TARIF;
						            	?>
						            	<tr>
							                <td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
							                <td style="vertical-align:middle;"> <?=$row->KET;?> <br> (<?=$row->JUMLAH;?> x Rp. <?=$row->HARGA;?>) </td>
							                <td style="vertical-align:middle;" align="right"> <?=number_format($row->TARIF);?> </td>
							            </tr>      
							            <?PHP } } ?>

							            <tr>
							        		<td colspan="3"><b>Diagnosa</b></td>
							        	</tr>
							        	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "DIAGNOSA"){
							        		$no++;
						            	?>
						            	<tr>
							                <td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
							                <td style="vertical-align:middle;"> <?=$row->KET;?> </td>
							                <td style="vertical-align:middle;" align="right"> - </td>
							            </tr>      
							            <?PHP } } ?>

							            <tr>
							        		<td colspan="3"><b>ICU</b></td>
							        	</tr>
							        	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "ICU"){
							        		$no++;
							        		$total += $row->TARIF;
						            	?>
						            	<tr>
							                <td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
							                <td style="vertical-align:middle;"> <?=$row->KET;?> </td>
							                <td style="vertical-align:middle;" align="right"> <?=number_format($row->TARIF);?> </td>
							            </tr>      
							            <?PHP } } ?>

							            <tr>
							        		<td colspan="3"><b>Operasi</b></td>
							        	</tr>
							        	<?PHP
						            		$no = 0; 
							            	foreach ($dt_detail as $key => $row) {
							            	if($row->ORD == "OPERASI"){
							        		$no++;
							        		$total += $row->TARIF;
						            	?>
						            	<tr>
							                <td style="vertical-align:middle;" align="center"> <?=$no;?> </td>
							                <td style="vertical-align:middle;"> <?=$row->KET;?> </td>
							                <td style="vertical-align:middle;" align="right"> <?=number_format($row->TARIF);?> </td>
							            </tr>      
							            <?PHP } } ?>




							        </tbody>
							        <tfoot>
							        	<tr class="success">
							        		<td style="border: 2px solid #666;" colspan="2"> <b> TOTAL </b> </td>
							        		<td style="border: 2px solid #666;" align="right"> <b><?=number_format($total);?></b> </td>
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
