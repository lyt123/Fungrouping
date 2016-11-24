<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/8/16  15:55
 */
class ShareactCommentNoticeController extends BaseController
{
    public function checkNewNotice()
    {
        $this->reqLogin();
        $userid = session('user')['id'];
        $res = D("ShareactCommentNotice")->getNewNotice($userid);
        if ($res)
            $this->ajaxReturn(qc_json_success(count($res)));
        $this->ajaxReturn(qc_json_error("暂无新消息"));
    }

    public function getNewNotice()
    {
        $this->reqLogin();
        $userid = session('user')['id'];
        $comment_model = D('ShareactCommentNotice');
        $comment_model ->startTrans();
        $res = $comment_model->getNewNotice($userid);

        $new_notice = array();
        foreach ($res as $value) {
            $commentInfo = D('ShareactComment')->getCommentInfo($value['commentid'])[0];
            if (!$commentInfo['parent_id']) {

                $data['status']          = 1;
                $data['createuser_id']   = $userid;
                $data['createuser_name'] = session('user')['username'];
                $data['actid']           = $commentInfo['actid'];
                $data['act_title']       = D('ShareAct')->getActInfo(
                    $data['actid'],
                    array('title')
                )[0]['title'];
                $data['comment_userid'] = $commentInfo['userid'];
                $data['comment_content']        = $commentInfo['content'];
                $data['comment_ctime']          = $commentInfo['ctime'];

                $user_info = D('User')  ->getUserInfo(
                    $data['comment_userid'],
                    array('username', 'head_path')
                )[0];
                $data['comment_username']       = $user_info['username'];
                $data['comment_user_head_path'] = $user_info['head_path'];

            } elseif ($commentInfo['parent_id'] && !$commentInfo['reply_id']) {

                $data['status'] = 2;
                $data['actid']  = $commentInfo['actid'];

                $act_info = D('ShareAct')->getActInfo(
                    $data['actid'], array('userid', 'title')
                )[0];
                $data['createuser_id'] = $act_info['userid'];
                $data['act_title']     = $act_info['title'];

                $data['createuser_name'] = D('User')->getUserInfo(
                    $data['createuser_id'], array('username')
                )[0]['username'];
                $data['replyuser_id'] = $commentInfo['userid'];

                $replyuser_info = D('User')->getUserInfo(
                    $data['replyuser_id'], array('username', 'head_path')
                )[0];
                $data['replyuser_name']      = $replyuser_info['username'];
                $data['replyuser_head_path'] = $replyuser_info['head_path'];
                $data['reply_content']       = $commentInfo['content'];
                $data['reply_ctime']         = $commentInfo['ctime'];

                $mycomment_info = D('ShareactComment')
                    ->getCommentInfo($commentInfo['parent_id'])[0];
                $data['mycommentcontent'] = $mycomment_info['content'];
                $data['myusername']       = session('user')['username'];
                $data['myuserid']         = session('user')['id'];

            } else {

                //第三种情况（$commentInfo['parent_id'] && $commentInfo['reply_id']）
                $data['status'] = 3;
                $data['actid']  = $commentInfo['actid'];

                $act_info = D('ShareAct')->getActInfo(
                    $data['actid'], array('userid', 'title')
                )[0];
                $data['createuser_id']   = $act_info['userid'];
                $data['act_title']       = $act_info['title'];
                $data['createuser_name'] = D('User')->getUserInfo(
                    $data['createuser_id'], array('username')
                )[0]['username'];
                $data['replyuser_id']    = $commentInfo['userid'];

                $replyuser_info = D('User')->getUserInfo(
                    $data['replyuser_id'], array('username', 'head_path')
                )[0];
                $data['replyuser_name']      = $replyuser_info['username'];
                $data['replyuser_head_path'] = $replyuser_info['head_path'];
                $data['reply_content']       = $commentInfo['content'];
                $data['reply_ctime']         = $commentInfo['ctime'];

                $data['comment'] = D('ShareactComment')
                    ->getCommentInfo($commentInfo['parent_id'])[0];

                $data['comment']['username'] =
                    D('User')->getUserInfo(
                        $data['comment']['userid'], array('username')
                )[0]['username'];

                $data['comment']['comment_sec'] =
                    D('ShareactComment')->getCommentSec(
                        $data['actid'], $data['comment']['id']
                    );

                for ($i = 0, $len = count($data['comment']['comment_sec']); $i < $len; $i++) {
                    if ($data['comment']['comment_sec'][$i]['id'] == $value['commentid']) {
                        unset($data['comment']['comment_sec'][$i]);
                        break;
                    }
                }
                $data['comment']['comment_sec'] = array_values($data['comment']['comment_sec']);
            }
            $new_notice[] = $data;
            unset($data);
        }
        if ($new_notice) {
            $comment_model->finishNewNotice($userid);
            $comment_model->commit();
            $this->ajaxReturn(qc_json_success($new_notice));
        }
        $comment_model->rollback();
        $comment_model->commit();
        $this->ajaxReturn(qc_json_error("暂无新消息"));
    }
}
