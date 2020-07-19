<?php

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
	
	//检查签名
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function transmitNews($object, $newsArray)
    {

        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[news]]></MsgType>
                   <ArticleCount>%s</ArticleCount>
                   <Articles>
                   $item_str</Articles>
                   </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

	//消息回复
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $MsgType = $postObj->MsgType;						//消息类型
            $time = time();
            $Event = $postObj->Event;							//事件的内容
            $EventKey = $postObj->EventKey;						//key
            $ScanType = $postObj->ScanCodeInfo->ScanType;
            $ScanResult =$postObj->ScanCodeInfo->ScanResult;	//扫描结果
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";

			//自定义菜单点击回复
            if($Event=="CLICK")
            {
                //19级 18级 17级 16级 创新分查询
                if($EventKey == "search"){
                    $openId = $fromUsername;
                    $mysql = new mysqli('localhost','root','Ji1234','yuage');
                    mysqli_query($mysql,"set names utf8");

                    $query1 = $mysql->query("SELECT * FROM 学号绑定 WHERE A = '$openId' ");
                    $row1 = $query1->fetch_array();   //row1是学号绑定表中对应openid的一行
                    if(isset($row1['B'])) {       //row1['A']openId  row1['B']stuId
                        $stuId = $row1['B'];      //stuId => 学号  openId => 微信id
                        $year = 19; //学年
                        $contentStr = "";   //初始化输出内容

                        //查找该学号对应的年级
                        $grade = $year;
                        while ($grade > ($year - 4)) {  //从19到16级查找，一定程度解决留级学生问题
                            $db = $grade . "级" . $year . "学年人文讲座";
                            $query2 = $mysql->query("SELECT * FROM $db WHERE A = '$stuId' ");
                            if (mysqli_num_rows($query2) != 0) {
                                break;
                            }
                            $grade--;
                        }
                        if ($grade == ($year - 4)) {
                            $contentStr = "未找到相关信息，\n请检查学号是否绑定有误！\n如有疑问请联系QQ:2834552723";
                        }

                        //查找该学号对应的分数
                            //16级 17级 18级 19级
                        if ($grade == 16 ||$grade == 17 || $grade == 18 || $grade == 19) {
                            $allCount = 0;  //至今为止全部总分
                            //从入学到最新学年
                            for ($y = $grade; $y <= $year; $y++) {
                                $xiangmu = array(); //活动的名字
                                $count = array();   //小分
                                $cou = array();    //总分
                                $db = array($grade . "级" . $y . "学年人文讲座",
                                    $grade . "级" . $y . "学年人文比赛",
                                    $grade . "级" . $y . "学年科技讲座",
                                    $grade . "级" . $y . "学年科技比赛");
                                //每一学年的创新分记录
                                for ($h = 0; $h < 4; $h++) {
                                    $sheet = $db[$h];
                                    $result = $mysql->query("SELECT * FROM $sheet WHERE A = '$stuId' ");
                                    $row = $result->fetch_array();  //row是创新分表单对应stuID的一行
                                    if ($row['A'] == NULL && $h == 0) {    //row['A'] 学号  row['B']名字   row['C']班级  row['DEF..']项目
                                        break;
                                    }
                                    $xiangmu[$h] = array();
                                    $count[$h] = array();
                                    $ziduan = array();
                                    while ($fieldinfo = mysqli_fetch_field($result)) {
                                        $ziduan[] = $fieldinfo->name;
                                    }
                                    $cou[$h] = 0;  //单项总分
                                    $k = 0;
                                    for ($j = 3; $j < count($ziduan); $j++) {
                                        if ($row[$ziduan[$j]] != NULL) {   //$row[$ziduan[$j]]具体的分数
                                            $xiangmu[$h][$k] = $ziduan[$j];
                                            $count[$h][$k] = $row[$ziduan[$j]];
                                            $cou[$h] += $row[$ziduan[$j]];
                                            $k++;
                                        }
                                    }
                                }
                                //每一学年的创新分统计与输出
                                $stuName = $row['B'];
                                if ($cou[0] > 0.2) $cou[0] = 0.2;
                                if ($cou[2] > 0.3) $cou[2] = 0.3;
                                if ($cou[0] + $cou[1] > $cou[2] + $cou[3]) $zongfen = ($cou[2] + $cou[3]) * 2; //判断人文分比科技分大
                                else $zongfen = $cou[0] + $cou[1] + $cou[2] + $cou[3]; //最终分
                                $allCount += $zongfen;
                                if ($stuName != null) {
                                    $contentStr = $contentStr."**".$y."学年\n"."**名字 ".$stuName."\n";
                                    $contentStr = $contentStr."**人文讲座总分 ".$cou[0] . "\n";
                                    for ($j = 0; $xiangmu[0][$j] != null; $j++) {
                                        $contentStr = $contentStr."  ".$xiangmu[0][$j]." ".$count[0][$j]."\n";
                                    }
                                    $contentStr = $contentStr."**人文比赛总分 ".$cou[1]."\n";
                                    for ($j = 0; $xiangmu[1][$j] != null; $j++) {
                                        $contentStr = $contentStr."  ".$xiangmu[1][$j]." ".$count[1][$j]."\n";
                                    }
                                    $contentStr = $contentStr."**科技讲座总分 ".$cou[2]."\n";
                                    for ($j = 0; $xiangmu[2][$j] != null; $j++) {
                                        $contentStr = $contentStr."  ".$xiangmu[2][$j]." ".$count[2][$j]."\n";
                                    }
                                    $contentStr = $contentStr."**科技比赛总分 ".$cou[3]."\n";
                                    for ($j = 0; $xiangmu[3][$j] != null; $j++) {
                                        $contentStr = $contentStr."  ".$xiangmu[3][$j]." ".$count[3][$j]."\n";
                                    }
                                    $contentStr = $contentStr."**最终分 ".$zongfen."\n";
                                } else {
                                    $contentStr = $contentStr."你不在".$y."学年名单中。"."\n";
                                }
                                $contentStr = $contentStr."\n";
                            }
                            $contentStr = $contentStr."**截止当前总分 ".$allCount;
                        }

                      
                    }else{           //未绑定
                        $contentStr = "请先绑定。请直接发送: 「 绑定#学号 」";
                    }

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }
				
				/*
                if($EventKey=="bbjd"){
                    $T="百部经典&名著演绎";
                    $D="点此查看比赛详情";
                    $P="";
                    $U="http://yanming.win/wechat/jyxk/bbjd/index.html";
                    $content[] = array("Title"=>$T, "Description"=>$D, "PicUrl"=>$P, "Url" =>$U);
                    $result = $this->transmitNews($postObj, $content);
                    echo $result;
                }
				*/
				
				/*新主播*/
				if($EventKey=="xzb"){
					$openid = $fromUsername;
					$mysqli = new mysqli('localhost','root','Ji1234','19xzb');
					$query1 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row1 = mysqli_fetch_array($query1);
					if(count($row1)==0){
						$mysqli->query("INSERT INTO apply_vote(openid) VALUES('$openid')");
					}
					$query2 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row2 = mysqli_fetch_array($query2);
					if(count($row2)!=0){
						$T = "「星主播」投票";
						$D = "请为你喜欢的选手投票\n";
						$P = "";
						$U = "http://www.vanot.top/19xzb/vote/19xzbVote.php?openid=".$openid;
						$content[] = array("Title" => $T, "Description" => $D, "PicUrl" => $P, "Url" => $U);
						$result = $this->transmitNews($postObj, $content);
						echo $result;
					}
					mysqli_close($mysqli);
				}
				
				/*创意代码*/
				if($EventKey=="cydm"){
					$openid = $fromUsername;
					$mysqli = new mysqli('localhost','root','Ji1234','19cydm');
					$query1 = $mysqli->query("SELECT * FROM vote WHERE openid = '$openid'");
					$row1 = mysqli_fetch_array($query1);
					if(count($row1)==0){
						$mysqli->query("INSERT INTO vote(openid) VALUES('$openid')");
					}
					$query2 = $mysqli->query("SELECT * FROM vote WHERE openid = '$openid'");
					$row2 = mysqli_fetch_array($query2);
					if(count($row2)!=0){
						$T = "「Running Code创意代码」投票";
						$D = "请为你喜欢的作品投票\n请务必通过计E学科公众号投票\n转发页面均无法投票\n";
						$P = "http://prnjgwevc.bkt.clouddn.com/0.jpg";
						$U = "http://www.vanot.top/19cydm/19cydm.php?openid=$openid&page=1";
						$content[] = array("Title" => $T, "Description" => $D, "PicUrl" => $P, "Url" => $U);
						$result = $this->transmitNews($postObj, $content);
						echo $result;
					}
				mysqli_close($mysqli);
				}
				
				/*演讲赛*/
				if($EventKey=="yj"){
					$openid = $fromUsername;
					$mysqli = new mysqli('localhost','root','Ji1234','19yj');
					mysqli_query($mysqli, "set names utf8");
					$query1 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row1 = mysqli_fetch_array($query1);
					if(count($row1)==0){
						$mysqli->query("INSERT INTO apply_vote(openid) VALUES('$openid')");
					}
					$query2 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row2 = mysqli_fetch_array($query2);
					if(count($row2)!=0){
						$T = "【新生中文演讲赛】";
						$D = "请为选手评分\n";
						$P = "";
						$U = "http://www.vanot.top/19yj/vote/19cybVote.php?openid=$openid&num=1";
						$content[] = array("Title" => $T, "Description" => $D, "PicUrl" => $P, "Url" => $U);
						$result = $this->transmitNews($postObj, $content);
						echo $result;
					}
					mysqli_close($mysqli);
				}
				
				/*原声配音*/
				if($EventKey=="yspy"){
					$openid = $fromUsername;
					$mysqli = new mysqli('localhost','root','Ji1234','19yspy');
					$query1 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row1 = mysqli_fetch_array($query1);
					if(count($row1)==0){
						$mysqli->query("INSERT INTO apply_vote(openid) VALUES('$openid')");
					}
					$query2 = $mysqli->query("SELECT * FROM apply_vote WHERE openid = '$openid'");
					$row2 = mysqli_fetch_array($query2);
					if(count($row2)!=0){
						$T = "「英文原声配音」投票";
						$D = "请为你喜欢的小组投票\n我们将通过投票票数决出「最佳人气奖」\n";
						$P = "http://www.vanot.top/jyxk/19yspy/yspy_cover.jpg";
						$U = "http://www.vanot.top/jyxk/19yspy/vote/19yspyVote.php?openid=".$openid;
						$content[] = array("Title" => $T, "Description" => $D, "PicUrl" => $P, "Url" => $U);
						$result = $this->transmitNews($postObj, $content);
						echo $result;
					}
					mysqli_close($mysqli);
				}
            }
			
			
			
            /* 被弃用的代码 因为不知道还有没有用 因此保留
			if($EventKey=="yspy_vote"){
                $id = $fromUsername;
                $mysql = new mysqli('localhost','root','root','vote');
                $res1 = $mysql->query("SELECT * from openids WHERE openid = '$id'");
                $row1 = $res1->fetch_array();
                $res2 = $mysql->query("SELECT * from vote_openids WHERE openid = '$id'");
                $row2 = $res2->fetch_array();
                if(!$row2) {
                    $mysql->query("INSERT INTO openids(openid) VALUES('$id')");
                    $mysql->query("INSERT INTO vote_openids(openid,info,vote) VALUES('$id',null,0)");
                }
                $res2 = $mysql->query("SELECT * from vote_openids WHERE openid = '$id'");
                $row2 = $res2->fetch_array();
                if($row2){
                    $msgType = "news";
                    $T = "「英语原生配音」小组投票";
                    $D = "请为你喜欢的小组投票\n我们将通过投票票数决出「最佳人气奖」\n你还有" . (3 - $row2['vote']) . "票";
                    $P = "";
                    $U = "http://yuage.top/vote/yspy/vote.php?type=1&openid=" . $id;
                    $content[] = array("Title" => $T, "Description" => $D, "PicUrl" => $P, "Url" => $U);
                    $result = $this->transmitNews($postObj, $content);
                    echo $result;
                }
            }
			*/
			
			
			//被关注时
            if($Event=="subscribe")
            {
                $msgType = "text";
                $contentStr = "人文匠心，科技情怀，\n技术创新，为你而行！\n感谢你关注『计E学科』~";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
			
			//关键字 绑定
            if(strpos($keyword,'绑定#') !== false){
                $arr = explode('#',$keyword);   //拆分 查分 +  stuId
                $stuId = $arr[1];
                $regex = "/^201[\d]{9}$/";
                $msgType = "text";
                if(preg_match($regex, $stuId)){
                    $openId = $postObj->FromUserName;
                    $mysql = new mysqli('localhost','root','Ji1234','yuage');
                    $mysql_result = $mysql->query("SELECT * FROM 学号绑定 WHERE A = '$openId'");
                    $row = $mysql_result->fetch_array();
                    if($row['B'] != null){
                        $res = $mysql->query("UPDATE 学号绑定 SET B = $stuId WHERE A = '$openId'");
                    }else{
                        $res = $mysql->query("INSERT INTO 学号绑定 (A, B) VALUES ('$openId', '$stuId')");
                    }

                    if($res){
                        $contentStr = "绑定成功";
                    }else{
                        $contentStr = "绑定失败，请重试";
                    }

                }else{
                    $contentStr = "请检查绑定的帐号是否有误。\n(学号应为12位数字)";
                }
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;

            }
			
			
			
			/*从这开始，为一些曾被考虑的但是并未实现的功能*/
            /*
            if($keyword == '学科课表填写'){
                $msgType = "text";
                $contentStr = "http://yuage.top/getlist/index.html";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword == '学科课表查看'){
                $msgType = "text";
                $contentStr = "http://yuage.top/getlist/display.php";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }*/
			/*if(strpos($keyword,'投票') !== false){
                $msgType = "text";
                $contentStr = "http://120.77.248.191/jyxk/18yspyVote.php";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }*/
            if(strpos($keyword,'闪讯') !== false){
                $msgType = "text";
                $contentStr = "十分抱歉，由于种种原因，我们关闭了「查询闪讯」的功能。我们会努力在不久的将来重新为大家开放。敬请期待。";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            // if(strpos($keyword,'创新分') !== false){
            //     $msgType = "text";
            //     $contentStr = "当前只能查询个人该学年的创新分哦，我们将在今后开放对之前学年创新分的查询。";
            //     $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            //     echo $resultStr;
            // }
            if(strpos($keyword,'课表') !== false){
                $msgType = "text";
                $contentStr = "不好意思，现在无法查询课表哦，我们将尽快为大家开放哦。";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword == "时间") {
                $msgType = "text";
                $contentStr = date("Y年m月d日 H点i分s秒",time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, "现在是".$contentStr);
                echo $resultStr;
            }
			/*到此*/
			
			

			//不满足上述要求
            if($keyword){
                $msgType = "text";
                $contentStr = "创新分疑问联系蒋同学:QQ2834552723\n绑定格式：「 绑定#学号 」\n 已绑定的也可以再次按上述格式重新绑定\n有其它问题也可留言,我们十分愿意为你效劳!";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }

        }else{
            echo "";
            exit;
        }
    }
}
?>