<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/3  10:53
 */

class TeamCommentModel extends BaseModel {
    protected $_validate = array(
        array('content', '1,512', 'comment too long', 0, 'length')
    );

    /**
     * 创建评论
     */
    public function addComment($data) {
        if (!$this->create($data)) {
            return qc_json_error($this->getError());
        }
        $res = $this->add();

        if ($res) {
            return qc_json_success("发布留言成功");
        }

        return qc_json_error("发布留言失败");
    }

    /**
     * 显示评论
     */
    public function showComment($team_id, $page = 1, $limit = 5) {
        $res = $this
            ->field('tc.team_id, tc.content, tc.id, tc.ctime, tc.user_id, u.username, u.head_path')
            ->table('fg_team_comment tc, fg_user u')
            ->where(array('team_id' => $team_id))
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

    /**
     * 删除评论
     */
    public function deleteComment($data) {
        if ($data['team_id'] && $data['commentid']) {

            $where = array(
                'team_id' => $data['team_id'],
                'id' => $data['commentid']
            );

            $res = $this
                ->where($where)
                ->delete();

            if ($res) {
                return qc_json_success("删除留言成功");
            } else {
                return qc_json_error("删除失败");
            }

        } else {
            return qc_json_error("数据错误");
        }
    }
}
