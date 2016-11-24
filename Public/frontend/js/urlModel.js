//var base_url="http://119.29.61.123/Fungrouping";
var base_url="http://119.29.77.37/Fungrouping";
var base_url_login=base_url+"/Home/User/login";//登录
var base_url_logout=base_url+"/Home/User/logout";//退出登录
var base_url_sendMsg=base_url+"/Home/User/sendMsg";//注册获取验证码
var base_url_adduser=base_url+"/Home/User/adduser";//用户注册
var base_url_uploadAvatar=base_url+"/Home/User/uploadAvatar";//用户上传或更新头像
var base_url_updatePassword=base_url+"/Home/User/updatePassword";//修改密码
//找回密码
var base_url_forgetPasswordSendMsg=base_url+"/Home/User/forgetPasswordSendMsg";//发送验证码
var base_url_forgetPasswordCheckCode=base_url+"/Home/User/forgetPasswordCheckCode";//检测验证码
var base_url_forgetPasswordNewPassword=base_url+"/Home/User/forgetPasswordNewPassword";//修改密码
//更换绑定手机号码
var base_url_updatePhoneSendMsg=base_url+"/Home/User/updatePhoneSendMsg";//向旧号码发送短信验证码
var base_url_updatePhoneCheckCode=base_url+"/Home/User/updatePhoneCheckCode";//验证旧号码的验证码是否正确
var base_url_updatePhoneSendMsg=base_url+"/Home/User/updatePhoneSendMsg";//向新手机发送短信验证码
var base_url_updatePhone=base_url+"/Home/User/updatePhone";//更换绑定手机

var base_url_addact=base_url+"/home/act/addact";//发布活动
var base_url_myact=base_url+"/home/act/myact";//我发起的
var base_url_inviteact=base_url+"/home/act/inviteact";//邀请我的
var base_url_actdetail=base_url+"/home/act/actdetail";//显示具体活动信息
var base_url_timeVoteDetail=base_url+"/home/act/timeVoteDetail";//活动时间投票具体情况(响应情况)
var base_url_joinDetail=base_url+"/home/act/joinDetail";//活动参加情况
var base_url_createActQRcode=base_url+"/home/act/createActQRcode";//活动二维码 
var base_url_geteditact=base_url+"/home/act/geteditact";//待编辑活动
var base_url_editact=base_url+"/home/act/editact";//编辑活动
var base_url_responseAct=base_url+"/home/act/responseAct";//响应活动
var base_url_reResponseAct=base_url+"/home/act/reResponseAct";//再次响应活动
var base_url_joinact=base_url+"/home/act/joinact";//参加活动
var base_url_createUserResponseAct=base_url+"/home/act/createUserResponseAct";//活动发布人选中时间地点
var base_url_createrRejectJoin=base_url+"/home/act/createrRejectJoin";//活动发布人删除参与者
var base_url_deleteact=base_url+"/home/act/deleteact";//删除活动

var base_url_ShowComment=base_url+"/home/actComment/ShowComment";//显示留言

var base_url_teamDetail=base_url+"/home/team/teamDetail";//显示活动具体信息-交友活动
var base_url_joinTeamNotuser=base_url+"/home/team/joinTeamNotUser";//未注册用户参与活动