<?php
	include './mongo.php';
	/**
	  *@desc server项目webhook接口文件 
	  *@author zhangyh
	  */
	$json = file_get_contents('php://input');
	$data = json_decode($json, true);
        //判断uid,user_name project_id
        if(!$data['user_id'] || !$data['user_name'] || !$data['project_id']){
		return false;
        }

//是否发布tag
if(strpos($data['ref'], 'tags')){
        //是否包含online，如果有的话，刚自动上线
        if (strpos($data['ref'], 'online')){
                //检查用户名是否允许
                if(in_array($data['user_name'], find(array('status'=>0)))){
                        //触发上线脚本
                        system("bash/update.sh");
                        //此次上线内容写入mongo日志,防止后期出现问题查找问题
                        mongo($json);
                }
        }
	if (strpos($data['ref'], 'beta')){
                if(in_array($data['user_name'], find(array('status'=>0)))){
			
		}

	}
	if (strpos($data['ref'], 'bak')){
                if(in_array($data['user_name'], find(array('status'=>0)))){
		}

	}
}

	
?>
