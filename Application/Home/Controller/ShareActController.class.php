<?php
namespace Home\Controller;

use Common\Controller\BaseController;

/**
 * User: lyt123
 * Date: 2016/7/25  16:28
 */

class ShareActController extends BaseController {
    /**
     * 获取过期活动详细信息
     */
    public function toShareActDetail() {
        $post_data = $this->reqLogin()->reqPost(array(), array('id', 'team_id'));

        if($post_data['id']) {
            $res = D('Act')->findact($post_data['id'], array('id', 'title', 'intro', 'join_num'));
            if (!$res) {
                $this->ajaxReturn(qc_json_error("活动已被删除"));
            }
            $share_act_info = $res;

            $res = D('ActTime')->getActtime($share_act_info['id']);
            $share_act_info['starttime'] = $res[0]['starttime'];
            $share_act_info['timelast'] = $res[0]['timelast'];

            $res = D('ActAddress')->getActaddress($share_act_info['id']);
            $share_act_info['address'] = $res[0]['address'];
        } else {
            $share_act_info = D('Team')->getData(array('id' => $post_data['team_id']), array('id', 'title', 'num_join', 'intro', 'starttime', 'timelast', 'address'));

            //填坑
            $share_act_info['join_num'] = $share_act_info['num_join'];
            unset($share_act_info['num_join']);
        }

        $this->ajaxReturn(qc_json_success($share_act_info));
    }

    /**
     * 发布分享的活动--（重新）生成文字图片
     */
    public function generatePic() {
        $data = $this->reqLogin()->reqPost(
            array(
                'title',     'join_num', 'starttime',
                'timelast',  'address',  'pic_background'
            ),
            array('intro')
        );

        //重新生成图片时删除原图片
        if ($dir = session('text_pic'))
            if (file_exists($dir))
                unlink($dir);

        if ($data['pic_background'] == 1) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '1.jpg';
            $data['text_pos']   = array(50, 50);
        } elseif($data['pic_background'] == 2) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '2.jpg';
            $data['text_pos']   = array(50, 50);
        } elseif($data['pic_background'] == 3) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '3.jpg';
            $data['text_pos']   = array(50, 50);
        } elseif($data['pic_background'] == 4) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '4.jpg';
            $data['text_pos']   = array(50, 50);
        } elseif($data['pic_background'] == 5) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '5.jpg';
            $data['text_pos']   = array(50, 50);
        } elseif($data['pic_background'] == 6) {
            $data['font_color'] = array(18, 15, 15);
            $data['font_type']  = 'simkai.ttf';
            $data['font_size']  = 40;
            $data['bg_pic']     = '6.jpg';
            $data['text_pos']   = array(50, 50);
        }

        if ($res = generate_pic($data)) {
            session('text_pic', $res);
            $this->ajaxReturn(qc_json_success($res));
        }
        $this->ajaxReturn(qc_json_error()); 
    }

    /**
     * 发布分享的活动share_act_id,picture_path
     */
    public function shareActRelease() {
        $data = $this->reqLogin()->reqPost(array('title', 'feeling'));

        $data['title'] = filterWords($data['title']);
        $data['feeling'] = filterWords($data['feeling']);

        $data['ctime'] = NOW_TIME;
        $data['userid'] = session('user')['id'];

        $data['head_path'] = D('User')->getUserInfo($data['userid'], array('head_path'))[0]['head_path'];

        $share_act_model = D('ShareAct');
        $share_act_model->startTrans();

        $share_act_id = $share_act_model->addShareAct($data)['response'];

        $add_share_act = $share_act_model->changeCover(
            $share_act_id,
            strstr(
                $this->reqPost(array('cover'))['cover'], 'text_pic_tmp/', true).
                $share_act_id.'/cover.png'
            );

        if ($add_share_act) {

            if ($_FILES) {
                $res = $this->uploadPictures($share_act_id, 'share_act_photo', true);

                //处理上传图片后的结果
                if(!is_array($res) && count($res['success_array'])) {
                    $share_act_model->rollback();
                    $share_act_model->commit();
                    $this->ajaxReturn(qc_json_error("图片太大"));//这里
                }
                $save_data = array();

                foreach($res['success_array'] as $key => $val) {

                    $save_data[$key]['picture_path'] = $val['url'];
                    $save_data[$key]['share_act_id'] = $share_act_id;
                }

                $res = $share_act_model->addShareActPic($save_data);
            }

            if ($res['code'] == 20000) {
                $res = D('ShareActHandle')->editShareActRelease($data['userid'], $share_act_id, 'add');

                if ($res['code'] == 20000) {
                    if(empty($this->reqPost(array(), array('id')))) {
                        $mark_res = D('TeamJoin')->markActShared($this->reqPost(array('team_id'))['team_id'], $data['userid']);
                    } else {
                        $mark_res = D('Joinact')->markActShared($this->reqPost(array('id'))['id'], $data['userid']);
                    }
                    $des_file = ROOT_PATH."Public/share_act/".$share_act_id."/cover.png";
                    $move_text_pic = rename(session('text_pic'), $des_file);

                    if ($mark_res && $move_text_pic) {
                        session('text_pic', null);
                        $share_act_model->commit();
                        $this->ajaxReturn(qc_json_success());
                    }
                }
            }
        }
        $share_act_model->rollback();
        $share_act_model->commit();
        $this->ajaxReturn(qc_json_error($res));
    }

    /**
     * 精彩活动列表
     */
    public function shareActList($page = 1, $limit = 20) {
        $userid = session('user')['id'];

        if ($userid) {
            $ignore_act = current(
                D('ShareActHandle')->getActField($userid, array('share_act_ignore'))
            );

            $res = D("ShareAct")->shareActList($ignore_act, $page, $limit);

            $user_collected = D('ShareActHandle')->getCollect($userid);
            $user_collected = explode(',', $user_collected);
        } else {
            $res = D('ShareAct')->shareActList(false, $page, $limit);
        }

        //无数据返回空数组
        if(!$res) $this->ajaxReturn(qc_json_success(array()));

        //未登录 is_collected为0
        foreach ($res['response'] as &$item) {
            if (isset($user_collected) && in_array($item['id'], $user_collected)) {
                $item['is_collected'] = 1;
            } else $item['is_collected'] = 0;
        }
        $this->ajaxReturn($res);
    }

    /**
     * 分享的活动详细信息
     */
    public function shareActDetail($id) {
        $this->reqLogin();

        $res = D('ShareAct')->getShareActDetail($id);

        $photos = M('ShareActPic')
            ->field('picture_path')
            ->where(array('share_act_id' => $id))
            ->select();

        foreach ($photos as &$photo) {
            $photo = $photo['picture_path'];
        }
        unset($photo);

        $res['response'][0]['photo'] = $photos;
        $res['response'] = $res['response'][0];

        $user_collected = D('ShareActHandle')->getCollect(session('user')['id']);
        $user_collected = explode(',', $user_collected);

        if (in_array($id, $user_collected))
            $res['response']['is_collected'] = 1;
        else $res['response']['is_collected'] = 0;

        $this->ajaxReturn($res);
    }

    /**
     * 删除我的分享活动
     */
    public function shareActDelete() {
        $post_data = $this->reqLogin()->reqPost(array('id'));
        $userid = session('user')['id'];

        $share_act_model = D('ShareAct');
        $share_act_model->startTrans();
        $res = $share_act_model->deleteShareActPhotos($post_data['id']);

        if ($res['code'] == 20000) {
            $res = $share_act_model->deleteShareAct($post_data);

            if ($res['code'] == 20000) {
                $res = D('ShareActHandle')->editShareActRelease($userid, $post_data['id'], 'delete');

                if ($res['code'] == 20000) {
                    $share_act_model->commit();

                    $this->ajaxReturn(qc_json_success("删除我的分享活动成功"));
                }
            }
        }
        $share_act_model->rollback();
        $share_act_model->commit();
        $this->ajaxReturn($res);
    }

    /**
     * 待编辑的分享活动
     */
    public function shareActToEdit() {
        $data = $this->reqLogin()->reqPost(array('id'));
        $act_info = D('ShareAct')->getActInfo($data['id'], array('title', 'feeling', 'cover'))[0];
        $pic_info = D('ShareAct')->getPicInfo($data['id']);

        $res['id'] = $data['id'];
        $res['title'] = $act_info['title'];
        $res['feeling'] = $act_info['feeling'];
        $res['cover'] = $act_info['cover'];
        $res['pictures'] = $pic_info;

        $this->ajaxReturn(qc_json_success($res));
    }

    /**
     * 修改分享活动内容
     */
    public function shareActEdit() {
        $data = $this->reqLogin()->reqPost(array('id', 'title', 'feeling'));
        $data['title'] = filterWords($data['title']);
        $data['feeling'] = filterWords($data['feeling']);

        //修改分享活动内容
        $share_act_model = D('ShareAct');
        $res = $share_act_model->shareActEdit($data);
        if (!is_int($res)) {
            $this->ajaxReturn(qc_json_error($res));
        };

        //上传新图片
        if ($_FILES) {
            $res = $this->uploadPictures($data['id'], 'share_act_photo', true);

            //处理上传图片后的结果
            if(!is_array($res) && count($res['success_array'])) {
                $this->ajaxReturn(qc_json_error($res));
            }
            $save_data = array();
            foreach($res['success_array'] as $key => $val) {

                $save_data[$key]['picture_path'] = $val['url'];
                $save_data[$key]['share_act_id'] = $data['id'];
            }

            //将新图片数据保存到数据库中
            $res = $share_act_model->addShareActPic($save_data);
            if ($res['code'] == 40000)
                $this->ajaxReturn(qc_json_error("新上传图片数据保存到数据库失败"));
        }

        //删除图片
        if ($this->reqPost(array(),array('delete_pics'))) {

            $res = $share_act_model->EditShareActPic(current($this->reqPost(array('delete_pics'))));
            if (!$res)
                $this->ajaxReturn(qc_json_error("删除图片失败"));
        }

        $this->ajaxReturn(qc_json_success("编辑活动成功"));
    }

    /**
     * 编辑分享活动-更换封面图
     */
    public function shareActChangeCover() {
        $data = $this->reqLogin()->reqPost(array('id', 'pic_url'));

        $des_file = ROOT_PATH."Public/share_act/".$data['id']."/cover.png";
        $move_text_pic = rename(session('text_pic'), $des_file);
        if (
            $move_text_pic /*&&
            D('ShareAct')->changeCover
            (
                $data['id'],
                strstr($data['pic_url'], 'text_pic_tmp/', true). $data['id'].'/cover.png'
            )*/
        ) {
            $this->ajaxReturn(qc_json_success());
        }
        $this->ajaxReturn(qc_json_error());

    }

    /**
     * 分享活动列表-不感兴趣
     */
    public function ignoreShareAct() {
        $data = $this->reqLogin()->reqPost(array('actid'));
        $userid = session('user')['id'];
        $data['ignore_act'] = current(
            D('ShareActHandle')->getActField($userid, array('share_act_ignore'))
        );
        $ignore_act = implode(',', $data);

        $res = D('ShareActHandle')->updateActField($userid, $ignore_act);

        if($res !== false)
            $this->ajaxReturn(qc_json_success());
        $this->ajaxReturn(qc_json_error());
    }
}