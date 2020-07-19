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
<h1>结果页面<button value="back" onclick="javascript:window.location.href='InsertTYB.html'" > 返回 </button></h1>
<b>更新失败栏：</b><br>
<?php

$findStr = $_POST["findStr"];
$output = $_POST["output"];

$mysql = new mysqli('localhost','root','Ji1234','tyb');
mysqli_query($mysql,"set names utf8");

//批量处理
$strArray = explode(' ', $findStr);
$j = 0;
while(($j+4)<count($strArray)) {
    $i = 0;
    $insertStr = array();
    while($i<5 && $j<count($strArray)) {
        if($strArray[$j]!="" && $strArray[$j]!=" ") {
            $insertStr[$i] = $strArray[$j];
            $i++;
            $j++;
        }
        else {
            $j++;
        }
    }
    //开始查询插入
    if($i == 5){
        $db = "19year";
        $query1 = $mysql->query("SELECT * FROM `$db` WHERE `num` = '$insertStr[0]'");
        if(!$query1){
            echo "select error";
            die("select error");
        }else if(mysqli_num_rows($query1) != 0){
            $oneOutput = "$insertStr[0]，$insertStr[1] 已经在 $db 中！<br>";
            echo $oneOutput;
            continue;
        }
        $query1 = $mysql->query("INSERT INTO `$db` VALUES ('$insertStr[0]','$insertStr[1]','$insertStr[2]','$insertStr[3]','$insertStr[4]')");
        if(!$query1){
            $oneOutput = "'$insertStr[0]' error！<br>";
            echo $oneOutput;
            break;
        }else{
            $oneOutput = "'$insertStr[0]' success！<br>";
            $output = $output.$oneOutput;
            continue;
        }
//        for($k = 0; $k < 4; $k++) {
//            $query1 = $mysql->query("SELECT * FROM $db[$k] WHERE A = '$insertStr[0]'");
//            if(!$query1){
//                die("select error");
//            }else if(mysqli_num_rows($query1) != 0){
//                /* 此处用来修改已添加的名单班级
//                $query3 = $mysql->query("UPDATE `$db[$k]` SET `C` = '$insertStr[2]' WHERE `A` = '$insertStr[0]'");
//                if($query3){
//                    $oneOutput = "$insertStr[0] $insertStr[1] 在 $db[$k] 中成功修改为 $insertStr[2]！<br>";
//                    $output = $oneOutput.$output;
//                    continue;
//                }else{
//                    $oneOutput = "$insertStr[0] $insertStr[1] 在 $db[$k] 中修改失败！<br>";
//                    echo $oneOutput;
//                    continue;
//                }
//                */
//                /* 此处用来判断是否添加过 */
//                $oneOutput = "$insertStr[0] $insertStr[1] $insertStr[2] 已经在 $db[$k] 中！<br>";
//                echo $oneOutput;
//                continue;
//
//            }
//
//            $query2 = $mysql->query("INSERT INTO $db[$k](`A`, `B`, `C`) VALUES ('$insertStr[0]','$insertStr[1]','$insertStr[2]')");
//            if ($query2) {
//                $oneOutput = "$insertStr[0] $insertStr[1] $insertStr[2] 成功添加进 $db[$k] 中！<br>";
//                $output = $oneOutput.$output;
//                continue;
//            }
//        }
    }
}

?>

<form action="InsertTYBE.php" method="post">
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