<?php
    require_once("19config.php");
    //连库
    $mysql = mysqli_connect(HOST,USERNAME,PASSWORD);
    if(!$mysql){
        die("连接数据库错误：". mysqli_error());
    }
    //选库
    if(!mysqli_select_db($mysql,'19yspy')){
        die("选择数据库错误：". mysqli_error());
    }
    if(!mysqli_query($mysql,'set names utf8')){
        die("设定字符集错误：". mysqli_error());
    }

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/7/28
 * Time: 16:44
 */
?>