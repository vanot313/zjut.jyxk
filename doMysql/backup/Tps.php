<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=2,user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>统票</title>
</head>

<body>
<h1>结果页面<button value="back" onclick="javascript:window.location.href='Tp.html'" > 返回 </button></h1>
<b>更新失败栏：</b><br>
<?php

if(isset($_POST["output"])) //输出信息
    $output = $_POST["output"];
else
    $output = "";
$project=$_GET["project"];   //加分项目
$points=$_GET["points"];    //加分分数
$method=$_GET["method"];    //检索方式
$scoreMethod = $_GET["scoreMethod"];    //加分方式

$postStr="method=".$method."&project=".$project."&points=".$points."&scoreMethod=".$scoreMethod;

$mysql = new mysqli('localhost','root','Ji1234','yuage');
mysqli_query($mysql,"set names utf8");

$year = 19; //学年
if(isset($_POST["findStr"])){
    $findStr = $_POST["findStr"];
    $strArray = explode(' ',$findStr);
    for($j=0; $j<count($strArray); $j++){
        if($strArray[$j] != ""){//去掉空项
            //检索依据-列名
            if($method == "byxuehao")
                $column = "A";
            if($method == "byname")
                $column = "B";
            //检索依据-列名内容
            $findStr = $strArray[$j];
            //单个输出项
            $oneOutput = "NONE";

            for($grade=$year; $grade>($year-4); $grade--) {//19级加分频率大于16级
                //表单
                $db = array($grade."级".$year."学年人文讲座",
                    $grade."级".$year."学年科技讲座",
                    $grade."级".$year."学年人文比赛",
                    $grade."级".$year."学年科技比赛");
                $queryFindStr = $mysql->query("SELECT * FROM $db[0] WHERE $column = '$findStr'");
                $rowFindStr = mysqli_fetch_array($queryFindStr);
                if(count($rowFindStr)!=0) {
                    //循环表单
                    for ($k = 0; $k < 4; $k++){
                        $sheet = $db[$k];
                        //查找
                        $queryFindStr = $mysql->query("SELECT * FROM $sheet WHERE $column = '$findStr'");
                        $ifFindProject = false; //是否找到对应的加分项目列
                        while ($fieldInfo = mysqli_fetch_field($queryFindStr)) {
                            if($fieldInfo->name == $project){
                                $ifFindProject = true;
                                //加分方式
                                if($scoreMethod == "byAdd") {
                                    $rowFindStr = mysqli_fetch_array($queryFindStr);
                                    $iniScore = $rowFindStr[$project];
                                    if ($iniScore == NULL) {
                                        $iniScore = 0;
                                    }
                                    $finalScore = doubleval($iniScore) + doubleval($points);
                                }else{
                                    $finalScore = $points;
                                }
                            }
                        }
                        if ($ifFindProject == false){ continue; }
                        //开始加分
                        if ($mysql->query("UPDATE $sheet SET $project = $finalScore WHERE $column = '$findStr'")) {
                            $query2 = $mysql->query("SELECT * FROM $sheet WHERE $column = '$findStr' AND $project LIKE '%$finalScore%' ");
                            if (mysqli_num_rows($query2) != 0) {
                                $row2 = $query2->fetch_array();
                                $oneOutput = $row2['C'] . " " . $row2['B'] . " " . $sheet . " " . $project . " " . $row2[$project] . "<br>";
                                break;
                            }
                        }
                    }
                }
                //判断是否已加好
                if ($oneOutput != "NONE"){
                    $output = $oneOutput.$output;
                    break;
                }
            }
            if ($oneOutput == "NONE"){
                echo $findStr." 更新失败 没有检索到<br>";
            }
        }
    }
}

?>
<form action="Tps.php?<?php echo $postStr; ?>" method="post">
    <br />
    <p>
        <?php
        if($method=="byname") echo "姓名：";
        if($method=="byxuehao") echo "学号：";
        ?>
        <input type="text" name="findStr"></p>
    <input type="hidden" name="output" value="<?php echo $output; ?>">
    <p> <input type="submit" value="  提交  "></p>
</form>
<br />
<h3>已添加：</h3>
<?php
echo $output;
?>
</body>
</html>