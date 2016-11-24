function setCookie(NameOfCookie,value,expiredays){
	//把天数转换为合法的日期
	var ExpireDate=new Date();
	ExpireDate.setTime(ExpireDate.getTime()+(expiredays*24*3600*1000));
	//日期通过toGMTstring()函数转换成GMT 时间
	/*path="/";
	domain="119.29.61.123";*/
	document.cookie=NameOfCookie+"="+escape(value)+((expiredays==null)?"":";expires="+ExpireDate.toGMTString());
}

function getCookie(NameOfCookie){
	if(document.cookie.length>0){
		begin=document.cookie.indexOf(NameOfCookie+"=");
		if(begin!=-1){
			//cookie值得初始位置
			begin+=NameOfCookie.length+1;
			//结束位置
			end=document.cookie.indexOf(";",begin);
			if(end==-1){
				//如果没，则end为字符串结束位置
				end=document.cookie.length;
			}
			return unescape(document.cookie.substring(begin,end));
		}
		return null;
	}
}
