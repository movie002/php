<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/dbaction.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//get_m1905('一万年以后','Ever Since We Love',1,'2015-05-05 00:00:00',4);
//mysql_close($conn);

//处理电影名  
function get_m1905($title,$aka,$type,$updatetime,$ids,$pageid=-1)
{ 
	echo " \n begin to get from 1905:\n";	
	//if($type!=1)
	//	return;
	$name = rawurlencode($title);
	$url='http://www.1905.com/search/index-p-type-film-q-'.$name.'.html';
	echo $url."\n";
	$buffer = get_file_curl($url);
	//echo $buffer;
	
	if(false==$buffer)
	{
		sleep(5);
		$buffer = get_file_curl($url);
		if(false==$buffer)
		{
			echo $title."搜索失败 </br>\n";
			return;
		}
	}
	preg_match('/<h2 class="title\-mv".*?>.*?<\/h2>/s',$buffer,$match);
	//print_r($match);
	preg_match('/http:\/\/www\.1905\.com\/mdb\/film\/(.*?)\//s',$match[0],$match1);
	//print_r($match1);
	$m1905id=$match1[1];
	preg_match('/<2>(.*?)<\/2>/s',$ids,$match);
	if(empty($match[1]))
	{
		$ids .= '<2>'.$m1905id.'</2>';
	}
	
    $m1905url = 'http://www.1905.com/mdb/film/'.$m1905id.'/';
    echo $m1905url."</br>";
	$buffer= get_file_curl($m1905url);
	//echo $buffer;
	
	preg_match_all('/<a href="(http:\/\/www.1905.com\/video\/play\/[0-9]+.shtml)".*?>(.*?)<\/a>/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
        foreach($match[1] as $key=>$eachurl)
        {
		    $xtitle = $match[2][$key];
		    addnoupdatelink($pageid,'m1905预告花絮',$xtitle,$eachurl,'',4,3,7,0,0,$updatetime);
        }
	}
	
	preg_match('/http:\/\/www.1905.com\/yx\/film\/.*?.html/s',$buffer,$match1);
	//print_r($match1);
	if(!empty($match1[0]))
	{
		$xtitle =  '《'.$title.'》m1905 购票链接';
		$xurl = $match1[0];
		addnoupdatelink($pageid,'m1905购票',$xtitle,$xurl,'',4,5,7,0,0,$updatetime);
	}

	//评分
	preg_match('/<span class="score">(.*?)<\/span>[\s]+<span class="nop">\(<q id="rating_num">(.*?)<\/q>人评分\)<\/span>/s',$buffer,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$newr2='<r2>'.$match[1].'('.$match[2].')</r2>';	
		preg_match('/<r2>(.*?)<\/r2>/s',$ids,$match3);
		if(empty($match3[1]))
		{
			$ids .=$newr2;
		}
		else
		{
			$ids = preg_replace('/<r2>(.*?)<\/r2>/s',$newr2,$ids);
		}
	}
	
	//更新page
	$sql="update page set ids='$ids' where id=$pageid";
	echo $sql;
	$sqlresult=dh_mysql_query($sql);	
}
?>  
