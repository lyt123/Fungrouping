<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/8/15  20:53
 */
class ShareactCommentController extends BaseController
{
    public function addComment()
    {
        $data = $this->reqLogin()->reqPost(
            array('actid', 'content'),
            array('parent_id', 'reply_id')
        );

        $data['content'] = filterWords($data['content']);
        $data['userid']  = session('user')['id'];
        $data['ctime']   = NOW_TIME;

        $share_act_model = D('ShareAct');
        $share_act_model ->startTrans();

        $res = $share_act_model->addComment($data['actid']);
        if ($res) {
            $res = D("ShareactComment")->addComment($data);
        }

        //添加评论提醒
        $noticeData = array();
        if ($res) {
            if (!$data['parent_id']) {
                $noticeData['commentid'] = $res;
                $noticeData['ctime']     = NOW_TIME;
                $noticeData['to_userid'] = D('ShareAct')->getUserId($data['actid'])[0]['userid'];
            } elseif ($data['parent_id'] && !$data['reply_id']) {
                $noticeData['commentid'] = $res;
                $noticeData['ctime']     = NOW_TIME;
                $noticeData['to_userid'] = D('ShareactComment')->getUserId($data['parent_id'])[0]['userid'];
            } else {
                //第三种情况（$data['parent_id'] && $data['reply_id']）
                $noticeData['commentid'] = $res;
                $noticeData['ctime']     = NOW_TIME;
                $noticeData['to_userid'] = D('ShareactComment')->getUserId($data['reply_id'])[0]['userid'];
            }
        }

        if (D("ShareactCommentNotice")->addNotice($noticeData)) {
            $share_act_model->commit();
            $this->ajaxReturn(qc_json_success("添加评论成功"));
        }

        $share_act_model->rollback();
        $share_act_model->commit();
        $this->ajaxReturn(qc_json_error("添加评论失败"));
    }

    public function showComment($actid, $page = 1, $limit = 10) {
        $this->reqLogin();

        $shareact_comment_model = D('ShareactComment');
        $comment_fir = $shareact_comment_model->getCommentFir($actid, $page, $limit);

        if (!$comment_fir) $this->ajaxReturn(qc_json_error("暂无评论"));

        $act_createuser_id = D('ShareAct')->getUserId($actid)[0]['userid'];

        $userid = session('user')['id'];
        if ($act_createuser_id == $userid)
            $is_act_creater = 1;
        else
            $is_act_creater = 0;

        foreach ($comment_fir as &$value) {
            $value['is_creater'] = ($value['userid'] == $userid || $is_act_creater) ? 1 : 0;
            $comment_sec = $shareact_comment_model->getcommentSec($actid, $value['id']);
            if ($comment_sec) {
                foreach ($comment_sec as &$item) {
                    $item['is_creater'] = ($item['userid'] == $userid || $is_act_creater) ? 1 : 0;
                }
                $value['comment_sec'] = $comment_sec;
            } else $value['comment_sec'] = array();
        }
        unset($value);

        $this->ajaxReturn(qc_json_success($comment_fir));
    }

    public function deleteComment() {
        $data = $this->reqLogin()->reqPost(array('commentid'));

        $this->ajaxReturn(D('ShareactComment')->deleteComment($data['commentid']));
    }
}