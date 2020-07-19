<?php
require_once ("19connect.php");

$openid=$_GET['openid'];
$info=$_GET['info'];

//防止直接进入后台导致出错
$query1 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");
$row1 = mysqli_fetch_array($query1);
if(count($row1) == 0){
    echo "你没有评委资格！<br>请通过计E学科公众号进入投票页面。";
    mysqli_close($mysqli);
    exit();
}
//投票信息更新
$voteInfo = $row1['info'];
$toupiaoshu = $row1['vote'];
if($toupiaoshu >= 3){
    echo "你的投票数已用完！";
    mysqli_close($mysqli);
    exit();
}
$info = str_replace("D","&",$info);
$voteInfo = "$voteInfo$info";
$info = explode("&",$info);
for ($i=0; $i<count($info); $i++){
    if($info[$i] != ""){
        $query2 = mysqli_query($mysqli, "SELECT * FROM player_vote WHERE num = $info[$i] ");
        $row2 = mysqli_fetch_array($query2);
        $depiaoshu = $row2['vote'] + 1;
        $queryPlayer = mysqli_query($mysqli, "UPDATE player_vote SET vote = $depiaoshu WHERE num = $info[$i]");
        if(!$queryPlayer){
            echo "投票失败！<br>请重试！";
            mysqli_close($mysqli);
            exit();
        }else{
            $toupiaoshu++;
        }
    }
}
$queryApply = mysqli_query($mysqli, "UPDATE `apply_vote` SET `info`='$voteInfo',`vote`=$toupiaoshu WHERE `openid`='$openid'");
if(!$queryApply){
    echo "投票失败！<br>请重试！";
    mysqli_close($mysqli);
    exit();
}

echo "投票成功！";
mysqli_close($mysqli);
exit();

?>