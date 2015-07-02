<?php
define('ROOT', __DIR__);
include ROOT . '/../common/Mongo.class.php';
use common\Mongo;
	
	/**
	  *@desc server项目webhook接口文件 
	  *@author zhangyh
	  */
	$json = file_get_contents('php://input');
	//file_put_contents('online.txt', $json);
//$json=file_get_contents('online.txt');
	$data = json_decode($json, true);
        //判断uid,user_name project_id
        if(!$data['user_id'] || !$data['user_name'] || !$data['project_id']){
		return false;
        }


//是否发布tag
if(strpos($data['ref'], 'tags')){
	$mongo = new common\Mongo();
	$mongo->select_db('soa');

	$res = $mongo->find('user', array('status'=>0));
	foreach ($res as $key=>$val) {
		$user[] = $val['name'];
	}
        if(!in_array($data['user_name'], $user)){
                $mongo->insert('errorlog', array('content'=>$json, 'time'=>time(), 'name'=>$user, 'msg'=>'you cant access'));
		return false;
	}

	// 通过后刚初始化mongo
	

	 if (strpos($data['ref'], 'sim')){
                      $mongo->insert('log', array('content'=>$json, 'time'=>time(), 'type'=>'sim', 'name'=>$user));
                                      system("bash/sim");
      
         }
	 if (strpos($data['ref'], 'online')){
                      $mongo->insert('log', array('content'=>$json, 'time'=>time(), 'type'=>'online', 'name'=>$user));
                                      system("bash/online");
      
	}


}


	
?>
