<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生静态的页面的html页面
/// 函数作者: DH
/// 作者地址: http://movie002.com 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
require("../config.php");
#需要使用的基础函数
require("../common/common_gen.php");
require("../common/base.php");
require("../common/share.php");
require("../common/compressJS.class.php");
require("queue.php");
set_time_limit(600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");
dh_gen_page();

//echo output_page_path(63);

function dh_gen_page()
{
	global $DH_home_url,$DH_html_path,$DH_output_path,$DH_page_store_deep,$DH_output_html_path;
	if (!file_exists($DH_output_html_path))  
		mkdir($DH_output_html_path,0777);
	
	$DH_input_html  = $DH_html_path . 'page.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");

	$DH_output_content = setshare($DH_output_content,'page.js');
	$deep = '';
	for($i= 0;$i<$DH_page_store_deep;$i++)
	{
		$deep .= '../';
	}
	$DH_output_content = str_replace("%deep%",$deep,$DH_output_content);	
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);		
	dh_gen_each_page_file('page',$DH_output_html_path,$DH_output_content);
}

function dh_gen_each_page_file($table,$path,$DH_output_content)
{
	global $DH_index_url,$conn,$linkquality,$linkway,$linktype,$DH_home_url;
	
	//从参数里面去的天数
	$sqldays='';
	if( isset($_REQUEST['d']))
	{
		$daybegin = getupdatebegin($_REQUEST['d']);
		$sqldays=" where updatetime >= '$daybegin'";
	}
	if( isset($_REQUEST['id']))
	{
		$id = $_REQUEST['id'];
		$sqldays=" where id = $id";
	}	
	$sql = "select * from ". $table.$sqldays;
	echo $sql;
	$results=mysql_query($sql,$conn);	
	if($results)
	{	
		$count=0;
		$queue = new Queue(9);
		while($row = mysql_fetch_array($results))
		{	
			$count++;			
			//左邻右舍
			$queueadd = array($row['title'],$row['id']);
			if($count > 8)
				$queue->out_queue($queue);
			$queue->en_queue($queue,$queueadd);
			$others = others($queue); 
			$DH_output_content_page = str_replace("%others%",$others,$DH_output_content);			
			
			$DH_output_content_page = dh_replace_snapshot('big',$row,$DH_output_content_page);
			$DH_output_content_page = dh_replace_content($count,$row,$DH_output_content_page);
			$cat = dh_get_catname($row['cattype'],$row['catcountry']);
			$DH_output_content_page = str_replace("%cat%",$cat,$DH_output_content_page);
			//提取年份
			if($row['pubdate']=='0000-00-00' || $row['pubdate']=='1970-01-01')
			{
				$pubyear = '';	
			}
			else
			{
				$pubyear =date("Y",strtotime($row['pubdate']));			
			}
			$DH_output_content_page = str_replace("%pubyear%",$pubyear,$DH_output_content_page);
			
			//提取标题
//			$title = $row['title'];
//			if($row['aka']!='')
//			{
//				$akas=preg_split("/[\/]+/", $row['aka']);
//				$title.=' '.$akas[0];
//				//echo $title."</br>\n";
//			}
			$keywords = $row['title'];			
			$keywords.=','.strtr($row['aka'],'/',',');
			$DH_output_content_page = str_replace("%title_key%",$row['title'],$DH_output_content_page);
			$DH_output_content_page = str_replace("%keywords%",$keywords,$DH_output_content_page);
			$DH_output_content_page = str_replace("%id%",$row['id'],$DH_output_content_page);
			
			//新浪微博控件
			$weibotitle=rawurlencode($row['title']).'|'.rawurlencode($row['title']);
			$weibo='<wb:livestream color="BEBEBE,E0E0E0,666666,000000,EEEEEE,E6E6E6" publish="n" topic="'.$weibotitle.'" width="auto" height="500" ></wb:livestream>';			
			$DH_output_content_page = str_replace("%weibo%",$weibo,$DH_output_content_page);		
			
			
			$cathref = $DH_index_url.$row['cattype'].'_'.$row['catcountry'].'_l/1.html';
			$DH_output_content_page = str_replace("%cathref%",$cathref,$DH_output_content_page);
						
			$sqllinks = "select pageid,author,title,link,linkquality,linkway,linktype,linkonlinetype,linkdowntype,linkproperty,updatetime from link t where t.pageid = '".$row['id']."' order by linkquality desc,updatetime desc";
			//echo $sqllinks;
			$DH_output_content_page = dh_replace_link($sqllinks,$row,$DH_output_content_page);
			
			$sqllinks = "select pageid,author,title,link,linktype,updatetime from link2 t where t.pageid = '".$row['id']."' order by updatetime desc";		
			$DH_output_content_page = dh_replace_link2($sqllinks,$row,$DH_output_content_page);
			$summary=$row['summary'].' <a href="http://movie.douban.com/subject/'.$row['mediaid'].'" target="_blank">[更多内容到豆瓣]</a>';
			$DH_output_content_page = str_replace("%summary%",$summary,$DH_output_content_page);
			$DH_output_content_page = str_replace("%home%",$DH_home_url,$DH_output_content_page);
			$DH_output_file = output_page_path($path,$row['id']);
			dh_file_put_contents($DH_output_file,$DH_output_content_page);
		}
		echo 'gen page count:'.$count."</br>\n";
	}	
}

		//循环数输出队列
function others($QUEUE)
{
	global $DH_html_url;
	$liout = '';
	$q = $QUEUE->front ;
	while ( $q != $QUEUE->rear)
	{
		$page_path = output_page_path($DH_html_url,$QUEUE->pBase[$q][1]);
		$liout.='<li style="width:48%;float:left"><a href="'.$page_path.'" target="_blank">《'.$QUEUE->pBase[$q][0].'》在线下载等资源链接</a></li>';
		$q = ($q+1) % $QUEUE->Num;//向上移动一位
	}
	return $liout;
}
?>