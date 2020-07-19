<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=2,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>添加加分项目</title>
</head>

<body>

<h1>结果页面<button value="back" onclick="javascript:window.location.href='InsertField.html'" > 返回 </button></h1>
<b>更新失败栏：</b><br>
<?php

if(isset($_POST["output"])) //输出信息
    $output = $_POST["output"];
else
    $output = "";
$grade = array();   //添加年级
for ($i = 16; $i<20; $i++){
    if(isset($_GET[$i])){
        $grade[$i] = true;
    }else{
        $grade[$i] = false;
    }
}

$mysql = new mysqli('localhost','root','Ji1234','yuage');
mysqli_query($mysql,"set names utf8");

$year = 19; //学年
if(isset($_GET["project"])){
    $project=$_GET["project"];   //加分项目
    for ($i=16; $i<20; $i++){
        //构造$db作为表单名
        if ($grade[$i] == true){
            $db = $i."级".$year."学年";
        }else{
            continue;
        }
        if ($_GET["db1"] == "rw"){ $db = $db."人文"; }
        else{ $db = $db."科技"; }
        if ($_GET["db2"] == "jz"){ $db = $db."讲座"; }
        else{ $db = $db."比赛"; }
        //查找是否存在该项目
        $queryField = $mysql->query("SELECT * FROM $db WHERE 1");
        $ifFindProject = false;
        while($fieldInfo = mysqli_fetch_field($queryField)){
            if($fieldInfo->name == $project){
                $ifFindProject = true;
                break;
            }
        }
        //存在则更新失败，不存在则插入项目
        if($ifFindProject == true){
            echo $project."更新失败，在".$db."中已存在<br>";
        }else{
            $queryInsert = $mysql->query("ALTER TABLE `$db` ADD `$project` FLOAT NULL DEFAULT NULL");
            if($queryInsert){
                $oneOutput = $project." 在 ".$db." 中插入成功<br>";
                $output = $output.$oneOutput;
            }else{
                echo $project."因未知原因在".$db."中更新失败，请重试<br>";
            }
        }
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