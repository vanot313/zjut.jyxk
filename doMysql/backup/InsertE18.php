<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=2,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>添加名单</title>
    <script src="cssjs/jquery-3.2.1.min.js"></script>
</head>

<body>
<h1>结果页面<button value="back" onclick="javascript:window.location.href='Insert18.html'" > 返回 </button></h1>
<b>更新失败栏：</b><br>
<?php

$findStr = $_POST["findStr"];
$grade = $_POST["grade"];
$output = $_POST["output"];
//初始化查找字段，年级，输出

$mysql = new mysqli('localhost','root','Ji1234','yuage');
mysqli_query($mysql,"set names utf8");

//批量处理
$year = "18"; //学年
$strArray = explode(' ', $findStr);			//将findStr中得到的数据按空格划分为字段存入strArray
$j = 0;
while(($j+2)<count($strArray)) {
											//
    $i = 0;
    $insertStr = array();					//用来储存网页上输入的数据
    while($i<3 && $j<count($strArray)) {
        if($strArray[$j] != "") {
            $insertStr[$i] = $strArray[$j];
            $i++;
            $j++;
        }
        else {
            $j++;
        }
    }
    //开始查询插入
    if($i == 3){
        $db = array($grade."级".$year."学年人文讲座",
            $grade."级".$year."学年科技讲座",
            $grade."级".$year."学年人文比赛",
            $grade."级".$year."学年科技比赛");
        for($k = 0; $k < 4; $k++) {
            $query1 = $mysql->query("SELECT * FROM $db[$k] WHERE A = '$insertStr[0]'");	//找到学号为指定的行
            if(!$query1){																//没找到输出error
                die("select error");
            }else if(mysqli_num_rows($query1) != 0){
                /* 此处用来修改已添加的名单班级
                $query3 = $mysql->query("UPDATE `$db[$k]` SET `C` = '$insertStr[2]' WHERE `A` = '$insertStr[0]'");
                if($query3){
                    $oneOutput = "$insertStr[0] $insertStr[1] 在 $db[$k] 中成功修改为 $insertStr[2]！<br>";
                    $output = $oneOutput.$output;
                    continue;
                }else{
                    $oneOutput = "$insertStr[0] $insertStr[1] 在 $db[$k] 中修改失败！<br>";
                    echo $oneOutput;
                    continue;
                }
                */
                /* 此处用来判断是否添加过 */
                $oneOutput = "$insertStr[0] $insertStr[1] $insertStr[2] 已经在 $db[$k] 中！<br>";
                echo $oneOutput;
                continue;

            }

            $query2 = $mysql->query("INSERT INTO $db[$k](`A`, `B`, `C`) VALUES ('$insertStr[0]','$insertStr[1]','$insertStr[2]')");
			//将目标数据插入到对应表中
            if ($query2) {
                $oneOutput = "$insertStr[0] $insertStr[1] $insertStr[2] 成功添加进 $db[$k] 中！<br>";
                $output = $oneOutput.$output;
                continue;
            }
        }
    }
}

?>

<form action="InsertE.php" method="post">
    <br />
    <p>
        <?php
        echo "添加人学号+姓名+班级";
        ?>
        <input type="text" name="findStr">
    </p>
    <select name="grade">
		<option value="19">19级</option>
        <option value="18">18级</option>
        <option value="17">17级</option>
        <option value="16">16级</option>
    </select>
    <input type="hidden" name="output" value="<?php echo $output; ?>">
    <p> <input type="submit" value="  提交  "></p>
</form>
<br />
<h3>已添加：</h3>
<?php
echo $output;
?>
</body>

<script>
    var grade = <?php echo $grade; ?>;
    $(" select option[value='"+grade+"']").attr("selected","selected");
</script>
</html>