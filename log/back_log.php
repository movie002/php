<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_list
/// 函数作用：产生静态的最新的文章列表页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
include("../config.php");
include("../common/base.php");
require("../mail/mail.php");

//phpinfo();
$logdir=".";
$zipfile='log.zip';
//$zipsendfile='log'.date("Ymd_H_i_s").'.zip';
$error = dh_gen_log_file($zipfile,$logdir);
echo $error;
//postmail('zhonghua0747@gmail.com',$zipfile,$error);

function dh_gen_log_file($zipfile,$logdir)
{
	$error = '';
	$zip = new ZipArchive; //首先实例化这个类
	if ($zip->open($zipfile,ZipArchive::OVERWRITE) === TRUE) 
	{
		$handle = @opendir($logdir) or die("Cannot open " . $logdir);
		// 用 readdir 读出文件列表
		while($file = readdir($handle))
		{
			// 将 "." 及 ".." 排除不显示
			if(strstr($file,'.log'))
			{
				$DH_output = dh_file_get_contents($file);
				$match=array();
				preg_match('/error|Notice|Fatal|Warning/i',$DH_output,$match);
				//print_r($match);
				if(!empty($match[0]))
				{
					$error.='<div>'.$file.'-->'. $match[0].'</div>';
				}
				$zip->addFile($file);
			}
		}
		// 关闭目录读取
		closedir($handle);
		$zip->close(); //关闭
	}
	else
	{
		echo 'zip log failed';
	}
	return $error;
}
