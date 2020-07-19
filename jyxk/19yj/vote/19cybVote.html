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
	//对应parax."x"   GET的num=x	
    $num = $_GET['num'];			
    $openid = $_GET['openid'];
	
	//获得默认选手序号以及访问者微信ID  
    $mysqli = new mysqli('localhost','root','Ji1234','19yj');
    mysqli_query($mysqli, "set names utf8");
    //读取选手信息
    $query1 = mysqli_query($mysqli, "SELECT * FROM player_info WHERE num = '$num'");
    $row1 = mysqli_fetch_array($query1);
    $name = $row1['name'];
    $word = $row1['word'];
    $open = $row1['open'];
    //读取用户信息,并与html页面内容交互
    $query2 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");
	//openid是字符串，需要加‘’才能正确运行
    $row2 = mysqli_fetch_array($query2);
	//在apply_vote表中查询对应openID的一行
    mysqli_close($mysqli);
    if(count($row2) == 0) {
        echo alert('你没有获得大众评委的资格！');
        exit();
    }
    $voteInfo = $row2['vote'];
    $voteInfo = explode('&',$voteInfo);
    $arr = array();
    for ($i = 0; $i < count($voteInfo); $i++){
        $arr[$i] = array();
        $arr[$i] = explode('+',$voteInfo[$i]);
    }
    $isThisVoted = 0;			//初始化投票状态
    $votedScore = 0;			
    for ($i = 0; $i< count($arr); $i++){
        if($arr[$i][0]  == $num) {
            $isThisVoted = 1;
            $votedScore = $arr[$i][1];
            break;
        }
    }

    //跳转备用
    $para1 = "19flyVote.php?openid=".$openid."&num=";
    $para2 = "19cybVote.php?openid=".$openid."&num=";
    $para3 = "lottery.php?openid=$openid";
	//para对应html中页面链接
    ?>

</head>
<body>

<div class="ui three item menu">
    <a class="item active" href="<?php echo $para2."9" ?>">雏鹰杯</a>
	
    <a class="item" href="<?php echo $para3 ?>">抽奖</a>
</div>

<div class="ui hidden divider"></div>
<div class="box1">
    <h4 class="ui horizontal divider header"> 介绍 </h4>
    <div class="ui link cards">
        <div class="card">
            <div class="image">
                <img src="images/<?php echo $num ?>.jpg">
            </div>
            <div class="content">
                <div class="header"><?php echo $name ?></div>
                <div class="description"><?php echo $word ?></div>
            </div>
        </div>
    </div>
	
    <h4 id="toupiao" class="ui horizontal divider header"> 评分 </h4>
    <div class="ui fluid vertical menu">
        <a id="vote5" class="red item" onclick="submit(5)">★★★★★</a>
        <a id="vote4" class="orange item" onclick="submit(4)">★★★★☆</a>
        <a id="vote3" class="olive item" onclick="submit(3)">★★★☆☆</a>
        <a id="vote2" class="green item" onclick="submit(2)">★★☆☆☆</a>
        <a id="vote1" class="teal item" onclick="submit(1)">★☆☆☆☆</a>
    </div>
	
	<div class="btn btn-block" onclick="bind()">提交</div>
    <div class="ui six item menu">
        <a id="page9" class="item" href="<?php echo $para2."9" ?>">1</a>
        <a id="page10" class="item" href="<?php echo $para2."10" ?>">2</a>
        <a id="page11" class="item" href="<?php echo $para2."11" ?>">3</a>
        <a id="page12" class="item" href="<?php echo $para2."12" ?>">4</a>
        <a id="page13" class="item" href="<?php echo $para2."13" ?>">5</a>
    </div>
    <div class="ui six item menu">
        <a id="page14" class="item" href="<?php echo $para2."14" ?>">6</a>
        <a id="page15" class="item" href="<?php echo $para2."15" ?>">7</a>
        <a id="page16" class="item" href="<?php echo $para2."16" ?>">8</a>
        <a id="page17" class="item" href="<?php echo $para2."17" ?>">9</a>
        <a id="page18" class="item" href="<?php echo $para2."18" ?>">10</a>

    </div>
</div>
<div class="ui hidden divider"></div>

</body>
<script>
	//<a id="page*" class="disabled item">*</a>
    //选手编号
    var pageNum = <?php echo $num ?>;
    var pageValue = "#page" + pageNum;
    $(pageValue).addClass('active');
    //是否为该选手投过票
    var isThisVoted = <?php echo $isThisVoted ?>;
    if(isThisVoted){
        var idValue = "#vote" + <?php echo $votedScore ?>;
        $(idValue).addClass('active');
        $('#vote1').removeAttr("onclick");
        $('#vote2').removeAttr("onclick");
        $('#vote3').removeAttr("onclick");
        $('#vote4').removeAttr("onclick");
        $('#vote5').removeAttr("onclick");
    }
    //投票是否开启
    var open = <?php echo $open ?>;
    if(!open){
        $('#toupiao').text("投票（关闭）");
    }

    //处理提交按钮
    var num = <?php echo $num ?>;
    var openid = '<?php echo $openid ?>';
    function submit(score) {
		
        $('#vote1').removeAttr("onclick");
        $('#vote2').removeAttr("onclick");
        $('#vote3').removeAttr("onclick");
        $('#vote4').removeAttr("onclick");
        $('#vote5').removeAttr("onclick");

        if(isThisVoted){
            alert("已经为这位选手投过票！\n请不要重复投票！");
            return;
        }
        if(!open){
            alert("这位选手的投票暂时关闭！\n去看看别的选手吧！");
            return;
        }
        //正式开始处理
        /*        
		var msg = {
			num:num,
			score:score,
			openid:openid
		};
		$.get('end.php',msg, function (res, status) {
			if (status) {
			alert(res);
		} else {
			alert('投票失败,请检查网络');
		}
		};*/
        var msg = "num="+num+"&score="+score+"&openid="+openid;
        var xmlHttp=new XMLHttpRequest();
        var newUrl="end.php?"+msg;
        xmlHttp.open("GET",newUrl,false);
        xmlHttp.send(null);
        var result=xmlHttp.responseText;
        alert(result);

        location.reload();
    }

</script>

</html>