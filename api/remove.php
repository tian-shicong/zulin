<?php
//name:表名
//id:数据id,
//type:删除0   恢复1
//data 0:成功  -1：缺少参数  1：操作失败
    header("Access-Control-Allow-Origin:*");
	header("Content-Type:text/html;charset=utf-8");
	date_default_timezone_set('PRC');
    require('./conn.php');
    $data = [];
    if(isset($_POST["name"]) && isset($_POST["id"])){
        $name = $_POST["name"];
        $id = $_POST["id"];
        if(isset($_POST["type"])){
            $type = $_POST["type"];
        }else{
            $type = 0;
        }
        if($type == 0){
                $sql = "UPDATE $name SET status = 0 WHERE id = '$id'";
            }else{
                $sql = "UPDATE $name SET status = 1 WHERE id = '$id'";
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