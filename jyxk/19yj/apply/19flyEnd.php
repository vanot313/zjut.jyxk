<?php
require_once ("19connect.php");
@header("Content-type: text/html;charset=utf-8");

/*
//检测是否超过报名期限10月12日10:00:00
$dateline = date("mdHis");
if($dateline > "1012100000"){
    echo '2';
	exit();
}
*/
if(isset($_GET['numberSingle'])) {
    $number = $_GET['numberSingle'];
    $name1 = $_GET['studentSingle'];
    $telephone = $_GET['telephoneSingle'];
	$QQ = $_GET['QQsingle'];
	$contest = "fly";
	
	$mysql = new mysqli('localhost','root','Ji1234','apply');

	$stmt = $mysql->prepare("INSERT INTO 19yj(name, number, telephone, QQ, contest) VALUES (?,?,?,?,?)");
	
	$stmt->bind_param("sssss", $name1, $number, $telephone, $QQ, $contest);
	$res = $stmt->execute();
    if($res){
        echo '1';
    }else{
        echo '0';
    }
}

/*else if(isset($_GET['numberGroup'])) {
    $number = $_GET['numberGroup'];
    $name1 = $_GET['studentGroup1'];
    $telephone = $_GET['telephoneGroup'];
    $name2 = $_GET['studentGroup2'];
    $name3 = $_GET['studentGroup3'];
    $name4 = $_GET['studentGroup4'];

	
    //$mysql = new mysqli('localhost', 'root', 'Ji1234', 'apply');
    $stmt = $mysql->prepare("INSERT INTO 17yj(name, number, telephone, name1, name2, name3, dateline) VALUES (?,?,?,?,?,?,?)");
	$stmt->bind_param("sssssss", $name1, $number, $telephone, $name2, $name3, $name4, $dateline);
	$res = $stmt->execute();
	if ($res) {
        echo '1';
    } else {
        echo '0';
    }
}*/

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