/**
 * 封装GET POST的请求
 */

if ("undefined" == typeof $) throw new Error("Fungrouping Client's JavaScript requires jQuery");

var fg = {
    base_url : "http://localhost/Fungrouping/",
    debug : true
};

fg.log = function(msg){
    if(this.debug)
        console.log(msg);
};

fg.post = function(url, data, success_handler, error_handler){
    url = fg.base_url + url;
    $.ajax({
        type : "POST",
        async : false,
        url : url,
        data : data,
        dataType : "json",
        cache : false,
        //开启携带认证信息，默认为关闭
        xhrFields: {
            withCredentials: true
        },
        success : function(resJson){
            fg.log(resJson);
            if(resJson.code == 20000)
                success_handler? success_handler(resJson.response) : '';
            else if(resJson.code == 40000) {
                fg.log(resJson);
                error_handler? error_handler(resJson.response) : '';
            }
        },
        error: function(jqXHR, textStatus, error){
            td.log('发生错误：' + jqXHR.status + ':'+ jqXHR.readyState +': ' + textStatus + ': ' +error);
            alert("网络发生错误了");
            return false;
        }
    });
};

fg.get = function(url, data, success_handler, error_handler){
    url = fg.base_url + url;

    if(data && typeof data === 'object') {
        for(var key in data) {
            if(data[key])
                url += '/' + key + '/' + data[key];
        }
    }

    $.ajax({
        type : "GET",
        url : url,
        dataType : "json",
        async : false,
        cache : false,
        success : function(resJson){
            fg.log(resJson);
            if(resJson.code == 20000) {
                success_handler? success_handler(resJson.response) : '';
                return false;
            }
            else if(resJson.code == 40000) {
                error_handler? error_handler(resJson.response) : '';
            }
        },
        error : function(jqXHR, textStatus, error){
            fg.log('发生错误：' + textStatus + error);
            return false;
        }
    });
};
