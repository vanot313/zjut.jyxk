<?php

    $appid = 'wx93a2841cbddac31e';
    $redirect_uri = urlencode('http://wzcwzc.top/18yspy/vote/getAccessToken.php');

    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1234#wechat_redirect";
    header('location:'.$url);
	exit();
?>