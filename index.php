<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/7
 * Time: 20:42
 */
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_DEBUG',true);

define('APP_PATH','./Application/');

define('ROOT_PATH', dirname(__FILE__).'/');

//配置信任的跨域来源
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

$allow_origin = array(
    'http://localhost',
    'http://119.29.121.240'
);
//access-control-allow-origin的
if(in_array($origin, $allow_origin)){
    header('Access-Control-Allow-Origin:'.$origin);
}
//header('Access-Control-Allow-Origin:http://localhost ');
//配置允许发送认证信息 比如cookies（会话机制的前提）
header('Access-Control-Allow-Credentials: true');
//过期时间
header('Access-Control-Max-Age: 120000');
require './ThinkPHP/ThinkPHP.php';
















