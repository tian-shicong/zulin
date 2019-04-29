<?php
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST['id']) && isset($_POST['settle_last'])){
        $id = $_POST['id'];
        $settle_last = $_POST['settle_last'];
        $flowSql = "select * from flow where id = '$id'";
        $result1 = $conn->query($flowSql);
        $results1 = array();
        while ($row1 = $result1->fetch_assoc()) {
            $results1[] = $row1;
        }
        if(count($results1) > 0){
            $flow = $results1[0];
            $curr_last = $flow['settle_last'];
            $new_last = (int)$curr_last + (int)$settle_last;
        }else{
            $data['code'] = -2;
            echo json_encode($data);
            return;
        }
        if(isset($_POST['remove'])){
            $remove = $_POST['remove'];
        }else{
            $remove = false;
        }
        if($remove){
            $sql = "UPDATE flow SET settle_last = '$new_last', settle = 0 WHERE id = '$id'";
        }else{
            if($settle_last == 0){
                $sql = "UPDATE flow SET settle_last = '$settle_last', settle = 1 WHERE id = '$id'";
            }else{
                $sql = "UPDATE flow SET settle_last = '$settle_last' WHERE id = '$id'";
            }
        }
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