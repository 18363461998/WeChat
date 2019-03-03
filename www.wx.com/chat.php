<?php 

$c = new chat();

/*
主动微信公众号请求类
 */

class chat
{
	const APPOD = 'wx4a7a3baeef637fbe';
	const SECRET = 'f67dbb7ddead596835956746462e050d';

	const BS = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36";

	private $config = [];

	public function __construct()
	{
		$this->config = include 'api.php';
		$this->getAccessToken();
	}


	public function getAccessToken()
	{
		$url = sprintf($this->config['access_token_url'],self::APPOD,self::SECRET);

		$mem = new Memcache();
		$mem->addserver('127.0.0.1','11211');
		if(!$mem->get(self::APPOD))
		{
			$token = $this->http_request($url);
			$mem->set(self::APPOD,$token);
		}

		echo $mem->get(self::APPOD);
	}

	public function http_request(string $url,$data = '',string $filepath='')
	{
		if(!empty($filepath))
		{
			$data['pic'] = new CURLFile($filepath);
		}

		//CURL进行请求
		//初始化
		$ch = curl_init();

		//相关的设置
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		//谁知请求 超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_USERAGENT, self::BS);

		if(!empty($data))
		{
			if(!empty($data))
			{
				//告诉curl使用了post请求
				curl_setopt($ch,CURLOPT_POST,1);
				curl_setopt($ch,CURLOPT_HTTPHEADER,
					[
					'Content-Type: application/json; charset=UTF-8'
					]);
			}
		}else
		{
			curl_setopt($ch,CURLOPT_HTTPGET,1);
		}


		//post数据，如果使用了json类型的数据信息，就必须加一个响应头


		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

		//文件的上传


		$data = curl_exec($ch);

		if(curl_errno($ch)>0)
		{
			throw new Exception(curl_error($ch), 1000);	
		}

		curl_close($ch);
		return $data;
	}
}
 ?>
