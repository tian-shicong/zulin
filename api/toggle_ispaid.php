<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_GET["flow_id"])){
        $id = $_GET['flow_id'];
        $sql = "select * from flow where id = '$id'";
        $result = $conn->query($sql);
        $results = array();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        $flow = $results[0];
        $ispaid = $flow['ispaid'] == 0 ? 1 : 0;
        $sql = "update flow set ispaid = '$ispaid' where id = '$id'";
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