<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_GET['status'])){
        $sql = "select * from device where status = 1";
    }else{
        $sql = "select * from device";
    }
    $result = $conn->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $data["data"] = $results;
    $data['code'] = 0;
    echo json_encode($data);
?>