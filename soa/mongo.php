<?php

function mongo($json) {
        $con  = new MongoClient("172.16.1.124:30000");
        $db   = $con->git;//使用git库
        $res  = $db->online;//使用online表
        $data = array('time'=>time(), 'content'=>$json);
        return $res->insert($data);
}
function find($arr, $coll='user'){
        $con  = new MongoClient("172.16.1.124:30000");
        $db  = $con->soa;
        $res = $db->$coll;
        $user = array();
        $cursor= $res->find($arr);
        foreach ($cursor as $cur ) {
                $user[] = $cur['name'];
        }

        return $user;

}
//var_dump(find(array('type'=>'beta','commit'=>'872b9e251d211280de9a1d9ded5555de99883abe'), 'record'));
function insert($data) {
        $con  = new MongoClient("172.16.1.124:30000");
        $db   = $con->soa;//使用soa库
        $res  = $db->user;//使用user表
        //$data = array('name'=>'zhangyh', 'status'=>0); //0可以上线，1禁止
        return $res->insert($data);

}
function record($data) {
        $con  = new MongoClient("172.16.1.124:30000");

        $db   = $con->soa;//使用soa库
        $res  = $db->record;//使用user表
        return $res->insert($data);

}
insert(array('name'=>'liuaj','status'=>0));
//record(array('stable'=>'10.0.201.106', 'bak'=>'10.0.201.107', 'beta'=>'10.0.201.112', 'type'=>'beta','commit'=>'872b9e251d211280de9a1d9ded5555de99883abe'));
//type 分为beta,stable,bak
