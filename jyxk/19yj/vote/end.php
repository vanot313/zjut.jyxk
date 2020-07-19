<?php

@header("content-Type: text/html; charset=UTF-8");
$mysqli =new mysqli('localhost','root','Ji1234','19yj'); //服务器与本地不同

$num=$_GET['num'];
$openid=$_GET['openid'];
$score=$_GET['score'];

//防止直接进入后台导致出错,如果在数据库中没有获得权限则无法参与投票
$query1 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");
$row1 = mysqli_fetch_array($query1);
if(count($row1) == 0){
    echo "你没有大众评委资格！";
    exit();
}
//用户投票信息更新
$voteInfo = $row1['vote'];
$voteInfo = "$voteInfo&$num+$score";
mysqli_query($mysqli, "UPDATE `apply_vote` SET `vote`='$voteInfo' WHERE `openid`='$openid'");

//选手投票信息更新
$query2 = mysqli_query($mysqli, "SELECT * FROM player_vote WHERE num = $num");
$row2 = mysqli_fetch_array($query2);
$allScore = $row2['score'];
$allCount = $row2['count'];
$allScore = $allScore + $score;
$allCount++;
mysqli_query($mysqli, "UPDATE `player_vote` SET `score`=$allScore,`count`=$allCount WHERE `num` = $num");
mysqli_close($mysqli);

echo "投票成功！";
exit();

?>