$.extend({
	popup: function(options) {
		
		var o = $.extend({
			'popup': '#popup',
			'by': '',
			'url': '',
			'get_detil': '',
			'result': Array(),
			'find': '#SEARCH',
			'grid': '#popup .grid tbody tr',
			'left': '',
			'top': '',
			'format': {},
			'image_dir': "./",
			'edit_image': "edit.gif",
			'del_image': "del.gif",
			'is_update': true,
			'is_delete': true,
			'update': '',
			'form': '',
			'freeze'	: false,
			'add_detil'	: false,
		}, options);

		var selected = -1;
		var size = 0;
		var result = new Array();
		var nama_akun;
		var data_temp;
		var object;
		var url_plus = "";
		var ajax;
		var $objp;
		var $page = 2;
		
		var scrll = function (){
			if($(this).scrollTop() + $(this).height() >= ($('#popup #grid').height()+17)) {
				if($page != 0){
					get_data_per_page($page);
				}
			}
		}

		$(o.by).off('click'); //#HM
		$(o.by).click(function() {
			idex = $(this).index(o.by); 
			$page = 2;
            $objp = $(this);
			if ($(o.popup).text() == '') {
				$_newDiv = '<div  id="popup" style="max-height:500px;border:blue;min-height:50px;max-width:110%;overflow: auto;border-style:solid;border-width:1px;position: absolute;z-index:9999999;background-color: #F3F3F3;min-width:360px;padding:5px;">'+
											'&nbsp;Keyword <span id="click_search">:</span> <input type="text" id="SEARCH" style="width:250px">'+
											'<span class="tutup" title="Tutup">&nbsp;&nbsp;&nbsp;&nbsp;</span>'+
										'<div id="isi_popup"><div id="grid" class="tabel-data"></div></div>';
				if(o.add_detil)
					$_newDiv += '<div id="dtl_popup"><textarea readonly style="width:99%;"></textarea></div>';

				$_newDiv += '</div>';
				$("body").after($_newDiv);
				$(o.popup).css("display", "block");
 
 				//#HM --
                position = $(o.by).eq(idex).position();
               
               if($(o.by).length == 1){
	               if(o.left == ''){
	                    left = position.left;
	                    o.left = position.left-180;
	                    o.top  = position.top+30;
	                }
                }else{
                	left = position.left;
	                    o.left = position.left-180;
	                    o.top  = position.top+30;
                }

                //----
               //  console.log(o.left+"--"+o.top); #HM
			   
                $("#popup").attr('style', 'left:'+o.left+'px !important;top:'+o.top+'px !important;max-height:500px;border:blue;min-height:50px;max-width:110%;overflow: auto;border-style:solid;border-width:1px;position: absolute;z-index:99999;background-color: #F3F3F3;min-width:360px;padding:5px;');

                $(o.find).focus();


				nama_akun = $("#SEARCH").val();
				tampil_data();
				
				//------------------------------------------
					$( "#isi_popup" ).scroll(scrll);
				//------------------------------------------
				
				
			} else {
				 $(o.popup).remove();
				$("#isi_popup").off('scroll', scrll);
			}

			$(o.popup).find(".tutup").click(function() {
               $(o.popup).remove();
			   $("#isi_popup").off('scroll', scrll);
			});

			$(o.find).keydown(function(e) {
				object = $(this);
				object.attr("autocomplete", "off");
				if (e.which == 13) {
					get_result(data_temp);
					$(o.popup).remove();
					$("#isi_popup").off('scroll', scrll);
					e.preventDefault();
				}
				
			});
			$(o.find).keyup(function(e) {
				
				object = $(this);
				position = object.position();
				if (o.left == '') {
					o.left = position.left;
					o.top = position.top;
				}
				nama_akun = object.val();
				object.attr("autocomplete", "off");
				if (e.which == 40 || e.which == 9 || e.which == 38) //move up, down 
				{
					size = $(o.grid).length;

					switch (e.which) {
						case 40:
							selected = selected >= size - 1 ? 0 : selected + 1;
							break;
						case 38:
							selected = selected <= 0 ? size - 1 : selected - 1;
							break;
						default:
							break;
					}
					$(o.grid).removeClass('selected').eq(selected).addClass("selected");
					e.preventDefault();

				} else if (e.which == 13) {
					get_result(data_temp);
					$(o.popup).remove();
					$("#isi_popup").off('scroll', scrll);
					e.preventDefault();
				} else if (e.which == 27) {
					$(o.popup).remove();
					e.preventDefault();
					$("#isi_popup").off('scroll', scrll);
				} else {
					if (e.which != 37 && e.which != 39){
						if (ajax) {
							ajax.abort();
						}
						$page = 2;
						tampil_data();
					}
				}
				
				
			});
		
		});

		function get_data_per_page(page){
			if (o.url_plus) {
				url_plus = o.url_plus.call();
			}
			if (ajax) {
				ajax.abort();
			}
			selected = -1;
			preloader(o.popup, 1);
			ajax = $.ajax({
				url: o.url + '/'+ page + url_plus,
				data: {
					keyword: nama_akun
				},
				type: "GET",
				dataType: "json",
				cache: true,
				success: function(data) {
					
					if(data['data'].length != 0 && typeof data['fields'] != 'undefined'){
						$page = page+1;
						var no 		= parseInt($(o.popup + " #isi_popup .grid tbody tr").eq(-1).find('td').eq(0).text());
						
						$.each(data['data'],  function(c, d){
							no++;
							if(no % 2 == 0) klas = 'even'; else klas = '';
							var str = '<tr class="'+klas+'"><td class="num">'+no+'</td>';
							
							for(var key in data['fields']){
								if(typeof data['fields'][key]['link'] == 'undefined')
									str +='<td>'+d[key]+'</td>'; 
								else
									str +='<td><a href="#">'+d[key]+'</a></td>'; 
							};
							str += '</tr>';
							
							$(o.popup + " #isi_popup .grid tbody").append(str);
						});
						
						data_temp = data_temp.concat(data['data']);
						
						 $( "body" ).off( "click", "#popup a");
						$("#popup a").click(function() {
							get_result(data_temp, $(this));
							$(o.popup).remove();
							$("#isi_popup").off('scroll', scrll);
							return false;
						});
						preloader(o.popup, 0);
					}
					else
					{
						$page = 0;
						preloader(o.popup, 0);
					}
				}
			});
		
		}
		
		function tampil_data() {
			if (o.url_plus) {
				url_plus = o.url_plus.call();
			}
			selected = -1;
			preloader(o.popup, 1);
			ajax = $.ajax({
				url: o.url + url_plus,
				data: {
					keyword: nama_akun
				},
				type: "GET",
				dataType: "json",
				cache: true,
				success: function(data) {
					$(o.popup + " #isi_popup #grid").html('<br>'+data['grid']);
					if(o.add_detil)
						$("#popup .grid tbody tr").hover( function() {
							var indexs = $(this).index("#popup .grid tbody tr");
							$("#dtl_popup textarea").html(data['data'][indexs][o.add_detil]);
						}, function() {
							//$( this ).find('td').attr('style','backgroud-color:#FDFDFD !important');
						});

					data_temp = data['data'];
					//klik link------------------------
					$("#popup a").click(function() {
						get_result(data_temp, $(this));
						$(o.popup).remove();
						$("#isi_popup").off('scroll', scrll);
						return false;
					});

					if(o.freeze){
									
						$_col = $("#popup .grid thead th").length;
						$_array = new Array();
						$_array[0] = 35;
						for(var i=1; i<$_col;i++){
							$_array[i] = $("#popup .grid thead th").eq(i).width()+25;
						}
						
						$_width = $("#popup .grid").width()+40;
						$_width = $_width < 620?$_width:620;
						$_height = $("#popup .grid").height();
						$_height = $_height > 245?245:$_height;

						$('#popup .grid').fixheadertable({
							colratio : $_array,
							height : $_height,
							width : $_width,
							resizeCol   : true,
						});

						if(o.filter){
							$_pHeight = 350;
						}else{
							if(o.freeze){
								$_pHeight = 350;
							}else{
								$_pHeight = 335;
							}
						}
						$("#popup").css({"overflow":"hidden","height":$_pHeight+"px","max-height":"","max-width":""});
						if($_height > 245){
							$(".headtable").css("margin-right","17px");
						}
						$(".ui-corner-all .ui-widget-header").css("background","none repeat scroll 0 0 #F3F3F3");
					}
					preloader(o.popup, 0);
                    //$indexss = $("#isi_popup td:has(a)").eq(0).index('#isi_popup tbody tr:eq(0) td');
                    //$("#isi_popup td:has(a)").attr('style','white-space:unset !important');
                    //$('#isi_popup thead th').eq($indexss).attr("style", "width:30% !important");
                    //$(".grid th").css("width", '400px !important');
                    //$(".grid td").css("width", '400px  !important');

				}
			});
			$(o.grid).removeClass('selected').eq(selected).addClass("selected");

		}

		function get_result(data, obj) {
			
			if (obj) {
				selected = $("#popup a").index(obj);
			}
			if(selected != -1){
				inpt_selected = $($objp).index(o.by);
				//inpt_selected = 0; #hm
				//console.log(inpt_selected);
				var az = o.result;
				for (field in az) {
					var val = $.trim(data[selected][field]);
					if (o.format[field] == "currency") {
						val = number_format(val);
					}
					if ($($(az[field]).eq(inpt_selected)).is(':text')) {

						$(az[field]).eq(inpt_selected).val(val);
						
					} else if ($($(az[field]).eq(inpt_selected)).is(':hidden')) {
						$(az[field]).eq(inpt_selected).val(val);
					} else if ($($(az[field]).eq(inpt_selected)).is(':checkbox')) {
						if (val != 0) {
							$(az[field]).eq(inpt_selected).checked = true;
						}
					} else if ($($(az[field]).eq(inpt_selected)).is('select')) {
						$(az[field] + ' option:[value=' + val + ']').eq(inpt_selected).attr("selected", "selected");
					} else {
						$(az[field]).eq(inpt_selected).html(val);
					}

				}
				if (o.done) {
					o.done.call(1, data[selected], $objp);
				}

				//set action form
				if (o.is_update == true) {
					if(o.form) $form = o.form; else $form = "#form";
					$($form).attr("action", o.update + "/update/" + data[selected]["ID"]);
				}
				$("#delete").remove();
				if (o.is_delete) {
					$("input:submit").after('<input type="button" id="delete" value="Delete" onclick="window.location.href=\'' + o.update + '/delete/' + data[selected]["ID"] + '\';">');
				}
			}
			preloader(o.popup, 0);
		}
		
	},
	upload_file: function(options) {
		var o = $.extend({
			'input': '.images',
			'preview': '#preview',
			'response': '#response',
			'img_list': 'image-list',
			'width': '350',
			'base_url': '',
		}, options);

		var input = $(o.input),
			formdata = false;
		$(o.preview).html('<div id="response"></div><ul id="image-list"></ul>');

		function showUploadedItem(source) {
			$("#response").prepend('<div class="preloader">&nbsp;</div>');
			var list = document.getElementById(o.img_list),
				li = document.createElement("li"),
				img = document.createElement("img");
			list.innerHTML = '';
			img.src = source;
			if (o.width) {
				img.width = o.width;
			}
			li.appendChild(img);
			list.appendChild(li);
			$(".preloader").remove();
		}

		if (window.FormData) {
			formdata = new FormData();
		}
		//event onclick
		$(".toview").find('img').click(function() {
			index_img = $(this).index("img") - 1;
			$(".toview").eq(index_img).find("a").html("File");
			$(".toview").eq(index_img).find('img').attr("src", o.base_url + "del.gif");
			$(".toview").find("input[type='hidden']").eq(index_img).remove();
			$(o.preview).find("#image-list").html("");
		});
		$(".toview").find('a').click(function() {
			index_a = $(this).index(".toview a");
			source = $(".toview").eq(index_a).find("input[type='hidden']").val();
			showUploadedItem(source);
		});
		//end event
		input.change(function() {
			obj = this;
			index = $(obj).index("input[type='file']");
			$("#response").prepend('<div class="preloader">&nbsp;</div>');
			$(".toview").find("input[type='hidden']").eq(index).remove();
			//$(o.response).html("Uploading . . .");
			var i = 0,
				len = this.files.length,
				img, reader, file;
			for (; i < len; i++) {
				file = this.files[i];
				if (window.FileReader) {
					reader = new FileReader();
					reader.onloadend = function(e) {
						showUploadedItem(e.target.result);
						$(".toview").eq(index).find("a").html(file['name']);
						$(".toview").eq(index).find("a").html(file['name']).after("<input type='hidden' name='img' value='" + e.target.result + "'>");
						$(".toview").eq(index).find('img').attr("src", o.base_url + "del.gif");
						$(".toview").eq(index).find('img').click(function() {
							index_img = $(this).index("img") - 1;
							$(obj).eq(index_img).val("");
							$(".toview").eq(index_img).find("a").html("File");
							$(".toview").eq(index_img).find('img').attr("src", o.base_url + "del.gif");
							$(".toview").find("input[type='hidden']").eq(index_img).remove();
							$(o.preview).find("#image-list").html("");
						});
						$(".toview").eq(index).find('a').click(function() {
							index_a = $(this).index(".toview a");
							source = $(".toview").eq(index_a).find("input[type='hidden']").val();
							showUploadedItem(source);
						});
					};
					reader.readAsDataURL(file);
				}
				// if (formdata) {
				// formdata.append(o.input, file);
				// }

			}

			// if (formdata) {
			// $.ajax({
			// url: o.url,
			// type: "POST",
			// data: formdata,
			// processData: false,
			// contentType: false,
			// success: function (res) {
			// $(o.response).html(res); 
			// }
			// });
			// }
		});
	},
	//====== tambahan dicky ==============================================================================================
	upload_fileQ: function(options) {
		var o = $.extend({
			'input': '.images',
			'preview': '#preview',
			'response': '#response',
			'img_list': 'image-list',
			'width': '180',
			'height': '230',
			'base_url': '',
		}, options);

		var input = $(o.input),
			formdata = false;
		$(o.preview).html('<div id="response"></div><ul id="image-list"></ul>');

		function showUploadedItem(source) {
			$("#response").prepend('<div class="preloader">&nbsp;</div>');
			var list = document.getElementById(o.img_list),
				li = document.createElement("li"),
				img = document.createElement("img");
			list.innerHTML = '';
			// list1.innerHTML='';
			// list2.innerHTML='';
			img.src = source;
			if (o.width) {
				img.width = o.width;
				img.height = o.height;
			}
			li.appendChild(img);
			list.appendChild(li);
			$(".preloader").remove();
		}

		if (window.FormData) {
			formdata = new FormData();
		}
		//event onclick
		$(".toview").find('img').click(function() {
			index_img = $(this).index("img") - 1;
			$(".toview").eq(index_img).find("a").html("");
			$(".toview").eq(index_img).find('img').attr("src", o.base_url + "del.gif");
			$(".toview").find("input[type='hidden']").eq(index_img).remove();
			$(o.preview).find("#image-list").html("");
		});
		$(".toview").find('a').click(function() {
			index_a = $(this).index(".toview a");
			source = $(".toview").eq(index_a).find("input[type='hidden']").val();
			showUploadedItem(source);
		});
		//end event
		input.change(function() {
			obj = this;
			index = $(obj).index("input[type='file']");
			$("#response").prepend('<div class="preloader">&nbsp;</div>');
			$(".toview").find("input[type='hidden']").eq(index).remove();
			//$(o.response).html("Uploading . . .");
			var i = 0,
				len = this.files.length,
				img, reader, file;
			for (; i < len; i++) {
				file = this.files[i];
				if (window.FileReader) {
					reader = new FileReader();
					reader.onloadend = function(e) {
						showUploadedItem(e.target.result);
						$(".toview").eq(index).find("a").html(file['name']);
						$(".toview").eq(index).find("a").html(file['name']).after("<input type='hidden' name='img' value='" + e.target.result + "'>");
						$(".toview").eq(index).find('img').attr("src", o.base_url + "del.gif");
						$(".toview").eq(index).find('img').click(function() {							

							index_img = $(this).index("img")-1;

							alert("index= "+index+", index_img= "+index_img);							
							
							switch(index_img){
								case 0: 
									if(index==2){
										$(obj).eq(-2).val("");
									}else if(index==1){
										$(obj).eq(0).val("");
									}else{
										$(obj).eq(index_img).val("");
									}
									break;
								case 1: 
									if(index==2){
										$(obj).eq(-1).val("");
									}else if(index==1){
										$(obj).eq(-1).val("");
									}
									break;
								case 2: 
									
									$(obj).eq(0).val("");
									
									break;
							}
				
							$(".toview").eq(index_img).find("a").html("File");
							$(".toview").eq(index_img).find('img').attr("src", o.base_url + "del.gif");
							$(".toview").find("input[type='hidden']").eq(index_img).remove();
							$(o.preview).find("#image-list").html("");
						});
						$(".toview").eq(index).find('a').click(function() {
							index_a = $(this).index(".toview a");
							source = $(".toview").eq(index_a).find("input[type='hidden']").val();
							showUploadedItem(source);
						});
					};
					reader.readAsDataURL(file);
				}
				// if (formdata) {
				// formdata.append(o.input, file);
				// }

			}

			// if (formdata) {
			// $.ajax({
			// url: o.url,
			// type: "POST",
			// data: formdata,
			// processData: false,
			// contentType: false,
			// success: function (res) {
			// $(o.response).html(res); 
			// }
			// });
			// }
		});
	},

	//====== end of tambahan dicky =======================================================================================
	get_data1: function(options) {
		var o = $.extend({
			'url': '',
		}, options);
		alert(o.url);
	},

	get_data: function(options) {
		var o = $.extend({
			'url': '',
			'result': '',
			'find': '',
			'event': 'change',
		}, options);

		var nama_akun;

		$(o.find).bind(o.event, function() {
			preloader(o.find, 1);
			nama_akun = $(o.find).val();
			$.ajax({
				url: o.url,
				data: {
					keyword: nama_akun
				},
				type: "GET",
				dataType: "json",
				success: function(data) {
					var az = o.result;
					for (field in az) {
						var val = $.trim(data[az[field]]);
						if ($(field).is(':text')) {
							$(field).val(val);
						} else if ($(field).is(':hidden')) {
							$(field).val(val);
						} else if ($(field).is('select')) {
							$(field + ' option:[value=' + val + ']').attr("selected", "selected");
						} else {
							$(field).html(val);
						}
					}
					if (o.done) {
						o.done.call(1, data);
					}
					preloader(o.find, 0);
				}
			});
		});

	},

	grid: function(options) {
		var o = $.extend({
			'base_url': '',
			'url': '',
			'result': '',
			'data': {},
			'field': {},
			'image_dir': '',
			'type': {},
			'action_img': '',
			'link': '',
			'width': {},
			'append': false,
			'totextarea': false,
		}, options);

		get_data();

		function get_data() {
			$(o.result).prepend('<div class="preloader">&nbsp;</div>');
			$.ajax({
				url: o.url,
				data: o.data,
				type: "GET",
				dataType: "json",
                async : false,
				success: function(data) {
					var x = "";
					var y = "";
					var jumlah = 0;
					var kelas = 0;
					$data = !data['data'] ? data : data['data'];

					$.each(data, function(i, n) {
						i++;
						jumlah++;
						kelas++;
						//x +="<tr id='row-"+i+"'>";
						if(o.type['ID'] == 'manual_tam')
						{
							$rowtrid = o.field['ID']+"-"+n['ID'];
						}
						else
						{
							$rowtrid = n['ID'];
						}
						//x += "<tr id='row-" + $rowtrid + "'>";
							if(kelas%2 == 1){
								x +="<tr id='row-" + $rowtrid + "'>";
							}
							else{
								x +="<tr class='even' id='row-" + $rowtrid + "'>";
							}
					
						for (field in o.field) {

							if (o.field[field].substr(0, 2) == "ID") {
								out = n[field];
								out1 = n[o.field[field]];
								name = o.field[field];
								// console.log(name+"bener");
								if (o.type[field] == "number") {
									out = number_format(out);
								}
							} else {
								//alert(n+"-"+o.field[field]);
								out = n[o.field[field]];
								out1 = n[o.field[field]];

								name = field;
								if (o.type[field] == "number") {
                                    console.log(out+'2');
									out = number_format(out);
								}
								//alert(o.link+"link");
								//alert(name+"salah");
							}
							//alert(o.type[field]);
							if (o.type[field] == "hidden") {
								if (o.append == true) {
									x += "<input type='hidden' id='" + name + "" + n['ID'] + "' class='" + name + "' name='" + name + "[" + i + "]' value='" + out1 + "'/>";
								} else {
									x += "<input type='hidden' id='" + name + "" + n['ID'] + "' class='" + name + "' name='" + name + "[" + n['ID'] + "]' value='" + out1 + "'/>";
								}
							} else if (o.type[field] == "hidden_bar") {
								if (o.append == true) {
									x += "<input type='hidden' id='" + name + "" + n['ID'] + "' class='" + name + "' name='" + name + "[" +n['ID']+ "]' value='" + out1 + "'/>";
								} else {
									x += "<input type='hidden' id='" + name + "" + n['ID'] + "' class='" + name + "' name='" + name + "[" + n['ID'] + "]' value='" + out1 + "'/>";
								}
							}
							else if (o.type[field] == "text") {
								if (out1 === undefined) {
									out1 = '';
								}
								if (o.append == true) {
									x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + " require' onKeyup='get_tot(" + n['ID'] + ")' value='" + out1 + "'/></td>";
								} else {
									x += "<td align = 'center' id='col-" + name + "" + n['ID'] + "' ><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + i + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "'/></td>";
								}
							} 
							else if (o.type[field] == "text_true") {
								if (out1 === undefined) {
									out1 = '';
								}
								if (o.append == true) {
									x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + " require' onKeyup='get_tot(" + n['ID'] + ")' value='" + out1 + "'/></td>";
								} else {
									x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + " require' onKeyup='get_tot(" + n['ID'] + ")' value='" + out1 + "'/></td>";
								}
							}else if (o.type[field] == "textcoba") {
								if (out1 === undefined) {
									out1 = '';
								}
								// if(o.append == true){
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "' onKeyup='get_jum(" + n['ID'] + ", this, event)' onclick='get_selisih(" + n['ID'] + ")' /></td>";
								// }else{
								// x +="<td align = 'center'><input style='width:"+o.width[field]+"px' type='text' name='"+name+"["+n['ID']+"]' id='"+name+""+n['ID']+"' class='"+name+"' value='"+out1+"' onKeyup='get_jum("+n['ID']+")' /></td>" ;
								// }
							} 
							//==================
							else if(o.type[field] == "textcurrency"){
								if (out1 === undefined) {
									out1 = '';
								}
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "' onKeyup='set_currency(" + n['ID'] + "); set_number_format("+n['ID']+");'  /></td>";
							}
							else if(o.type[field] == "textfield"){
								if (out1 === undefined) {
									out1 = '';
								}
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "'/></td>";
							}
							else if(o.type[field] == "textnumber"){
								if (out1 === undefined) {
									out1 = '';
								}
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "' onKeyup='set_number_format("+n['ID']+");' /></td>";
							}
							//=============
							else if (o.type[field] == "textkhusus") {
								if (out1 === undefined) {
									out1 = '';
								}
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + "' value='" + out1 + "' onKeyup='roudhah(" + n['ID'] + ")'  /></td>";

							} else if (o.type[field] == "textread") {
								if (out1 === undefined) {
									out1 = '';
								}
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' readonly name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='" + name + " ' value='" + out1 + "'  /></td>";

							} else if (o.type[field] == "texttanggal") {
								if (out1 === undefined) {
									out1 = '';
								}


								// if(o.append == true){
								x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' class='tanggal" + n['ID'] + "' value='" + out1 + "' onKeyup='get_jum(" + n['ID'] + ",this, event)'  /></td>";
								// }else{
								// x +="<td align = 'center'><input style='width:"+o.width[field]+"px' type='text' name='"+name+"["+n['ID']+"]' id='"+name+""+n['ID']+"' class='"+name+"' value='"+out1+"' onKeyup='get_jum("+n['ID']+")' /></td>" ;
								// }
							} else if (o.type[field] == "textbisa") {
								if (out1 === undefined) {
									out1 = '';
								}


								if (o.append == true) {
									x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + n['ID'] + "]' id='" + name + "" + n['ID'] + "' value='" + out1 + "' onKeyup='get_jum(" + n['ID'] + ", this, event)' /></td>";
								} else {
									x += "<td align = 'center'><input style='width:" + o.width[field] + "px' type='text' name='" + name + "[" + i + "]' value='" + out1 + "'/></td>";
								}
							} else if (o.type[field] == "checkbox") {

								if (out == 1) {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" + n['ID'] + "]' value='1' class='" + name + "' checked/></td>";
								} else {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" + n['ID'] + "]' value='1' class='" + name + "'/></td>";
								}
							} else if (o.type[field] == "checkbox_oy") {
                                //console.log(out);
								if (out == 1) {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" +n['ID'] + "]' value='1' class='" + name + "' checked/></td>";
								} else {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" + n['ID']+ "]' value='1' class='" + name + "'/></td>";
								}
							}else if (o.type[field] == "checkbox_oys") {
                                //console.log(out);
								if (out >= 1) {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" +n['ID'] + "]' value='1' class='" + name + "' checked/></td>";
								} else {
									x += "<td align = 'center'><input type='checkbox' name='" + name + "[" + n['ID']+ "]' value='1' class='" + name + "'/></td>";
								}
							}
							else if (o.type[field] == "checkboxQ") {
								if (out == 1) {
									x += "<td align = 'center'><input type='checkbox' onclick='return false' onkeydown='return false' name='" + name + "[" + i + "]' value='1' class='" + name + "' checked/></td>";
								} else {
									x += "<td align = 'center'><input type='checkbox' onclick='return false' onkeydown='return false' name='" + name + "[" + i + "]' value='1' class='" + name + "'/></td>";
								}
							} else if (o.type[field] == "select") {
								if (out1 === undefined) {
									out1 = '';
								}


								if (o.append == true) {
									x += "<input id='" + name + "" + n['ID'] + "' type='hidden' name='" + name + "[" + i + "]' value='" + out1 + "'/>";
								} else {
									x += "<input id='" + name + "" + n['ID'] + "' type='hidden' name='" + name + "[" +n['ID'] + "]' class='" + name + "' value='" + out1 + "'/>";
								}
							} else if (o.type[field] == "manual") {
								//console.log(n[o.field[field]] + "--" + field + "--" + o.field[field]);
								x += "<input type='hidden' id='" + name + n['ID'] + "' class='" + name + "' name='" + name + "[" + i + "]' value='" + o.field[field] + "'/>";
							} else if (o.type[field] == "manual_tam") {
								//console.log(n[o.field[field]] + "--" + field + "--" + o.field[field]);
								x += "<input type='hidden' id='" + name + n['ID'] + "' class='" + name + "' name='" + name + "[" + i + "]' value='" + o.field[field] +"-"+ n['ID']+ "'/>";
							}else {

								if (out1 === undefined) {
									out1 = '';
								}

								if (out === undefined) {
									out = '';
								} else if (out === '') {
									out = '';
								} else if (out === null) {
									out = '';
								}

								if (o.link == name) {
									if (o.append == true) {
										x += "<td><a href='javascript:void(0)' id='edit" + n['ID'] + "' class='edit' rowid='" + i + "'>" + out + "</a></td>";
										x += "<input id='" + name + "" + n['ID'] + "' type='hidden' name='" + name + "[" + i + "]' value='" + out1 + "'/>";
									}
									else
									{
										x += "<td><a href='javascript:void(0)' id='edit" + n['ID'] + "' class='edit' rowid='" + n['ID'] + "'>" + out + "</a></td>";
										x += "<input id='" + name + "" + n['ID'] + "' type='hidden' name='" + name + "[" + n['ID'] + "]' value='" + out1 + "'/>";
									}
								} else {

									x += "<td>" + out + "</td>";
									if (typeof n['ID'] != "undefined") {
										x += "<input type='hidden' id='" + name + "" + n['ID'] + "'  name='" + name + "[" + n['ID'] + "]' value='" + out1 + "'/>";
									}else
									{
										x += "<input type='hidden' id='" + name + "" + n['ID'] + "'  name='" + name + "[" + i + "]' value='" + out1 + "'/>";									
									}
								}
							}
						}
						//alert(o.action_img);
						if (o.action_img == 'all_image') {
							x += "<td align='center'><a href='javascript:void(0)' class='edit' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/edit.gif' /></a></td>" + "<td align='center'><a href='javascript:void(0)' class='del' rowid='" + i + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
						} else if (o.action_img == 'edit_image') {
							x += "<td align='center'><a href='javascript:void(0)' class='edit' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/edit.gif' /></a></td>"+"</tr>";
						}else if (o.action_img == 'no_image') {
							x += "</tr>";
						} else if (o.action_img == 'check_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='checkbox' name='pilih[]' value='" + n['ID'] + "'/></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='checkbox' name='pilih[]' value='" + i + "'/></td>" + "</tr>";
							}
						} else if (o.action_img == 'check_del_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='checkbox' name='pilih[]' value='" + n['ID'] + "'/></td>" + "<td align='center'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='checkbox' name='pilih[]' value='" + i + "'/></td>" + "</tr>";
							}
						} else if (o.action_img == 'text_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='text' name='isi[" + n['ID'] + "]' style='width:50px;' value=''/></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='text' name='isi[" + i + "]' style='width:50px;' value=''/></td>" + "</tr>";
							}
						} else if (o.action_img == 'button_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='button' class='btn' row='" + n['ID'] + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='' style='background-image:  url(" + o.base_url + "ico/caris.png); background-repeat: no-repeat; background-position: 7px 50%;'/></td>" + "<td align='center' id='dd" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='button' class='btn' row='" + i + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='' style='background-image:  url(" + o.base_url + "ico/caris.png); background-repeat: no-repeat; background-position: 7px 50%;'/></td>" + "<td align='center' id='dd" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'button_img2') {
							if (o.append == true) {
								x += "<td align='center'><input type='button' class='btn'  row='" + n['ID'] + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='' style='background-image:  url(" + o.base_url + "ico/caris.png); background-repeat: no-repeat; background-position: 7px 50%;'/>" + "<input id='coret" + n['ID'] + "' name='coret' class='coret' type='button' value='x' row='" + n['ID'] + "'> </td>" + "<td align='center' id='dd" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='button' class='btn' row='" + i + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='' style='background-image: url(" + o.base_url + "ico/caris.png); background-repeat: no-repeat; background-position: 7px 50%;	'/>" + "<input id='coret" + n['ID'] + "' name='coret' class='coret' type='button' value='x' row='" + n['ID'] + "'> </td>" + "<td align='center' id='dd" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'button_only_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='button' class='btn' row='" + n['ID'] + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='.'/></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='button' class='btn' row='" + i + "' id='cari" + n['ID'] + "' name='klik[" + n['ID'] + "]'  value='.'/></td>"  + "</tr>";
							}
						}else if (o.action_img == 'cari_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='button' class='btn' row='" + n['ID'] + "' id='find" + n['ID'] + "' name='klik2[" + n['ID'] + "]'  value='.'/></td>" + "<td align='center'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='button' class='btn' row='" + i + "' id='find" + n['ID'] + "' name='klik2[" + n['ID'] + "]'  value='.'/></td>" + "<td align='center'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'rinci_img') {
							if (o.append == true) {
								x += "<td align='center'><input type='button' class='btn' row='" + n['ID'] + "' id='look" + n['ID'] + "' name='klik2[" + n['ID'] + "]'  value='.'/></td>" + "</tr>";
							} else {
								x += "<td align='center'><input type='button' class='btn' row='" + i + "' id='look" + n['ID'] + "' name='klik2[" + n['ID'] + "]'  value='.'/></td>" + "</tr>";
							}
						} else if (o.action_img == 'carix') {
							if (o.append == true) {
								x += "<td align='center' id='cari" + n['ID'] + "'><a href='javascript:void(0)' class='btn' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/caris.png' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center' id='cari" + n['ID'] + "'><a href='javascript:void(0)' class='btn' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/caris.png' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'del_img') {
							if (o.append == true) {
								x += "<td align='center' id='dl" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center' id='dl" + n['ID'] + "'><a href='javascript:void(0)' class='del' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'dele_img') {
							if (o.append == true) {
								x += "<td align='center' id='de" + n['ID'] + "'><a href='javascript:void(0)' class='dele' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center' id='de" + n['ID'] + "'><a href='javascript:void(0)' class='dele' rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						} else if (o.action_img == 'destroy_img') {
							if (o.append == true) {
								x += "<td align='center'><a href='javascript:void(0)' class='destroy'  rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							} else {
								x += "<td align='center'><a href='javascript:void(0)' class='destroy'  rowid='" + n['ID'] + "'><img src='" + o.base_url + "ico/del.gif' /></a></td>" + "</tr>";
							}
						}


					});
					y += parseInt(jumlah);
					if (o.before) {
						o.before.call(1);
					}
					if (o.append == true) {
						$(o.result).append(x);
					} else {
						$(o.result).html(x);
					}
					crud();
					//console.log(data);
					if (o.done) {
						o.done.call(1, data);
					}
					preloader(o.result, 0);
				}
			});
		}

		function crud() {
			$(".grid").dinatagrid({
				items: o.field,
				width: o.width,
				type: o.type,
				image_dir: o.image_dir,
				action_img: o.action_img,
				link: o.link,
			});
		}

		function IsNumeric(num) {
			return (num >= 0 || num < 0);
		}

	}
});

function preloader(obj, status) {
	if (status == 1) {
        //console.log(obj);
        var position = $(obj).position();
		$(obj).after('<div class="preloader">&nbsp;</div>');
		$(".preloader").css({
			"left": position.left,
			"top": position.top
		});
	} else {
		$(".preloader").remove();
	}
}

function number_format(number, decimals, dec_point, thousands_sep) {
	// Formats a number with grouped thousands

	// *     example 1: number_format('1234,56');
	// *     returns 1: '1.235'
	// *     example 2: number_format(1234.56, 2, ',', ' ');
	// *     returns 2: '1 234,56'
	// *     example 3: number_format(1234.5678, 2, '.', '');
	// *     returns 3: '1234.57'
	// *     example 4: number_format(67, 2, ',', '.');
	// *     returns 4: '67.00'
	// *     example 5: number_format(1000);
	// *     returns 5: '1.000'
	// *     example 6: number_format(67,311, 2);
	// *     returns 6: '67,31'
	// *     example 7: number_format(1000,55, 1);
	// *     returns 7: '1.000,6'
	// *     example 8: number_format(67000, 5, ',', '.');
	// *     returns 8: '67.000,00000'
	// *     example 9: number_format(0,9, 0);
	// *     returns 9: '1'
	// *     example 10: number_format('1,20', 2);
	// *     returns 10: '1,20'
	// *     example 11: number_format('1,20', 4);
	// *     returns 11: '1,2000'
	// *     example 12: number_format('1,2000', 3);
	// *     returns 12: '1,200'
	var n = number,
		prec = decimals;

	var toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return (Math.round(n * k) / k).toString();
	};

	n = !isFinite(+n) ? 0 : +n;
	prec = !isFinite(+prec) ? 0 : Math.abs(prec);
	var sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep;
	var dec = (typeof dec_point === 'undefined') ? ',' : dec_point;

	var s = (prec > 0) ? toFixedFix(n, prec) : toFixedFix(Math.round(n), prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

	var abs = toFixedFix(Math.abs(n), prec);
	var _, i;

	if (abs >= 1000) {
		_ = abs.split(/\D/);
		i = _[0].length % 3 || 3;

		_[0] = s.slice(0, i + (n < 0)) + _[0].slice(i).replace(/(\d{3})/g, sep + '$1');
		s = _.join(dec);
	} else {
		s = s.replace(',', dec);
	}

	var decPos = s.indexOf(dec);
	if (prec >= 1 && decPos !== -1 && (s.length - decPos - 1) < prec) {
		s += new Array(prec - (s.length - decPos - 1)).join(0) + '0';
	} else if (prec >= 1 && decPos === -1) {
		s += dec + new Array(prec).join(0) + '0';
	}
	return s;
}

$(document).ready(function() {
	numeric();
});

function bulan_to_romawi(value)
{
	switch (value) {
		case 1: return 'I';
		case 2: return 'II';
		case 3: return 'III';
		case 4: return 'IV';
		case 5: return 'V';
		case 6: return 'VI';
		case 7: return 'VII';
		case 8: return 'VIII';
		case 9: return 'IX';
		case 10: return 'X';
		case 11: return 'XI';
		case 12: return 'XII';
	}
}
function month_to_num($month)
	{
		switch ($month)
		{
			case  "Januari"		: return "1";
			case  "Februari"	: return "2";
			case  "Maret"		: return "3";
			case  "April"		: return "4";
			case  "Mei"			: return "5";
			case  "Juni"		: return "6";
			case  "Juli"		: return "7";
			case  "Agustus"		: return "8";
			case "September"	: return "9";
			case "Oktober"		: return "10";
			case "November"		: return "11";
			case "Desember"		: return "12";
			
			//default : return FALSE;
		}
	}
function numeric()
{
	$(".numeric").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, and enter
        if (event.keyCode == 116 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 190 || event.keyCode == 110|| event.keyCode == 188) {
                 // let it happen, don't do anything
				return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
}

function currency()
{
	$(".currency").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, and enter
        if (event.keyCode == 116 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39) || event.keyCode == 190 || event.keyCode == 110|| event.keyCode == 188) {
                 // let it happen, don't do anything
				return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }        
    });
}

/*format d-m-Y*/
function strtotime($tgl)
{
    d1 = $tgl.split("-");
    var tgl = new Date(Date.UTC(d1[2],d1[1],d1[0],0,0,0)).getTime();
    return tgl;
}

/* menghitung  selisih hari format d-m-Y */
function selisihTanggal (tgl1, tgl2){
    // varibel miliday sebagai pembagi untuk menghasilkan hari
    var miliday = 24 * 60 * 60 * 1000;
    //buat object Date
    $tanggal1 = tgl1.split('-');
    $tanggal2 = tgl2.split('-');
    tgl1 = $tanggal1[2]+"-"+$tanggal1[1]+"-"+$tanggal1[0];
    tgl2 = $tanggal2[2]+"-"+$tanggal2[1]+"-"+$tanggal2[0];

    var tanggal1 = new Date(tgl1);
    var tanggal2 = new Date(tgl2);
    // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
    var tglPertama = Date.parse(tanggal1);
    var tglKedua = Date.parse(tanggal2);
    var selisih = (tglKedua - tglPertama) / miliday;
    return selisih;
}

var $_src = '';

function readImage(file, index, preview) {

    var reader = new FileReader();
    var image  = new Image();

    reader.readAsDataURL(file);
    reader.onload = function(_file) {
        image.src    = _file.target.result;              // url.createObjectURL(file);
        image.onload = function() {
            var w = this.width,
                h = this.height,
                t = file.type,                           // ext only: // file.type.split('/')[1],
                n = file.name,
                s = ~~(file.size/1024) +'KB';
            $(preview).eq(index).html('<img src="'+ this.src +'">');
        };
        image.onerror= function() {
            alert('Invalid file type: '+ file.type);
        };
    };

}