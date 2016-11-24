<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/7/23  21:09
 */
class UserModel extends CURDModel {
    protected $_validate = array(
        //noteequal验证的值与第二个参数对应
        //self::EXISTS_VALIDATE存在字段就验证
        array("username",'','用户名必须唯一',self::EXISTS_VALIDATE,'unique'),
        array("username",'',"用户名不能为空",self::EXISTS_VALIDATE,'notequal'),
        array("phone",'',"手机号码必须唯一",self::EXISTS_VALIDATE,'unique'),
        array("phone",'',"手机号码不能为空",self::EXISTS_VALIDATE,'notequal'),
        array("code",'',"验证码不能为空",self::EXISTS_VALIDATE,'notequal'),
        array("password",'',"密码不能为空",self::EXISTS_VALIDATE,'notequal'),
        array("sex",'checkSex','性别不仔细',self::EXISTS_VALIDATE,'callback'),
    );

    public function checkSex($sex) {
        if($sex == 'f' || $sex == 'm'){
            return true;
        }
        return false;
    }

    /**
     * 注册用户
     */
    public function adduser($data) {

        $data["password"] = md5($data["password"]);
        if($this->create($data)) {
            $res = $this->add();
            if($res) {
                return qc_json_success();
            }else{
                return qc_json_error("添加错误");
            }
        }
        return qc_json_error($this->getError());
    }

    /**
     * 用户登录检查
     */
    public function chklogin($phone,$password){
        $where = array(
            'phone'=>$phone,
            'password'=>$password
        );
        $res=$this
            ->where($where)
            ->find();

        if ($res) {
            //session不存入密码这属性
            unset($res['password']);
            //将登录成功会员数据存在session中
            session(array('expire' => 1800));
            session('user', $res);
            return qc_json_success($res);
        } else {
            return qc_json_error("账号不存在或密码错误");
        }
    }

    /**
     * 获取用户名
     */
    public function getUserInfo($userid, array $field) {
        $info = $this
            ->field($field)
            ->where(array('id'=>$userid))
            ->select();

        return $info;
    }
    
    /**
     * 上传或更新头像
     */
    public function uploadAvatar(array $path, $user_id) {

        if($this->create($path)) {

            $status = $this->where('id = %d', $user_id)->save();
            if(!is_int($status))  return qc_json_error('修改失败！');
            return qc_json_success($path[key($path)]);
        }
        return qc_json_error($this->getError());
    }

    /**
     * 修改密码
     */
    public function updatePassword($userid, $prepassword, $newpassword) {
        $res = $this->chkpassword($userid, $prepassword);

        if ($res) {
            if ($this->create(array('id' => $userid, 'password' => $newpassword))) {
                if ($this->save()) {
                    return qc_json_success("修改密码成功");
                }
            }

            return qc_json_error("更新失败");
        }

        return qc_json_error("原密码错误");
    }

    /**
     * 检查密码
     */
    public function chkpassword($userid, $password) {
        $map = array(
            'id' => $userid,
            'password' => $password
        );

        $res = $this
            ->where($map)
            ->find();

        return $res;
    }

    /**
     * 检测手机号是否注册
     */
    public function checkPhoneExist($phone) {
        $res = $this
            ->where(array('phone'=>$phone))
            ->find();

        return $res;
    }

    /**
     * 忘记密码-修改为新密码
     */
    public function forgetPasswordNewPassword(array $data) {
        $res = $this
            ->where(array('phone' => $data['phone']))
            ->data(array('password' => $data['password']))
            ->save();

        if ($res) {
            return qc_json_success("密码重置成功");
        }

        return qc_json_error("密码重置失败");
    }

    /**
     * 修改绑定手机
     */
    public function updatePhone($phone, $userid) {
        $res = $this
            ->where(array('id' => $userid))
            ->data(array('phone' => $phone))
            ->save();
        return $res;
    }
}