<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/8/11  10:24
 */
class ShareActHandleController extends BaseController
{
    /**
     * 我的分享的活动列表
     */
    public function shareActListSelf($page = 1, $limit = 10)
    {
        $this->reqLogin();//判断session是否过期
        $userid = session('user')['id'];

        $res = D("ShareActHandle")->shareActListSelf($userid);
        if (!$res['response']) $this->ajaxReturn(qc_json_success(array()));

        if ($res['code'] == 20000) {
            $list = D("ShareAct")->shareActListSelf($res['response'], $page, $limit);

            if ($list['code'] == 20000) {
                $this->ajaxReturn($list);
            }
        }

        $this->ajaxReturn($res);
    }

    /**
     * 添加收藏
     */
    public function shareActCollectAdd()
    {
        $data = $this->reqLogin()->reqPost(array('actid'));
        $userid = session('user')['id'];

        $handle_model = D('ShareActHandle');
        $res = $handle_model->getCollect($userid);
        if (!$res) {
            $collect = $data['actid'];
        } else {
            $collect[] = $res;
            $collect[] = $data['actid'];
            $collect = implode(',', $collect);
        }
        $res = $handle_model->updateCollect($collect, $userid);

        if ($res) {
            if (D('ShareAct')->addCollect($data['actid'])) {
                $this->ajaxReturn(qc_json_success("添加收藏成功"));
            }
        }
        $this->ajaxReturn(qc_json_error("添加收藏失败"));
    }

    /**
     * 删除收藏
     */
    public function shareActCollectDelete()
    {
        $data = $this->reqLogin()->reqPost(array('actid'));
        $userid = session('user')['id'];

        $handle_model = D('ShareActHandle');
        $res = $handle_model->getCollect($userid);
        $collect = explode(',', $res);
        for ($i = 0; $i < count($collect); $i++) {
            if ($collect[$i] == $data['actid']) {
                unset($collect[$i]);
                break;
            }
        }
        $collect = implode(',', array_values($collect));

        $res = $handle_model->updateCollect($collect, $userid);

        if ($res) {
            if (D('ShareAct')->decCollect($data['actid'])) {
                $this->ajaxReturn(qc_json_success("取消收藏成功"));
            }
        }
        $this->ajaxReturn(qc_json_error("取消收藏失败"));
    }

    /**
     * 我收藏的活动列表
     */
    public function shareActListCollectSelf($page = 1, $limit = 10)
    {
        $this->reqLogin();//判断session是否过期
        $userid = session('user')['id'];
        $res = D("ShareActHandle")->shareActListCollectSelf($userid);
        if (!$res['response']) $this->ajaxReturn(qc_json_success(array()));

        if ($res['code'] == 20000) {
            if(strpos($res['response'], ',') !== false)
                $ids = explode(',', $res['response']);
            else
                $ids[] = $res['response'];
            foreach ($ids as $id) {
                $list[] = D("ShareAct")->shareActListSelf($id, $page, $limit)['response'][0];
            }
            $this->ajaxReturn(qc_json_success($list));
        }

        $this->ajaxReturn($res);
    }

    /**
     * 别人的分享的活动列表
     */
    public function shareActListOthers()
    {
        $post_data = $this->reqLogin()->reqPost(array('userid'), array('page', 'limit'));//判断session是否过期
        $userid = $post_data['userid'];

        //判断是否公开
        if (0 == current(D('ShareActHandle')->getActField($userid, array('expose_release')))) {
            $this->ajaxReturn(qc_json_success("该用户已设置为不公开", 20001));
        }

        $res = D("ShareActHandle")->shareActListSelf($userid);
        if (!$res['response']) $this->ajaxReturn(qc_json_success(array()));

        $post_data['page'] = $post_data['page'] ? $post_data['page'] : 1;
        $post_data['limit'] = $post_data['limit'] ? $post_data['limit'] : 5;

        if ($res['code'] == 20000) {
            $list = D("ShareAct")->shareActListSelf($res['response'], $post_data['page'], $post_data['limit']);

            if ($list['code'] == 20000) {
                $this->ajaxReturn($list);
            }
        }

        $this->ajaxReturn($res);
    }

    /**
     * 别人的收藏的活动列表
     */
    public function shareActListCollectOthers()
    {
        $post_data = $this->reqLogin()->reqPost(array('userid'), array('page', 'limit'));//判断session是否过期
        $userid = $post_data['userid'];

        //判断是否公开
        if (0 == current(D('ShareActHandle')->getActField($userid, array('expose_collect')))) {
            $this->ajaxReturn(qc_json_success("该用户已设置为不公开", 20001));
        }

        $res = D("ShareActHandle")->shareActListCollectSelf($userid);
        if (!$res['response']) $this->ajaxReturn(qc_json_success(array()));

        $post_data['page'] = $post_data['page'] ? $post_data['page'] : 1;
        $post_data['limit'] = $post_data['limit'] ? $post_data['limit'] : 5;

        if ($res['code'] == 20000) {
            $list = D("ShareAct")->shareActListSelf($res['response'], $post_data['page'], $post_data['limit']);

            if ($list['code'] == 20000) {
                $this->ajaxReturn($list);
            }
        }

        $this->ajaxReturn($res);
    }

    /* 获取隐私设置信息获取隐私设置信息 */
    public function getPrivateSetting()
    {
        $userid = session('user.id');

        $this->ajaxReturn(qc_json_success(D('ShareActHandle')->getActField($userid, array('expose_release', 'expose_collect'))));
    }

    /* 修改隐私设置 */
    public function updatePrivateSetting()
    {
        $data = $this->reqLogin()->reqPost(array(), array('expose_release', 'expose_collect'));
        $userid = session('user.id');

        if (!is_null($data['expose_release'])) {
            $this->ajaxReturn(
                D('ShareActHandle')->update(
                    $userid,
                    array('expose_release' => $data['expose_release']),
                    'userid'
                ));
        } else {
            $this->ajaxReturn(
                D('ShareActHandle')->update(
                    $userid,
                    array('expose_collect' => $data['expose_collect']),
                    'userid'
                ));
        }
    }
}