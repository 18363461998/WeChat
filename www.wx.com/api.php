<?php 

//主动请求url的接口数组
return 
[
	//获取access_token的微信接口地址
	'access_token_url'=>'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
];