<?php
/**
 * User: lyt123
 * Date: 2016/9/7  20:41
 */
namespace Common\Model;

class TeamPicModel extends CURDModel
{
    protected $_validate = array(
        array('picture', '', 'picture field is null', 0, 'notequal'),
    );
    protected $resourceFields = array('picture');
}