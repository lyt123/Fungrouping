<?php
/**
 * User: lyt123
 * Date: 2016/9/13  17:09
 */
namespace  Home\Controller;

use Common\Controller\BaseController;

class AdminController extends BaseController
{
    /**
     * 添加管理员
     */
    public function addAdmin()
    {
        $data = $this->reqLogin()->reqPost(array(
            'account', 'password'
        ));

        $this->ajaxReturn(D('Admin')->addAdmin($data));
    }

    /**
     * 登录
     */
    public function login()
    {
        $data = $this->reqPost(array(
            'account', 'password'
        ));

        $this->ajaxReturn(D('Admin')->auth($data['account'], $data['password']));
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->ajaxReturn(D('Admin')->logout());
    }

    /**
     * 更新管理员信息
     */
    public function updateAdmin()
    {
        $data = $this->reqLogin()->reqPost(array('id'), array(
            'account', 'password'
        ));

        $this->ajaxReturn(D('Admin')->updateAdmin($data['id'], $data));//只传$data如何
    }

    /**
     * 删除管理员
     */
    public function deleteAdmin()
    {
        $data = $this->reqLogin()->reqPost(array('id'));

        $this->ajaxReturn(D('Admin')->deleteAdmin($data['id']));
    }

    /**
     * 打印管理员
     *
     */
    public function listAdmin()
    {
        $this->reqLogin();

        $this->ajaxReturn(D('Admin')->listAdmin());
    }
}
