<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "select * from flow where status = 1 and site_id = '$id'";
    }else{
        $sql = "select * from flow where status = 1";
    }
    $result = $conn->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        if($row["device_id"]){
            $device_id = $row["device_id"];
            $deviceSql = "select * from device where id = '$device_id'";
            $result1 = $conn->query($deviceSql);
            $results1 = array();
            while ($row1 = $result1->fetch_assoc()) {
                $results1[] = $row1;
            }
            $row['device'] = $results1[0];
        }
        $results[] = $row;
    }
    $data["data"] = $results;
    $data['code'] = 0;
    echo json_encode($data);
?>