<?php
/**
 * User: lyt123
 * Date: 2016/9/28  22:02
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class UserIntroController extends BaseController
{
    /**
     * 获取个人详细信息
     */
    public function getUserIntro() {
        $data = $this->reqLogin()->reqPost(array('userid'));
        $this->ajaxReturn(qc_json_success(D('UserIntro')->getData(array('user_id' => $data['userid']))));
    }

    /**
     * 编辑我的个人信息
     */
    public function editSelfIntro() {
        $data = $this->reqLogin()->reqPost(array('userid'), array('birth', 'profession', 'constellation', 'blood_group', 'self_intro', 'resident'));

        $this->ajaxReturn(D('UserIntro')->update($data['userid'], $data, 'userid'));
    }
}