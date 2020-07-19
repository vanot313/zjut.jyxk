<?php
header('Content-Type:text/html; charset=utf-8;');
$appid = "wx2c83bd79d6b3d518"; 
$appsecret = "1f5d71213437684789a93c3da2783e87"; 
//$appid = 'wx93a2841cbddac31e';
//$appsecret = '234771140dbac4ecb697fbabd2607871';
/*回调的时候自带的这个参数*/
//微信端参数，用于连接指定的微信端口
$code = $_GET['code'];

$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
//微信方的操作，用于确认id与密码以确认你是指定微信测试号的管理人
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
$data = curl_exec($ch);
curl_close($ch);
/*这里接收到的data数据是Json格式的，我在这转换成数组了*/
$result = json_decode($data,true);
/*取出数组中的access_token这个值*/
//access token是指一个特定的访问令牌，每次用户行使特权都会获得一个指定的访问令牌
$access_token = $result['access_token'];
$expires_in = $result['expires_in'];
/*拿到Openid就知道是哪个用户了，例如：参加活动次数，统计量的统计，没参加一下就写一次，在这里可以写入数据库*/
//每一个特定的用户在访问微信公众号时都会产生一个唯一的与之对应的openid，用于识别。
$openid = $result['openid'];
if(!isset($result['openid'])){
    exit();
}
$mysqli = new mysqli('localhost','root','Ji1234','19yj');
//访问数据库内的19yj表
mysqli_query($mysqli, "set names utf8");
$query1 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");
$row1 = mysqli_fetch_array($query1);
if(count($row1) == 0){
	mysqli_query($mysqli, "INSERT INTO `apply_vote`(`openid`) VALUES ('$openid')");
}
mysqli_close($mysqli);
header("location:http://www.vanot.top/jyxk/19yj/vote/19cybVote.php?openid=$openid&num=1");
exit();

?>