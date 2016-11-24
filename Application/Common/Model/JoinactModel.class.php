<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/7/26  16:01
 */
class JoinactModel extends BaseModel {

    /**
     * 加入活动
     * @param $data
     * @return array
     */
    public function addjoin($data) {
        $res = '';

        if($data['userid']) {
            $where = "actid=" . $data["actid"] . " and userid=" . $data["userid"] . " and power<>1";
            $res = $this->where($where)->find();

            if ($res)
                return qc_json_error("您已加入该活动");

            $map = "actid=" . $data["actid"] . " and userid=" . $data["userid"] . " and power=1";
            if($res_data = $this->field('id')->where($map)->find()) {
                $data['power'] = 2;
                if($this->create($data)) {
                    $res = $this->where(array('id' => $res_data['id']))->save($data);
                }
                else return qc_json_error($this->getError());
            } else {
                if ($this->create($data))
                    $res = $this->add();
            }
        }
        else {
            if ($this->create($data))
                $res = $this->add();
        }

        if ($res)
            return qc_json_success("加入活动成功");
        return qc_json_error("加入活动失败");

    }

    /**
     * 获取我的活动
     * @param $userid
     * @return array
     */
    public function getmyact($userid, $page, $limit) {
        $res = $this
            ->field("a.title, a.join_num, a.vote_state, a.ctime, a.id, j.is_share, a.logo_id")
            ->table("fg_joinact j,fg_act a")
            ->where("j.actid=a.id and j.power<>0 and j.userid=" . $userid)
            ->order("ctime desc")
            ->limit(($page-1)*$limit, $limit)
            ->select();
        $i = 0;
        foreach ($res as $value) {
            $res[$i]['over_date'] = 0;
            if ($value['vote_state'] == 1) {
                $time = D('ActTime')
                    ->field('starttime, timelast')
                    ->where("choose=1 and actid=" . $value['id'])
                    ->select();

                if (($time[0]['starttime'] + $timelast[0]['timelast'] * 3600) < NOW_TIME) {

                    $res[$i]['over_date'] = 1;
                }
            }

            if ($value['vote_state'] && !$res[$i]['over_date']) {
                $res[$i]["starttime"] = $time[0]['starttime'];
            }

            if ($value['is_share']) {
                $res[$i]['vote_state'] = 2;
            }
            unset($res[$i]['is_share']);
            $i++;
        }

        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_success(array());
    }

    /**
     * 获取邀请我的活动
     * @param $userid
     * @return array
     */
    public function getinviteact($userid, $page, $limit) {
        $res = $this
            ->field("a.title, a.join_num, a.vote_state, a.id, a.ctime, a.userid, a.logo_id")
            ->table("fg_joinact j,fg_act a")
            ->where("j.actid=a.id and j.power=0 and j.userid=" . $userid)
            ->order("ctime desc")
            ->limit(($page-1)*$limit, $limit)
            ->select();

        $i = 0;
        foreach ($res as $value) {
            $res[$i]['over_date'] = 0;
            if($value['vote_state']) {

                $time = D('ActTime')
                    ->field('starttime, timelast')
                    ->where("choose=1 and actid=" . $value['id'])
                    ->select();

                if (($time[0]['starttime'] + $timelast[0]['timelast'] * 3600) < NOW_TIME) {
                    $res[$i]['over_date'] = 1;
                }
            }

            if($value['vote_state'] && !$res[$i]['over_date']) {
                $res[$i]["starttime"] = $time[0]['starttime'];
            }

            $creatuser = D('User')->getUserInfo($value['userid'], array('username'));
            $res[$i]["creatuser"] = $creatuser[0]['username'];

            unset($res[$i]['userid']);
            unset($res[$i]['ctime']);
            $i++;
        }

        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_success(array());
    }

    /**
     * 删除活动
     * @param $id
     * @return \multitype|null
     */
    public function deleteact($id, $userid, $key = 'userid') {
        $where = array(
            'actid'=>$id,
            $key=>$userid,
            'power'=>array('NEQ', 0)
        );
        $exist = $this->where($where)->select();//判断是否活动发布人

        $this->startTrans();
        if ($exist) {
            $res = $this->where(array('actid'=>$id))->delete();
                if ($res) {
                    $res = D('Act')->deleteact($id);
                     if ($res) {
                         $res = D('ActTime')->deleteacttime($id);
                         if ($res) {
                             $res = D('ActAddress')->deleteactaddress($id);
                         }
                     }
                 }
        } else {
            $res = $this->where(array('actid'=>$id, $key=>$userid))->delete();
        }
        if ($res) {
            $this->commit();
            return(qc_json_success('活动删除成功'));
        }

        $this->rollback();
        $this->commit();
        return(qc_json_error('活动删除失败'));
    }

    /**
     * 取出userid和time_voted
     */
    public function voteDetail($actid) {
        $res = $this
            ->field('j.name_format, j.time_voted, u.head_path, j.userid')
            ->table('fg_joinact j, fg_user u')
            ->where(array('actid' => $actid, 'power' => array('NEQ', 1)))
            ->where('j.userid = u.id')
            ->select();
        return $res;
    }

    /**
     * 取出非注册用户的投票信息
     */
    public function voteDetailNotUser($actid) {
        $res = $this
            ->field('name_format, time_voted, id')
            ->where(array('actid' => $actid, 'userid' => 0))
            ->select();
        return $res;
    }

    public function getVoted($actid, $userid, $key = 'userid') {
        $res = $this
            ->field(array('time_voted', 'address_voted'))
            ->where(array('actid' => $actid, $key => $userid))
            ->select();

        return $res;
    }

    /**
     * 再次响应活动
     */
    public function reResponseAct($data, $userid) {
        if (!$this->create($data)) {
            return qc_json_error($this->getError());
        }

        $res = $this
            ->where(array('actid' => $data['actid'], 'userid' => $userid))
            ->save($data);

        if ($res)
            return true;
        return false;
    }

    /**
     * 获取参加活动情况
     */
    public function getJoinUser($actid) {
        $res = $this
            ->field('j.name_format, u.head_path, j.userid')
            ->table('fg_joinact j, fg_user u')
            ->where(array('actid' => $actid, 'power' => array('NEQ', 1)))
            ->where('j.userid = u.id')
            ->select();
        return $res;
    }

    /**
     * 获取参加活动情况
     */
    public function getJoinNotUser($actid) {
        $res = $this
            ->field('name_format, id')
            ->where(array('actid' => $actid, 'userid' => 0))
            ->select();
        return $res;
    }

    /**
     * 判断用户是否已加入活动
     */
    public function checkJoin($actid, $userid) {
        $res = $this
            ->where(array('userid' => $userid, 'actid' => $actid))
            ->find();
        if ($res)
            return true;
        return false;
    }

    /**
     * 检验被删除的活动参与者是否已注册
     */
    public function chkNotUser($data) {
        $res = $this->where(array(
            'actid'  => $data['actid'],
            'id' => $data['userid']
        ))->find();

        return $res ? true : false;
    }


    /**
     * 标记过期活动已分享
     */
    public function markActShared($actid, $userid) {
        return $res = $this
            ->where(array('actid' => $actid, 'userid' => $userid))
            ->setField(array('is_share' => 1));
    }
}