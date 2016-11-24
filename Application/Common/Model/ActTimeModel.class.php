<?php
namespace Common\Model;
class ActTimeModel extends BaseModel {
    /**
     * 获取活动时间
     * @param $id
     * @return mixed
     */
    public function getActtime($id) {
        $res = $this
            ->field('starttime, timelast')
            ->where(array('actid'=>$id,'choose'=>1))
            ->select();
        return $res;
    }

    /**
     * 获取活动时间及投票情况
     * @param $id
     * @return mixed
     */
    public function getMultiActtime($id, $field) {
        $res = $this
            ->field($field)
            ->where(array('actid'=>$id))
            ->select();
        return $res;
    }

    /**
     * 删除活动所有时间
     * @param $id
     * @return
     */
    public function deleteacttime($id) {
        $res = $this->where(array('actid'=>$id))->delete();
        return $res;
    }

    /**
     * 按时间id删除
     */
    public function deleteActTimeById($id) {
        $res = $this->delete($id);

        return $res;
    }

    /**
     * 添加、编辑活动时间
     */
    public function addActTime($time, $actid) {
        for ($i = 0; $i < count($time); $i++) {
            $time[$i]['actid'] = $actid;
            $time[$i]['starttime'] = strtotime($time[$i]['starttime']);
            !$time[$i]['choose'] ? : $time[$i]['choose'] = 1;
        };

        $res = D('ActTime')->addAll($time);

        return $res;
    }

    /**
     * 响应活动时间
     */
    public function responseActTime($time) {
        $timeid = explode('-', $time);

        foreach ($timeid as $id) {

            $res = $this
                ->where(array('id' => $id))
                ->setInc('votes', 1);
            if (!$res) {
                return qc_json_error("投票时间写入失败");
            }
        }
        return qc_json_success("响应时间成功");
    }

    /**
     * 发布人选中时间地点-更新choose
     */
    public function updateChoose($id) {
        $res = $this
            ->where(array('id' => $id))
            ->data(array('choose' => 1))
            ->save();

        if ($res) {
            return qc_json_success();
        }
        return qc_json_error("更新时间表失败");
    }

    /**
     * 再次响应活动-取消原来投票
     */
    public function decVotes($time) {
        $timeid = explode('-', $time);

        foreach ($timeid as $id) {

            $res = $this
                ->where(array('id' => $id))
                ->setDec('votes', 1);
            if (!$res) {
                return false;
            }
        }
        return true;
    }
}