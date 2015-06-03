<?php
include '../index.php';
use namespace common;
	
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
        if(in_array($data['user_name'], find(array('status'=>0)))){
		return 'can't access to online';
	}

	// 通过后刚初始化mongo
	$mongo = new common\Mongo();
	$mongo->select_db('soa');
	
        $last = $mongo->find('record', array(), array('sort'=>array('time'=>-1), 'limit'=>1));
        //是否包含online，如果有的话，刚自动上线
        if (strpos($data['ref'], 'online')){
		// 去mongo里查下是否之前已经操作过,如果有的话说明上次上线可能意外造成部分机器切换失败，刚需要重新执行，根据查出来的记录即可
		$record = $mongo->find('record', array('type'=>'online', 'commit'=>$data['after']));
		if($record){
		//把ip写入文件
			file_put_contents('./ip/stable',long2ip($record[0]['stable']));
			file_put_contents('./ip/bak',long2ip($record[0]['bak']));
			file_put_contents('./ip/beta',long2ip($record[0]['beta']));
                	system("bash/stable");
                	system("bash/beta");
                	system("bash/bak");
		}else{
		//获取最后一条记录并更改主主次
 		//stable->bak,bak->beta, beta->stable
			file_put_contents('./ip/stable',long2ip($last[0]['beta']));
			file_put_contents('./ip/bak',long2ip($last[0]['stable']));
			file_put_contents('./ip/beta',long2ip($last[0]['bak']));
		//保存到mongo
			$insert['stable'] = $last[0]['beta'];
			$insert['bak'] = $last[0]['stable'];
			$insert['beta'] = $last[0]['bak'];
			$insert['time'] = time();
			$insert['type'] = 'online';
			$insert['commit'] = $data['after'];
			if ($mongo->insert('record', $insert)) {
				//些次上线也要写入日志
                		system("bash/stable");
                		system("bash/beta");
                		system("bash/bak");
                		system("bash/betaonline");
			}else{
				return 'insert error!please check mongo';
			}
		
		}
        }

	if (strpos($data['ref'], 'beta')){
		$record = $mongo->find('record', array('type'=>'beta', 'commit'=>$data['after']));
		if (!$record) {
			file_put_contents('./ip/beta',long2ip($last[0]['beta']));
			$insert['stable'] = $last[0]['stable'];
			$insert['bak'] = $last[0]['bak'];
			$insert['beta'] = $last[0]['beta'];
			$insert['time'] = time();
			$insert['type'] = 'beta';
			$insert['commit'] = $data['after'];
			if ($mongo->insert('record', $insert)) {
				//beta线上的生成	
                		system("bash/check");
			}else{
				return 'insert error!please check mongo';
			}
	
		}else{
			file_put_contents('./ip/beta',long2ip($last[0]['beta']));
                	system("bash/check");
		}

	}
	if (strpos($data['ref'], 'bak')){
		$record = $mongo->find('record', array('type'=>'bak', 'commit'=>$data['after']));
		if ($record) {
			//如果有记录，说明可能是意外造成部分回滚不成功，不需要插入记录，重新生成文件执行即可
				file_put_contents('./ip/stable',long2ip($last[0]['bak']));
				file_put_contents('./ip/bak',long2ip($last[0]['beta']));
				file_put_contents('./ip/beta',long2ip($last[0]['stable']));
                		system("bash/stable");
                		system("bash/beta");
                		system("bash/bak");
		}else{
			//如果没有记录,说明这次我要回溯
			//回滚原则，bak->stable,stable->beta,beta->bak
			$insert['stable'] = $last[0]['bak'];
			$insert['bak'] = $last[0]['beta'];
			$insert['beta'] = $last[0]['stable'];
			$insert['time'] = time();
			$insert['type'] = 'bak';
			$insert['commit'] = $data['after'];
			
			if ($mongo->insert('record', $insert)) {
				file_put_contents('./ip/stable',long2ip($last[0]['bak']));
				file_put_contents('./ip/bak',long2ip($last[0]['beta']));
				file_put_contents('./ip/beta',long2ip($last[0]['stable']));
                		system("bash/stable");
                		system("bash/beta");
                		system("bash/bak");
                		system("bash/bakonline");
					
			        		
			}else{
				return 'insert error!please check mongo';
			}
		}
		

	}
	$mongo->insert('log', $json);
}

	
?>
