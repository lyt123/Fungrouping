<?php
/**
 * User: lyt123
 * Date: 2016/9/28  21:56
 */

namespace Common\Model;


class UserIntroModel extends CURDModel
{
    protected $_validate = array(
        array('resident', '1,50', '内容过长', 0, 'length'),
        array('profession', '1,50', '内容过长', 0, 'length'),
        array('constellation', '1,10', '内容过长', 0, 'length'),
        array('blood_group', '1,5', '内容过长', 0, 'length'),
        array('self_intro', '1,25', '内容过长', 0, 'length'),
    );
}