<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type:text/html;charset=utf-8");
function downFile($url, $path){
    $file = file_get_contents($url);
    echo $file;
    file_put_contents($path, $file, FILE_APPEND);
}
downFile("http://liyq.club/zulin/backup/zulin_backup.sql","../backup1/zulin_backup".time().".sql");
?>