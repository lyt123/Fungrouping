<?php
/**
 * User: lyt123
 * Date: 2016/9/7  20:41
 */
namespace Common\Model;

class TeamJoinModel extends CURDModel
{

    /**
     * 活动参加情况
     */
    public function getJoinInfo($team_id)
    {
        return $this
            ->table('fg_team_join tj, fg_user u')
            ->field('tj.phone, u.username, u.sex, tj.user_id, u.head_path')
            ->where('tj.user_id = u.id and team_id = %d', $team_id)
            ->select();
    }

    /**
     * 我参加的
     */
    public function getTeamSelf($user_id)
    {
        return $this
            ->table('fg_team_join tj, fg_team t')
            ->field('t.title, t.ctime, t.cover, t.id, t.logo_id, tj.is_share')
            ->where('tj.user_id = %d and t.id = tj.team_id and team_user_id = %d', $user_id, $user_id)
            ->select();
    }

    /**
     * 我发起的
     */
    public function getTeamInvited($user_id)
    {
        return $this
            ->table('fg_team_join tj, fg_team t')
            ->field('t.title, t.ctime, t.cover, t.id, t.logo_id, tj.is_share')
            ->where('tj.user_id = %d and t.id = tj.team_id and team_user_id <> %d', $user_id, $user_id)
            ->select();
    }

    /**, 'team_user_id = {$userid}'
     * 踢人
     */
    public function rejectJoin($data)
    {
        $result = $this
            ->where(array(
                'team_id' => $data['team_id'],
                'user_id' => $data['user_id']
            ))
            ->delete();
        if ($result)
            return qc_json_success();
        return qc_json_error();
    }

    /**
     * 参与者头像
     */
    public function getJoinUserInfo($team_id)
    {
        return $this
            ->table('fg_team_join tj, fg_user u')
            ->field('tj.user_id, u.head_path, u.username, u.sex')
            ->where('tj.user_id = u.id and team_id = %d', $team_id)
            ->select();
    }

    /**
     * 删除活动
     */
    public function deleteJoin($id, $user_id = null)
    {
        if ($user_id)
            return $this->where(array(
                'team_id' => $id, 'user_id' => $user_id
            ))->delete();
        return $this->where(array('team_id' => $id))->delete();
    }

    /**
     * 检验是否有新提醒
     */
    public function checkNotice($user_id)
    {
        $exist = $this->where(array('team_user_id' => $user_id, 'is_read' => 0))->find();
        if($exist)
            return true;
        return false;
    }

    /**
     * 获取提醒信息
     */
    public function getNotice($user_id)
    {
        return $this
            ->table('fg_team_join tj, fg_user u')
            ->field('tj.team_id, tj.user_id, tj.phone, u.username, u.head_path, u.sex')
            ->where(array('team_user_id' => $user_id, 'is_read' => 0))
            ->where('u.id = tj.user_id')
            ->select();
    }

    /**
     * 标记活动已分享
     */
    public function markActShared($team_id, $userid) {
        return $res = $this
            ->where(array('teamid' => $team_id, 'userid' => $userid))
            ->setField(array('is_share' => 1));
    }
}
