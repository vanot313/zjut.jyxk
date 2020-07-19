<!DOCTYPE html>
<html ng-app="ionicApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>星主播投票</title>
    <link href="cssjs/css/ionic.min.css" rel="stylesheet" type="text/css">
    <script src="cssjs/js/ionic.bundle.min.js"></script>
    <script src="cssjs/jquery-3.2.1.min.js"></script>
    <style>
        .card {
            border-bottom: 6px solid #009688;
            border-radius: 5px;
        }
    </style>
    <?php
    require_once ("18connect.php");
    //获取传递信息
	if(!isset($_GET['openid'])){
		exit();
	}
    $openid = $_GET['openid'];
	$maxCount = 3;//票数
	
	
    //读取用户信息
    $query1 = mysqli_query($mysqli, "SELECT * FROM apply_vote WHERE openid = '$openid'");//openid是字符串，需要加‘’才能正确运行
    $row1 = mysqli_fetch_array($query1);
    //读取选手信息
    $query2 = $mysqli->query("SELECT * FROM player_vote");

    mysqli_close($mysqli);
    if(count($row1) == 0) {
        echo "<script>alert('你没有获得评委的资格！');</script>";
        exit();
    }
    $voteInfo = $row1['info'];
    $count = $row1['vote'];
    $isVoted = explode("&",$voteInfo);
	$take = $row1['take'];
    ?>

</head>
<body>


<div class="bar bar-header bar-stable">
    <h1 class="title"> 介绍&投票 </h1>
</div>
<div class="content has-header ionic-pseudo" style="margin-right: 5%;margin-left: 5%;">
    <?php
    $name = array();
    $k = 1;
    while($row2 = $query2->fetch_array())
    {
        $num = $row2['num'];
        $name[$k] = $row2['name'];
        echo '<div class="list card">';
        echo '<div class="item item-image"><img src="http://wzcwzc.top/18xzb/vote/images/xzbP'.$num.'.jpg"></div>';
        echo '<a href="#" class="item item-icon-left"><i class="icon ion-ios-people"></i>'.$name[$k].'</a>';
        echo '</div>';
        $k++;
    }
    ?>
</div>
<br>
<div class="item item-divider"> <b>投票（多选）</b><br>
    剩余票数：<?php echo ($maxCount-$count); ?> <br>
    已投：<?php foreach ($isVoted as $i) if ($i!="") echo $name[$i]."&nbsp;&nbsp;" ?>
</div>
<ul class="list">
    <?php
        for ($i=1; $i<$k; $i++){
            echo '<li class="item item-checkbox">';
            echo '<label class="checkbox"><input type="checkbox" name="checkbox" value="'.$i.'"></label>'.$name[$i].'</li>';
        }
    ?>
    <button class="button button-block button-positive" name="submit" onclick="submit(this)"<?php echo $count<$maxCount?'>投票':'disabled="true">票数已用完'?> </button>
</ul>

<div class="item item-divider"> <b> 抽奖结果 </b><br>
    <?php
		if($take==0) echo '未开奖，请耐心等待！';
			else if($take==2) echo '可惜，未中奖...';
			else if($take==1) echo '恭喜你，中奖了！';
	?>
</div>

</body>
<script>
    var count = <?php echo $count ?>;
    var maxCount = <?php echo $maxCount ?>;
    var voteInfo = '<?php echo $voteInfo ?>';
    var openid = '<?php echo $openid ?>';
		
    function submit(obj) {
        obj.onclick = "";
		
		var startTime = new Date(2018,10-1,9,18,30,00);
		var endline = new Date(2018,12-1,9,20,00,00);
		var nowTime = new Date();
		if(nowTime < startTime){
			alert("投票失败，未开始投票");
			location.reload();
			return;
		}else if(nowTime > endline){
			alert("投票失败，投票已结束");
			location.reload();
			return;
		}

        var arrBox=document.getElementsByName("checkbox");
        var arrCheck = new Array();
        for (var i=0; i<arrBox.length; i++){
            if(arrBox[i].checked){
                arrCheck.push(arrBox[i].value);
            }
        }
        if(arrCheck.length <= 0) {
            alert("请选择你想要投票的选手后再提交！");
            location.reload();
        } else if(arrCheck.length > (maxCount-count)) {
            alert("你最多只能投 3 位选手！");
            location.reload();
        } else {
            var info = "";
            for (var i=0; i<arrCheck.length; i++){
                if(voteInfo.indexOf(arrCheck[i]) != -1){
                    alert("请不要给同一位选手重复投票！");
                    location.reload();
					return;
                }
                info = info + arrCheck[i] + "D";
            }
            var msg = "openid="+openid+"&info="+info;
            var xmlHttp=new XMLHttpRequest();
            var newUrl="end.php?"+msg;
            xmlHttp.open("GET",newUrl,false);
            xmlHttp.send(null);
            var result=xmlHttp.responseText;
            alert(result);
            location.reload();
        }
    }

</script>

</html>