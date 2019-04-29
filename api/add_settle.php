<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["site_id"])){
        $device_id = $_POST["device_id"];
        $create_date = $__POST['create_date'];
        $detail = $_post['detail'];
        $money = $_post['money'];
        $sql = "INSERT INTO settle (device_id, create_date, detail, money) VALUES ('$device_id', '$create_date', '$detail', '$money')";
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