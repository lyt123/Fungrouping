<?php
namespace Common\Model;

class ActAddressModel extends BaseModel {
    /**
     * 获取活动地点
     * @param $id
     * @return mixed
     */
    public function getActaddress($id) {
        $res = $this
            ->field('address')
            ->where(array('actid'=>$id,'choose'=>1))
            ->select();;
        return $res;
    }

    /**
     * 获取活动地点及投票情况
     * @param $id
     * @return mixed
     */
    public function getMultiActaddress($id, $field) {
        $res = $this
            ->field($field)
            ->where(array('actid'=>$id))
            ->select();
        return $res;
    }

    /**
     * 获取活动地点及投票情况
     * @param $id
     * @return mixed
     */
    public function editActaddress($id) {
        $res = $this
            ->field('votes, address, voters')
            ->where(array('actid'=>$id))
            ->select();
        return $res;
    }

    /**
     * 删除活动地点
     * @param $id
     * @return
     */
    public function deleteactaddress($id) {
        $res = $this->where(array('actid'=>$id))->delete();
        return $res;
    }

    /**
     * 按地点id删除
     */
    public function deleteActAddressById($id) {
        $res = $this->delete($id);

        return $res;
    }

    public function addActAddress($address, $actid) {
        !$address['choose'] ? : $choose = 1;
        unset($address['choose']);

        for ($i = 0; $i < count($address); $i++) {
            $addresslist[] = array(
                'actid' => $actid,
                'address' => $address[$i]
            );
        };

        !$choose ? : $addresslist[0]['choose'] = 1;

        $result = D('ActAddress')->addAll($addresslist);

        return $result;
    }

    /**
     * 响应活动地点
     */
    public function responseActAddress($address) {
        $addressid = explode('-', $address);

        foreach ($addressid as $id) {
            $res = $this
                ->where(array('id' => $id))
                ->setInc('votes', 1);

            if (!$res) {
                return qc_json_error("投票地点写入失败");
            }
        }
        return qc_json_success("响应地点成功");
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
        return qc_json_error("更新地点表失败");
    }

    /**
     * 再次响应活动--取消投票
     */
    public function decVotes($address) {
        $addressid = explode('-', $address);

        foreach ($addressid as $id) {
            $res = $this
                ->where(array('id' => $id))
                ->setDec('votes', 1);

            if (!$res)
                return false;
        }
        return true;
    }

}