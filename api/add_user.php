<?php
//    name 用户名
//    password 密码
//    type 1:超级管理员  0：普通用户
//    site_id 如果type为0，绑定工地id

//data 0:成功  -1：缺少参数    -2：用户名已存在  1：插入失败

    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["name"]) && isset($_POST["type"]) && isset($_POST["password"])){
        $name = $_POST["name"];
        $type = intval($_POST["type"]);
        $password = md5($_POST["password"]);
        if(isset($_POST["site_id"])){
            $site_id = $_POST["site_id"];
        }else{
            $site_id = null;
        }
        $sql = "select * from zulin_user where name = '$name' and status = 1";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) > 0){
            $data['code'] = -2;
        }else{
            if($type == 0){
                $sql = "INSERT INTO zulin_user (name, password, type, site_id, status) VALUES ('$name', '$password', '$type', '$site_id', 1)";
            }else{
                $sql = "INSERT INTO zulin_user (name, password, type, status) VALUES ('$name', '$password', '$type', 1)";
            }

            if ($conn->query($sql) == TRUE) {
                $data['code'] = 0;
            } else {
                $data['code'] = 1;
            }
        }
    }else{
        $data['code'] = -1;
    }
    echo json_encode($data);
?>