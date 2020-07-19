<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=2,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>添加加分公示</title>
</head>

<body>

<h1>结果页面<button value="back" onclick="javascript:window.location.href='InsertJFGS.html'" > 返回 </button></h1>
<b>更新失败栏：</b><br>
<?php

if(isset($_POST["output"])) //输出信息
    $output = $_POST["output"];
else
    $output = "";

if(isset($_GET["type"]) && isset($_GET["title"]) && isset($_GET["scoreT"]) && isset($_GET["href"])){
    $type = $_GET["type"];
    $title = $_GET["title"];
    $scoreT = $_GET["scoreT"]=="rw"?"人文":"科技";
    $href = $_GET["href"];
	$href = str_replace('120.77.248.181/jyxk','wzcwzc.top',$href);
}else{
    exit(-1);
}

$score="";
if (isset($_GET["score1"])){
    $score=$score.$_GET["score1"];
}else{
    exit(-2);
}
if (isset($_GET["score2"]) && $_GET["score2"]!=""){
    $score=$score."-".$_GET["score2"];
}

$grade="";
if (isset($_GET["grade1"])){
    $grade=$grade.$_GET["grade1"];
}else{
    exit(-3);
}
if (isset($_GET["grade2"]) && $_GET["grade2"]!=""){
    $grade=$grade."-".$_GET["grade2"];
}

$year = date("Y");
$month = date("m");
$day = date("d");
$term = ($month>2 && $month<8)?2:1;

$mysql = new mysqli('localhost','root','Ji1234','yuage');
mysqli_query($mysql,"set names utf8");
$res1 = $mysql->query("SELECT * FROM `jfgs` WHERE `title`='$title'");
if($res1->fetch_array()){
    echo "已存在：".$title."<br>";
}else{
    $res2 = $mysql->query("INSERT INTO `jfgs` VALUES ('$term','$month','$day','$title','$scoreT','$score','$grade','$href','$type','$year')");
    if($res2){
        $output=$output."成功添加：".$title."<br>";
    }else{
        echo "添加失败：".$title."<br>";
    }
}

?>
<br />
<h3>已添加：</h3>
<?php
echo $output;
?>
</body>
</html>