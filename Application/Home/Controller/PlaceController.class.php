<?php
/**
 * User: lyt123
 * Date: 2016/9/13  19:14
 */
namespace Home\Controller;

use Common\Controller\BaseController;

class PlaceController extends BaseController
{
    public function addPlace()
    {
        $data = $this->reqLogin()->reqPost(array(
            'name',  'intro',   'phone',  'region',
            'clerk', 'address', 'type_id'
        ));

        $data['ctime'] = date('Y-m-d H:i:s');

        $upload_result = $this->uploadPictures($data['type_id'], 'place');
        $data['show_picture'] = 'Public/'.$upload_result['savepath'].$upload_result['savename'];

        $this->ajaxReturn(D('Place')->addOne($data));
    }

    public function listPlace()
    {
        $data = $this->reqLogin()->reqPost(array('type_id'), array('page', 'limit'));

        $data['page'] = $data['page']? $data['page']: 1;
        $data['limit'] = $data['limit']? $data['limit']: 8;

        $this->ajaxReturn(qc_json_success(D('Place')->getList($data)));
    }

    public function PlaceDetail()
    {
        $data = $this->reqLogin()->reqPost(array('id'));

        $this->ajaxReturn(qc_json_success(D('Place')->getData($data)));
    }
}
