<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST['id']) && isset($_POST['num'])){
        $id = $_POST['id'];
        $sql = "select * from device where id = '$id'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        $device = $results[0];
        $num = int($_POST['num']);
        $newLast = $device['count'] + $num;
        $data['newLast'] = $newLast;
        $sql = "UPDATE device SET count = '$newLast' WHERE id = '$id'";
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