<?php

$info = new info();

class info 
{	

	private const TOKEN = 'weixin';

	private $obj;

	private $config;

	private $fun;

	public function __construct()
	{
		if(isset($_GET['echostr']))
		{
			echo $this->checkSignature();
		}else
		{
			$xml = file_get_contents('php://input');

			$this->config = include 'config.php';

			$this->obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		
			$this->writeLog($xml);

			$this->processingData();

		}
	}

	private function processingData()
	{
		$fun = $this->obj->MsgType.'Fun';
		echo $this->$fun();
	}


	/*
	用户提交的文本反馈
	 */
	private function textFun()
	{
		$content = "这是文本";

		//将用户保存到redis
		include 'connect.php';
		$cn = new connect();
		$link = $cn->getConnect();

		if(!$link->SISMEMBER("fromUSer",(string)$this->obj->FromUserName))
		{
		$link->sadd("fromUSer",(string)$this->obj->FromUserName);
		}

		if(stristr((string)$this->obj->Content,'图文-')) $content = "你发送了图文二字";
		$msg = $this->createText('text',$content);
		$this->writeLog($msg,1);
		return $msg;
	}	

	/*
	*书写要返回的内容
	 */

	private function createText(string $type,$cnt)
	{
		return sprintf($this->config[$type],$this->obj->FromUserName,$this->obj->ToUserName,time(),$cnt);
	}


	/*
	* 书写日志文件到本地
	* param1 判断是发送的操作还是接收的操作
	 */
	private function writeLog($xml,$flag=false)
	{
		$title = $flag ?  "发送" : "接收";
		$time = "日志记录时间:".date("Y-m-d H:i:s",time());
		$type = $title."的格式为".$this->obj->MsgType;
		$from = "发送者：".$this->obj->FromUserName;

		$log = $title.'日志信息，'.$time."\n";
		$log .= $type."\n";
		$log .= $from."\n";
		$log .= "=================================\n";
		$log .= $xml."\n";
		$log .= "=================================\n";

		file_put_contents("log.log", $log ,FILE_APPEND);
	}

	private function checkSignature()
	{
	    $signature = $_GET["signature"];
	    $timestamp = $_GET["timestamp"];
	    $nonce = $_GET["nonce"];
	    $echostr = $_GET["echostr"];

	    $tmpArr['token'] = self::TOKEN;
	    $tmpArr['timestamp'] = $timestamp;
	    $tmpArr['nonce'] = $nonce;

	    sort($tmpArr, SORT_STRING);

	    $tmpStr = implode($tmpArr);

	    $tmpStr = sha1($tmpStr);

	    if($signature == $tmpStr)
	    {
	    	return $echostr;
	    }else
	    {
	    	return '';
	    }
	}
}
