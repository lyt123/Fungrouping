<?php
namespace Common\Model;

/**
 * User: lyt123
 * Date: 2016/8/10  10:17
 */
class ShareActModel extends BaseModel {
    protected $_validate = array(
        array("title",'','分享活动标题不能为空',self::EXISTS_VALIDATE,'notequal')
    );

    /**
     * 创建活动
     */
    public function addShareAct($data) {
        if(!$this->create($data)) {
            return qc_json_error($this->getError());
        }

        $res = $this->add();
        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_error("分享活动添加失败");

    }

    /**
     * 删除活动
     */
    public function deleteShareAct($id) {
        $res = $this
            ->where($id)
            ->delete();
        if ($res) {
            return qc_json_success();
        }
        return qc_json_error("从share_act表删除失败");
    }

    /**
     * 将上传的分享活动的图片保存到数据库
     */
    public function addShareActPic($data) {
        $res = M("ShareActPic")->addAll($data);
        if ($res)
            return qc_json_success("添加图片成功");
        return qc_json_error("添加图片失败");
    }

    /**
     * 将上传的分享活动的图片从数据库删除
     */
    public function deleteShareActPic($id) {
        $share_act_pic_model = M("ShareActPic");

        $file = $share_act_pic_model
            ->field('picture_path')
            ->where(array('share_act_id' => $id))
            ->select();
        foreach ($file as $value) {
            @unlink($value['picture_path']);
        }
        $res = $share_act_pic_model
            ->where(array('share_act_id' => $id))
            ->delete();

        if ($res) {
            return qc_json_success();
        }

        return qc_json_error("删除图片失败");
    }

    /**
     * 获取分享的活动列表
     */
    public function shareActList($ignore_act, $page, $limit) {
        if ($ignore_act) {
            $this->where(array('id' => array('NOTIN', $ignore_act)));
        }
        $res = $this
        ->field('id, title, head_path, comments, collects, ctime, cover')
        ->order("ctime desc")
        ->limit(($page-1)*$limit, $limit)
        ->select();

        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_success(array());
    }

    /**
     * 获取分享活动详细信息
     */
    public function getShareActDetail($id) {
        $res = $this
            ->field('id, title, feeling, head_path, userid, cover, logo_id')
            ->where(array('id' => $id))
            ->select();

        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_success(array());
    }

    /**
     * 获取我的分享活动列表
     */
    public function shareActListSelf($share_act_info, $page, $limit) {
        $res = $this
            ->field('id, title, ctime, comments, collects, cover')
            ->order("ctime desc")
            ->limit(($page-1)*$limit, $limit)
            ->select($share_act_info);
        if ($res) {
            return qc_json_success($res);
        }
        return qc_json_success(array());
    }

    /**
     * 获取活动发布人id
     */
    public function getUserId($actid) {
        $userid = $this
            ->field('userid')
            ->where(array('id' => $actid))
            ->select();
        return $userid;
    }

    public function getActInfo($actid, $field) {
        $info = $this
            ->field($field)
            ->where(array('id' => $actid))
            ->select();
        return $info;
    }

    public function addComment($actid) {
        $res = $this
            ->where(array('id' => $actid))
            ->setInc('comments', 1);
        return $res;
    }

    public function addCollect($actid) {
        $res = $this
            ->where(array('id' => $actid))
            ->setInc('collects', 1);
        return $res;
    }

    public function decCollect($actid) {
        $res = $this
            ->where(array('id' => $actid))
            ->setDec('collects', 1);
        return $res;
    }

    public function getPicInfo($actid) {
        $res = M('ShareActPic')
            ->field('picture_path, id')
            ->where(array('share_act_id' => $actid))
            ->select();
        return $res;
    }

    public function shareActEdit($data) {
        if ($this->create($data)) {
            $res = $this->where(array('id' => $data['id']))->save();
            if ($res !== false) {
                return $res;
            }
        }
        return $this->getError();
    }

    /**
     * 编辑活动-修改分享活动内容
     */
    public function EditShareActPic($id) {

        $share_act_pic_model = M("ShareActPic");

        $ids = explode(',', $id);

        foreach ($ids as $value) {
            $file = $share_act_pic_model
                ->field('picture_path')
                ->where(array('id' => $value))
                ->select();

            $status = @unlink(ROOT_PATH.$file[0]['picture_path']);
            if (!$status)
                return false;
        }
        $res = $share_act_pic_model->delete($id);
        if ($res != 0)
            return $res;
        return false;
    }

    /**
     * 编辑分享的活动-删除封面图
     */
    public function changeCover($actid, $path) {
        return $this->where(array('id' => $actid))
            ->setField(array('cover' => $path));
    }

    /**
     * 将分享活动的图片和封面图全部删除
     */
    public function deleteShareActPhotos($actid) {
        $res = $this
            ->field('cover')
            ->where(array('id' => $actid))
            ->find();

        unlink($res['cover']);
        rmdir(dirname($res['cover']));

        $res = M('ShareActPic')
            ->field('picture_path')
            ->where(array('share_act_id' => $actid))
            ->select();

        M('ShareActPic')->where(array('share_act_id' => $actid))->delete();

        foreach ($res as $value) {
            unlink($value['picture_path']);
        }
        rmdir(dirname($res[0]['picture_path']));

        rmdir(dirname(dirname($res[0]['picture_path'])));



        return qc_json_success('删除图片成功');
    }
}