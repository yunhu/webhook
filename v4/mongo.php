<?php

function connect () {
        $con  = new MongoClient("172.16.1.124:30000");
        return $con;
}

function mongo($json) {
        //$con  = connect();
        $con  = new MongoClient("172.16.1.124:30000");
        $db   = $con->v4;//使用git库
        $res  = $db->online;//使用online表
        $data = array('time'=>time(), 'content'=>$json);
        return $res->insert($data);
}
function find($arr){
        //$con = connect();
        $con  = new MongoClient("172.16.1.124:30000");
        $db  = $con->v4;
        $res = $db->user;
        $user = array();
        $cursor= $res->find($arr);
        foreach ($cursor as $cur ) {
                $user[] = $cur['name'];
        }

        return $user;

}

function insert($data) {
        //$con  = connect();
        $con  = new MongoClient("172.16.1.124:30000");
        $db   = $con->v4;//使用git库
        $res  = $db->user;//使用user表
        //$data = array('name'=>'zhangyh', 'status'=>0); //0可以上线，1禁止
        return $res->insert($data);

}

