<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/3  10:53
 */

class ActCommentModel extends BaseModel {
    protected $_validate = array(
        array("content",'1,256','留言内容太长',self::EXISTS_VALIDATE,'length'),
        array("actid",'','活动id不能为空',self::EXISTS_VALIDATE,'notequal'),
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
    public function showComment($actid, $page = 1, $limit = 5) {
        $res = $this
            ->field('ac.actid, ac.content, ac.id, ac.ctime, ac.userid, u.username, u.head_path')
            ->table('fg_act_comment ac, fg_user u')
            ->where(array('actid' => $actid))
            ->where('ac.userid = u.id')
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
        if ($data['actid'] && $data['commentid']) {

            $where = array(
                'actid' => $data['actid'],
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
