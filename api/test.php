<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
//    判断表是否存在
    $sql = "show table status like 'device'";
    $result = $conn->query($sql);
    $results = array();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    var_dump ($results);
?>