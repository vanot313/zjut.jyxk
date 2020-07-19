<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>讲座信息</title>
    <link href="../cssjs/accordion.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/divider.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/button.css" rel="stylesheet" type="text/css">
    <link href="../cssjs/icon.css" rel="stylesheet" type="text/css">
	<script src="../cssjs/jquery-1.10.2.js"></script>
    <script src="../cssjs/dimmer.js"></script>
    <script src="../cssjs/accordion.js"></script>
    <style>
        /*.box3{
            text-align: center;
        }*/
        .addPoint{
            float: right;
        }
        a{
            text-decoration: none;
            color: #0f0f10;
        }
        #1 {
            display: none;
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
                <button class="ui button active"><a href="index_jz.php">讲座加分</a></button>
                <button class="ui button"><a href="index_bs.php">比赛加分</a></button>
            </div>
        </div>
        <div class="box3" >
            <?php
            require_once ("connect.php");
            //下半学期
			//此文件备注参考同目录下index_bs.php文件
            for ($term=2;$term>0;$term--){
                $list1=$mysql->query("SELECT * FROM `jfgs` WHERE `type`='2' AND `term`='$term' ORDER BY `year` DESC,`month` DESC,`day` DESC");
				
                $fy=false;
				
                $month=0;
				
                while($list1_row=$list1->fetch_array()){
					
                    if($fy==false){
						
                        $str=null;
						
                        if ($term==1){
                            $str='19学年 | 上学期';
                        }else{
                            $str='19学年 | 下学期';
                        }
						
                        echo '
                        <h2 class="ui horizontal divider header">
                        <i class="pointing down  icon"></i>'.$str.'
                        </h2>
                            ';
							
                        $fy=true;
						
                    }
                    if($list1_row["month"]!=$month){
						$month = $list1_row["month"];
						
						echo '
						</div>
						<h4 class="ui horizontal divider header"> '.$month.' 月 </h4>
						<div class="ui styled  fluid accordion">
							';
							
                    }
                    $title = $list1_row["title"];
                    $scoreT = $list1_row["scoreT"];
                    $score = $list1_row["score"];
                    $grade = $list1_row["grade"];
                    $href = $list1_row["href"];
					
                    echo '
                        <div class="title"><i class="dropdown icon"></i> '.$title.' </div>
                        <div class="content">
                            <p>'.$scoreT.'分: '.$score.'(分)<br>对象 : '.$grade.'级</p>
                        <a href="'.$href.'" class="mingdan">
                        <i class="list layout icon"></i>名单下载
                        </a>
                        </div>
                        ';
						
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