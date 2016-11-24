<?php
/**
 * User: lyt123
 * Date: 2016/9/13  19:16
 */
namespace Common\Model;

class PlaceModel extends CURDModel
{
    protected $_validate = array(
        array('name', '1,64', 'name too long', 0, 'length'),
        array('intro', '1,256', 'intro too long', 0, 'length'),
        array('clerk', '1,10', 'clerk too long', 0, 'length'),
        array('phone', '1,20', 'phone too long', 0, 'length'),
    );

    protected $readonlyField = array('id', 'ctime');

    protected $resourceFields = array('show_picture');

    public function getList($data)
    {
        return $this
            ->field('intro, name, show_picture, id')
            ->where(array('type_id' => $data['type_id']))
            ->order('id asc')
            ->limit(($data['page']-1)*$data['limit'], $data['limit'])
            ->select();
    }
}
