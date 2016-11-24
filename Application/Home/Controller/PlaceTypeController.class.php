<?php
/**
 * User: lyt123
 * Date: 2016/9/13  20:04
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class PlaceTypeController extends BaseController
{
    public function addPlaceType()
    {
        $data = $this->reqLogin()->reqPost(array('name'));

        $this->ajaxReturn(D('PlaceType')->addOne($data));
    }

    public function listPlaceType()
    {
        $this->reqLogin();

        $this->ajaxReturn(qc_json_success(D('PlaceType')->getData(array(),array(), true)));
    }
}