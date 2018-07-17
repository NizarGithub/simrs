<style type="text/css">
.recent_add td{
	background: #CDE69C;
}

#tes td {
	vertical-align: middle;
}
</style>

<div class="row-fluid ">
	<div class="span12">
		<div class="primary-head">
			<h3 class="page-header"> <i class="icon-random"></i>  Transfer Data Akuntansi </h3>

		</div>
		<ul class="breadcrumb">
			<li><a href="#" class="icon-home"></a><span class="divider "><i class="icon-angle-right"></i></span></li>
			<li><a href="#">Dashboard</a><span class="divider"><i class="icon-angle-right"></i></span></li>
			<li class="active"> Transfer Data Akuntansi  </li>
		</ul>
	</div>
</div>


<form class="form-horizontal" method="post" action="<?=base_url().$post_url;?>" enctype="multipart/form-data">
  <div class="row-fluid" id="edit_data">
  	<div class="span12">
  		<div class="content-widgets light-gray">
  			<div class="widget-head blue">
  				<h3> Transfer Data </h3>
  			</div>

  			<div class="widget-container">				

  					<div class="control-group">
              <label class="control-label"> <b style="font-size: 14px;"> Filter </b> </label>
              <div class="controls">
                <label class="radio inline">
                <input onclick="isfilter();" type="radio" checked="" value="Harian" id="harian" name="filter">
                  Harian </label>
                <label class="radio inline">
                <input onclick="isfilter();" type="radio" value="Bulanan" id="bulanan" name="filter">
                Bulanan </label>
              </div>
            </div>

            <div class="control-group harian">
              <label class="control-label"> <b style="font-size: 14px;"> Tanggal </b> </label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on"><i class="icon-calendar"></i></span>
                  <input type="text" name="tgl" id="reservation" value=""/>
                </div>
              </div>
            </div>

            <div class="control-group bulanan" style="display:none;">
              <label class="control-label"> <b style="font-size: 14px;"> Bulan </b> </label>
              <div class="controls">
                <select class="span4" name="bulan">
                  <option <?PHP if(date('m') == '01' ){ echo "selected"; } ?> value="01"> Januari </option>
                  <option <?PHP if(date('m') == '02' ){ echo "selected"; } ?> value="02"> Februari </option>
                  <option <?PHP if(date('m') == '03' ){ echo "selected"; } ?> value="03"> Maret </option>
                  <option <?PHP if(date('m') == '04' ){ echo "selected"; } ?> value="04"> April </option>
                  <option <?PHP if(date('m') == '05' ){ echo "selected"; } ?> value="05"> Mei </option>
                  <option <?PHP if(date('m') == '06' ){ echo "selected"; } ?> value="06"> Juni </option>
                  <option <?PHP if(date('m') == '07' ){ echo "selected"; } ?> value="07"> Juli </option>
                  <option <?PHP if(date('m') == '08' ){ echo "selected"; } ?> value="08"> Agustus </option>
                  <option <?PHP if(date('m') == '09' ){ echo "selected"; } ?> value="09"> September </option>
                  <option <?PHP if(date('m') == '10' ){ echo "selected"; } ?> value="10"> Oktober </option>
                  <option <?PHP if(date('m') == '11' ){ echo "selected"; } ?> value="11"> November </option>
                  <option <?PHP if(date('m') == '12' ){ echo "selected"; } ?> value="12"> Desember </option>
                </select>
              </div>
            </div>

            <div class="control-group bulanan" style="display:none;">
              <label class="control-label"> <b style="font-size: 14px;"> Tahun </b> </label>
              <div class="controls">
                <select class="span4" name="tahun">
                  <option value="2016"> 2016 </option>
                  <option value="2017"> 2017 </option>
                  <option value="2018"> 2018 </option>
                </select>
              </div>

            </div>

            <div class="form-actions" style="padding-left: 0;">
              <center>
              <input type="submit" class="btn btn-lg btn-success" name="simpan" value="Transfer Data">
              </center> 
            </div>


  			</div>
  		</div>
  	</div>
  </div>
</form>

<script type="text/javascript">
function isfilter(){

  if($("#harian").is(':checked')){
      $('.harian').show(); 
      $('.bulanan').hide(); 
  } 

  if($("#bulanan").is(':checked')){
      $('.harian').hide(); 
      $('.bulanan').show();  
  } 
}

</script>