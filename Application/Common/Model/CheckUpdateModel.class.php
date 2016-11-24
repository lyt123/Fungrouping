<?php
/**
 * User: clanner
 * Date: 2016/11/4
 */
namespace Common\Model;

use Think\Model\AdvModel;

class CheckUpdateModel extends AdvModel
{
    private $currentVersionCode = "1.3.11";
    /**
     * 下载apk
     */
    function downloadApk()
    {
        $file_name = "Public/apk/FunGrouping.apk";
        if (!file_exists($file_name)) return qc_json_error("文件不存在");
        $fp = fopen($file_name, "r");
        $file_size = filesize($file_name);

        //返回的文件
        header("Content-type: application/octet-stream");
        //按字节大小返回
        header("Accept-Ranges: bytes");
        //返回文件大小
        header("Accept-Length: $file_size");
        //这里客户端的弹出对话框，对应的文件名
        header("Content-Disposition: attachment; filename=" . "FunGrouping.apk");

        //向客户端回送数据
        $buffer = 1024;
        $file_count = 0;
        while (!feof($fp) && $file_size - $file_count > 0) {
            $file_data = fread($fp, $buffer);
            //统计读了多少个字节
            $file_count += $buffer;
            //把部分数据回送给浏览器
            echo $file_data;
        }

        //关闭文件
        fclose($fp);
    }

    /**
     * 检查更新
     */
    function checkUpdate($data){
        if($data['versionCode'] === $this->currentVersionCode) {
            return qc_json_error("this is the lastest version");
        }else{
            return qc_json_success("the lastest version is".$this->currentVersionCode);
        }
    }
}