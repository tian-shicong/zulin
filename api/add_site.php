<?php
//name：工地名称;
//person_name: 工地负责人
//phone：负责人电话
//data 0:成功  -1：缺少参数   1：插入失败  -2:名称已存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["name"]) && isset($_POST["person_name"]) && isset($_POST["phone"])){
        $name = $_POST["name"];
        $person_name = $_POST["person_name"];
        $phone = $_POST["phone"];
        $sql = "select * from site where name = '$name'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) > 0){
            $data['code'] = -2;
        }else{
            $sql = "insert into site (name, person_name, phone, status) VALUES ('$name', '$person_name', '$phone', 1)";
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