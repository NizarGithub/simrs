<div class="row" id="profil_dokter">
  
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>js-devan/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    get_dokter();
    
    setInterval(function () {
        get_dokter();
    }, 5000);
});

function get_dokter(){
    $.ajax({
        url : '<?php echo base_url(); ?>portal_pasien/get_dokter',
        type : "GET",
        dataType : "json",
        success : function(res){
            $div = '';

            if(res == null || res == ""){
                $div = '<div class="pt-btn">'+
                        '     <a class="btn btn-info" href="javascript:;">Belum Ada Dokter Yang Bertugas</a>'+
                        '</div>';
            }else{
              for(var i=0; i<res.length; i++){
                  var foto = '';
                  var stt = '';

                  if(res[i].FOTO == null || res[i].FOTO == ""){
                    foto = '<?php echo base_url(); ?>picture/user.png';
                  }else{
                    foto = '<?php echo base_url(); ?>files/foto_pegawai/'+res[i].FOTO;
                  }

                  if(res[i].STS_LOGIN == '1'){
                    stt = '<a class="btn btn-success btn-sm" href="javascript:;">Sedang Online</a>';
                  }else{
                    stt = '<a class="btn btn-danger btn-sm" href="javascript:;">Offline</a>';
                  }

                  res[i].NAMA_DIV = res[i].NAMA_DIV==null?"-":res[i].NAMA_DIV;
                  
                  $div += '<div class="col-md-6">'+
                            '<div class="element-wrapper">'+
                            '  <h6 class="element-header">'+res[i].NAMA+'</h6>'+
                              '<div class="element-box-tp">'+
                                '<div class="profile-tile">'+
                                '  <div class="profile-tile-box">'+
                                '    <div class="pt-avatar-w">'+
                                '      <img src="'+foto+'" style="max-height:60px; max-width:60px;">'+
                                '    </div>'+
                                '    <div class="pt-user-name">'+res[i].NAMA_DIV+'</div>'+
                                '  </div>'+
                                '  <div class="profile-tile-meta">'+
                                '    <ul>'+
                                '      <li>'+
                                '        Last Login:<strong>'+res[i].TIME_LOG+'</strong>'+
                                '      </li>'+
                                '      <li>'+
                                '        Tanggal:<strong>'+res[i].DATE_LOG+'</strong>'+
                                '      </li>'+
                                '    </ul>'+
                                '    <div class="pt-btn">'+stt+'</div>'+
                                '  </div>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>';
              }
            }

            $('#profil_dokter').html($div);
        }
    });
}
</script>