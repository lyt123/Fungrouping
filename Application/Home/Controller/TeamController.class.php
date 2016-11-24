<?php
/**
 * User: lyt123
 * Date: 2016/9/7  21:12
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class TeamController extends BaseController
{
    /**
     * 这里设置了自动调用reqLogin方法后，如果调用的方法在TeamController中不存在也会先提示no login
     */
    public function __construct()
    {
        $undesired_login_interface = array('teamDetail', 'joinTeamNotUser');
        if(!in_array(array_pop(explode('/', $_SERVER['REQUEST_URI'])), $undesired_login_interface)) {
            $this->reqLogin();
        }
        parent::__construct();
    }

    /**
     * 创建活动
     */
    public function addTeam()
    {
        $data = $this->reqPost(array(
            'title', 'intro', 'phone', 'num_max',
            'starttime', 'timelast', 'address', 'expect_score', 'logo_id'
        ), array('group_num'));

        $Team = D('Team');
        $Team->startTrans();

        //添加活动
        $data['user_id'] = session('user.id');
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['starttime'] = strtotime($data['starttime']);
        $add_team_result = $Team->addOne($data);

        //上传图片
        $upload_result = $this->uploadPictures($add_team_result['response'], 'team_pic', true);
        if (!is_array($upload_result))
            $this->ajaxReturn(qc_json_error($upload_result));

        //处理上传图片数据
        $team_pic = array();
        $team_cover = array();
        foreach ($upload_result['success_array'] as $item) {
            if ($item['key'] == 'cover') {
                $team_cover = array(
                    'cover' => $item['url']
                );
            } else {
                $team_pic[] = array(
                    'team_id' => $add_team_result['response'],
                    'picture' => $item['url']
                );
            }
        }

        D('TeamPic')->addData($team_pic);

        //添加封面图
        $add_cover_result = $Team->update($add_team_result['response'], $team_cover);

        //发布人自动加入活动
        $join_team_result = D('TeamJoin')->addOne(array(
            'team_user_id' => session('user.id'),
            'team_id' => $add_team_result['response'],
            'user_id' => session('user.id'),
            'expect_score' => $data['expect_score'],
            'is_read' => 1
        ));

        unset($upload_result['success_array']);
        if (
            $add_team_result['code'] == 20000 &&
            $join_team_result['code'] == 20000 &&
            $add_cover_result['code'] == 20000
        ) {
            $Team->commit();
            $this->ajaxReturn(qc_json_success($upload_result));
        }

        $Team->rollback();
        $Team->commit();
        $this->ajaxReturn(qc_json_error($upload_result));
    }

    /**
     * 修改活动
     */
    public function updateTeam()
    {
        $data = $this->reqPost(array(
            'title', 'intro', 'phone', 'num_max',
            'starttime', 'timelast', 'address', 'id',
        ), array('group_num', 'del_pic', 'logo_id'));

        //上传图片
        if($_FILES) {
            $upload_result = $this->uploadPictures($data['id'], 'team_pic', true);
            if (!is_array($upload_result))
                $this->ajaxReturn(qc_json_error($upload_result));

            //处理上传图片数据
            $team_pic = array();
            foreach ($upload_result['success_array'] as $item) {
                if ($item['key'] == 'cover') {
                    $data['cover'] = $item['url'];
                } else {
                    $team_pic[] = array(
                        'team_id' => $data['id'],
                        'picture' => $item['url']
                    );
                }
            }
        }


        $Team = D('Team');
        $Team->startTrans();

        //添加图片
        $add_pic_result = array();
        if ($team_pic)
            $add_pic_result = D('TeamPic')->addData($team_pic);

        //修改活动信息
        $data['starttime'] = strtotime($data['starttime']);
        $update_team_result = $Team->update($data['id'], $data);

        //删除图片
        if ($data['del_pic']) {
            $del_pic_ids = explode(',', $data['del_pic']);
            foreach ($del_pic_ids as $item) {
                $result = D('TeamPic')->destroy($item);
                if ($result['code'] != 20000) {
                    $Team->rollback();
                    $Team->commit();
                    $this->ajaxReturn(qc_json_error('system error'));
                }
            }
        }

        if (
            $update_team_result['code'] == 20000 ||
            (empty($team_pic) || $add_pic_result['code'] == 20000)
        ) {
            $Team->commit();
            $this->ajaxReturn(qc_json_success());
        }
        $Team->rollback();
        $Team->commit();
        $this->ajaxReturn(qc_json_error());
    }

    /**
     * 删除活动
     */
    public function deleteTeam()
    {
        $data = $this->reqPost(array('id'));

        $info = D('Team')->getData(array('id' => $data['id']), array('user_id'));

        $Team = D('Team');
        $Team->startTrans();


        if ($info['user_id'] == session('user.id')) {
            //发布人删除活动
            $del_team_result = $Team->destroy($data['id']);
            $del_pic_result = D('TeamPic')->destroy($data['id'], 'team_id', true);
            $del_join_result = D('TeamJoin')->deleteJoin($data['id']);
            $del_notuser_result = D('TeamJoinNotUser')->deleteJoin($data['id']);
            if (
                $del_team_result['code'] == 20000 &&
                $del_pic_result['code'] == 20000 &&
                $del_join_result && $del_notuser_result
            ) $status = true;
            else $status = false;
        } else {
            //参与者删除活动
            if (D('TeamJoin')->deleteJoin($data['id'], session('user.id')))
                $status = true;
            else $status = false;
        }

        if ($status) {
            $Team->commit();
            $this->ajaxReturn(qc_json_success());
        }
        $Team->rollback();
        $Team->commit();
        $this->ajaxReturn(qc_json_error());
    }

    /**
     * 活动列表
     */
    public function teamList()
    {
        $data = $this->reqPost(array(), array('page', 'limit', 'srh_string', 'city_id'));

        $this->ajaxReturn(qc_json_success(
            D('Team')->getList($data['page'], $data['limit'], $data['srh_string'], $data['city_id'])
        ));
    }

    /**
     * 具体活动信息
     */
    public function teamDetail()
    {
        $data = $this->reqPost(array('id'));

        $info = D('Team')->getData(array('id' => $data['id']));
        $info['head_path'] = D('User')->getUserInfo($info['user_id'], array('head_path'))[0]['head_path'];
        $info['picture'] = D('TeamPic')->getData(array('team_id' => $info['id']), array('picture', 'id'), true);
        $info['join'] = D('TeamJoin')->getJoinUserInfo($data['id']);
        $info_notuser = D('TeamJoinNotUser')->getJoinNotuserInfo($data['id'], array('head_path', 'username', 'sex', 'id'));

        $info['join'] = array_merge($info['join'], $info_notuser);

        if($info['user_id'] == session('user.id'))
            $info['is_creater']  = true;
        else
            $info['is_creater'] = false;

        //面向比赛编程
        $info['logo_url'] = "Public/business/slideShow/4.jpg";

        $this->ajaxReturn(qc_json_success($info));
    }

    /**
     * 我发起的
     */
    public function teamSelf()
    {
        $userid = session('user.id');
        $this->ajaxReturn(qc_json_success(D('TeamJoin')->getTeamSelf($userid)));
    }

    /**
     * 我参与的
     */
    public function teamInvited()
    {
        $userid = session('user.id');
        $this->ajaxReturn(qc_json_success(D('TeamJoin')->getTeamInvited($userid)));
    }

    /**
     * 活动参加情况
     */
    public function teamJoinInfo()
    {
        $data = $this->reqPost(array('id'));

        $join_info = D('TeamJoin')->getJoinInfo($data['id']);

        $join_info_notuser = D('TeamJoinNotUser')->getJoinNotuserInfo($data['id'], array('username', 'sex', 'phone', 'id', 'head_path'));

        $join_info = array_merge($join_info, $join_info_notuser);

        $this->ajaxReturn(qc_json_success($join_info));
    }

    /**
     * 报名活动
     */
    public function joinTeam()
    {
        $data = $this->reqPost(array('team_id', 'expect_score', 'phone'));
        $data['team_user_id'] = current(D('Team')->getData(array('id' => $data['team_id']), array('user_id')));
        $data['user_id'] = session('user.id');

        D('Team')->incOne($data['team_id'], 'num_join');
        $this->ajaxReturn(D('TeamJoin')->addOne($data));
    }

    /**
     * 报名活动-未注册用户
     */
    public function joinTeamNotUser()
    {
        $data = $this->reqPost(array(
            'team_id', 'phone',
            'username', 'sex',
        ));
        $data['team_user_id'] = current(D('Team')->getData(
            array('id' => $data['team_id']),
            array('user_id')
        ));

        $result = $this->uploadPictures($data['username'], 'user_head_temp');
        $data['head_path'] = 'Public/' . $result['savepath'] . $result['savename'];

        D('Team')->incOne($data['team_id'], 'num_join');

        $this->ajaxReturn(D('TeamJoinNotUser')->addOne($data));
    }

    /**
     * 踢人
     */
    public function rejectJoin()
    {
        $data = $this->reqPost(array('team_id'), array('id', 'user_id'));
        $info = D('Team')->getData(array('id' => $data['team_id']), array('user_id'));
        if ($info['user_id'] != session('user.id'))
            $this->ajaxReturn(qc_json_error('not team creater'));

        D('Team')->decOne($data['id'], 'num_join');
        if ($data['id'])
            $result = D('TeamJoinNotUser')->destroy($data['id']);
        else
            $result = D('TeamJoin')->rejectJoin($data);

        $this->ajaxReturn($result);
    }

    /**
     * 具体活动信息-链接
     */
    public function teamDetailLink($id)
    {
        $info = D('Team')->getData(array('id' => $id));
        $info['head_path'] = D('User')->getUserInfo($info['user_id'], array('head_path'))[0]['head_path'];
        $picture = D('TeamPic')->getData(array('team_id' => $info['id']), array('picture'), true);
        $info_picture = array();
        foreach ($picture as $item) {
            $info_picture = $item['picture'];
        }
        $info_join = D('TeamJoin')->getJoinUserInfo($id);

        $this->assign("info", $info);
        $this->assign("info_picture", $info_picture);
        $this->assign("info_join", $info_join);

        $this->display();
    }

    /**
     * 活动评分
     */
    public function assessTeam()
    {
        $data = $this->reqPost(array('id', 'satisfy_score'));

        $this->ajaxReturn(D('TeamJoin')->update($data['id'], $data, 'team_id'));
    }

    /**
     * 是否有活动提醒
     */
    public function checkTeamNotice()
    {
        $user_id = session('user.id');

        $exist = D('TeamJoin')->checkNotice($user_id);
        $exist_not_user = D('TeamJoinNotUser')->checkNotice($user_id);

        if ($exist || $exist_not_user)
            $this->ajaxReturn(qc_json_success('new notice exist'));
        $this->ajaxReturn(qc_json_error('no new notice'));
    }

    /**
     * 获取活动提醒信息
     */
    public function getTeamNotice()
    {
        $user_id = session('user.id');

        $notice_user = D('TeamJoin')->getNotice($user_id);
        $notice_not_user = D('TeamJoinNotUser')->getData(
            array('team_user_id' => $user_id),
            array('team_id', 'username', 'head_path', 'sex', 'phone'),
            true
        );
        $notice = array_merge($notice_user, $notice_not_user);

        D('TeamJoin')->update(
            $user_id, array('is_read' => 1), 'team_user_id'
        );
        D('TeamJoinNotUser')->update(
            $user_id, array('is_read' => 1), 'team_user_id'
        );

        $this->ajaxReturn(qc_json_success($notice));
    }
}