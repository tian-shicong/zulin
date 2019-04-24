<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    $sql = "select * from user where status = 1";
    $result = $conn->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        if($row['type'] == 0){
            $siteSql = "select * from site where id = '$row[site_id]'";
            $result1 = $conn->query($siteSql);
            $results1 = array();
            while ($row1 = $result1->fetch_assoc()) {
                $results1[] = $row1;
            }
            if(count($results1) > 0){
                $row['site'] = $results1[0];
            }
        }
        $results[] = $row;
    }
    $data["data"] = $results;
    $data['code'] = 0;
    echo json_encode($data);
?>