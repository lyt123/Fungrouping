<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/8/3  10:38
 */
class ActCommentController extends BaseController {
    /**
     * 添加评论
     */
    public function addComment() {
        $postdata = array("actid", "content");
        $this->reqLogin()->reqPost($postdata);
        $data = I("post.");
        $data["ctime"]=time();

        $data['userid'] = session("user")['id'];

        $res = D("ActComment")->addComment($data);

        $this->ajaxReturn($res);
    }

    /**
     * 显示评论
     */
    public function ShowComment($actid, $page = 1, $limit = 5) {
        $res = D("ActComment")->showComment($actid, $page, $limit);

        $this->ajaxReturn($res);
    }

    /*
     * 删除评论
     */
    public function deleteComment() {
        $postdata = array("actid", "commentid");
        $this->reqLogin()->reqPost($postdata);
        $data = I("post.");

        $res = D("ActComment")->deleteComment($data);
        $this->ajaxReturn($res);
    }
}
