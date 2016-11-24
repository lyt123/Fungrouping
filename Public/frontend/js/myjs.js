/*登入加载*/
//获取url？后面的字符串
var url_act=window.location.search;
var data_act=url_act.substring(1);

request(base_url_actdetail,"GET",data_act,function(data){
				var da_re=data.response;
				$("#times").html(getTime(da_re.ctime));
				$("#R_Num").html(da_re.join_num);
				$("#J_Num").html(da_re.join_num);
				$("#contact_phone").html(da_re.phone);
				$("#ids").html(da_re.id);
				$("#createuser").html(da_re.createuser);
				$("#codes").html(data.code);
				$("#nameformat").html(da_re.name_format);
				/*根据内容的长度进行修改*/
				var intro=da_re.intro;
				if(intro.length<155){
					$("#actIntro_con").html(intro);
				}else{
					$("#actIntro_con").html(intro.substring(0,155)+"...<span id='add'>加载全部</span>");
					$("#add").css("color","blue");
					$("#add").on("click",function(){
						$("#actIntro_con").html(intro);
						$("#actIntro_tit").css("height",$("#actIntro_con").height());
					})
				}
				/*加载时间地点，按钮*/
				if(data.code==20000){
					$("#submit").html("响应活动");
					var len=180;
					var dev=6;
					/*
						时间
					 */
					for(var i=1;i<=da_re.time.length;i++){
						if(i==1){
							$("#actTime_con_ul").append("<li class='ul_li ul_li_time'>&nbsp&nbsp&nbsp"+da_re.time[i-1]['votes']+"人&nbsp&nbsp&nbsp"+getTime(da_re.time[i-1]['starttime'])+"&nbsp&nbsp&nbsp<img src='image/2.png' class='btns' value='"+da_re.time[i-1]['id']+"'></li<");
						}else{
							$("#actTime_con_ul").append("<li class='ul_li_top ul_li ul_li_time'>&nbsp&nbsp&nbsp"+da_re.time[i-1]['votes']+"人&nbsp&nbsp&nbsp"+getTime(da_re.time[i-1]['starttime'])+"&nbsp&nbsp&nbsp<img src='image/2.png' class='btns' value='"+da_re.time[i-1]['id']+"'></li>");
						}
					}

					$(".ul_li_time").css("height",len/da_re.time.length-dev*(da_re.time.length-1)/da_re.time.length);
					$(".ul_li_time").css("line-height",len/da_re.time.length-dev*(da_re.time.length-1)/da_re.time.length+"px");
					for(var i=1;i<=da_re.address.length;i++){
						if(i==1){
							$("#actAddress_con_ul").append("<li class='ul_li ul_li_address'>&nbsp&nbsp&nbsp"+da_re.address[i-1]['address']+"&nbsp&nbsp&nbsp<img src='image/2.png' class='btns' value='"+da_re.address[i-1]['id']+"'></li>");
						}else{
							$("#actAddress_con_ul").append("<li class='ul_li_top ul_li ul_li_address'>&nbsp&nbsp&nbsp"+da_re.address[i-1]['address']+"&nbsp&nbsp&nbsp<img src='image/2.png' class='btns' value='"+da_re.address[i-1]['id']+"'></li>");
						}
					}
					$(".ul_li_address").css("height",len/da_re.address.length-dev*(da_re.address.length-1)/da_re.address.length);
					$(".ul_li_address").css("line-height",len/da_re.address.length-dev*(da_re.address.length-1)/da_re.address.length+"px");

					$(".btns").on("click",function(){
						if($(this).attr("src")=="image/2.png"){
							$(this).attr("src","image/1.png");
						}else{
							$(this).attr("src","image/2.png");
						}
					})
				}else if(data.code==20001){
					$("#submit").html("参加活动");
					$("#actTime_con_ul").append("<li class='ul_li'>&nbsp&nbsp&nbsp"+getTime(da_re['starttime'])+"</li>");
					$("#actAddress_con_ul").append("<li class='ul_li'>&nbsp&nbsp&nbsp"+da_re['address']+"</li>");
				}

		})

/*加载二维码*/
$("#QR").click(function(){
				$("#QR_img").html(" ");
				var srcs="text=http://119.29.61.123/Fungrouping/home/act/actdetail/actid/400&size=150";
				$("#QR_img").append("<img id='QRimg' src='"+base_url_createActQRcode+"?"+srcs+"'/>");
		})

/*加载留言*/
request(base_url_ShowComment,"GET",data_act,function(data){
				for(var i=1;i<=data.response.length;i++){
					$("#LeaMess_ul").append("<li class='LeaMessLi'><img class='imgs' src='"+base_url+"/"+data.response[i-1].head_path+"'/>&nbsp&nbsp&nbsp"+data.response[i-1].content+"</li>");
				}
			})
$("#sub").click(function(){
	var bodyheight=document.body.clientHeight+"px";
	$("#master").css("height",bodyheight);
	$("#master").show();
	document.body.style.overflow = "hidden";
})
$("#close").click(function(){
	$("#name_format").val(" ");
	document.body.style.overflow = "auto";
	$("#master").hide();
})
/*提交*/
$("#submit").click(function(){
				var value=0;
				var name_format=$("#nameformat").html();
				var actid=$("#ids").html();
				var ctime_voted=[];
				var caddress_voted=[];
				var name_format=$("#name_format").val();
				if(name_format==""){
					alert("请输入您的名字！");
					return;
				}
				$("#actTime_con_ul img").each(function(){
					if($(this).attr("src")=="image/1.png"){
						ctime_voted.push($(this).attr("value"));
					}
				})
				$("#actAddress_con_ul img").each(function(){
					if($(this).attr("src")=="image/1.png"){
						caddress_voted.push($(this).attr("value"));
					}
				})
				/*响应活动*/
				if($("#codes").html()==20000){
					var time_voted=0;
					var address_voted=0;
					/*判断用户是否选择时间地点*/
					if((ctime_voted.length==0)||(caddress_voted.length==0)){
						alert("请选择时间和地点！");
						return 0;
					}
					for(var i=0;i<ctime_voted.length;i++){
						if(i==0){
							time_voted=ctime_voted[i];
						}else{
							time_voted+="-"+ctime_voted[i];
						}
					}
					for(var i=0;i<caddress_voted.length;i++){
						if(i==0){
							address_voted=caddress_voted[i];
						}else{
							address_voted+="-"+caddress_voted[i];
						}
					}

					request(base_url_responseAct,"POST",{
							'actid':actid,
							'time_voted':time_voted,
							'address_voted':address_voted,
							'name_format':name_format
						},function(data){
							alert(data.response);
							window.location.reload();
							})
				}
				/*参加活动*/
				if($("#codes").html()==20001){
					request(base_url_joinact,"POST",{
							'id':actid,
							'name_format':name_format
						},function(data){
							alert(data.response);
							window.location.reload();
							})
				}
			})

