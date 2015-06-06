<?php 
/* 使用示例 */   

//header('Content-Type:text/html;charset= UTF-8');
//require("../../config.php");
//require("../../common/curl.php");
//require("../../common/base.php");
//require("../../common/dbaction.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//dh_mysql_query("set names utf8;");
//get_wangpiao('一万年以后','Ever Since We Love',1,'2015-05-05 00:00:00',4);
//mysql_close($conn);

//处理电影名  
function get_wangpiao($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from wangpiao:\n";	
	//if($type!=1)
	//	return;
	$name = rawurlencode($title);
	$url='http://www.gewara.com/newSearchKey.xhtml?skey='.$name.'&channel=movie';
	echo $url."\n";
	$buffer = geturl($url);
	//echo $buffer;
	
	if(false==$buffer)
        return;

	preg_match('/href="\/movie\/([0-9]+)/s',$buffer,$match);
	//print_r($match);
	
	if(empty($match[1]))
        return;
        
    $xtitle='《'.$title.'》的格瓦拉预告花絮';
    $xurl = "http://www.gewara.com/movie/v5/pv.xhtml?mid=".$match[1]."&type=videos";
    $ytitle='《'.$title.'》的格瓦拉购票';
    $yurl = "http://www.gewara.com/movie/".$match[1];
	
    addnoupdatelink($pageid,'格瓦拉预告花絮',$xtitle,$xurl,'',4,3,7,0,0,$updatetime);
	addnoupdatelink($pageid,'格瓦拉购票',$ytitle,$yurl,'',4,5,7,0,0,$updatetime);
}
?>  
