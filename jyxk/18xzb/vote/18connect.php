<?php
    require_once("18config.php");
    //连库
    $mysqli = mysqli_connect(HOST,USERNAME,PASSWORD);
    if(!$mysqli){
        die("连接数据库错误：". mysqli_error());
    }
    //选库
    if(!mysqli_select_db($mysqli,'18xzb')){
        die("选择数据库错误：". mysqli_error());
    }
    if(!mysqli_query($mysqli,'set names utf8')){
        die("设定字符集错误：". mysqli_error());
    }

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/28
 * Time: 16:44
 */
?>