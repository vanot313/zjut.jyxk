<?php 
/* 配置连接微信公众号id与密钥 */

/* 测试公众号
$appid = "wx2c83bd79d6b3d518"; 
$appsecret = "1f5d71213437684789a93c3da2783e87"; 
*/

/* 学科 */
$appid = "wx48ce83ca21c18ab0"; 
$appsecret = "bf8b1b034cac673e5924acdc91d7fd6b";

$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret"; 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 
curl_close($ch); 
$jsoninfo = json_decode($output, true); 
$access_token = $jsoninfo["access_token"]; 

/* 微信下栏部分功能按钮 */
$jsonmenu = '
	{"button":[
		{
			"name":"科技创新",
			"sub_button":[
			{
				"type":"view",
				"name":"赛事介绍",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/kjcx/index_bs.html"
			},{
				"type":"view",
				"name":"创新政策",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/kjcx/index_info.html"
			},{
				"type":"view",
				"name":"科技团队",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/kjcx/index_team.html"
			}]
		},{
			"name":"创新分",
			"sub_button":[{
				"type":"view",
				"name":"加分公示",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/cxf/index_jz.php"
			},{
				"type":"click",
				"name":"查询创新分",
				"key":"search"
			}]
		},{
			"name":"关于我们",
			"sub_button":[{
				"type":"view",
				"name":"部门介绍",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/gywm/index.html"
			},{
				"type":"view",
				"name":"部门赛事",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/gywm/index_huodong.html"
			}
	]}
 ';
 
 /*	缺少维护的页面
			{
				"type":"view",
				"name":"最新赛事",
				"url":"http://www.vanot.top/jyxk/wechat/display_pages/kjcx/index_newbs.html"
			},
*/
 
 /* 
			"type":"view",
			"name":"原声配音报名",
			"url":"http://www.vanot.top/18yspy/apply/18yspy.html"
			
			"type":"click",
			"name":"原声配音投票",
			"key":"yspy"
			
			"type":"view",
			"name":"fly杯报名",
			"url":"http://www.vanot.top/jyxk/apply/17fly2.0.html"
			
		
			"type":"view",
			"name":"辩论赛报名",
			"url":"http://www.vanot.top/apply/bls.html"
			
			"type":"click",
			"name":"大众评分",
			"key":"yj"
			
			"type":"click",
			"name":"星主播投票",
			"key":"xzb"
*/

$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token; 
$result = https_request($url, $jsonmenu); 
var_dump($result); 
  
function https_request($url,$data = null){ 
 $curl = curl_init(); 
 curl_setopt($curl, CURLOPT_URL, $url); 
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
 if (!empty($data)){ 
  curl_setopt($curl, CURLOPT_POST, 1); 
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
 } 
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
 $output = curl_exec($curl); 
 curl_close($curl); 
 return $output; 
}


/*被弃用的代码段 因为不知道还有没有用 因此保留*/
/*
//该页面要访问过才能实现（即网页运行）
		//{一级菜单}
			//{二级菜单}
$body='
	{"button":[
		{
			"name":"科技创新",
			"sub_button":[
			{
				"type":"view",
				"name":"创新政策(必点)",
				"url":"http://jijy.top/jyxk/wechat/display_pages/kjcx/index_info.html"
			},{
				"type":"view",
				"name":"榜样学子",
				"url":"http://jijy.top/jyxk/wechat/display_pages/kjcx/index_people.html"
			},{
				"type":"view",
				"name":"科技团队",
				"url":"http://jijy.top/jyxk/wechat/display_pages/kjcx/index_team.html"
			},{
				"type":"view",
				"name":"赛事介绍",
				"url":"http://jijy.top/jyxk/wechat/display_pages/kjcx/index_bs.html"
			},{
				"type":"view",
				"name":"最新赛事",
				"url":"http://jijy.top/jyxk/wechat/display_pages/kjcx/index_newbs.html"
			}]
		},{
			"name":"创新分",
			"sub_button":[{
				"type":"view",
				"name":"加分公示",
				"url":"http://jijy.top/jyxk/wechat/display_pages/cxf/index_bs.html"
			},{
				"type":"click",
				"name":"16级16学年分数",
				"key":"16"
			},{
				"type":"click",
				"name":"15级16学年分数",
				"key":"15"
			},{
				"type":"click",
				"name":"14级16学年分数",
				"key":"14"
			},{
				"type":"click",
				"name":"13级4年分数",
				"key":"13"
			}]
		},{
			"name":"关于我们",
			"sub_button":[{
				"type":"view",
				"name":"部门赛事",
				"url":"http://jijy.top/jyxk/wechat/display_pages/gywm/index_huodong.html"
			},{
				"type":"view",
				"name":"部门介绍",
				"url":"http://jijy.top/jyxk/wechat/display_pages/gywm/index.html"
			}],{
				"type":"view",
				"name":"fly杯报名",
				"url":"http://jijy.top/apply/17fly.html"
			}]
		}
	]}
 ';*/

?>