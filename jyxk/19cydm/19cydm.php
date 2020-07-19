<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once ("19connect.php");
    if(isset($_GET['openid']) && isset($_GET['page'])){
        $openid = $_GET['openid'];
        $page = $_GET['page'];
    }else{
        echo "<script>alert('请通过计E学科微信公众号(jyxkforever)投票!')</script>";
        exit();
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>「RunningCode」--创意代码投票</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="http://g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
    <link rel="stylesheet" href="http://g.alicdn.com/msui/sm/0.6.2/css/sm-extend.min.css">
    <script  type="text/javascript">
        window.onload = function(){
            var pname="mulu";
            var params = location.search.substr(1);
            var ArrParam = params.split('&');
            for (var i = 0; i < ArrParam.length; i++) {
                if (ArrParam[i].split('=')[0] == "m") {
                    pname = ArrParam[i].split('=')[1];
                    break;
                }
            }
            document.querySelector("#" + pname).scrollIntoView(true);
        };
    </script>
    <script type="text/javascript">
        var openid = "<?php echo $_GET['openid'];?>";
        function vote(type,id,obj){
            var res0 = confirm("确定给"+id+"号作品投票");
            if(res0==true){
                var url = "19cydmEnd.php";
                var data = {openid:openid,type:type,id:id};
                $.post(url,data,function(res,status){
                    if(status == 'success'){
                        $.toast(res);
                        if(res.indexOf("投票成功")!=-1){
                            var ps =  Number($(obj).next().next().children("#ps").html());
                            $(obj).html('<span class="icon icon-check"></span>&nbsp&nbsp已投票');
                            $(obj).next().next().children("#ps").html(++ps);
                        }
                    }else{
                        $.toast('未知错误,请重试');
                    }
                });
            }
        }
    </script>
    <style>
        .card {
            border-top: 6px solid #009688;
            border-radius: 5px;
        }
        #team-name {
            font-weight: bold;
            color: #009688;
            font-size: 1em;
            margin: 0px;
        }
        #team-info {
            color: #F98900;
            margin: 0px;
        }
        #vote-btn {
            font-weight: bolder;
            color: #F98900;
        }
        .block {
            height: 1em;
        }
        .box1 {
            margin-top: 10%;
            margin-left: 5%;
            margin-right: 5%;
            margin-bottom: 10%;
        }
        .box2{
            margin-left: 2%;
            margin-right: 2%;
        }
        h4{
            padding: 16px 0 16px 50px;
            color: #fff;
            background:url("titlebg.png")no-repeat;
            background-size:100% 100%;
        }
    </style>
</head>
<body>
<div class="page-group">
    <div class="page page-current">
        <header class="bar bar-nav">
            <h1 class="title">「RunningCode」--创意代码投票</h1>
        </header>
        <nav class="bar bar-tab">
            <a class="tab-item external<?php echo $_GET['page']==1? ' active':''; ?>" href="19cydm.php?page=1&openid=<?php echo $_GET['openid'];?>">
                <span class="icon icon-menu"></span>
                <span class="tab-label">1-10</span>
            </a>
            <a class="tab-item external<?php echo $_GET['page']==2? ' active':''; ?>" href="19cydm.php?page=2&openid=<?php echo $_GET['openid'];?>&m=yi">
                <span class="icon icon-menu"></span>
                <span class="tab-label">11-20</span>
            </a>
            <a class="tab-item external<?php echo $_GET['page']==3? ' active':''; ?>" href="19cydm.php?page=3&openid=<?php echo $_GET['openid'];?>&m=yi">
                <span class="icon icon-menu"></span>
                <span class="tab-label">21-29</span>
            </a>
        </nav>
        <div class="content">
            <div class="box2">
                <a id="mulu"></a>
                <h4>目录：</h4>
                <div class="box2">
                    <a class="tab-item external" href="19cydm.php?&page=1&openid=<?php echo $_GET['openid'];?>&m=yi"><h3><u>一、情为何物（1-25）</u></h3></a>
                    <a class="tab-item external" href="19cydm.php?page=3&openid=<?php echo $_GET['openid'];?>&m=er"><h3><u>二、旅行的意义（26-27）</u></h3></a>
                    <a class="tab-item external" href="19cydm.php?page=3&openid=<?php echo $_GET['openid'];?>&m=san"><h3><u>三、工大志趣（28-29）</u></h3></a>
                </div>
            </div>
            <?php
            $list = $mysqli->query("SELECT * FROM `list` WHERE `page`='$page' ORDER BY `id` ASC");
            $date = intval(date("d"))-21;
            $info_array = null;
            if($date>0 && $date<6){
                $info = "info".$date;
                $vote_row = $mysqli->query("SELECT * FROM `vote` WHERE `openid` = '$openid'")->fetch_array();
                $info_array = explode('+',$vote_row[$info]);
                $rest_ps = 3-count($info_array);
            }else{
                $info_array = array(0=>0);
                $rest_ps = 0;
            }
            $typeI=0;
            while($list_row = $list->fetch_array()){
                $type = $list_row['type'];
                $id = $list_row['id'];
                $str_id = (string)$id;
                if(in_array(0,$info_array,TRUE)){
                    $btn = '<a href="javascript:void(0);" class="link" id="vote-btn"></span>非投票时间</a>';
                }else if(!in_array($str_id,$info_array,TRUE)){
                    $btn = '<a onclick="vote('.$type.','.$id.',this)" class="link" id="vote-btn"><span class="icon icon-emoji"></span>&nbsp&nbsp立即投票</a>';
                }else{
                    $btn = '<a href="javascript:void(0);" class="link" id="vote-btn"><span class="icon icon-check"></span>&nbsp&nbsp已投票</a>';
                }
                if($typeI==0){$typeI = $type;}
                if($type==$typeI){
                    switch ($type){
                        case(1):echo '<h4 class="box2" id="yi">一、情为何物</h4>';break;
                        case(2):echo '<h4 class="box2" id="er">二、旅行的意义</h4>';break;
                        case(3):echo '<h4 class="box2" id="san">三、工大志趣</h4>';break;
                        default:break;
                    }
                    $typeI++;
                }
                echo '
                    <div class="card demo-card-header-pic">
                        <div class="card-header"><p class="color-gray" id="team-name"><span class="icon icon-friends"></span>'.$list_row["title"].'——'.$list_row['name'].'</p></div>
                        <div valign="bottom" class="card-header color-white no-border no-padding">
                            <img class="card-cover" src="http://prv48wttj.bkt.clouddn.com/cydmm'.$id.'.'.$list_row["img"].'">
                        </div>
                        <div class="card-footer">
                            '.$btn.'
                            <a href="javascript:void(0);" class="link">编号：<span>'.$list_row["id"].'</span></a>
                            <a href="javascript:void(0);" class="link">票数：<span id="ps">'.$list_row["ps"].'</span>票</a>
                        </div>
                    </div>
                    <div class="block"></div>
                ';
            }
            ?>
        </div>
    </div>
</div>
<script type='text/javascript' src='http://g.alicdn.com/sj/lib/zepto/zepto.min.js'></script>
<script type='text/javascript' src='http://g.alicdn.com/msui/sm/0.6.2/js/sm.min.js'></script>
<script type='text/javascript' src='http://g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js'></script>
</body>
</html>