<?php
//id:用户id,
//name 用户名
//type：角色 1：管理员    0：普通用户
//site_id: 单位id
//password：新密码

//data 0:成功  -1：缺少参数   1：插入失败  -2:找不到设备   -3:名称已存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["id"]) ){
        $id = $_POST["id"];
        $sql = "select * from zulin_user where id = '$id' and status = 1";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) == 0){
            $data['code'] = -2;
        }else{
            if(isset($_POST["name"])){
                $name = $_POST["name"];
                $nameSql = "select * from zulin_user where name = '$name' and status = 1";
                $result1 = $conn->query($nameSql);
                $results1 = array();
                while ($row1 = $result1->fetch_assoc()) {
                    $results1[] = $row1;
                }
                if(count($results1) > 0){
                    $data["code"] = -3;
                    echo json_encode($data);
                    return;
                }
            }else{
                $name = $results[0]["name"];
            }
            if(isset($_POST["type"])){
                $type = $_POST["type"];
            }else{
                $type = $results[0]["type"];
            }
            if(isset($_POST["site_id"])){
                $site_id = $_POST["site_id"];
            }else{
                $site_id = $results[0]["site_id"];
            }
            if(isset($_POST["password"])){
                $password = md5($_POST["password"]);
            }else{
                $password = $results[0]["password"];
            }
        }
        $sql = "UPDATE zulin_user SET name = '$name', type = '$type', site_id = '$site_id', password = '$password' WHERE id = '$id' and status = 1";
        if ($conn->query($sql) == TRUE) {
            $data['code'] = 0;
        } else {
            $data['code'] = 1;
        }
    }else{
        $data['code'] = -1;
    }
    echo json_encode($data);
?>