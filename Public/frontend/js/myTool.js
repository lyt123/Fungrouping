function request(url,type,data,suc){
	$.ajax({
		url:url,
		type:type,
		data:data,
		datatype:"json",
		xhrFields:{
			withCredentials:true
		},
		success:suc
	});
}

function getTime(nS){
    var time=new Date(parseInt(nS) * 1000);
    var Y=time.getFullYear();
    var M=time.getMonth();
    var D=time.getDate();
    var h=time.getHours();
    var m=time.getMinutes();
    if(M<10){
    	M="0"+M;
    }
    if(D<10){
    	D="0"+D;
    }
    if(h<10){
    	h="0"+h;
    }
    if(m<10){
    	m="0"+m;
    }
    var times=Y+"-"+M+"-"+D+" "+h+":"+m;
    return times;
}