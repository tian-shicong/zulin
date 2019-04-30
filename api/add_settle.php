<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["site_id"])){
        $site_id = $_POST["site_id"];
        $create_date = $_POST['create_date'];
        $detail = $_POST['detail'];
        $money = $_POST['money'];
        $sql = "INSERT INTO settle (site_id, create_date, detail, money) VALUES ('$site_id', '$create_date', '$detail', '$money')";
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