<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
//    if(isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["count"]) && isset($_POST["last"])){
//        $name = $_POST["name"];
//        $price = $_POST["price"];
//        $count = $_POST["count"];
//        $last = $_POST['last'];
//        $sql = "select * from device where name = '$name'";
//        $result = $conn->query($sql);
//        $results = array();
//        while ($row = $result->fetch_assoc()) {
//            $results[] = $row;
//        }
//        if(count($results) > 0){
//            $data['code'] = -2;
//        }else{
//           $sql = "INSERT INTO device (name, price, count, last, status) VALUES ('$name', '$price', '$count', '$last', 1)";
//           if ($conn->query($sql) == TRUE) {
//               $data['code'] = 0;
//           } else {
//               $data['code'] = 1;
//           }
//        }
//
//    }else{
//        $data['code'] = -1;
//    }
    $sql = "select * from device";
    $result = $conn->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    $data["data"] = $results;
    $data['code'] = 0;
    echo json_encode($data);
?>