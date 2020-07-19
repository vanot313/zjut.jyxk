<!DOCTYPE html>
<html ng-app="ionicApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>原声配音投票</title>
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
    require_once ("19connect.php");
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
    $query2 = $mysqli->query("SELECT * FROM player_info");

    mysqli_close($mysqli);
    if(count($row1) == 0) {
        echo "<script>alert('你没有获得评委的资格！');</script>";
        exit();
    }
    $voteInfo = $row1['info'];
    $count = $row1['vote'];
    $isVoted = explode("&",$voteInfo);

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
        $word = $row2['word'];
        echo '<div class="list card">';
        echo '<div class="item item-image"><img src="http://101.132.131.184/jyxk/19yspy/vote/images/'.$num.'.jpg"></div>';
        echo '<a href="#" class="item item-icon-left"><i class="icon ion-ios-people"></i>'.$k.'号：'.$name[$k].'</a>';
        echo '</div>';
        $k++;
    }
    ?>
</div>
<br>
<div class="item item-divider"> <b>投票（请直接选择3支队伍进行投票）</b><br>
    剩余票数：<?php echo ($maxCount-$count); ?> <br>
    已投：<?php foreach ($isVoted as $i) if ($i!="") echo $i."号" ?></div>
<ul class="list">
    <?php
        for ($i=1; $i<$k; $i++){
            echo '<li class="item item-checkbox">';
            echo '<label class="checkbox"><input type="checkbox" name="checkbox" value="'.$i.'"></label>'.$i.'号：'.$name[$i].'</li>';
        }
    ?>
    <button class="button button-block button-positive" name="submit" onclick="submit(this)"<?php echo $count<$maxCount?'>投票':'disabled="true">票数已用完'?> </button>
</ul>

</body>
<script>
    var count = <?php echo $count ?>;
    var maxCount = <?php echo $maxCount ?>;
    var voteInfo = '<?php echo $voteInfo ?>';
    var openid = '<?php echo $openid ?>';
    function submit(obj) {
        obj.onclick = "";
        var arrBox=document.getElementsByName("checkbox");
        var arrCheck = new Array();
        for (var i=0; i<arrBox.length; i++){
            if(arrBox[i].checked){
                arrCheck.push(arrBox[i].value);
            }
        }
        if(arrCheck.length <= 2) {	//若可以投1-3票，数字为0，必须3票，数字为2
            alert("请选择3支队伍后提交！\n如果没想好，那就过会再来吧");
            location.reload();
        } else if(arrCheck.length > (maxCount-count)) {
            alert("你最多只能投 3 支队伍哦！");
            location.reload();
        } else {
            var info = "";
            for (var i=0; i<arrCheck.length; i++){
                if(voteInfo.indexOf(arrCheck[i]) != -1){
                    alert("请不要重复给"+arrCheck[i]+"号队伍投票！");
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