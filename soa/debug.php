<?php
include "../index.php";
use common;
$mongo = new common\Mongo();
$mongo->select_db('soa');

#$mongo->select_collection('soa','user');
$data = array('stable'=>ip2long('10.0.201.106'), 'bak'=>ip2long('10.0.201.107'), 'beta'=>ip2long('10.0.201.112'), 'type'=>'beta', commit=>'fwfwfafafawfafafafssssa', 'time'=>time());
#var_dump($mongo->insert('record', $data));
$res = $mongo->find('record',array('type'=>'beta'),array('sort'=>array('time'=>-1),'limit'=>1));
var_dump($res);
die;

include './mongo.php';
$json = file_get_contents('./bak/tiger.txt');
$data = json_decode($json,true);
var_dump($data);
$user = find(array('status'=>0));
var_dump($user);
//die;
//是否发布tag
if(strpos($data['ref'], 'tags')){
	//是否包含online，如果有的话，刚自动上线
	if (strpos($data['ref'], 'online')){
		//检查用户名是否允许
		if(in_array($data['user_name'], find(array('status'=>0)))){
			//触发上线脚本			
//                    	var_dump(system("bash/update.sh"));
			//此次上线内容写入mongo日志,防止后期出现问题查找问题
//			mongo($json);
		}
	}
}

?>
