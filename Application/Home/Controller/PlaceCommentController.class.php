<?php
/**
 * User: lyt123
 * Date: 2016/9/13  19:14
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class PlaceCommentController extends BaseController
{
    /**
     * 添加评论
     */
    public function addComment()
    {
        $data = $this->reqLogin()->reqPost(array("place_id", "content"));
        $data["ctime"] = date('Y-m-d H:i:s');
        $data['user_id'] = session("user.id");

        $this->ajaxReturn(D("PlaceComment")->addOne($data));
    }

    /**
     * 显示评论
     */
    public function ShowComment()
    {
        $data = $this->reqLogin()->reqPost(array('id'), array('page', 'limit'));

        $res = D("PlaceComment")->showComment(
            $data['id'], $data['$page'], $data['limit']
        );

        $this->ajaxReturn($res);
    }
}
