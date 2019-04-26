<?php
//id:设备id
//price：价格 单位（元/天）;
//count: 总数
//last：库存剩余

//data 0:成功  -1：缺少参数   1：插入失败  -2:找不到设备  -3:名称已存在
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["id"]) ){
        $id = $_POST["id"];
        $sql = "select * from device where id = '$id'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        if(count($results) == 0){
            $data['code'] = -2;
            echo json_encode($data);
            return;
        }else{
            if(isset($_POST["name"])){
                $name = $_POST["name"];
                $sql1 = "select * from device where name = '$name' and id != '$id'";
                $result1 = $conn->query($sql1);
                $results1 = array();
                while ($row1 = $result1->fetch_assoc()) {
                    $results1[] = $row1;
                }
                if(count($results1) > 0){
                    $data['code'] = -3;
                    echo json_encode($data);
                    return;
                }
            }else{
                $name = $results[0]["name"];
            }
            if(isset($_POST["price"])){
                $price = $_POST["price"];
            }else{
                $price = $results[0]["price"];
            }
            if(isset($_POST["count"])){
                $count = $_POST["count"];
            }else{
                $count = $results[0]["count"];
            }
            if(isset($_POST["last"])){
                $last = $_POST["last"];
            }else{
                $last = $results[0]["last"];
            }
        }
        $sql = "UPDATE device SET price = '$price', count = '$count', last = '$last', name = '$name' WHERE id = '$id'";
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