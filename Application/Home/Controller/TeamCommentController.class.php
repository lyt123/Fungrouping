<?php
/**
 * User: lyt123
 * Date: 2016/8/3  10:38
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class TeamCommentController extends BaseController
{
    public function __construct()
    {
        $this->reqLogin();
        parent::__construct();
    }

    /**
     * 添加评论
     */
    public function addComment()
    {
        $postdata = array("team_id", "content");
        $this->reqPost($postdata);
        $data = I("post.");
        $data["ctime"] = time();
        $data['user_id'] = session("user")['id'];

        $res = D("TeamComment")->addComment($data);

        $this->ajaxReturn($res);
    }

    /**
     * 显示评论
     */
    public function ShowComment()
    {
        $data = $this->reqPost(array('team_id'), array('page', 'limit'));
        $res = D("TeamComment")->showComment(
            $data['team_id'], $data['$page'], $data['limit']
        );

        $this->ajaxReturn($res);
    }

    /*
     * 删除评论
     */
    public function deleteComment()
    {
        $data = $this->reqPost(array('team_id', 'commentid'));

        $res = D("TeamComment")->deleteComment($data);
        $this->ajaxReturn($res);
    }
}