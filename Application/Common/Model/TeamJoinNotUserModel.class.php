<?php
/**
 * User: lyt123
 * Date: 2016/9/10  9:48
 */
namespace Common\Model;

class TeamJoinNotUserModel extends CURDModel
{
    protected $_validate = array(
        array('username', '1,24', '用户名过长', 0, 'length'),
        array('phone', '1,20', '电话号码过长', 0, 'length'),
    );
    protected $resourceFields = array('head_path');

    public function deleteJoin($team_id)
    {
        $result = $this->getData(array('team_id' => $team_id), array('id'), true);
        foreach ($result as $item) {
            if (!$this->destroy(current($item)))
                return false;
        }
        return true;
    }

    public function getJoinNotuserInfo($team_id, array $field = null, $key = 'team_id')
    {
        return $this
            ->field($field)
            ->where(array($key => $team_id))
            ->select();
    }

    public function checkNotice($user_id)
    {
        $exist = $this->where(array(
            'team_user_id' => $user_id,
            'is_read' => 0
        ))->find();

        if ($exist)
            return true;
        return false;
    }
}
