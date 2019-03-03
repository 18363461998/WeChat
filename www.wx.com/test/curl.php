<?php 

$curl = "http://localhost:8090/file.php";
const BS = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36";

// $json = json_encode("id=1&name=cool",256);
$filepath = __DIR__.'img/48540923dd54564e29c20f94b9de9c82d0584ffc.jpg';
//兼容问题：如果是php5.5之前
// $data['pic'] = '@'.$filepath;

//php5.6之后，我们要写成这样
$data['pic'] = new CURLFile($filepath);
$data['id'] = 100;

//CURL进行请求
//初始化
$ch = curl_init();

//相关的设置
curl_setopt($ch,CURLOPT_URL,$curl);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//谁知请求 超时时间
curl_setopt($ch,CURLOPT_TIMEOUT,10);
curl_setopt($ch, CURLOPT_USERAGENT, BS);

//告诉curl使用了post请求
curl_setopt($ch,CURLOPT_POST,1);
//post数据，如果使用了json类型的数据信息，就必须加一个响应头

// curl_setopt($ch,CURLOPT_HTTPHEADER,
// 	[
// 	'Content-Type: application/json; charset=UTF-8'
// 	]);

curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

//文件的上传


$data = curl_exec($ch);

if(curl_errno($ch)>0)
{
	throw new Exception(curl_error($ch), 1000);	
}

curl_close($ch);
echo $data;