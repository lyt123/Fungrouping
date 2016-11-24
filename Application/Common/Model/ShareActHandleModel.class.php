<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/11  10:26
 */
class ShareActHandleModel extends CURDModel {
    /**
     * 处理中间表的share_act_release
     */
    public function editShareActRelease($userid, $id, $type) {
        $res = $this
            ->field('share_act_release')
            ->where(array('userid' => $userid))
            ->select();
        if (!$res) {
            $res = $this->add(array('userid' => $userid, 'share_act_release' => $id));
        } elseif($type == 'add') {
            $share_act_id[] = $res[0]['share_act_release'];
            $share_act_id[] = $id;
            $share_act_id = implode(',', $share_act_id);
            $res = $this
                ->where(array('userid' => $userid))
                ->save(array('share_act_release' => $share_act_id));
        } elseif ($type == 'delete') {
            $share_act_id = explode(',', $res[0]['share_act_release']);
            for ($i=0; $i<count($share_act_id); $i++) {
                if ($share_act_id[$i] == $id) {
                    unset($share_act_id[$i]);
                }
            }
            $share_act_id = array_values($share_act_id);
            $share_act_id = implode(',', $share_act_id);
            $res = $this
                ->where(array('userid' => $userid))
                ->save(array('share_act_release' => $share_act_id));
        }

        if ($res) {
            return qc_json_success("操作成功");
        }

        return qc_json_error("操作失败");
    }


    /**
     * 获取分享的活动列表
     */
    public function shareActListSelf($userid) {
        $res = $this
            ->field('share_act_release')
            ->where(array('userid' => $userid))
            ->select();

        if ($res !== false) {
            return qc_json_success($res[0]['share_act_release']);
        }
        return qc_json_error("中间表读取失败");
    }

    /**
     * 获取收藏信息
     */
    public function getCollect($userid) {
        $res = $this
           ->field('share_act_collect')
           ->where(array('userid' => $userid))
           ->select();
        return $res[0]['share_act_collect'];
    }

    /**
     * 更新收藏信息
     */
    public function updateCollect($data, $userid) {
        $res = $this
            ->where(array('userid' => $userid))
            ->setField('share_act_collect', $data);
        return $res;
    }

    /**
     * 获取收藏的活动列表
     */
    public function shareActListCollectSelf($userid) {
        $res = $this
            ->field('share_act_collect')
            ->where(array('userid' => $userid))
            ->select();

        if ($res) {
            return qc_json_success($res[0]['share_act_collect']);
        }
        return qc_json_error("中间表读取失败");
    }

    /**
     * 获取表中某一字段
     */
    public function getActField($userid, array $field = null) {
        return $this->field($field)
            ->where(array('userid' => $userid))
            ->find();
    }

    /**
     * 修改表中某一字段
     */
    public function updateActField($userid, $ignore_actid) {
        if ($this->create(array(
            'share_act_ignore' => $ignore_actid
            ))) {
            $res = $this->where(array('userid' => $userid))->save();
            return $res;
        }
        return false;
    }
}