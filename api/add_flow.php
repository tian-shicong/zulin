<?php
//begin_date:起始时间
//create_date：创建时间 ;
//number: 数目
//type：类别；1：出租    0:归还
//money 金额 归还时才必填
//discount 折扣
//status 状态
//settle 是否结清
//settle_last 待结算数量
//site_id 客户id
//device_id 设备id
//data 0:成功  -1：缺少参数   1：插入失败

    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["device_id"]) && isset($_POST["site_id"]) && isset($_POST["type"]) && isset($_POST["number"])){
        $device_id = $_POST["device_id"];
        $site_id = $_POST["site_id"];
        $type = $_POST["type"];
        $number = $_POST['number'];
        if(isset($_POST['begin_date'])){
            $begin_date = $_POST['begin_date'];
        }
        if(isset($_POST['create_date'])){
            $create_date = $_POST['create_date'];
        }
        if($type == 0){
            if(isset($_POST['money'])){
                $money = $_POST['money'];
            }
            if(isset($_POST['discount'])){
                $discount = $_POST['discount'];
            }
            $sql = "INSERT INTO flow (device_id, site_id, type, number, create_date, status, money, discount) VALUES ('$device_id', '$site_id', '$type', '$number', '$create_date', 1, '$money', '$discount')";
        }else{
            $sql = "INSERT INTO flow (device_id, site_id, type, number, begin_date, create_date, status, settle, settle_last) VALUES ('$device_id', '$site_id', '$type', '$number', '$begin_date', '$create_date', 1, 0, '$number')";
        }
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