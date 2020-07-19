<?php
require_once ("19connect.php");

$team = $_GET['teamName'];
$leader = $_GET['teamLeader'];
$telephone = $_GET['teamPhone'];
$QQ = $_GET['teamQQ'];
$num = $_GET['teamNum'];
$teamer_2 = $_GET['teamer_2'];
$teamer_3 = $_GET['teamer_3'];
$teamer_4 = $_GET['teamer_4'];
$teamer_5 = $_GET['teamer_5'];

$stmt = $mysql->prepare("INSERT INTO player_apply (team, leader, telephone, QQ, num , teamer_2, teamer_3, teamer_4, teamer_5) VALUES (?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("sssssssss", $team, $leader, $telephone, $QQ, $num , $teamer_2, $teamer_3, $teamer_4, $teamer_5);
$res = $stmt->execute();
if($res){
    echo "1";
}else{
    echo "0";
}

$stmt->close();
$mysql->close();

?>