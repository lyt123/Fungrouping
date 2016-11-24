<?php
namespace Home\Controller;

/**
 * User: lyt123
 * Date: 2016/7/23  20:57
 */

use Common\Controller\BaseController;

class UserController extends BaseController {

    /**
     * 注册获取验证码
     */
    public function sendMsg()
    {
        $data = $this->reqPost(array('phone'));
        $this->ajaxReturn(sendMsg($data['phone']));
    }

    /**
     * 注册用户
     */
    public function adduser() {

        $postdata=array('phone','code','username','password','sex');
        $this->reqPost($postdata);
        $data=I("post.");

        $usermodel = D("User");
        if(test_code($data['code']))
            $this->ajaxReturn($usermodel->adduser($data));
        else
            $this->ajaxReturn(qc_json_error('验证码失效或者错误！'));
    }

    /**
     * 用户登录
     */
    public function login() {

        $postdata = array("phone","password");
        $this->reqPost($postdata);
        $phone = I("post.phone");
        $password = md5(I("post.password"));

        $usermodel = D("User");
        $res = $usermodel->chklogin($phone, $password);

        $this->ajaxReturn($res);
    }

    /**
     * 退出登录
     */
    public function logout() {

        if(session('?user')){
            session('user', null);
            $this->ajaxReturn(qc_json_success('退出登录成功！'));
        }
        $this->ajaxReturn(qc_json_error());
    }

    /**
     * 上传或更新头像
     */
    public function uploadAvatar() {
        $this->reqLogin();

        $user = session("user");
        $userid = $user['id'];

        $status = $this->uploadPictures($userid, 'user_head');

        if (!is_array($status)) {
            $this->ajaxReturn(qc_json_error($status));
        }

        $path = 'Public/'.$status['savepath'].$status['savename'];

        $res = D('User')->uploadAvatar(array('head_path'=>$path), $userid);
        $this->ajaxReturn($res);
    }

    /**
     * 修改密码
     */
    public function updatePassword(){
        $this->reqlogin()->reqPost(array('prepassword','newpassword'));

        $prepassword = md5(I("post.prepassword"));
        $newpassword = md5(I("post.newpassword"));

        $user = session("user");
        $userid = $user["id"];

        $usermodel = D("User");
        $res = $usermodel->updatePassword($userid,$prepassword,$newpassword);

        $this->ajaxReturn($res);
    }

    /**
     * 忘记密码-发送验证码
     */
    public function forgetPasswordSendMsg() {
        $this->reqPost(array('phone'));
        $phone = I('phone');

        $res = D('User')->checkPhoneExist($phone);
        if (!$res) {
            $this->ajaxReturn(qc_json_error("手机号未注册"));
        }

        $res = sendMsg($phone);

        if ($res['code'] == 20000) {
            session('forget_password_sendMsg_phone', $phone);
        }

        $this->ajaxReturn($res);
    }

    /**
     * 忘记密码-检测验证码
     */
    public function forgetPasswordCheckCode() {
        $this->reqPost(array('code'));
        $res = test_code(I('code'));

        if ($res) {
            //验证码正确,记录进session
            session('forget_code_success', true);
            $this->ajaxReturn(qc_json_success("验证码正确"));
        }

        $this->ajaxReturn(qc_json_error("验证码错误"));
    }

    /**
     * 忘记密码-新密码
     */
    public function forgetPasswordNewPassword() {
        $this->reqPost(array('password'));
        $data['password'] = md5(I("post.password"));
        $data['phone'] = session('forget_password_sendMsg_phone');

        if (session('?forget_password_sendMsg_phone') && session('?forget_code_success')) {
            $res = D("User")->forgetPasswordNewPassword($data);
            $this->ajaxReturn($res);
        }

        $this->ajaxReturn(qc_json_error('请先验证短信验证码！'));
    }

    /**
     * 修改绑定手机-给旧(新)号码发短信验证码
     */
    public function updatePhoneSendMsg() {
        $this->reqLogin();

        if (I('post.phone')) {
            $phone = $this->reqPost(array('phone'))['phone'];
            $exist = D('User')->checkPhoneExist($phone);

            if ($exist) {
                $this->ajaxReturn(qc_json_error("手机号已注册"));
            }
        } else {
            $phone = session("user")["phone"];
        }

        $res = sendMsg($phone);

        if ($res['code'] == 20000) {
            session('update_phone_new_phone', $phone);
        }
        $this->ajaxReturn($res);
    }

    /**
     * 修改绑定手机-验证短信验证码和登录密码
     */
    public function updatePhoneCheckCode() {
        $post_data = $this->reqLogin()->reqPost(array('code', 'password'));
        $user = session("user");
        $phone = $user["phone"];

        if (test_code($post_data['code'])) {
            $res = D('User')->chklogin($phone, md5($post_data['password']));
            if ($res['code'] == 20000) {
                session('phone_code_success', true);
                $this->ajaxReturn(qc_json_success("验证码和密码正确"));
            }
            $this->ajaxReturn(qc_json_error("密码错误"));
        }
        $this->ajaxReturn(qc_json_error("验证码错误"));
    }

    /**
     * 修改绑定手机-修改密码
     */
    public function updatePhone() {
        $post_data = $this->reqLogin()->reqPost(array('code'));

        if (session('?phone_code_success')) {
            if (test_code($post_data['code'])) {
                $userid = session("user")["id"];
                $res = D('User')->updatePhone(session('update_phone_new_phone'),$userid);
                if ($res) {
                    session('phone_code_success', null);
                    session('security_code', null);
                    session('send_time', null);
                    $this->ajaxReturn(qc_json_success("新号码绑定成功！下次登陆请使用新号码登陆。"));
                }
            }
            $this->ajaxReturn(qc_json_error('验证码失效或者错误！'));
        }
        $this->ajaxReturn(qc_json_error('请先给旧号码发送验证短信！'));

    }

    /**
     * 别人的个人信息
     */
    public function OtherUserInfo() {
        $data = $this->reqLogin()->reqPost(array('userid'));
        $this->ajaxReturn(qc_json_success(D('User')->getUserInfo($data['userid'], array('username', 'head_path', 'sex'))));
    }
}
