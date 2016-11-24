<?php
/**
 * User: lyt123
 * Date: 2016/9/7  20:41
 */
namespace Common\Model;

class TeamModel extends CURDModel
{
    protected $_validate = array(

        array('title', '1,256', '标题过长', 0, 'length'),
        array('intro', '1,2048', '详情过长', 0, 'length'),
        array('phone', '1,20', '电话号码过长', 0, 'length'),
        array('group_num', '1,20', '讨论组群号过长', 0, 'length'),
    );
    protected $resourceFields = array('cover');

    public function getList($page = 1, $limit = 5, $srh_string, $city_id = null)
    {
        if($srh_string) {
            $map['title'] = array('like', '%'.$srh_string.'%');
            $this->where($map);
        }

        if($city_id)
            $this->where('t.user_id = u.id and t.city_id = '.$city_id);
        else
            $this->where('t.user_id = u.id');

        return $this
            ->table('fg_team t, fg_user u')
            ->field('t.*, u.head_path')
            ->order("ctime desc")
            ->limit(($page - 1) * $limit, $limit)
            ->select();
    }
}
