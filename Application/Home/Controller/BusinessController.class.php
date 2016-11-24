<?php
/**
 * User: lyt123
 * Date: 2016/9/13  19:14
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class BusinessController extends BaseController
{
    public function show() {
        $this->ajaxReturn(qc_json_success(array('slide_picture' => array(
            'Public/business/slideShow/1.jpg',
            'Public/business/slideShow/2.png',
            'Public/business/slideShow/3.png',
            'Public/business/slideShow/4.jpg',
        ))));
    }
}
