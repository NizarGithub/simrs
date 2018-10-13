<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
var ajax = "";
var offset          = 5; //customize this as your need
$(document).ready(function(){
	load_data_pasien();

	$('#scroll_data2').scroll(function(event){
        var keyword = '';
        offset += 1;

        if(ajax){
            ajax.abort();
        }

        ajax = $.ajax({
            url : '<?php echo base_url(); ?>admum/admum_pasien_rj_c/load_data_pasien',
            data : {
                keyword:keyword,
                offset: offset
            },
            type : "GET",
            dataType : "json",
            success : function(result){
                $tr = "";

                if(result == "" || result == null){
                    $tr = "<tr><td style='text-align:center;' colspan='9'><b>Data Tidak Ada</b></td></tr>";
                }else{
                    var no = 0;

                    for(var i=0; i<result.length; i++){
                        no++; 

                        result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=='L'?"Laki - Laki":'Perempuan';
                        result[i].TANGGAL_LAHIR = (result[i].TANGGAL_LAHIR==null || result[i].TANGGAL_LAHIR=='')?"-":result[i].TANGGAL_LAHIR;
                        result[i].NAMA_AYAH = result[i].NAMA_AYAH==null?"-":result[i].NAMA_AYAH;
                        result[i].NAMA_IBU = result[i].NAMA_IBU==null?"-":result[i].NAMA_IBU;
                        result[i].ALAMAT = (result[i].ALAMAT==null || result[i].ALAMAT=='')?"-":result[i].ALAMAT;

                        var umur = result[i].UMUR+' Tahun '+result[i].UMUR_BULAN+' Bulan';

                        $tr += "<tr>"+
                        			"<input type='hidden' name='id[]' value='"+result[i].ID+"'>"+
                                    "<td style='white-space:nowrap; text-align:center;'>"+no+"</td>"+
                                    "<td style='white-space:nowrap; text-align:center;'>"+result[i].KODE_PASIEN+"</td>"+
                                    "<td style='white-space:nowrap;'>"+result[i].NAMA+"</td>"+
                                    "<td style='white-space:nowrap; text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
                                    "<td style='white-space:nowrap; text-align:center;'>"+result[i].TANGGAL_LAHIR+"</td>"+
                                    "<td style='white-space:nowrap; text-align:center;'>"+umur+"</td>"+
                                    "<td style='white-space:nowrap;'>"+result[i].NAMA_AYAH+"</td>"+
                                    "<td style='white-space:nowrap;'>"+result[i].NAMA_IBU+"</td>"+
                                    "<td style='white-space:nowrap;'>"+result[i].ALAMAT+"</td>"+
                                "</tr>";
                    }
                }

                $('#tabel_pasien tbody').html($tr);
            }
        });
    });

    $('#btn_proses').click(function(event){
    	$('#popup_load').show();
    	$.ajax({
            url : '<?php echo base_url(); ?>bantuan_dewe/ubah',
            data : $('#form_data').serialize(),
            type : "POST",
            dataType : "json",
            success : function(result){
                alert('ok');
                load_data_pasien();
                // $('#popup_load').hide();
            }
        });
    });
});

function load_data_pasien(){
    $('#popup_load').show();
    var keyword = '';

    if(ajax){
        ajax.abort();
    }

    ajax = $.ajax({
        url : '<?php echo base_url(); ?>bantuan_dewe/load_data_pasien',
        data : {
            keyword:keyword,
            offset: offset
        },
        type : "GET",
        dataType : "json",
        success : function(result){
            $tr = "";

            if(result == "" || result == null){
                $tr = "<tr><td style='text-align:center;' colspan='9'><b>Data Tidak Ada</b></td></tr>";
            }else{
                var no = 0;

                for(var i=0; i<result.length; i++){
                    no++; 

                    result[i].JENIS_KELAMIN = result[i].JENIS_KELAMIN=='L'?"Laki - Laki":'Perempuan';
                    result[i].TANGGAL_LAHIR = (result[i].TANGGAL_LAHIR==null || result[i].TANGGAL_LAHIR=='')?"-":result[i].TANGGAL_LAHIR;
                    result[i].NAMA_AYAH = result[i].NAMA_AYAH==null?"-":result[i].NAMA_AYAH;
                    result[i].NAMA_IBU = result[i].NAMA_IBU==null?"-":result[i].NAMA_IBU;
                    result[i].ALAMAT = (result[i].ALAMAT==null || result[i].ALAMAT=='')?"-":result[i].ALAMAT;

                    var umur = result[i].UMUR+' Tahun '+result[i].UMUR_BULAN+' Bulan';

                    var tanggal_lahir = result[i].TANGGAL_LAHIR;
                    var tanggal_daftar = result[i].TANGGAL_DAFTAR;

                    var str = tanggal_lahir.split('/').join('-');
                    var str2 = tanggal_daftar.split('/').join('-');

                    // Here are the two dates to compare
				    var tgl = result[i].TANGGAL_LAHIR; //10-07-2018
				    var d = tgl.substr(0,2);
				    var m = tgl.substr(3,2);
				    var y = tgl.substr(6);
				    var date2 = "<?php echo date('Y-m-d'); ?>";
				    var date1 = y+'-'+m+'-'+d;

				    // First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
				    date1 = date1.split('-');
				    date2 = date2.split('-');

				    // Now we convert the array to a Date object, which has several helpful methods
				    date1 = new Date(date1[0], date1[1], date1[2]);
				    date2 = new Date(date2[0], date2[1], date2[2]);

				    // We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
				    date1_unixtime = parseInt(date1.getTime() / 1000);
				    date2_unixtime = parseInt(date2.getTime() / 1000);

				    // This is the calculated difference in seconds
				    var timeDifference = date2_unixtime - date1_unixtime;

				    // in Hours
				    var timeDifferenceInHours = timeDifference / 60 / 60;

				    // and finaly, in days :)
				    var timeDifferenceInDays = timeDifferenceInHours  / 24;

				    //in month
				    var timeDifferenceInMonth = (date2.getMonth() - date1.getMonth());

				    //in year
				    var timeDifferenceInYear = (date2.getFullYear() - date1.getFullYear());

				    if(parseInt(timeDifferenceInMonth) < 0){
				        timeDifferenceInMonth = '0';
				    }

                    $tr += "<tr>"+
                    			"<input type='hidden' name='id[]' value='"+result[i].ID+"'>"+
                    			"<input type='hidden' name='tanggal_lahir[]' value='"+str+"'>"+
                    			"<input type='hidden' name='tanggal_daftar[]' value='"+str2+"'>"+
                    			// "<input type='hidden' name='umur[]' value='"+timeDifferenceInYear+"'>"+
                    			// "<input type='hidden' name='umur_bulan[]' value='"+timeDifferenceInMonth+"'>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+no+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].KODE_PASIEN+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].JENIS_KELAMIN+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+result[i].TANGGAL_LAHIR+"</td>"+
                                "<td style='white-space:nowrap; text-align:center;'>"+umur+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA_AYAH+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].NAMA_IBU+"</td>"+
                                "<td style='white-space:nowrap;'>"+result[i].ALAMAT+"</td>"+
                            "</tr>";
                }
            }

            $('#tabel_pasien tbody').html($tr);
            $('#popup_load').hide();
        }
    });
}
</script>

<div id="popup_load">
    <div class="window_load">
        <img src="<?=base_url()?>picture/progress.gif" height="100" width="125">
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
    	<div class="card-box">
	        <form class="form-horizontal" role="form" action="" method="post" id="form_data">
		        <div class="form-group">
		        	<div class="table-responsive" style="height: 400px;">
	                    <div id="scroll_data" style="overflow-y: scroll; overflow-x: hidden; height: 400px;">
	                        <table class="table table-hover table-bordered" id="tabel_pasien">
	                            <thead>
	                                <tr class="merah_popup">
	                                    <th style="text-align:center; color: #fff;" width="50">No</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">No. RM</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Pasien</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Jenis Kelamin</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Tanggal Lahir</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Umur</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Ayah</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Nama Ibu</th>
	                                    <th style="text-align:center; color: #fff; white-space: nowrap;">Alamat</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
		        </div>
		        <div class="form-group">
		        	<button type="button" class="btn btn-success" id="btn_proses">
                        <i class="fa fa-refresh"></i> <span><b>Proses</b></span>
                    </button>
		        </div>
	        </form>
    	</div>
    </div>
</div>