<style type="text/css">
.coba .active a {
    background: #21AFDA !important;
    color: #fff !important;
}
</style>

<div class="row">
	<div class="col-lg-12">
	    <div class="card-box card-tabs">
	        <ul class="nav nav-tabs coba">
	            <li role="presentation" class="active">
	                <a style="background:#f4f8fb;" href="#all" role="tab" data-toggle="tab" aria-expanded="true"> <i class="fa fa-list"></i> Daftar Jadwal </a>
	            </li> 
	            <li role="presentation" class="">
	                <a style="background:#f4f8fb;" href="#add" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-pencil"></i> Tambah / Ubah Jadwal </a>
	            </li>
	        </ul>
	        <div class="tab-content">
	            <div role="tabpanel" class="tab-pane fade active in" id="all">
	            	<div class="row">
					    <div class="col-sm-12">
					        <div class="card-box table-responsive">
					            <table id="datatable-scroller" class="table table-striped table-bordered table-responsive">
					                <thead>
					                    <tr>
					                        <th style="text-align:center;">KAMAR</th>
					                        <th style="text-align:center;">SENIN</th>
					                        <th style="text-align:center;">SELASA</th>
					                        <th style="text-align:center;">RABU</th>
					                        <th style="text-align:center;">KAMIS</th>
					                        <th style="text-align:center;">JUMAT</th>
					                        <th style="text-align:center;">SABTU</th>
					                        <th style="text-align:center;">MINGGU</th>
					                    </tr>
					                </thead>
					                <tbody>
					                	<?PHP 
						                	$dt_kamar = $this->model->get_data_kamar('');
						                	foreach ($dt_kamar as $key => $kmr) {
					                	?>
					                	<tr>
					                		<td><?=$kmr->KODE_KAMAR;?> <br> <b><?=$kmr->NAMA_KAMAR;?></b></td>
					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Senin');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Selasa');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Rabu');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Kamis');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Jumat');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Sabtu');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                		<td>
					                			<?PHP 
					                			$dt_jadwal_tugas = $this->model->getJadwalTugas($kmr->ID);
					                			foreach ($dt_jadwal_tugas as $key => $jdwl_tugas) {
					                				$dt_jadwal_tugas_detail = $this->model->getJadwalTugasDetail($jdwl_tugas->ID, 'Minggu');
					                			?>					                			
					                			<ul style="list-style: inside none disc;" class="m-b-5">
												    <li> <b><?=$jdwl_tugas->NAMA_TIM;?></b> </li>
												    <ol style="list-style:none none none !important;">
												    	<li> <?=$jdwl_tugas->KETUA;?> <b>(Ketua)</b> </li>

												    	<?PHP foreach ($dt_jadwal_tugas_detail as $key => $jdwl_tugas_det) { ?>
												    	<li> <?=$jdwl_tugas_det->NAMA;?> <b>(<?=$jdwl_tugas_det->WAKTU_AWAL;?> - <?=$jdwl_tugas_det->WAKTU_AKHIR;?>)</b></li>
												    	<?PHP } ?>

												    </ol>
												</ul>
					                			<?PHP } ?>

					                			<?PHP if(count($dt_jadwal_tugas) == 0){ echo "<center style='color:red;'>Tidak ada petugas.</center>"; } ?>
					                		</td>

					                	</tr>
					                	<?PHP } ?>
					                </tbody>
					            </table>
					        </div>
					    </div>
					</div>
	            </div>

	            <div role="tabpanel" class="tab-pane fade" id="add">
	            	<div class="row"> 
					    <div class="col-sm-12">
					        <div class="card-box">
					            <div class="row">
					                <div class="col-lg-12">
					                    <form class="form-horizontal" role="form" method="post" action="<?=base_url().$post_url;?>">
					                    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
											    <?PHP foreach ($dt as $key => $row) { ?>
											    <div class="panel panel-default bx-shadow-none">
											        <div class="panel-heading" role="tab" id="headingOne" style="background-color: #21afda;">
											            <h4 class="panel-title">
											                <a style="font-family: arial; color:#FFF;" role="button" data-toggle="collapse" data-parent="#accordion" href="#hd_<?=$row->ID;?>" aria-expanded="false" aria-controls="hd_<?=$row->ID;?>" class="collapsed">
											                    
											                    <center> <?=strtoupper($row->NAMA_TIM);?> </center>
											                </a>
											            </h4>
											        </div>
											        <div id="hd_<?=$row->ID;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
											            <div class="panel-body" style="border: 1px solid #ccc;">
											            	<div class="form-group">
										                        <label class="col-md-3 control-label"> Ketua / Penanggung Jawab </label>
										                        <div class="col-md-6">
										                            <input type="text" class="form-control" name="png_jawab" id="png_jawab" value="<?=$row->KETUA;?>" readonly style="background:#FFF;">
										                        </div>
										                    </div> 

										                    <div class="form-group">
										                        <label class="col-md-3 control-label"> Opsi </label>
										                        <div class="col-md-6">
										                        	<div class="checkbox checkbox-primary">
						                                                <input id="checkbox_<?=$row->ID;?>" onclick="cek_edit(<?=$row->ID;?>);" type="checkbox">
						                                                <label for="checkbox1">
						                                                    Edit Jadwal
						                                                </label>
						                                            </div>
										                        </div>
										                    </div> 

										                    <hr>

											            	<table id="" class="table table-striped table-bordered">
					                                            <thead>
					                                                <tr>
					                                                    <th style="text-align:center;">Nama</th>
					                                                    <th style="text-align:center;">Senin</th>
					                                                    <th style="text-align:center;">Selasa</th>
					                                                    <th style="text-align:center;">Rabu</th>
					                                                    <th style="text-align:center;">Kamis</th>
					                                                    <th style="text-align:center;">Jumat</th>
					                                                    <th style="text-align:center;">Sabtu</th>
					                                                    <th style="text-align:center;">Minggu</th>

					                                                </tr>
					                                            </thead>

					                                            <tbody>
					                                            	<?PHP 
					                                            		$dt_anggota = $this->model->getAnggotaTim($row->ID); 
					                                            		foreach ($dt_anggota as $key => $ag) {
					                                            	?>
						                                                <tr>
						                                                	<td style="vertical-align:middle;"><?=$ag->NIP;?> <br> <b><?=$ag->NAMA;?></b></td>
						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Senin"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Senin');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Senin" style="margin-top: 10px;" id="head2_Senin_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Senin');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Senin');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Selasa"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Selasa');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Selasa" style="margin-top: 10px;" id="head2_Selasa_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Selasa');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Selasa');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Rabu"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Rabu');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Rabu" style="margin-top: 10px;" id="head2_Rabu_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Rabu');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Rabu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Kamis"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Kamis');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Kamis" style="margin-top: 10px;" id="head2_Kamis_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Kamis');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Kamis');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Jumat"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Jumat');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Jumat" style="margin-top: 10px;" id="head2_Jumat_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Jumat');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Jumat');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Sabtu"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Sabtu');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Sabtu" style="margin-top: 10px;" id="head2_Sabtu_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Sabtu');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Sabtu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                    <td align="center"> 
						                                                    	<div id="tb_<?=$row->ID;?>_ag_<?=$ag->ID;?>_Minggu"> 
						                                                    		<?PHP 
						                                                    			$dt_jadwal = $this->model->getJadwal($row->ID, $ag->ID, 'Minggu');
						                                                    			foreach ($dt_jadwal as $key => $jadwal) {
						                                                    		?> 	   					
																				    <div class="alert alert-success Minggu" style="margin-top: 10px;" id="head2_Minggu_<?=$jadwal->ID;?>">
																				    	<input type="hidden" name="id_perawat[]" value="<?=$jadwal->ID_ANGGOTA;?>"/>
																					    <input type="hidden" name="id_tim[]" value="<?=$jadwal->ID_TIM;?>"/>
																					    <input type="hidden" name="hari[]" value="<?=$jadwal->HARI;?>"/>
																					    <input type="hidden" name="waktu_awal[]" value="<?=$jadwal->WAKTU_AWAL;?>"/>
																					    <input type="hidden" name="waktu_akhir[]" value="<?=$jadwal->WAKTU_AKHIR;?>"/>
												                                        <strong><?=$jadwal->WAKTU_AWAL;?> - <?=$jadwal->WAKTU_AKHIR;?></strong> <br> 
												                                        <button onclick="del_sch2(<?=$jadwal->ID;?>,'Minggu');" type="button" class="btn btn-sm btn-danger waves-effect waves-light"> Hapus </button>
												                                    </div>
																					<?PHP } ?>
						                                                    	</div> 
						                                                    	<button style="display:none;" onclick="$('#id_perawat').val(<?=$ag->ID;?>); $('#id_tim_pop').val(<?=$row->ID;?>); $('#sel_hari').val('Minggu');" data-toggle="modal" data-target="#add_jadwal" type="button" class="btn_<?=$row->ID;?> btn btn-success waves-effect waves-light"> <i class="fa fa-plus"></i> Tambah </button> 
						                                                    </td>

						                                                </tr>
					                                                <?PHP } ?>
					                                            </tbody>
					                                        </table>



											            </div>
											        </div>
											    </div>
											    <?PHP } ?>
											</div>

											<div class="form-group">
							                	<div class="col-sm-offset-5 col-sm-10">
							                		<input type="submit" class="btn btn-success" name="save" value="Simpan" />
										            <button type="reset" class="btn btn-danger">Batal</button>
							                	</div>
							                </div>

					                    </form>
					                </div><!-- end col -->

					            </div><!-- end row -->
					        </div>
					    </div><!-- end col -->
					</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>


<div id="add_jadwal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"> Tambah Jadwal Perawat </h4>
            </div>
            <form method="post" action="<?=base_url().$post_url;?>">
            <div class="modal-body">                
                <div class="row">
                    <div class="col-md-12">
                    	<input type="hidden" name="id_perawat" id="id_perawat" />
                    	<input type="hidden" name="sel_hari" id="sel_hari" />
                    	<input type="hidden" name="id_tim_pop" id="id_tim_pop" />
                        <div class="form-group">
                            <label for="ed_nama_dep" class="control-label">Waktu Awal</label>
                            <div class="input-group m-b-15">
	                            <div class="bootstrap-timepicker">
	                                <input id="waktu_awal_sel" type="text" class="form-control timepicker2" readonly style="background:#FFF;">
	                            </div>
	                            <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                       		</div>
                        </div>

                        <div class="form-group">
                            <label for="ed_nama_dep" class="control-label">Waktu Akhir</label>
                            <div class="input-group m-b-15">
	                            <div class="bootstrap-timepicker">
	                                <input id="waktu_akhir_sel" type="text" class="form-control timepicker2" readonly style="background:#FFF;">
	                            </div>
	                            <span class="input-group-addon bg-primary b-0 text-white"><i class="glyphicon glyphicon-time"></i></span>
                       		</div>
                        </div>                        

                    </div>
                </div>

                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default " data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info" onclick="add_jadwal_perawat();" data-dismiss="modal"> Simpan Jadwal </button>
            </div>
            </form>
        </div>
    </div>
</div>



<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.timepicker2').timepicker({
        showMeridian : false
    })

});

function cek_edit(id){
	if ($('#checkbox_'+id).is(':checked')){
     $('.btn_'+id).show();
    } else {
     $('.btn_'+id).hide();
    }

}

function add_jadwal_perawat(){
	var id_perawat    = $('#id_perawat').val();
	var sel_hari    = $('#sel_hari').val();
	var nama_poli   = $("#id_poli_sel option:selected").text();
	var id_tim     = $('#id_tim_pop').val();
	var waktu_awal  = $('#waktu_awal_sel').val();
	var waktu_akhir = $('#waktu_akhir_sel').val();

	var i = $('.'+sel_hari).length;
	i = parseInt(i) + 1;

	var isi =  
				'<div class="alert alert-success '+sel_hari+'" style="margin-top: 10px;" id="head_'+sel_hari+'_'+i+'">'+
					'<center>'+
					    '<input type="hidden" name="id_perawat[]" value="'+id_perawat+'"/>'+
					    '<input type="hidden" name="id_tim[]" value="'+id_tim+'"/>'+
					    '<input type="hidden" name="hari[]" value="'+sel_hari+'"/>'+
					    '<input type="hidden" name="waktu_awal[]" value="'+waktu_awal+'"/>'+
					    '<input type="hidden" name="waktu_akhir[]" value="'+waktu_akhir+'"/>'+
					    '<strong>'+waktu_awal+' - '+waktu_akhir+'</strong> <br> '+
					    '<button onclick="del_sch(\'' +i+ '\',\'' +sel_hari+ '\');" type="button" class="btn btn-danger waves-effect waves-light"> Hapus </button>'+
				    '</center>'+
				'</div>';

	$('#tb_'+id_tim+'_ag_'+id_perawat+'_'+sel_hari).append(isi);

}

function del_sch(i, sel_hari){
	$('#head_'+sel_hari+'_'+i).remove();
}

function del_sch2(i, sel_hari){
	$('#head2_'+sel_hari+'_'+i).remove();
}

</script>