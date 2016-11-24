<?php
/**
 * User: lyt123
 * Date: 2016/9/13  19:16
 */
namespace Common\Model;

class PlaceCommentModel extends CURDModel
{
    protected $_validate = array(
        array('content', '1,512', 'comment too long', 0, 'length')
    );

    /**
     * 显示评论
     */
    public function showComment($team_id, $page = 1, $limit = 5) {
        $res = $this
            ->field('tc.place_id, tc.content, tc.id, tc.ctime, tc.user_id, u.username, u.head_path')
            ->table('fg_place_comment tc, fg_user u')
            ->where(array('place_id' => $team_id))
            ->where('tc.user_id = u.id')
            ->limit(($page-1)*$limit, $limit)
            ->order("id desc")
            ->select();

        $login_user = session('user')['id'];
        foreach ($res as &$value) {
            if ($value['userid'] == $login_user)
                $value['is_createuser'] = 1;
            else
                $value['is_createuser'] = 0;
            unset($value['userid']);
        }
        unset($value);

        if (empty($res)) {
            $res = array();
        }

        return qc_json_success($res);
    }
}