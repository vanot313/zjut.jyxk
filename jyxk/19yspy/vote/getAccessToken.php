<?php
require_once ("19connect.php");

//$appid = "wx2c83bd79d6b3d518"; 
//$appsecret = "1f5d71213437684789a93c3da2783e87"; 

$appid = 'wx93a2841cbddac31e';
$appsecret = '234771140dbac4ecb697fbabd2607871';
/*回调的时候自带的这个参数*/
$code = $_GET['code'];

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
$data = curl_exec($ch);
curl_close($ch);
/*这里接收到的data数据是Json格式的，我在这转换成数组了*/
$result = json_decode($data,true);
/*取出数组中的access_token这个值*/
$access_token = $result['access_token'];
$expires_in = $result['expires_in'];
/*拿到Openid就知道是哪个用户了，例如：参加活动次数，统计量的统计，没参加一下就写一次，在这里可以写入数据库*/
$openid = $result['openid'];
if(!isset($result['openid'])){
    mysqli_close($mysqli);
    exit();
}

$query1 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");
$row1 = mysqli_fetch_array($query1);
if(count($row1) == 0){
	mysqli_query($mysqli, "INSERT INTO `apply_vote`(`openid`) VALUES ('$openid')");
    mysqli_close($mysqli);
}else{
    mysqli_close($mysqli);
}
header("location:http://101.132.131.184/jyxk/19yspy/vote/19yspyVote.php?openid=$openid");
exit();

?>