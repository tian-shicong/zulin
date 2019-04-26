<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $data['id'] = $id;
        $sql = "select * from site where id = '$id'";
    }else if(isset($_GET['status'])){
        $status = $_GET['status'];
        $sql = "select * from site where status = '$status'";
    }else{
        $sql = "select * from site";
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