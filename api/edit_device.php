<?php
//id:设备id
//price：价格 单位（元/天）;
//count: 总数
//last：库存剩余

//data 0:成功  -1：缺少参数   1：插入失败  -2:找不到设备
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
        }else{
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
        $sql = "UPDATE device SET price = '$price', count = '$count', last = '$last' WHERE id = '$id'";
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