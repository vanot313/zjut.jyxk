<?php

	$appid = 'wx2c83bd79d6b3d518';
    //$appid = 'wx93a2841cbddac31e';
	
    $redirect_uri = urlencode('http://www.vanot.top/jyxk/19yj/vote/getAccessToken.php');

    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1234#wechat_redirect";
    header('location:'.$url);
	exit();
?>