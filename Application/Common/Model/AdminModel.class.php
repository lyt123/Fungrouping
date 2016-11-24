<?php
/**
 * User: Shelter
 * Date: 2016/8/4
 * Time: 15:04
 */
namespace Common\Model;

class AdminModel extends CURDModel
{

    protected $_validate = array(

        array('account','1,32', 'account too long', 0, 'length'),
        array('password','1,64', 'password too long', 0, 'length'),
    );

    protected $readonlyField = array('id', 'created_at');

    /**
     * Description : 认证
     * Auth : Shelter
     *
     * @param $account
     * @param $password
     * @return array
     */
    public function auth($account, $password)
    {
        $admin = $this->field('id,account,password')
            ->where("account = '%s'", $account)
            ->find();
        if($admin === false) return qc_json_error(L('system_error'));

        if($admin && $admin['password'] === encrypt_password($password)) {
            session('admin.id', $admin['id']);

            $this->where('id = %d', $admin['id'])->save(array(
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip_place' => ip_place(get_client_ip())
            ));
            return qc_json_success(array(
                'admin_id' => $admin['id']
            ));
        }
        else return qc_json_error('login failed');
    }


    /**
     * Description : 注销认证
     * Auth : Shelter
     *
     * @return array
     */
    public function logout()
    {
        session('admin', null);
        return qc_json_success();
    }


    /**
     * Description : 修改admin
     * Auth : Shelter
     *
     * @param $id
     * @param array $data
     */
    public function updateAdmin($id, array $data)
    {
        if($this->create($data)) {

            if($data['password'])
                $this->password = encrypt_password($data['password']);

            $result = $this->where('id = %d', $id)->save();
            if($result === false) return qc_json_error('system_error');

            if($result) return qc_json_success();
            return qc_json_error('nothing_update');
        }
        return qc_json_error($this->getError());
    }


    /**
     * 添加管理员
     */
    public function addAdmin(array $data)
    {
        if($this->create($data)) {

            $this->password = encrypt_password($data['password']);
            $this->created_at = date('Y-m-d H:i:s');

            if($result = $this->add()) return qc_json_success($result);
            return qc_json_error('system error');
        }
        return qc_json_error($this->getError());
    }


    /**
     * 刪除管理員
     */
    public function deleteAdmin($id)
    {
        if($this->where('id = %d',$id)->delete()) return qc_json_success();
        return qc_json_error();
    }


    /**
     * 管理员列表
     */
    public function listAdmin()
    {
        $data = $this->select();
        return qc_json_success($data);
    }


}