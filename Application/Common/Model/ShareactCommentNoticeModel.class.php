<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/16  15:39
 */
class ShareactCommentNoticeModel extends BaseModel {
    public function addNotice($data) {
        if ($this->create($data))
            if ($this->add())
                return true;
        return false;
    }

    public function getNewNotice($userid) {
        $res = $this
            ->where(array('is_read' => 0, 'to_userid' => $userid))
            ->select();
        return $res;
    }

    public function finishNewNotice($userid) {
        $this->where(array('to_userid' => $userid))->setField('is_read', 1);
    }
}