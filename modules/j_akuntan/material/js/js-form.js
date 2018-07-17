function FormatCurrency(objNum){	
	var num = objNum.value
	if(num==undefined)
		var num = objNum.val();
	var ent, dec;

	if (num != '' && num != objNum.oldvalue)
	{
		num = MoneyToNumber(num);
		if (isNaN(num))
		{		
			objNum.value = (objNum.oldvalue)?objNum.oldvalue:'';
		} else {
			var ev = (navigator.appName.indexOf('Netscape') != -1)?Event:event;
	
			if (ev.keyCode == 190 || !isNaN(num.split('.')[1]))
			{	
				objNum.value = AddCommas(num.split('.')[0])+'.'+num.split('.')[1];
			}
			else
			{	
				objNum.value = AddCommas(num.split('.')[0]);
			}
	
			objNum.oldvalue = objNum.value;
		}
	}
}

function numberWithCommas(num) {
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function MoneyToNumber(num){
	if(num != '' && num != null)
	{
		// return num.split(',').join('');
		return num.toString().replace(/,/g, '');
	}
	else
	{
		return parseInt(0);
	}
}

function AddCommas(num){
	numArr=new String(num).split('').reverse();
	for (i=3;i<numArr.length;i+=3)
	{
		numArr[i]+=',';
	}
	return numArr.reverse().join('');
}

function NumberToMoney(num){
	numberArr = new String(num).split(',');
	numArr=new String(numberArr[0]).split('').reverse();
	for (i=3;i<numArr.length;i+=3)
	{
		numArr[i]+=',';
	}
	numberArr[0] = numArr.reverse().join('');
	if (numberArr[1] == null || numberArr[1] == '') numberArr[1] = '00';
	return numberArr[0] + "." + numberArr[1];
}

function NumbToMonDot(num){
    numberArr = new String(num).split('.');
    numArr=new String(numberArr[0]).split('').reverse();
    for (i=3;i<numArr.length;i+=3)
    {
        numArr[i]+=',';
    }
    numberArr[0] = numArr.reverse().join('');
    if (numberArr[1] == null || numberArr[1] == '') numberArr[1] = '00';
    return numberArr[0] + "." + numberArr[1];
}

function NumbToMon(num){
    numberArr = new String(num).split('.');
    numArr=new String(numberArr[0]).split('').reverse();
    for (i=3;i<numArr.length;i+=3)
    {
        numArr[i]+='.';
    }
    numberArr[0] = numArr.reverse().join('');
    if (numberArr[1] == null || numberArr[1] == '') numberArr[1] = '00';
    return numberArr[0] + "," + numberArr[1];
}

function only_number(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
        return true;
}

function isPositive(number){
    if(number < 0){
        number = Math.abs(number);
        number = "(Rp. "+NumberToMoney(number)+")";
    }else{
        number = "Rp. "+NumberToMoney(number);
    }
    return number;
}

(function ( $ ) {
 
    $.greenify = function(shade) {
        var settings = $.extend({
            // These are the defaults.
            selector : "#koper",
            url : "",
            result : "",
            color: "blue",
            backgroundColor: "white",
            data : {},
        }, shade );

        var ajax = '';

        function tabel(){

        	var $isi = '<div id="popup_koper">'+
						    '<div class="window_koper">'+
						    '    <a href="javascript:void(0);"><img src="'+base_url+'ico/cancel.gif" id="pojok"></a>'+
						    '    <div class="panel-body">'+
						    '    <input type="text" name="search_koper" id="search_koper" class="form-control" value="" placeholder="Cari perkiraan...">'+
				            '	 <div class="table-responsive">'+
							'            <table class="table table-hover" id="tes">'+
							'                <thead>'+
							'                    <tr>'+
							'                        <th>NO</th>'+
							'                        <th style="white-space:nowrap; text-align:center;">KODE PERKIRAAN</th>'+
							'                        <th>NAMA PERKIRAAN</th>'+
							'                    </tr>'+
							'                </thead>'+
							'                <tbody>'+
						
							'                </tbody>'+
							'            </table>'+
							'        </div>'+
						    '    </div>'+
						    '</div>'+
						'</div>';
				$('body').append($isi);

				$('#pojok').click(function(){
			        $('#popup_koper').css('display','none');
			        $('#popup_koper').hide();
			        $('#search_koper').val("");
			    });

		       $('#popup_koper').css('display','block');
		       $('#popup_koper').show();
        }

        function get_data(){
        	var koper = $('#search_koper').val();

        	if(ajax){
        		ajax.abort();
        	}

        	ajax = $.ajax({
        		url : settings.url,
        		type : "GET",
        		dataType : "json",
        		data : {
        			keyword : koper,
        		},
        		success : function(result){
        			var isine = '';
        			var no = 0;
        			$.each(result,function(i,res){
        				no++;
        				isine += '<tr>'+
								    '<td align="center">'+no+'</td>'+
								    '<td align="center"><a href="javascript:void(0);">'+res.KODE_PERKIRAAN+'</a></td>'+
								    '<td>'+res.NAMA_PERKIRAAN+'</td>'+
							    '</tr>';
        			});
        			$('#tes tbody').html(isine); 
        			$('#search_koper').off('keyup').keyup(function(){
			        	get_data();
			        });

			        $('#popup_koper tbody a').off('click').click(function(){
			        	var kode_perkiraan = $(this).text();
			        	$(settings.result).val(kode_perkiraan);
					    $('#popup_koper').css('display','none');
					    $('#popup_koper').hide();
			        })
        		}
        	});
        }

       	

        $(settings.selector).click(function(){
        	tabel();
        	get_data();
        });

    };
 
}( jQuery ));