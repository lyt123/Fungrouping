<?php
namespace Common\Controller;
/**
 * User: lyt123
 * Date: 2016/7/23  20:58
 */

use Think\Controller;

class BaseController extends Controller {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 检验post参数
     */
    protected function reqPost(array $require_data = null, array $unnecessary_data = null) {
        if (! IS_POST) {
            $this->ajaxReturn(qc_json_error_request());
        }
        $data = array();
        if ($require_data) {
            foreach ($require_data as $key) {
                $_k = I('post.' . $key, null);//过滤xss攻击
                if (is_null($_k)) {
                    $this->ajaxReturn(qc_json_error_request("require params: " . $key." value"));
                }
                if (I('post.'.$key) == '') {
                    if(I('post.'.$key) == '')
                        $this->ajaxReturn(qc_json_error_request("必填信息不能为空！"));
                }
                $data[$key] = $_k;
            }
        }
        if ($unnecessary_data) {
            foreach ($unnecessary_data as $key) {
                $_k = I('post.'.$key, null);
                if(!is_null($_k)) {
                    $data[$key] = $_k;      //非空则加入,即前端有post该字段
                }
            }
        }
        return $data;
    }

    /**
     * 判断是否已登陆
     */
    protected function reqLogin() {
        if (session("?user") || session('admin.id')) {
            return $this;
        }
        $this->ajaxReturn(qc_json_error("no login"));
    }

    /**
     * @param $fileName
     * @param $type
     * @param bool $multiple  单图false/多图true
     * @return array|mixed|string
     */
    protected function uploadPictures($fileName='undefined', $type, $multiple = false) {

        $upload = new \Think\Upload();                 // 实例化上传类
        $upload->exts = array('jpg', 'png', 'jpeg');   // 设置附件上传类型
        $upload->rootPath = './Public/';               // 根目录
        $upload->replace = true;                       // 覆盖同名文件
        $upload->autoSub = false;                      // 不自动子目录保存文件
        $upload->hash = false;                         // 不生成hash编码，提速

        switch($type) {                                // 定制配置

            case 'user_head':
                $upload->maxSize = 1048576;                 // 设置附件上传大小  1M
                $upload->saveName = array('uniqid', $fileName.'-');
                $upload->savePath = 'user/'.$fileName.'/user_head/';      // 设置附件上传目录
                break;
            case 'share_act_photo':
                $upload->saveRule = 'uniqid';
                $upload->maxSize = 2097152;                 // 设置附件上传大小  2M
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'share_act/'.$fileName.'/photos/';      // 设置附件上传目录
                break;
            case 'share_act_cover':
                $upload->saveRule = 'uniqid';
                $upload->maxSize = 2097152;                 // 设置附件上传大小  2M
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'share_act/'.$fileName.'/cover/';      // 设置附件上传目录
                break;
            case 'bg_pic':
                $upload->saveRule = 'uniqid';
                $upload->maxSize = 2097152;
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'share_act/bg_pic_tmp/'.$fileName;
                break;
            case 'team_pic':
                $upload->saveRule = 'uniqid';
                $upload->maxSize  = 2097152;
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'team/team_pic/'.$fileName.'/';
                break;
            case 'user_head_temp':
                $upload->saveRule = 'uniqid';
                $upload->maxSize  = 2097152;
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'team/notuser_head/';
                break;
            case 'place':
                $upload->saveRule = 'uniqid';
                $upload->maxSize  = 2097152;
                $upload->saveName = array('uniqid',$fileName.'-');
                $upload->savePath = 'place/';
                break;

            default: echo '$type错误！若需要,请自行扩展！';exit;
        }

        $count = 0;                              //记录上传的图片张数

        foreach($_FILES as &$file) {             //无后缀文件强制转化成就jpg文件
            if(is_array($file['name'])) {
                foreach($file['name'] as &$name) {
                    ++$count;
                    if (pathinfo($name, PATHINFO_EXTENSION) == '') $name .= '.jpg';
                }
            } else {
                ++$count;
                if (pathinfo($file['name'], PATHINFO_EXTENSION) == '') $file['name'] .= '.jpg';
            }
        }

        $info = $upload->upload();                //上传操作 false | array

        /* 上传成功返回数组，失败返回string */

        if(!$info) return $upload->getError();    //上传失败

        //拼接url
        $result_data = array();
        foreach($info as $key => $value) {
            $result_data[] = array(
                'key' => $value['key'],
                'url' => 'Public/'.$value['savepath'].$value['savename']
            );
        }

        if ($multiple) {                          //多图
            $success_count = count($info);
            return array(
                'success_count' =>  $success_count,                //上传成功的张数
                'error_count'   =>  $count - $success_count,       //上传失败的张数
                'success_array' =>  $result_data,                  //上传成功的信息数组
                'error_msg'     =>  $upload->getError()            //错误信息
            );
        }
        else {                                    //单图
            reset($info);
            return current($info);
        }
    }
}