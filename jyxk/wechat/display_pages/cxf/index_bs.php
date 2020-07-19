<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>比赛信息</title>
    <link href="../cssjs/accordion.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/divider.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/button.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/icon.css" rel="stylesheet" type="text/css">
	<script src="../cssjs/jquery-1.10.2.js"></script>
    <script src="../cssjs/dimmer.js"></script>
    <script src="../cssjs/accordion.js"></script>
    <style>
        /*.box1{*/
        /*margin: 10% 2%;*/
        /*}*/
        .addPoint{
            float: right;
        }
        a{
            text-decoration: none;
            color: #0f0f10;
        }
        p{
            line-height: 1.5em;
        }
		
		
		
    </style>
</head>																						
<body>
<div class="container">
    <div class="box1">
        <div class="box2">
            <div class="ui fluid buttons">
                <button class="ui button"><a href="index_jz.php">讲座加分</a></button>
                <button class="ui button active"><a href="index_bs.php">比赛加分</a></button>			
            </div>
        </div>
        <div class="box3" >
            <?php
            require_once ("connect.php");
            //下半学期
			
            for ($term=2;$term>0;$term--){
                $list1=$mysql->query("SELECT * FROM `jfgs` WHERE `type`='1' AND `term`='$term' ORDER BY `year` DESC,`month` DESC,`day` DESC");	
				//按时间先后选择讲座加分项目
                $fy=false;
				//初始化学期情况
                $month=0;
				//初始化页面月份
                while($list1_row=$list1->fetch_array()){
						//一个读取list中选中内容并对其进行格式化输出的循环
                    if($fy==false){
						//判断是否进入新学期
                        $str=null;
						//初始化文本框
                        if ($term==1){
                            $str='19学年 | 上学期';
                        }else{
                            $str='19学年 | 下学期';
                        }
						//判断当前学期
                        echo '
                        <h2 class="ui horizontal divider header">
                        <i class="pointing down  icon"></i>'.$str.'
                        </h2>
                            ';
						//在页面上按格式打印文本框内容（即按学期画分割线）
                        $fy=true;
						//进入新学期
                    }
                    if($list1_row["month"]!=$month){
						$month = $list1_row["month"];
						//初始化月份情况，也是初始化月份文本框
						echo '
						</div>
						<h4 class="ui horizontal divider header"> '.$month.' 月 </h4>
						<div class="ui styled  fluid accordion">
							';
						//在页面上按格式打印文本框内容（即按月份画分割线）
                    }
                    $title = $list1_row["title"];	//标题
                    $scoreT = $list1_row["scoreT"];	//加分类型
                    $score = $list1_row["score"];	//加分数
                    $grade = $list1_row["grade"];	//适用年级
                    $href = $list1_row["href"];		//链接
					//初始化具体加分内容文本
                    echo '
                        <div class="title"><i class="dropdown icon"></i> '.$title.' </div>
                        <div class="content">
                            <p>'.$scoreT.'分: '.$score.'(分)<br>对象 : '.$grade.'级</p>
                        <a href="'.$href.'" class="mingdan">
                        <i class="list layout icon"></i>名单下载
                        </a>
                        </div>
                        ';
						//在页面上按格式打印文本框内容（即打印加分事项的具体内容）
                }
                if($month!=0){
                    echo '</div>';
					
                }
            }

            //输出完毕
            $mysql->close();
            ?>
        </div>
    </div>
</div>
</body>
<script>
    $(".accordion").accordion();
</script>
</html>