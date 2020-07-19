<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>演讲赛 大众评委</title>
    <link href="cssjs/menu.min.css" rel="stylesheet" type="text/css">
    <link href="cssjs/divider.min.css" rel="stylesheet" type="text/css">
    <link href="cssjs/card.min.css" rel="stylesheet" type="text/css">
	<link href="cssjs/button.min.css" rel="stylesheet" type="text/css">
    <link href="cssjs/header.min.css" rel="stylesheet" type="text/css">
    <script src="cssjs/jquery-3.2.1.min.js"></script>
    <style>
        .box1{
            text-align: center;
            margin-right: 10%;
            margin-left: 10%;
        }
    </style>

    <?php
    header('Content-Type:text/html; charset=utf-8;');
    //获取传递信息
	if(!isset($_GET['openid'])){
		exit();
	}
    $openid = $_GET['openid'];

    $mysqli = new mysqli('localhost','root','Ji1234','19yj');
    mysqli_query($mysqli, "set names utf8");
    //读取用户信息
    $query2 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");//openid是字符串，需要加‘’才能正确运行
    $row2 = mysqli_fetch_array($query2);
    if(count($row2) == 0) {
        echo alert('你没有获得大众评委的资格！');
        exit();
    }
    $num = $row2['num'];
    $num = ($num % 7) + 1;

    //跳转备用
    $para1 = "19flyVote.php?openid=".$openid."&num=";
    $para2 = "19cybVote.php?openid=".$openid."&num=";
    $para3 = "lottery.php?openid=$openid";
    ?>

</head>
<body>
<div class="box0">
    <div class="ui three item menu">
        <a class="item" href="<?php echo $para2."9" ?>">雏鹰杯</a>
        <a class="item active" href="<?php echo $para3 ?>">抽奖</a>
    </div>

    <div class="ui hidden divider"></div>
    <div class="box1">
        <h4 class="ui horizontal divider header"> 你的抽奖图片 </h4>
        <div class="ui link cards">
            <div class="card">
                <div class="image">
                    <img src="images/lottery/<?php echo $num ?>.jpg">
                </div>
                <div class="content">
                    <div class="description">PS.抽奖环节会随机抽取一张图片，和上面图片相同的话即可领取奖励！</div>
                </div>
            </div>
        </div>
    <div class="ui hidden divider"></div>

</div>
</body>
<script>

</script>
</html>