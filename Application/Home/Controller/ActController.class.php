<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/7/25  16:28
 */

class ActController extends BaseController {
    /**
     * 添加活动
     */
    public function addact() {
        $postdata = array("title", "address", "phone", "name_format", "logo_id");
        $this->reqLogin()->reqPost($postdata);
        $data['title']       = I("post.title");
        $data['phone']       = I("post.phone");
        $data['intro']       = I("post.intro");
        $data['name_format'] = I("post.name_format");
        $data['logo_id']     = I("post.logo_id");
        $data["ctime"]       = time(); //创建时间

        $time = I("post.time", '', '');
        $time = json_decode($time, 1);
        $address = I("post.address", '', '');
        $address = json_decode($address, 1);

        //判断时间、地点是否都只有一个
        if (1 == count($time) && 1 == count($address)) {
            $data['vote_state'] = 1;
            $time[0]['choose'] = 1;
            $address['choose'] = 1;
        }

        $user = session("user");
        $data["userid"] = $user["id"];

        $actmodel = D("Act");
        $actmodel->startTrans();
        $res = $actmodel->addact($data);//返回活动id

        if ($res['code'] != 40000) {
            $result = D('ActTime')->addActTime($time, $res);

            if ($result) {
                $result = D('ActAddress')->addActAddress($address, $res);

                if ($result) {
                    $actmodel->commit();
                    $this->ajaxReturn(qc_json_success("添加活动成功"));
                }
            }
        }

        $actmodel->rollback();
        $actmodel->commit();
        $this->ajaxReturn(qc_json_error('添加活动失败'));
    }

    /**
     * 我的活动
     */
    public function myact($page = 1, $limit = 5) {
        $this->reqLogin();//判断session是否过期
        $user = session("user");
        $userid = $user['id'];
        $joinactmodel = D("Joinact");
        $res = $joinactmodel->getmyact($userid, $page, $limit);
        $this->ajaxReturn($res);
    }

    /**
     * 邀请我的
     */
    public function inviteact($page = 1, $limit = 5) {
        $this->reqLogin();//判断session是否过期
        $user = session("user");
        $userid = $user['id'];
        $joinactmodel = D("Joinact");
        $res = $joinactmodel->getinviteact($userid, $page, $limit);
        $this->ajaxReturn($res);
    }

    /**
     * 活动时间投票具体情况
     */
    public function timeVoteDetail() {
        $post_data = $this->reqLogin()->reqPost(array('id'));

        $data['time'] = D('ActTime')->getMultiActtime($post_data['id'], array('id', 'starttime', 'votes'));

        $data['user'] = D('Joinact')->voteDetail($post_data['id']);
        $data_notuser = D('Joinact')->voteDetailNotUser($post_data['id']);
        foreach($data_notuser as &$item) {
            $item['head_path'] = 'Public/img/head/notuser_head.jpg';
            $item['userid'] = $item['id'];
            unset($item['id']);
            $data['user'][] = $item;
        }
        unset($item);

        if ($data['time'] || $data['user']) {
            $this->ajaxReturn(qc_json_success($data));
        }
        $this->ajaxReturn(qc_json_error("获取数据失败"));
    }

    /**
     * 活动发布人选中时间地点
     */
    public function createUserResponseAct() {
        $data = $this->reqLogin()->reqPost(array('id'), array('timeid', 'addressid'));
        $actmodel = D('Act');
        $actmodel->startTrans();

        if ($data['timeid']) {
            $res = D('ActTime')->updateChoose($data['timeid']);
        } else {
            $res['code'] = 20000;
        }

        if ($res['code'] == 20000) {
            if ($data['addressid']) {
                $res = D('ActAddress')->updateChoose($data['addressid']);
            }
            if ($res['code'] == 20000) {
                $res = $actmodel->updateVotestate($data['id']);
                if ($res['code'] == 20000) {
                    $actmodel->commit();
                    $this->ajaxReturn(qc_json_success("选中成功"));
                }
            }
        }
        $actmodel->rollback();
        $actmodel->commit();
        $this->ajaxReturn($res);
    }

    /**
     * 具体活动信息
     */
    public function actdetail($actid) {
        //获取活动信息
        $actmodel = D("Act");
        $res = $actmodel->findact($actid);

        if ($res) {
            $res_act = $res;

            //获取当前用户userid
            if(session('?user'))
                $userid = session("user.id");
            else
                $userid = '';

            if ($userid == $res_act['userid'])
                $res_act['is_createuser'] = 1;
            else
                $res_act['is_createuser'] = 0;

            //获取活动发布人username
            $usermodel = D("User");
            $username = $usermodel->getUserInfo($res_act['userid'], array('username'));
            $res_act['createuser'] = $username[0]['username'];

            //获取活动时间地点
            $acttimemodel = D('ActTime');
            $actaddressmodel = D('ActAddress');

            if ($res_act['vote_state']) {

                $res = $acttimemodel->getActtime($res_act['id']);
                $res_act['starttime'] = $res[0]['starttime'];
                $res_act['timelast'] = $res[0]['timelast'];

                $res = $actaddressmodel->getActaddress($res_act['id']);
                $res_act['address'] = $res[0]['address'];

                $res_act['is_joined'] = D('Joinact')->checkJoin($res_act['id'], $userid);

                unset($res_act['vote_state']);
                unset($res_act['userid']);

                $this->ajaxReturn(qc_json_success($res_act, '20001'));
            } else {
                //获取当前用户时间地点投票情况
                $res_time = $acttimemodel->getMultiActtime($res_act['id'], array('id', 'votes', 'starttime'));
                $res_address = $actaddressmodel->getMultiActaddress($res_act['id'], array('id', 'votes', 'address'));

                $voted = D('Joinact')->getVoted($res_act['id'], $userid);
                $voted = $voted ? $voted : array(array('time_voted' => '', 'address_voted' => ''));
                $result = createVotefor($res_time, $res_address, $voted);

                $res_act['time'] = $result[0];
                $res_act['address'] = $result[1];

                $res_act['is_joined'] = D('Joinact')->checkJoin($res_act['id'], $userid);

                unset($res_act['vote_state']);
                unset($res_act['userid']);

                $this->ajaxReturn(qc_json_success($res_act, '20000'));
            }
        } else {
            $this->ajaxReturn(qc_json_error('活动已被删除'));
        }
    }

    /**
     * 活动参加情况
     */
    public function joinDetail() {
        $data = $this->reqLogin()->reqPost(array('id'));
        $join_user = D('Joinact')->getJoinUser($data['id']);
        $join_notuser = D('Joinact')->getJoinNotUser($data['id']);
        foreach($join_notuser as &$item) {
            $item['head_path'] = 'Public/img/head/notuser_head.jpg';
            $item['userid'] = $item['id'];
            unset($item['id']);
            $join_user[] = $item;
        }

        if ($join_user === false)
            $this->ajaxReturn(qc_json_error());
        $this->ajaxReturn(qc_json_success($join_user));
    }

    /**
     * 获取待编辑活动信息
     */
    public function geteditact($id) {
        $this->reqLogin();//判断session是否过期

        //获取活动信息
        $actmodel = D("Act");
        $res = $actmodel->findact($id);

        if ($res) {
            $res_act = $res;

            //获取活动时间地点
            $acttimemodel = D('ActTime');
            $actaddressmodel = D('ActAddress');

            unset($res_act['ctime']);
            unset($res_act['userid']);
            unset($res_act['join_num']);

            if ($res_act['vote_state']) {
                $res = $acttimemodel->getActtime($res_act['id']);
                $res_act['starttime'] = $res[0]['starttime'];
                $res_act['timelast'] = $res[0]['timelast'];

                $res = $actaddressmodel->getActaddress($res_act['id']);
                $res_act['address'] = $res[0]['address'];

                unset($res_act['vote_state']);
                $this->ajaxReturn(qc_json_success($res_act, '20001'));
            } else {
                $res = $acttimemodel->getMultiActtime($res_act['id'], array('starttime', 'timelast', 'id'));
                $res_act['time'] = $res;

                $res = $actaddressmodel->getMultiActaddress($res_act['id'], array('address', 'id'));
                $res_act['address'] = $res;

                unset($res_act['vote_state']);
                $this->ajaxReturn(qc_json_success($res_act, '20000'));
            }
        } else {
            $this->ajaxReturn(qc_json_error("活动不存在"));
        }
    }

    /**
     * 编辑活动
     */
    public function editAct() {
        $postdata = array("title", "phone", "name_format");
        $this->reqLogin()->reqPost($postdata);
        $data['title']       = I("post.title");
        $data['phone']       = I("post.phone");
        $data['intro']       = I("post.intro");
        $data['name_format'] = I("post.name_format");
        $data['id']          = I("post.id");
        $data['logo_id']     = I("logo_id");

        $actmodel = D("Act");

        //检验当前用户为活动创建人
        $is_creater = $actmodel->chkCreater($data['id'], session('user')['id']);
        if(!$is_creater)
            $this->ajaxReturn(qc_json_error("你没有权限修改"));

        $actmodel->startTrans();
        $res = $actmodel->editAct($data);

        if ($res) {
            $add_time = json_decode(I("post.add_time", '', ''), 1);
            $delete_time = I("post.delete_time");
            $add_address = json_decode(I("post.add_address", '', ''), 1);
            $delete_address = I("post.delete_address");;

            if ($add_time) {
                $res = D('ActTime')->addActTime($add_time, $data['id']);
            }

            if ($delete_time && $res) {
                $res = D('ActTime')->deleteActTimeById($delete_time);
            }

            if ($add_address && $res) {
                $res = D('ActAddress')->addActAddress($add_address, $data['id']);
            }

            if ($delete_address && $res) {
                $res = D('ActAddress')->deleteActAddressById($delete_address);
            }

            if ($res) {

                $actmodel->commit();
                $this->ajaxReturn(qc_json_success("编辑活动成功"));
            }
        }

        $actmodel->rollback();
        $actmodel->commit();
        $this->ajaxReturn(qc_json_error('编辑活动失败'));
    }

    /**
     * 响应活动
     */
    public function responseAct() {
        $post_data = $this->reqPost(
            array("actid", "time_voted", "address_voted"),
            array( "name_format")
        );
        if(session('user')['id'])
            $post_data['userid'] = session('user')['id'];
        else
            $post_data['userid'] = 0;

        $joinactmodel = D('Joinact');
        $joinactmodel->startTrans();
        $res = $joinactmodel->addjoin($post_data);

        if ($res['code'] == 20000) {
            $res = D("ActTime")->responseActTime($post_data['time_voted']);

            if ($res['code'] == 20000) {
                $res = D("ActAddress")->responseActAddress($post_data['address_voted']);

                if ($res['code'] == 20000) {
                    $res = D("Act")->addActJoinnum($post_data['actid']);

                    if ($res['code'] == 20000) {
                        $joinactmodel->commit();

                        $this->ajaxReturn(qc_json_success("响应成功"));
                    }
                }
            }
        }
        $joinactmodel->rollback();
        $joinactmodel->commit();
        $this->ajaxReturn($res);
    }

    /**
     * 再次响应活动
     */
    public function reResponseAct() {
        $post_data = $this->reqLogin()
            ->reqPost(
                array("actid", "time_voted", "address_voted"),
                array("name_format")
            );
        $userid = session('user')['id'];

        $joinact_model = D('Joinact');
        $joinact_model->startTrans();

        $pre_resonse = $joinact_model->getVoted($post_data['actid'], $userid)[0];

        $reRespond_time    = D('ActTime')   ->decVotes($pre_resonse['time_voted']);
        $reRespond_address = D('ActAddress')->decVotes($pre_resonse['address_voted']);
        $respond_time      = D('ActTime')   ->responseActTime($post_data['time_voted']);
        $respond_address   = D('ActAddress')->responseActAddress($post_data['address_voted']);
        $reRespond_act     = $joinact_model ->reResponseAct($post_data, $userid);

        if (
            $reRespond_time                   &&
            $reRespond_address                &&
            $respond_time['code'] == 20000    &&
            $respond_address['code'] == 20000 &&
            $reRespond_act
        ) {
            $joinact_model->commit();
            $this->ajaxReturn(qc_json_success());
        } else {
            $joinact_model->rollback();
            $joinact_model->commit();
            $this->ajaxReturn(qc_json_error());
        }
    }

    /**
     * 参加活动
     */
    public function joinact() {
        $this->reqPost(array('id'), array('name_format'));
        $data['name_format'] = I("post.name_format");
        $data['actid'] = I("post.id");

        if(session('user.id'))
            $data['userid'] = session('user')['id'];
        else
            $data['userid'] = 0;

        $joinactmodel = D('Joinact');
        D("Act")->addActJoinnum($data['actid']);
        $res = $joinactmodel->addjoin($data);

        $this->ajaxReturn($res);
    }

    /**
     * 删除活动
     */
    public function deleteact($id) {
        $this->reqLogin();
        $user = session('user');
        $userid = $user['id'];
        $joinactmodel = D("Joinact");
        $res = $joinactmodel->deleteact($id, $userid);
        $this->ajaxReturn($res);
    }

    /**
     * 生成活动二维码
     */
    public function createActQRcode($text = '', $size = 50, $margin = 2) {
        vendor('QRcode.phpqrcode');
        \QRcode::png($text,false,QR_ECLEVEL_L,$size/25,$margin);
    }

    /**
     * 活动发布人删除参与者
     */
    public function createrRejectJoin() {
        $data     = $this->reqLogin()->reqPost(array('actid', 'userid'));
        $act_info = D('Act')->findact($data['actid'], array('userid', 'vote_state'));
        $userid   = session('user')['id'];

        //验证当前登陆者为活动发布人
        if ($userid != $act_info['userid'])
            $this->ajaxReturn(qc_json_error('没有权限'));

        $joinact_model = D('Joinact');
        $joinact_model->startTrans();

        //判断所要删除的参与者是否是注册用户
        $is_notuser = D('Joinact')->chkNotUser($data);

        //时间地点未选中才删除投票记录
        if ($act_info['vote_state'] == 0) {

            if($is_notuser)
                $voted_info = $joinact_model->getVoted($data['actid'], $data['userid'], 'id')[0];
            else
                $voted_info = $joinact_model ->getVoted($data['actid'], $data['userid'])[0];

            $dec_time_votes = D('ActTime')   ->decVotes($voted_info['time_voted']);
            $dec_add_votes  = D('ActAddress')->decVotes($voted_info['address_voted']);
        }

        if($is_notuser)
            $del_join = $joinact_model ->deleteact($data['actid'], $data['userid'], 'id');
        else
            $del_join = $joinact_model ->deleteact($data['actid'], $data['userid']);

        $dec_add_votes_status  = isset($dec_add_votes)  ? $dec_add_votes  : 1;
        $dec_time_votes_status = isset($dec_time_votes) ? $dec_time_votes : 1;

        if (
            $del_join['code'] == 20000 &&
            $dec_time_votes_status     &&
            $dec_add_votes_status
        ) {
            D('Act')->decActJoinnum($data['actid']);
            $joinact_model->commit();
            $this->ajaxReturn(qc_json_success());
        }

        $joinact_model->rollback();
        $joinact_model->commit();
        $this->ajaxReturn(qc_json_error());
    }
}
