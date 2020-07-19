<?php
require_once ("19connect.php");
//各种初始参数检验
$openid = null;
$type = null;
$id = null;
if(isset($_POST['openid'])){
    $openid = $_POST['openid'];
    $check_row = $mysqli->query("SELECT * FROM `vote` WHERE `openid` = '$openid'")->fetch_array();
    if(count($check_row)==0){
        exit("请通过计E学科微信公众号(jyxkforever)投票!");
    }
}else{
    exit("请通过计E学科微信公众号(jyxkforever)投票--!");
}
if(isset($_POST['type']) && isset($_POST['id'])){
    $type = $_POST['type'];
    $id = $_POST['id'];
    if($type=="" && $id==""){
        exit("请通过计E学科微信公众号(jyxkforever)投票!");
    }
}else{
    exit("请通过计E学科微信公众号(jyxkforever)投票!");
}
$date = intval(date("d"))-20;
$info = null;
if($date>0 && $date<6){
    $info = "info".$date;
}else{
    exit("现在不是投票时间！");
}
//检验投票信息无误
$vote_row = $mysqli->query("SELECT * FROM `vote` WHERE openid = '$openid'")->fetch_array();
$vote_info = $vote_row[$info];
$info_array = explode('+', $vote_info);
$rest_ps = 4-count($info_array);//会产生一个多余的空数组
if($rest_ps<1){
    exit("今日投票次数已经用完~");
}
$str_id = (string)$id;
if (in_array($str_id, $info_array, TRUE)){
    exit("今天你已经为Ta投过票了!");
}
//投票操作
$vote_info = "".$vote_info.$id."+";
$res1 = $mysqli->query("UPDATE `vote` SET `$info` = '$vote_info' WHERE `openid` = '$openid'");
$res2 = $mysqli->query("UPDATE `list` SET `ps`=`ps`+1 WHERE `id` = '$id'");
if ($res1 && $res2) {
    $rest_ps--;
    if ($rest_ps > 0) {
        exit("投票成功, 今日剩余" . $rest_ps . "票");
    } else {
        exit("投票成功, 今日票数已经用完~");
    }
}else{
    exit("投票失败, 请重试!");
}

?>