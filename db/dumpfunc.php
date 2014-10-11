<?php
/////////////////////////////////////////////////////
/// 函数名称： 
/// 函数作用：
/// 函数作者: DH
/// 作者地址: http://movie002.com/ 
/////////////////////////////////////////////////////
header('Content-Type:text/html;charset= UTF-8'); 
//phpinfo();

//导出数据库设置
include("../config.php");
set_time_limit(300); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$file_dir=$DH_output_path.'backup/db/';
//数据库保存的最短时间 120天 4个月
$daysshort=10;
//数据库保存的最长时间是 210天 7个月 一个季度一个备份
$dayslong=11;
//数据库分段的大小
$max_limit = 49152;// 48KB

if(!file_exists($file_dir))//判断文件夹是否存在
{
	mkdir($file_dir,0777);
	@chmod($file_dir,0777);
}

$datetitle=gmdate('Ymd_H_i_s', time());
$table_all=array('author','celebrity','page','link','onlylink','src');

//判断数据库中的最早日期是否距离今日有180天
$rs = mysql_query("SELECT min(updatetime) as minupdatetime FROM page");
$row=mysql_fetch_assoc($rs);
$mintime =strtotime(date("Y-m-d H:i:s",strtotime($row['minupdatetime'])));
$todaytime = strtotime(date("Y-m-d H:i:s"));
$Days=($todaytime-$mintime)/86400;

echo $todaytime."</br>\n";
echo $mintime."</br>\n";
echo $Days."</br>\n";

if($Days<$dayslong)
//if(false)
{
	//每月导出全量一次
	$day=date("d");
	echo 'day:  '.$day;
	if($day!=26)
	{
		echo "不做任何动作，退出!</br>\n";
		return;
	}
	echo "不需要导出增量!</br>\n";
	echo "生成全量文件</br>\n";	
}
else
{
	//没90天导出删除数据一次，并导出增量部分
	//开始备份
	$incfilename='db/inc_'.$datetitle.'.sql.gz';
	$fp = fopen($incfilename, 'w');
	dumptop($fp);

	//生成sitemap	
	$sql="select * from page where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	$dump=gensql($fp,$sql,'page');
	//删除之前需要生成一个sitemap
	gen_sitemap($sql,date("Y-m-d H:i:s",$todaytime),'monthly','_'.date("Ymd",$todaytime));
	//重新生成最新的sitemap
	$sql="select * from page order by id desc";
	gen_sitemap($sql,date("Y-m-d H:i:s",$todaytime),'weekly','');
	//添加到siteindex中
	gen_siteindex(date("Y-m-d H:i:s",$todaytime));
	
	$sql="delete from page where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	dh_mysql_query($sql);
	$sql="OPTIMIZE TABLE page";	
	dh_mysql_query($sql);	
	
	$sql="select * from link where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	$dump=gensql($fp,$sql,'link',1);
	$sql="delete from link where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	dh_mysql_query($sql);	
	$sql="OPTIMIZE TABLE link";	
	dh_mysql_query($sql);

	$sql="select * from src where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	$dump=gensql($fp,$sql,'src',1);
	$sql="delete from src where DATE_SUB(CURDATE(), INTERVAL $daysshort DAY) >= date(updatetime)";
	dh_mysql_query($sql);	
	$sql="OPTIMIZE TABLE src";	
	dh_mysql_query($sql);
	
	fclose($fp);
	echo "export sqlfile ".$incfilename."<br/>\n";
}

$sql="select * from page order by updatetime";
gen_sitemap($sql,date("Y-m-d H:i:s",$todaytime),'weekly','');
gen_siteindex(date("Y-m-d H:i:s",$todaytime));

//onlylink数据不重要，不需要保留很长时间，保留两个月
$sql="delete from onlylink where DATE_SUB(CURDATE(), INTERVAL 60 DAY) >= date(updatetime)";
dh_mysql_query($sql);
echo "delete onlylink <br/>\n";	
$sql="OPTIMIZE TABLE onlylink";	
dh_mysql_query($sql);

//生成数据库生成语句
gencreate($table_all);
//生成tables
gentable($table_all);

//邮件发送
postmail_db("zhonghua0747@gmail.com",$file_dir);	

return;
//end

/* write to file */
function sql_dump($fp, $buffer) 
{
	$buffer = gzencode($buffer); // gzip
	return fwrite($fp, $buffer);
}

function dumptop($fp)
{
	global $dbname, $datetitle;
	$mysql_ver = mysql_get_server_info();
	$php_ver   = PHP_VERSION;

	$buffer = "-- DH SQL Dump version 1.3
-- Author: DH
-- URI: http://movie002.com
-- 生成日期: $datetitle GMT
-- 数据库版本: $mysql_ver
-- PHP 版本: $php_ver\n
SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n
-- 数据库: `" . $dbname . "`
-- " . str_repeat('-', 56);
	sql_dump($fp, $buffer);
}

function gencreate($tables)
{
	global  $datetitle;
	foreach (glob("db/create_*sql.gz") as  $delfile)
	{
		unlink($delfile);
	}
	$fp = fopen('db/create_'.$datetitle.'.sql.gz', 'w');
	dumptop($fp);
	foreach ($tables as $table)
	{
		$buffer = "\n-- 表的结构: `$table`\nDROP TABLE IF EXISTS `$table`;\n";
		$a=mysql_query("show create table `{$table}`");
		$row=mysql_fetch_assoc($a);
		$buffer.= $row['Create Table'].";\n";
		sql_dump($fp, $buffer);
	}
	fclose($fp);	
}

function gentable($tables)
{
	global  $datetitle;
	foreach (glob("db/table_"."*sql.gz") as  $delfile)
	{
		unlink($delfile);
	}
	$fp = fopen('db/table_'.$datetitle.'.sql.gz', 'w');
	dumptop($fp);
	foreach ($tables as $table)
	{
		$sql="select * from ".$table;
		$dump=gensql($fp,$sql,$table);
		echo "export table ".$table."<br/>\n";
	}
	fclose($fp);
}

function gensql($fp,$sql,$table,$inc=0)
{		
	global $max_limit;
	$limit_start = 0;
	$a=mysql_query("SHOW TABLE STATUS LIKE '$table'");
	$status=mysql_fetch_assoc($a);	
	
	$limit = (int)(($max_limit / (($status['Data_length'] + 1) / ($status['Rows'] + 1)))); // 以 max_limit 切割长度 (ROWS_PER_SEGMENT)

	do {
		//$dbs = $wpdb->get_results("SELECT * FROM $table LIMIT $limit_start, $limit", ARRAY_A);
		$rs = mysql_query($sql." LIMIT $limit_start, $limit");
		if(mysql_num_rows($rs)<1)
		{
			//说明没有值，应该退出
			break;
		}	
		$countfield =  mysql_num_fields($rs);
		$i=0;
		$buffer = "\nINSERT INTO $table(";
		while($i<$countfield)
		{
			$buffer .= mysql_field_name($rs,$i).",";
			$i++;
		}
		$buffer = rtrim($buffer, ',') . ") VALUES";

		while($db = mysql_fetch_row($rs)) 
		{
			foreach ($db as $str) 
			{
				$str = strtr($str, array("'" => "''", "\\" => "\\\\", "\x00" => "\\0", "\x0a" => "\\n", "\x0d" => "\\r", "\x1a" => "\\Z")); // 修改版 mysql_real_escape_string()
				$fild[] = is_numeric($str) ? $str : "'" . $str . "'";
			}
			$fild = implode(", ", $fild);
			$buffer .= "\n($fild),";
			unset($fild);
		}
		//比较特殊，由于link、onlylink,src会导致重复的记录，所以需要对其进行特殊处理
		if($inc==0)
			$buffer = rtrim($buffer, ',') . ";";		
		else
			$buffer = rtrim($buffer, ',') . "ON DUPLICATE KEY UPDATE updatetime=values(updatetime);";
		sql_dump($fp, $buffer);
		$limit_start += $limit;
	 } while (true);
}

function postmail_db($to,$file_dir)
{
	global $datetitle;
    //$to 表示收件人地址 $subject 表示邮件标题 $body 表示邮件正文
    //error_reporting(E_ALL);
    //error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区
    require_once('class-phpmailer.php');	
	$mail = new PHPMailer(); //new一个PHPMailer对象出来
//    $body = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1; // 启用SMTP调试功能 1 = errors and messages  2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "ssl";                 // 安全协议
    $mail->Host       = "smtp.gmail.com";      // SMTP 服务器
    $mail->Port       = 465;                   // SMTP服务器的端口号
    $mail->Username   = "xzhh0747";  // SMTP服务器用户名
    $mail->Password   = "19850523";            // SMTP服务器密码
    $mail->SetFrom('a@movie002.com', 'movie002.com');
    $mail->AddReplyTo("a@movie002.com","movie002.com");
    $mail->Subject    = "movie002.com 的数据库备份 ".$datetitle;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From www.jiucool.com"; // optional, comment out and test
//    $mail->MsgHTML($body);
	$mail->Body='this is a back up mail';
    $address = $to;
    $mail->AddAddress($address, "收件人名称");
	
	// 定于需要列出的目录地址
	// 用 opendir() 打开目录，失败则中止程序
//	$handle = @opendir($file_dir) or die("Cannot open " . $dir);
//	echo "<b>Files in " . $file_dir . ":</b><br/>\n";
//	// 用 readdir 读出文件列表
//	while($file = readdir($handle))
//	{
//		// 将 "." 及 ".." 排除不显示
//		if($file!= "." && $file !="..")
//		{
//			echo "$file<br/>";
//			$mail->AddAttachment($file_dir.$file);      // attachment 
//		}
//	}
//	// 关闭目录读取
//	closedir($handle);	
	foreach (glob("db/*sql.gz") as  $attchfile)
	{
		echo "attach file: ".$attchfile." </br>\n";
		$mail->AddAttachment($attchfile);      // attachment 
	}
	
    if(!$mail->Send())
	{
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
	else
	{
		echo "Message sent!恭喜，邮件发送成功！";
		//删除已经发送完成的文件
		echo "删除已经发送成功的文件！";
		foreach (glob("db/*sql.gz") as  $delfile)
		{
			echo "del file: ".$delfile." </br>\n";
			unlink($delfile);
		}
    }
}

function gen_sitemap($sql,$date,$cycle,$name)
{	
	global $DH_output_path,$DH_home_url,$DH_html_url;
	
	$timetmp = strtotime($date);
	$updatetime = date("Y-m-d",$timetmp)."T".date("H:i:s",$timetmp)."+00:00";

	
	$DH_input_html  = $DH_output_path . 'backup/sitemap/sitemap.xml';
	$DH_sitemap = dh_file_get_contents("$DH_input_html");
	$DH_sitemap = str_replace("%lastdate%",$updatetime,$DH_sitemap);
	$DH_input_html  = $DH_output_path . 'backup/sitemap/sitemap_each.xml';
	$DH_sitemap_each = dh_file_get_contents("$DH_input_html");	
	
	$DH_input_html  = $DH_output_path . 'backup/sitemap/sitemap_baidu.xml';
	$DH_sitemap_baidu = dh_file_get_contents("$DH_input_html");	
	$DH_sitemap_baidu = str_replace("%lastdate%",$updatetime,$DH_sitemap_baidu);
	$DH_input_html  = $DH_output_path . 'backup/sitemap/sitemap_baidu_each.xml';
	$DH_sitemap_baidu_each = dh_file_get_contents("$DH_input_html");		
	
	$rs = mysql_query($sql);
	$sitemap_all='';
	$sitemap_baidu_all='';
	
	//如果是每周更新，那么是第一个页面，就需要添加首页的链接
	if($cycle==='weekly')
	{
		$sitemap_each = str_replace("%url%",$DH_home_url,$DH_sitemap_each);
		$sitemap_each = str_replace("%updatetime%",$updatetime,$sitemap_each);
		$sitemap_each = str_replace("%cycle%",'daily',$sitemap_each);
		$sitemap_each = str_replace("%priority%",'1.0',$sitemap_each);
		$sitemap_all.=$sitemap_each;
		
		$sitemap_baidu_each = str_replace("%url%",$DH_home_url,$DH_sitemap_baidu_each);		
		$sitemap_baidu_each = str_replace("%updatetime%",$updatetime,$sitemap_baidu_each);	
		$title = '二手电影-做勤恳的电影中介';	
		$sitemap_baidu_each = str_replace("%title%",$title,$sitemap_baidu_each);		
		$sitemap_baidu_all.=$sitemap_baidu_each;		
	}
	
	while($row = mysql_fetch_array($rs))
	{
		$htmlpath = output_page_path($DH_html_url,$row['id']);
		$sitemap_each = str_replace("%url%",$htmlpath,$DH_sitemap_each);
		$timetmp = strtotime($row['updatetime']);
		$updatetime = date("Y-m-d",$timetmp)."T".date("H:i:s",$timetmp)."+00:00";
		
		$sitemap_each = str_replace("%updatetime%",$updatetime,$sitemap_each);
		$sitemap_each = str_replace("%cycle%",$cycle,$sitemap_each);
		$sitemap_each = str_replace("%priority%",'0.2',$sitemap_each);
		$sitemap_all.=$sitemap_each;
				
		$sitemap_baidu_each = str_replace("%url%",$htmlpath,$DH_sitemap_baidu_each);
		$sitemap_baidu_each = str_replace("%updatetime%",$row['updatetime'],$sitemap_baidu_each);	
		$title = $row['title'];
		if($row['aka']!='')
		{
			$akas=preg_split("/[\/]+/", $row['aka']);
			$title.=' '.$akas[0];
			//echo $title."</br>\n";
		}
		$title .=' 在线下载资源(二手电影)';
		$sitemap_baidu_each = str_replace("%title%",$title,$sitemap_baidu_each);		
		$sitemap_baidu_all.=$sitemap_baidu_each;
	}
	$DH_sitemap = str_replace("%sitemaps%",$sitemap_all,$DH_sitemap);
	$DH_sitemap_baidu = str_replace("%sitemaps%",$sitemap_baidu_all,$DH_sitemap_baidu);
	
	$DH_output_file = $DH_output_path.'sitemap'.$name.'.xml';
	dh_file_put_contents($DH_output_file,$DH_sitemap);	
	$DH_output_file = $DH_output_path.'sitemap_baidu'.$name.'.xml';	
	dh_file_put_contents($DH_output_file,$DH_sitemap_baidu);		
}

function gen_siteindex($date)
{
	global $DH_output_path,$DH_home_url,$DH_home_url;
	
	$timetmp = strtotime($date);
	$date = date("Y-m-d",$timetmp)."T".date("H:i:s",$timetmp)."+00:00";	
	
	$DH_input_html  = $DH_output_path . 'backup/sitemap/siteindex.xml';
	$DH_siteindex = dh_file_get_contents("$DH_input_html");
	$DH_input_html  = $DH_output_path . 'backup/sitemap/siteindex_each.xml';
	$DH_siteindex_each = dh_file_get_contents("$DH_input_html");	
	$DH_siteindex_each = str_replace("%home%",$DH_home_url,$DH_siteindex_each);
	
	$DH_input_html  = $DH_output_path . 'backup/sitemap/robots.txt';
	$DH_robots = dh_file_get_contents("$DH_input_html");
		
	$siteindex_all='';
	$siteindex_baidu_all='';
	$siterobots='';
	// 用 opendir() 打开目录，失败则中止程序
	$handle = @opendir($DH_output_path) or die("Cannot open " . $dir);
	echo "<b>Files in " . $DH_output_path . ":</b><br/>\n";
	// 用 readdir 读出文件列表
	while($file = readdir($handle))
	{
		$ext=strrchr($file,'.');
		if($ext!='.xml')
			continue;
		//得到sitemap的时间	
		preg_match("/([0-9]{8})/",$file,$match);
		//print_r($match);
		
		// 选出是哪个类型的sitemap
		if(strstr($file,'sitemap_baidu'))
		{
			echo "sitemap_baidu:$file<br/>";
			$siteindex_baidu_each = str_replace("%sitemap%",$file,$DH_siteindex_each);
			if(!empty($match[1]))
			{
				$timetmp = strtotime($match[1]);
				$updatetime = date("Y-m-d",$timetmp)."T".date("H:i:s",$timetmp)."+00:00";
				$siteindex_baidu_each = str_replace("%date%",$updatetime,$siteindex_baidu_each);
			}
			else
			{
				$siteindex_baidu_each = str_replace("%date%",$date,$siteindex_baidu_each);
			}
			$siteindex_baidu_all .=$siteindex_baidu_each;
		}
		if(strstr($file,'sitemap')&&(!strstr($file,'sitemap_baidu')))
		{
			echo "sitemap:$file<br/>";
			$siteindex_each = str_replace("%sitemap%",$file,$DH_siteindex_each);
			if(!empty($match[1]))
			{
				$timetmp = strtotime($match[1]);
				$updatetime = date("Y-m-d",$timetmp)."T".date("H:i:s",$timetmp)."+00:00";
				$siteindex_each = str_replace("%date%",$updatetime,$siteindex_each);
			}
			else
			{
				$siteindex_each = str_replace("%date%",$date,$siteindex_each);
			}			
			$siteindex_all .=$siteindex_each;
			$siterobots	.="\nSitemap: ".$DH_home_url.$file;	
		}	
	}
	
	// 关闭目录读取
	closedir($handle);		
	
	$o_siteindex = str_replace("%sitemaps%",$siteindex_all,$DH_siteindex);
	$o_siteindex_baidu = str_replace("%sitemaps%",$siteindex_baidu_all,$DH_siteindex);
	$o_siterobots = str_replace("%sitemaps%",$siterobots,$DH_robots);
	
	$DH_output_file = $DH_output_path.'siteindex.xml';
	dh_file_put_contents($DH_output_file,$o_siteindex);	
	$DH_output_file = $DH_output_path.'siteindex_baidu.xml';	
	dh_file_put_contents($DH_output_file,$o_siteindex_baidu);
	$DH_output_file = $DH_output_path.'robots.txt';	
	dh_file_put_contents($DH_output_file,$o_siterobots);	
}
?>
