<?php
header("Content-Type: text/plain;charset=utf-8");
$mysql = new mysqli('localhost','root','Ji1234','yuage');
if($mysql->connect_errno){
    die('ERROR:'.$mysql->connect_error);
}
$mysql->set_charset('utf8'); //设计字符集
$db = array();

/****修改点****/
$db[0]=array("17级19学年人文讲座", "17级19学年人文比赛", "17级19学年科技讲座", "17级19学年科技比赛");
$db[1]=array("17级18学年人文讲座", "17级18学年人文比赛", "17级18学年科技讲座", "17级18学年科技比赛");
$db[2]=array("17级17学年人文讲座", "17级17学年人文比赛", "17级17学年科技讲座", "17级17学年科技比赛");
/****修改点****/

/****修改点****/
$result_stu =  $mysql->query("SELECT * FROM 17级19学年科技讲座");
/****修改点****/

$line = 0;
$score = array();
$people = array();  //存总分分数
$ziduan = array();  //各个比赛的名称
$count = array();
while ($row_stu = $result_stu->fetch_array()){
    $line++;
    $people[$line] = array();
    $people[$line][0] = array($row_stu['A'],$row_stu['B'],$row_stu['C']); //个人信息
    $stu = $row_stu['A'];  //学号

/****修改点****/
    for($y = 0;$y < 3; $y++) {
/****修改点****/		

        for ($h = 0; $h < 4; $h++) {
            $sheet = $db[$y][$h];
            $result = $mysql->query("SELECT * FROM $sheet WHERE A = '$stu' ");
            $row = $result->fetch_array();
            //字段 存每个比赛的名字
            $ziduan[$h + $y * 4] = array();
            while ($fieldinfo = mysqli_fetch_field($result)) {
                $ziduan[$h + $y * 4][] = $fieldinfo->name;
            }

            //分数 每个人的小分 和 总分
            $people[$line][$h + $y * 4 + 1] = array();

            $count[$h]=0;  //各模块的和
            for($j = 3;$j < count($ziduan[$h + $y * 4] );$j++){
                $people[$line][$h + $y * 4 + 1][$j - 2] = $row[$ziduan[$h + $y * 4][$j]];
                if($row[$ziduan[$h + $y * 4][$j]] != NULL){
                    $count[$h] += $row[$ziduan[$h + $y * 4][$j]];
                }
            }

            switch ($h){
                case 0:  //人文讲座和
                    if($count[$h]>0.2) $count[$h] = 0.2;
                    $people[$line][$h + $y * 4 + 1][0] = $count[$h];
                    break;
                case 1: //人文比赛
                    $people[$line][$h+$y * 4 + 1][0] = $count[$h];
                    break;
                case 2:  //科技讲座
                    if($count[$h]>0.3) $count[$h]=0.3;
                    $people[$line][$h+$y * 4 + 1][0] = $count[$h];
                    break;
                case 3: //科技比赛
                    $people[$line][$h+$y * 4 + 1][0] = $count[$h];
                    //总分
                    if($count[0]+$count[1] > $count[2]+$count[3]) $zongfen=($count[2]+$count[3])*2;
                    else $zongfen=$count[0]+$count[1]+$count[2]+$count[3];
                    $people[$line][0][3 + $y] = $zongfen;
                    break;
            }
        }
    }
	
    /****修改点****//*总分*/
    $people[$line][0][6] = $people[$line][0][3]+$people[$line][0][4]+$people[$line][0][5];
	/****修改点****/	

//        if($line==1) break;
}
//print_r($people);



include "PHPExcel.php";
date_default_timezone_set('Asia/Shanghai');
header("Content-Type: text/plain;charset=utf-8");

$objPHPExcel = new PHPExcel();

$objPHPExcel
    ->getProperties()  //获得文件属性对象，给下文提供设置资源
    ->setCreator( "yanming")                 //设置文件的创建者
    ->setLastModifiedBy( "yanming")          //设置最后修改者
    ->setTitle( "Office 2007 XLSX Test Document" )    //设置标题
    ->setSubject( "Office 2007 XLSX Test Document" )  //设置主题
    ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
    ->setKeywords( "office 2007 openxml php")        //设置标记
    ->setCategory( "Test result file");
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', '学号')
    ->setCellValue('B1', '姓名')
    ->setCellValue('C1', '班级')
	
	/****修改点****/
    ->setCellValue('D1', '19年')
    ->setCellValue('E1', '18年')
    ->setCellValue('F1', '17年')
    ->setCellValue('G1', '0')
	/****修改点****/
	
    ->setCellValue('H1', '总')

	/****修改点****/
    ->setCellValue('I1', '19年人文讲座')
    ->setCellValue('J1', '19年人文比赛')
    ->setCellValue('K1', '19年科技讲座')
    ->setCellValue('L1', '19年科技比赛')
    ->setCellValue('M1', '18年人文讲座')
    ->setCellValue('N1', '18年人文比赛')
    ->setCellValue('O1', '18年科技讲座')
    ->setCellValue('P1', '18年科技比赛');
    ->setCellValue('Q1', '17年人文讲座')
    ->setCellValue('R1', '17年人文比赛')
    ->setCellValue('S1', '17年科技讲座')
    ->setCellValue('T1', '17年科技比赛')
////    ->setCellValue('U1', '13年人文讲')
////    ->setCellValue('V1', '13年人文比')
////    ->setCellValue('W1', '13年科技将')
////    ->setCellValue('X1', '13年科技比');
	/****修改点****/
	
;

for($i=1;$i<=$line;$i++) {
	$l = $i+1;
    $objPHPExcel->setActiveSheetIndex(0)
        
		/****修改点****/
		->setCellValue('A' . $l, $people[$i][0][0])
        ->setCellValue('B' . $l, $people[$i][0][1])
        ->setCellValue('C' . $l, $people[$i][0][2])
        ->setCellValue('D' . $l, $people[$i][0][3])
        ->setCellValue('E' . $l, $people[$i][0][4])
        ->setCellValue('F' . $l, $people[$i][0][5])
        ->setCellValue('G' . $l, 0)
        ->setCellValue('H' . $l, $people[$i][0][6])
		/****修改点****/
		
        ->setCellValue('I' . $l, $people[$i][1][0])
        ->setCellValue('J' . $l, $people[$i][2][0])
        ->setCellValue('K' . $l, $people[$i][3][0])
        ->setCellValue('L' . $l, $people[$i][4][0])
        ->setCellValue('M' . $l, $people[$i][5][0])
        ->setCellValue('N' . $l, $people[$i][6][0])
        ->setCellValue('O' . $l, $people[$i][7][0])
        ->setCellValue('P' . $l, $people[$i][8][0]);
////        ->setCellValue('Q' . $i, $people[$i][9][0])
////        ->setCellValue('R' . $i, $people[$i][10][0])
////        ->setCellValue('S' . $i, $people[$i][11][0])
////        ->setCellValue('T' . $i, $people[$i][12][0])
////        ->setCellValue('U' . $i, $people[$i][13][0])
////        ->setCellValue('V' . $i, $people[$i][14][0])
////        ->setCellValue('W' . $i, $people[$i][15][0])
////        ->setCellValue('X' . $i, $people[$i][16][0]);
}

$objPHPExcel->getActiveSheet()->setTitle('17');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel'); //excel头参数

/****修改点****/
header('Content-Disposition: attachment;filename="grade_17_With_3years.xls"');
/****修改点****/

header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'excel5'); //excel5为xls格式，excel2007为xlsx格式
$objWriter->save('php://output');
exit;
