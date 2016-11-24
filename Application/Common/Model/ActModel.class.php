<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/7/25  16:47
 */

class ActModel extends BaseModel {
    protected $_validate = array(
        array("title",'','活动主题不能为空',self::EXISTS_VALIDATE,'notequal'),
        array("phone",'','发布人手机号不能为空',self::EXISTS_VALIDATE,'notequal'),
        array("address",'','活动地点不能为空',self::EXISTS_VALIDATE,'notequal'),
    );

    /**
     * 创建活动
     */
    public function addact($data) {

        if(!$this->create($data)) {
            return qc_json_error($this->getError());
        }

        $res = $this->add();

        if($res){
            //往中间表中插入数据

            $joindata=array(
                "actid"=>$res,
                "userid"=>$data["userid"],
                "power"=>1
            );
            $result = D("Joinact")->addjoin($joindata);
            if($result['code'] == 20000) {
                return $res;
            }
        }
        return qc_json_error("活动添加失败");
    }

    /**
     * 获得(查询)活动相关信息
     * @param $id
     * @return \multitype|null
     */
    public function findact($id, array $field = null) {
        return $res=$this->field($field)
            ->where("id=".$id)
            ->find();
    }

    /**
     * 编辑活动
     */
    public function editAct($data) {
        if($this->create($data)) {
            $res = $this->save();
            if(false !== $res) {
                return true;
            }
        }
        return false;
    }

    /**
     * 删除活动
     */
    public function deleteact($id) {
        $res = $this
            ->where(array('id' => $id))
            ->delete();

        return $res;
    }

    /**
     * 活动响应（参与）人数+1
     */
    public function addActJoinnum($actid) {
        $res = $this
            ->where(array('id' => $actid))
            ->setInc('join_num', 1);
        if (!$res) {
            return qc_json_error("活动写入失败");
        }

        return qc_json_success("活动写入成功");
    }

    /**
     * 发布人选中时间地点-更新vote_state
     */
    public function updateVotestate($id) {
        $res = $this
            ->where(array('id' => $id))
            ->data(array('vote_state' => 1))
            ->save();

        if ($res) {
            return qc_json_success();
        }
        return qc_json_error("更新活动表失败");
    }

    /**
     * 检验当前用户是否活动创建人
     */
    public function chkCreater($id, $userid) {
        return $this->where(array('id' => $id, 'userid' => $userid))->find();
    }

    /**
     * 活动参加人数-1
     */
    /**
     * 活动响应（参与）人数+1
     */
    public function decActJoinnum($actid) {
        return $this
            ->where(array('id' => $actid))
            ->setDec('join_num', 1);
    }
}
