<?php
	/**
	  *@desc server项目webhook接口文件 
	  *@author zhangyh
	  */
	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
        //判断uid,user_name project_id
	//
        if(!$data['user_id'] || !$data['user_name'] || !$data['project_id']){
                throw new exception("access deny");
        }
	//git 自动更新
	system("bash/serverupdate.sh", $res);
	//文档自动更新	
	var_dump($res);

	
?>
