<?php
function get_file($url)
{
	$buff="";
    $file = fopen ($url, "r"); // 远程下载文件，二进制模式
    if ($file) 
	{ 	// 如果下载成功
		while ( !feof($file) ) 
		{  
			$buff .= fgets($file,4096);  
		}  
    }
	else
	{
		echo "can not open $url";
		return false;
	}
    if ($file) 
	{
        fclose($file); // 关闭远程文件
    }
    return $buff;
} 

function get_file_curl($url)
{
	$ch = curl_init();
	$starttime = time();
	//超时时间为10秒钟
	$timeout = 30;
	
	curl_setopt($ch, CURLOPT_PROXY,"http://172.28.89.1:8080");
	curl_setopt($ch, CURLOPT_URL, $url);
//	curl_setopt($ch, CURLOPT_HEADER, 1);	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING ,'gzip');
	curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
	$HTTP_SESSION=_rand();
	curl_setopt($ch, CURLOPT_COOKIE,$HTTP_SESSION);
	
	
	//在需要用户检测的网页里需要增加下面两行
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	//curl_setopt($ch, CURLOPT_USERPWD, US_NAME.”:”.US_PWD);
	$contents = curl_exec($ch);
	//错误判断
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($http_code >= 400)
	{	//400 - 600都是服务器错误
		echo "error :".$url."服务器错误!\n";
		//echo $contents;
		return false;
	} 
	curl_close($ch);
	
	if (false == $contents || empty($contents)) 
	{
		echo "error :".$url."内容访问失败\n";
		return false;
	} 
	
	$endtime = time();
	if($endtime-$starttime>=$timeout)
	{
		echo "error :".$url."php运行超时\n";
		return false;
	}
	return $contents;
}

function _rand()
{
	$length=26;
	$chars="0123456789abcdefghijklmnopqrstuvwxyz";
	$max=strlen($chars)-1;
	mt_srand((double)microtime()*1000000);
	$string='';
	for($i = 0;$i<$length;$i++)
	{
		$string.=$chars[mt_rand(0,$max)];
	}
	return $string;
}

/*
*功能：php多种方式完美实现下载远程图片保存到本地
*参数：文件url,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$filepath='',$filename='')
{
    if($url==''){return false;}
    if($filename=='')
	{
		$ext=strrchr($url,'/');
		//if($ext!='.gif' && $ext!='.jpg'){return false;}
		$filename = substr($ext,1,strlen($ext));
    }
	echo $filename.'  ';
	$img=get_file_curl($url);
    $size=strlen($img);
    //文件大小 
    $fp2=@fopen($filepath.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
	return $filename;
}

?>