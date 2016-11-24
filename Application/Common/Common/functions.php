<?php
/**
 * User: lyt123
 * Date: 2016/7/23  21:04
 */
function qc_json_error($msg = 'operate error', $error_code = 40000){
    return array('error_code' => $error_code,'msg' => $msg);
}

function qc_json_success($data = 'operate successfully', $code = 20000){
    return array('code' => $code,'response' => $data);
}

function qc_json_error_request($data = 'request method error', $code = 40001){
    return array('code'=> $code, 'response'=>$data);
}

/**
 * 发送短信验证码
 */
function sendMsg($phone) {
    $ch = curl_init();

    //生成验证码内容
    $security_code = rand(10000,999999);
    $content = '【趣组队】您收到的验证码是：'.$security_code.'，请勿告诉他人，5分钟内有效';

    $url = 'http://apis.baidu.com/kingtto_media/106sms/106sms?mobile='.$phone.'&content='.$content.'&tag=2';

    // apikey从106短信购买
    $header = array(
        'apikey: use your own apikey',
    );

    // 执行HTTP请求
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_URL , $url);
    $result = curl_exec($ch);

    //处理结果
    $result = json_decode($result, true);
    if($result['returnstatus'] == 'Success') {
        //记录当前时间戳以后续验证验证码是否超过设置的时间
        session('send_time', NOW_TIME);
        //设置验证码session值
        session('security_code', $security_code);
        return qc_json_success('发送成功,请注意查收');
    }
    return qc_json_error('发送失败');
}

/**
 *验证短信验证码有效性
 * @param $security_code
 * @return bool
 */
function test_code($security_code) {

    if(session('security_code') == $security_code/* && (NOW_TIME - session('send_time') <= 120)*/) {
        return true;
    }
    return false;
}

/**
 * 产生vote_for值
 * @param $res
 */
function createVotefor($res_time, $res_address, $voted) {
    $time_voted = explode('-', $voted[0]['time_voted']);
    $address_voted = explode('-', $voted[0]['address_voted']);
    foreach ($res_time as &$value) {
        if (in_array($value['id'], $time_voted)) {
            $value['vote_for'] = 1;
        } else {
            $value['vote_for'] = 0;
        }
    }

    foreach ($res_address as &$value) {
        if (in_array($value['id'], $address_voted)) {
            $value['vote_for'] = 1;
        } else {
            $value['vote_for'] = 0;
        }
    }

    return array($res_time, $res_address);
}

/**
 * 测试函数
 */
function dd($data = 'hahaa') {
    dump($data);
    exit();
}

/**
 * 过滤敏感词
 */
function filterWords($words) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'http://www.hoapi.com/index.php/Home/Api/check');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     //获取返回值
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('str' => $words)));
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);

    $result = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($result, 1);

    if ($result['status'] === false)
        return $result['data']['new_str'];
    return $words;
}

/**
 * 长文本换行显示
 * 参数：文本、每行字数、换行缩进长度
 */
function text_feed_line($text, $line_len = 10, $intent_len = 8) {
    if (mb_strlen($text) > $line_len) {

        $space_len = " ";
        for ($i = 0; $i<$intent_len; $i++) {
            $space_len .= " ";
        }

        $len = mb_strlen($text, 'utf8');
        $replace_num = ceil($len / $line_len);
        for ($i=1; $i<$replace_num; $i++) {
            $start_pos = $i*$line_len+($i-1)*11;
            $str1 = mb_substr($text,0,$start_pos,'utf-8');
            $str2 = mb_substr($text,$start_pos,100000,'utf-8');
            $text = $str1."\n".$space_len.$str2;
        }
    }
    return $text;
}

/**
 * 处理文字数据
 */
function generate_pic($data) {
    //长文本换行
    $data['title']   = text_feed_line($data['title']);
    $data['intro']   = text_feed_line($data['intro'], 15, 10);
    $data['address'] = text_feed_line($data['address']);

    //拼接文本
    $text  = "活动 ："  .$data['title'];
    $text .= "\n时间 ：".$data['starttime'];
    $text .= "\n时长 ：".$data['timelast'];
    $text .= "\n地点 ：".$data['address'];
    $text .= "\n人数 ：".$data['join_num'];
    $text .= $data['intro']? "\n详情 ：".$data['intro']: '';

    //处理图片参数
    $data['text_pos']   = $data['text_pos'] ? $data['text_pos'] : [50, 50];
    $data['font_type']  = ROOT_PATH."Public/textPic/fonts/".$data['font_type'];
    $data['bg_pic']     = ROOT_PATH."Public/textPic/backgrounds/".$data['bg_pic'];
    $save_file          = "Public/share_act/text_pic_tmp/".rand(1, 100000).".png";

    $status = draw_pic(
        $data['bg_pic'], $data['font_color'], $data['font_size'],
        $data['font_type'], $text,
        $data['text_pos'], $save_file
    );
    if ($status)
        return $save_file;
    return false;
}

/**
 * 生成文字图片
 */
function draw_pic($bg_pic, $font_color, $font_size, $font_type, $text, $text_pos, $save_file) {
    $img   = imagecreatefromjpeg($bg_pic);
    $color = imagecolorallocate($img, $font_color[0], $font_color[1], $font_color[2]);
    imagettftext(
        $img,
        $font_size, 0,
        $text_pos[0], $text_pos[1],
        $color,
        $font_type,
        $text
    );
    header("Content-Type: image/png");
    $status = imagepng($img, $save_file);
    imagedestroy($img);
    return $status;
}

/**
 * 获取数组中指定值组合
 */
function get_data_in_array(array $data, array $keys)
{
    $result = array();
    foreach ($keys as $key) {
        if(isset($data[$key]))
            $result[$key] = $data[$key];
    }
    return $result;
}

/**
 * 删除文件
 */
function delete_files($data, $multi_row = false)
{
    if(is_array($data)) {
        if($multi_row) {
            foreach ($data as $value) {
                $dir = ROOT_PATH.current($value);
                if(file_exists($dir))
                    unlink($dir);
            }
        }
        else {
            foreach ($data as $value) {
                $dir = ROOT_PATH.$value;
                if(file_exists($dir))
                    unlink($dir);
            }
        }
    } else {
        $dir = ROOT_PATH.$data;
        if(file_exists($dir))
            unlink($dir);
    }
}

/**
 * 密码加密
 */
function encrypt_password($password)
{
    return md5(sha1($password));
}

/**
 * 获取ip地址的位置
 */
function ip_place($ip)
{
    $Ip = new \Org\Net\IpLocation();
    $data = $Ip->getlocation($ip);
    return $data['ip'].$data['country'];
}
