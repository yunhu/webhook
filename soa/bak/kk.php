<?php
header("Content-Type: text/html; charset=UTF-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT |E_NOTICE |E_WARNING);
include './Mongo.class.php';
$json = file_get_contents('./bak/tiger.txt');
$data = json_decode($json,true);
var_dump($data);
$user = find(array('status'=>0));
var_dump($user);
//die;
//是否发布tag
if(strpos($data['ref'], 'tags')){
echo '2';
        //是否包含online，如果有的话，刚自动上线
        if (strpos($data['ref'], 'online')){
                //检查用户名是否允许
                if(in_array($data['user_name'], find(array('status'=>0)))){
                        //触发上线脚本
//                      var_dump(system("bash/update.sh"));
                        //此次上线内容写入mongo日志,防止后期出现问题查找问题
//                      mongo($json);
                }
        }
}


