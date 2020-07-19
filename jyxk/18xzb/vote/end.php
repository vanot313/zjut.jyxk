<?php
require_once ("18connect.php");

if(isset($_GET['openid'])){
    $openid=$_GET['openid'];
}else{
    echo "投票失败，请重新投票";
    mysqli_close($mysqli);
    exit();
}
$info=$_GET['info'];

//防止直接进入后台
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
$voteInfoNew = "$voteInfo$info";
$info = explode("&",$info);
for ($i=0; $i<count($info); $i++){
    if($info[$i] != ""){
        if(strpos($voteInfo, $info[$i]) === false){
            $query2 = mysqli_query($mysqli, "SELECT * FROM player_vote WHERE num = $info[$i] ");
            $row2 = mysqli_fetch_array($query2);
            $depiaoshu = $row2['vote'] + 1;
            $queryPlayer = mysqli_query($mysqli, "UPDATE player_vote SET vote = $depiaoshu WHERE num = $info[$i]");
            if(!$queryPlayer){
                echo "投票失败！<br>请重试！";
                mysqli_close($mysqli);
                exit();
            }else {
                $toupiaoshu++;
            }
        }else{
            echo "请不要给同一位选手重复投票";
            mysqli_close($mysqli);
            exit();
        }
    }
}
$queryApply = mysqli_query($mysqli, "UPDATE `apply_vote` SET `info`='$voteInfoNew',`vote`=$toupiaoshu WHERE `openid`='$openid'");
if(!$queryApply){
    echo "投票失败！<br>请重试！";
    mysqli_close($mysqli);
    exit();
}

echo "投票成功！";
mysqli_close($mysqli);
exit();

?>