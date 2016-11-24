<?php
/**
 * User: clanner
 * Date: 2016/11/4
 */
namespace Home\Controller;

use Common\Controller\BaseController;
use Common\Model\CheckUpdateModel;

class CheckUpdateController extends BaseController
{
    private $updateModel;

    public function __construct()
    {
        $this->updateModel = new CheckUpdateModel();
    }

    /**
     * 下载apk
     */
    public function downloadApk(){
        $this->ajaxReturn($this->updateModel->downloadApk());
    }

    /**
     * 检查更新
     */
    public function checkUpdate(){
        $this->reqPost(array('versionCode'));
        $this->ajaxReturn($this->updateModel->checkUpdate(I('post.')));
    }
}