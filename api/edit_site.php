<?php
//id:工地名称
//name：工地名称;
//person_name: 工地负责人
//phone：负责人电话
//data 0:成功  -1：缺少参数   1：插入失败  -2:名称已存在   -3:数据不存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["id"])){
        $id = $_POST["id"];
        $sql = "select * from site where id = '$id'";
        $result = $conn->query($sql);
        $myResults = array();
        while ($row = $result->fetch_assoc()) {
            $myResults[] = $row;
        }
        if(count($myResults) == 0){
            $data['code'] = -3;
            echo json_encode($data);
            return;
        }
        if(isset($_POST["name"])){
            $name = $_POST["name"];
            $sql = "select * from site where name = '$name'";
            $result = $conn->query($sql);
            $results = array();
            while ($row = $result->fetch_assoc()) {
                $results[] = $row;
            }
            if(count($results) > 0){
                $data['code'] = -2;
                echo json_encode($data);
                return;
            }
        }else{
            $name = $myResults[0]["name"];
        }

        if(isset($_POST["person_name"])){
            $person_name = $_POST["person_name"];
        }else{
            $person_name = $myResults[0]["person_name"];
        }

        if(isset($_POST["phone"])){
            $phone = $_POST["phone"];
        }else{
            $phone = $myResults[0]["phone"];
        }
        //更新数据
        $sql = "select * from site where name = '$name'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) > 0){
            $data['code'] = -2;
        }else{
            $sql = "UPDATE site SET name = '$name', person_name = '$person_name', phone = '$phone' WHERE id = '$id'";
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