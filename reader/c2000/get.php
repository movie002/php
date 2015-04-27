<?php
require("../../config.php");
require("../../common/base.php");
require("../../common/curl.php");
require("../../common/dbaction.php");
require("../c_common.php");

require("news.mtime.com.php");
require("www.mtime.com.review.php");
require("www.mtime.com.peoplereview.php");
require("www.mtime.com.trailer.php");
//require("www.hunantv.com.php");
//require("ent.ifeng.com.php");
require("ent.163.com.php");
require("ent.sina.com.cn.review.php");
require("yule.sohu.com.php");
//require("www.gewara.com.news.php");
//require("www.gewara.com.activity.php");
require("www.sfs-cn.com.php");
require("www.51oscar.com.php");


header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$id=0;
if(isset($_REQUEST['id']))
	$id = $_REQUEST['id'];

if($id==0||$id==1){echo "</br>id: 1   ";news_mtime_com_php();}
if($id==0||$id==2){echo "</br>id: 2   ";www_mtime_com_review_php();}
if($id==0||$id==3){echo "</br>id: 3   ";www_mtime_com_trailer_php();}
//if($id==0||$id==4){echo "</br>id: 4   ";www_hunantv_com_php();}
//if($id==0||$id==5){echo "</br>id: 5   ";ent_ifeng_com_php();}
//if($id==0||$id==6){echo "</br>id: 6   ";ent_163_com_php();}
if($id==0||$id==7){echo "</br>id: 7   ";ent_sina_com_cn_review_php();}
if($id==0||$id==8){echo "</br>id: 8   ";yule_sohu_com_php();}
//if($id==0||$id==9){echo "</br>id: 9   ";www_gewara_com_news_php();}

if($id==0||$id==9)
{
    echo "</br>id: 9   ";
	$authorname='格瓦拉影讯'; 
    $authorurl='http://www.gewara.com';
	$url = array('http://www.gewara.com/activity/ajax/sns/getCommentNewsList.xhtml?pageNo=');
	$urlcat= array('电影资讯');
    $eachurlp='%eachurl%%i%';
    $preg1='/<a href="([^>]+)" style="display:block; width:200px" title="([^>]+)" target="_blank" class="newImg center">(.*?)<\/a>/s';
    $preg2='/<p>([0-9]+年[0-9]+月[0-9]+日)<\/p>/s';
    c_common_read($authorname,$authorurl,$url,$urlcat,$preg1,$preg2,$eachurlp);
}

if($id==0||$id==10)
{
    echo "</br>id: 10   ";
	$authorname='格瓦拉活动'; 
    $authorurl='http://www.gewara.com';
	$url = array('http://www.gewara.com/activity/activityList.xhtml?tag=cinema');
	$urlcat= array('电影活动');
    $eachurlp='%eachurl%';
    $preg1='/<a href="([^>]+)" title="([^>]+)" target="_blank">(.*?)<\/a>/s';
    $preg2='/([0-9]+月[0-9]+日)/s';
    c_common_read($authorname,$authorurl,$url,$urlcat,$preg1,$preg2,$eachurlp,1);
}

//if($id==0||$id==10){echo "</br>id: 10   ";www_gewara_com_activity_php();}
if($id==0||$id==11){echo "</br>id: 11   ";www_sfscn_com_php();}
if($id==0||$id==12){echo "</br>id: 12   ";www_51oscar_com_php();}
if($id==0||$id==13){echo "</br>id: 13   ";www_mtime_com_peoplereview_php();}

if($id==0||$id==14)
{
    echo "</br>id: 14   ";
	$authorname='凤凰网娱乐';
    $authorurl='http://ent.ifeng.com';
	$url = array('http://ent.ifeng.com/listpage/44169/',
				 'http://ent.ifeng.com/listpage/44168/');				
	$urlcat= array('电影资讯','电视资讯');
    $eachurlp='%eachurl%%i%/list.shtml';
    $preg1='/<h2><a href="([^"]+)" target="_blank" title="([^"]+)">[^>]+<\/a><\/h2>/s';
    $preg2='/<span>([0-9\-\s\:]+)<\/span>/s';
    c_common_read($authorname,$authorurl,$url,$urlcat,$preg1,$preg2,$eachurlp);
}


mysql_close($conn);
?>
