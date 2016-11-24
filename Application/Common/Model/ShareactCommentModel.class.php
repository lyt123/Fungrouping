<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/15  21:13
 */
class ShareactCommentModel extends BaseModel {
    public function addComment($data) {
        if ($this->create($data)) {
            if ($res = $this->add()) {
                return $res;
            }
        }
        return null;
    }

    /**
     * 获取一级评论
     */
    public function getCommentFir($actid, $page, $limit) {
        $map = array(
            "sc.actid" => $actid,
            "sc.parent_id" => 0,
        );
        $res = $this
            ->field('u.username, u.head_path, sc.*')
            ->table("fg_user u, fg_shareact_comment sc")
            ->where('sc.userid = u.id')
            ->where($map)
            ->order('sc.id asc')
            ->limit(($page-1)*$limit, $limit)
            ->select();
        return $res;
    }
    /**
     * 获取二级评论及回复
     */
    public function getCommentSec($actid, $parent_id) {
        $map = array(
            'sc.actid' => $actid,
            'sc.parent_id' => $parent_id
        );
        $res = $this
            ->field('u.username, u.head_path, sc.*')
            ->table("fg_user u, fg_shareact_comment sc")
            ->where('sc.userid = u.id')
            ->where($map)
            ->order('sc.id asc')
            ->select();
        return $res;
    }

    public function deleteComment($commentid) {
        $parent_id_exist = $this
            ->field(array('parent_id'))
            ->where(array('id' => $commentid))
            ->select();
        $this->startTrans();
        if (!$parent_id_exist[0]['parent_id']) {
            $parent_id = $this
                ->field('id')
                ->where(array('parent_id' => $commentid))
                ->select();
            foreach ($parent_id as $value) {
                $res = $this->delete($value['id']);
                if (!$res) {
                    $this->rollback();
                    $this->commit();
                    return qc_json_error("删除失败");
                }
            }
        }
        $res = $this->delete($commentid);
        if (!$res) {
            $this->rollback();
            $this->commit();
            return qc_json_error("删除失败");
        }
        $this->commit();
        return qc_json_success("删除成功");
    }

    public function getCommentInfo($commentid) {
        $res = $this
            ->where(array('id' => $commentid))
            ->select();
        return $res;
    }

    public function getUserId($parent_id) {
        $res = $this
            ->field('userid')
            ->where(array('id' => $parent_id))
            ->select();
        return $res;
    }
}