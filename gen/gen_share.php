<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生foot head side文件
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
include("../config.php");
include("common.php");
include("compressJS.class.php");

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

dh_gen_share();

//echo output_page_path(63);

function dh_gen_share()
{
	global $DH_html_path,$DH_index_url,$DH_output_path,$DH_input_path,$DH_home_url,$DH_page_store_deep,$conn;
	

//	$DH_input_html  = $DH_html_path . 'sitemap.html';
//	$DH_output = dh_file_get_contents($DH_input_html);
//	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
//	$DH_output_file = $DH_output_path. 'sitemap.html';
//	dh_file_put_contents($DH_output_file,$DH_output);	
//	echo "gen sitemap success !</br>\n";

	$DH_share_output_path = $DH_input_path.'top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'foot.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_input_html  = $DH_html_path . 'foot.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output = str_replace("%footjs%",$DH_js,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'foot.html';
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen foot success !</br>\n";
	$DH_cse_foot=$DH_output;

	$DH_input_html  = $DH_html_path . 'head.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'head.html';
	dh_file_put_contents($DH_output_file,$DH_output);		
	echo "gen head success !</br>\n";
	$DH_cse_head=$DH_output;
	
	$DH_input_html  = $DH_html_path . 'meta.html';
	$DH_output = dh_file_get_contents($DH_input_html);	
	$DH_input_html  = $DH_html_path . 'meta.js';
	$DH_meta_js = dh_file_get_contents($DH_input_html);	
	$DH_meta_js = str_replace("%home%",$DH_home_url,$DH_meta_js);
	$myPacker = new compressJS($DH_meta_js);	
	$DH_meta_js = $myPacker->pack();
	$DH_output = str_replace("%metajs%",$DH_meta_js,$DH_output);	
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);
	//$DH_output = higrid_compress_html($DH_output);
	$DH_output_file = $DH_share_output_path. 'meta.html';
	dh_file_put_contents($DH_output_file,$DH_output);	
	echo "gen meta success !</br>\n";
	
	$DH_input_html  = $DH_html_path . 'side.html';
	$DH_side = dh_file_get_contents($DH_input_html);
	$DH_input_html  = $DH_html_path . 'side_each.html';
	$DH_side_each = dh_file_get_contents($DH_input_html);
	//广告
	$DH_side_ad= str_replace("%title%",'广告',$DH_side_each);
	$DH_side_ad= str_replace("%more%",'',$DH_side_ad);
	$DH_side_ad= str_replace("%content%",'',$DH_side_ad);
	
	//热门电影
	$DH_side_hotmovie= str_replace("%title%",'热门电影',$DH_side_each);
	$DH_side_hotmovie= str_replace("%more%",'<a href="'.$DH_index_url.'1_h/1.html">更多 >></a>',$DH_side_hotmovie);
	$sql="select * from page where cattype = 1 and DATE_SUB(CURDATE(), INTERVAL 12 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) order by hot desc limit 0,8";
	$DH_side_hotmovie= str_replace("%content%",dh_get_movie_type_ul($sql),$DH_side_hotmovie);
	//热门电视剧
	$DH_side_hottv= str_replace("%title%",'热门剧集',$DH_side_each);
	$DH_side_hottv= str_replace("%more%",'<a href="'.$DH_index_url.'2_h/1.html">更多 >></a>',$DH_side_hottv);
	$sql="select * from page where cattype = 2 and DATE_SUB(CURDATE(), INTERVAL 12 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) order by hot desc limit 0,8";
	$DH_side_hottv= str_replace("%content%",dh_get_movie_type_ul($sql),$DH_side_hottv);	
	//网站统计
	$DH_side_tongji= str_replace("%title%",'网站统计',$DH_side_each);
	$DH_side_tongji= str_replace("%more%",'',$DH_side_tongji);
	$diffecho = '';
	$datetoday = strtotime(date("Y-m-d"));
	$datebegin = strtotime('2013-02-08');
	$diff = round(($datetoday - $datebegin)/86400);
	$year = floor($diff / 360);
	if($year>0)
		$diffecho .= $year.'年';
	$monthc = $diff % 360;
	$month = floor($monthc/30);
	if($month>0)
		$diffecho .= $month.'月';	
	$days = $monthc % 30;
	if($days>0)
		$diffecho .= $days.'天';		
	$tongji="\n".'<li><span class="lt2v0">运行时间:</span>'.'<span class="rt2v0 cred">'.$diffecho.'</span></li>';
	
	$sql="select max(id) from page";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	$tongji.="\n".'<li><span class="lt2v0">影视总数:</span>'.'<span class="rt2v0 cred">'.$count[0].' 部</span></li>';

	$datetoday =date("Y-m-d");
	$sql="select count(*) from page where updatetime >= '$datetoday'";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	$tongji.="\n".'<li><span class="lt2v0">影视更新:</span>'.'<span class="rt2v0 cred">'.$count[0].' 部</span></li>';

	$sql="select count(*) from link where updatetime >= '$datetoday'";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	$tongji.="\n".'<li><span class="lt2v0">资源更新:</span>'.'<span class="rt2v0 cred">'.$count[0].' 个</span></li>';
	
	$sql="select count(*) from page where mstatus=3";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	
	$tongji.="\n".'<li><span class="lt2v0">正在上映:</span>'.'<span class="rt2v0 cred">'.$count[0].' 部</span></li>';

	$sql="select count(*) from page where mstatus=2";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	$tongji.="\n".'<li><span class="lt2v0">马上上映:</span>'.'<span class="rt2v0 cred">'.$count[0].' 部</span></li>';

	$tongji = '<ul>'.$tongji.'</ul>';
	$DH_side_tongji= str_replace("%content%",$tongji,$DH_side_tongji);
	
	//友情链接
	$DH_side_fl= str_replace("%title%",'友情链接',$DH_side_each);	
	$DH_side_fl= str_replace("%content%",'',$DH_side_fl);	
	
//	$DH_side_content=$DH_side_ad.$DH_side_hotmovie.$DH_side_hottv.$DH_side_tongji.$DH_side_fl;	
$DH_side_content=$DH_side_ad.$DH_side_hotmovie.$DH_side_hottv.$DH_side_tongji;	

	$DH_side= str_replace("%side_content%",$DH_side_content,$DH_side);
	$DH_output_file = $DH_share_output_path. 'side.html';
	dh_file_put_contents($DH_output_file,$DH_side);	
	echo "gen side success !</br>\n";
	
	$DH_input_html  = $DH_html_path . 'cse.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = setshare($DH_output,'page.js');	
	$DH_output_file = $DH_output_path. 'cse.html';
	dh_file_put_contents($DH_output_file,$DH_output);	
	
	$DH_input_html = $DH_html_path. 'search.php';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = setshare($DH_output,'list.js');
	$DH_output = str_replace("%deep%",'./',$DH_output);
	$DH_output = str_replace("输入影视名...",'<?php echo $q ?>',$DH_output);
	$DH_output = str_replace('<form name="f1"  target="_blank"','<form name="f1"',$DH_output);
	
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_output_path. 'search.php';
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen cse success !</br>\n";	
	
}

function dh_get_movie_type_ul($sql)
{
	global $DH_html_url,$conn,$linkway;
	$recs=dh_mysql_query($sql);
	$liout="";
	if($recs)
	{									
		$liout="<ul>";
		while($row = mysql_fetch_array($recs))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);
			//$author=$row['author'];
			$update = date("m-d",strtotime($row['updatetime']));					
			$lieach = "\n".'<li><span class="1line lt2v0"><a href="'.$htmlpath.'" target="_blank">'.$row['title'].'</a></span><span class="rt2v0 cred">'.$update.'</span></li>';
			$liout.= $lieach;		
		}
		$liout.= "\n</ul>";	
	}
	else
	{
		echo "查询返回0条记录！ <br>\n";
	}
	return $liout;	
}
?>