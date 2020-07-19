<?php
require_once ("19connect.php");
//@header("Content-type: text/html;charset=utf-8");

/*
//检测是否超过报名期限10月12日10:00:00
$dateline = date("mdHis");
if($dateline > "1012100000"){
    echo '2';
	exit();
}
*/
if(isset($_GET['numberSingle'])) {
	$name1 = $_GET['studentSingle'];
    $number = $_GET['numberSingle'];
    $telephone = $_GET['telephoneSingle'];
	$QQ = $_GET['QQSingle'];
    //$mysql = new mysqli('localhost','root','ykj199743','apply');
	$stmt = $mysql->prepare("INSERT INTO zhb(name, number, telephone,QQ) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss", $name1, $number, $telephone, $QQ);
	$res = $stmt->execute();
    if($res){
        echo '1';
    }else{
        echo '0';
    }
}else if(isset($_GET['numberGouple'])) {
    $number = $_GET['numberGouple'];
    $name1 = $_GET['studentGouple1'];
    $telephone = $_GET['telephoneGouple'];
	$QQ = $_GET['QQSingle'];
    $name2 = $_GET['studentGouple2'];
    $name3 = $_GET['studentGouple3'];
	$name4 = $_GET['studentGouple4'];
	
    //$mysql = new mysqli('localhost', 'root', 'Ji123456', 'apply');
    $stmt = $mysql->prepare("INSERT INTO zhb(name, number, telephone, QQ,name1, name2, name3) VALUES (?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssss", $name1, $number, $telephone, $QQ, $name2, $name3, $name4);
	$res = $stmt->execute();
	if ($res) {
        echo '1';
    } else {
        echo '0';
    }
}

$stmt->close();
$mysql->close();

//$number = $_GET['number'];
//$theme = $_GET['theme'];
//$student = urlencode($student);
//$theme = urlencode($theme);

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/6
 * Time: 19:29
 */
?>