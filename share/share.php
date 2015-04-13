<?php
/////////////////////////////////////////////////////
/// 函数名称：share 
/// 函数作用：产生foot head side文件，提供利用接口
/// 函数作者: DH
/// 作者地址: http://dhblog.org
/////////////////////////////////////////////////////

//#需要使用的基础函数
//include("../config.php");
//include("../common/compressJS.class.php");
//
//$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
//mysql_select_db($dbname, $conn) or die('选择数据库失败');
//mysql_query("set names utf8;");

//dh_gen_share('http://db.movie002.com');


function dh_gen_share($DH_home_url)
{
	 global $DH_html_path;
	 dh_gen_share_foot($DH_home_url);
	 dh_gen_share_head($DH_html_path,$DH_home_url);
	 dh_gen_share_meta($DH_home_url);
}

function dh_gen_share_foot($DH_home_url)
{
	global $DH_html_path,$DH_input_path,$DH_name,$DH_name_des;

	$DH_share_output_path = $DH_input_path.'top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'foot.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);
	$DH_output = str_replace("%DH_name%",$DH_name,$DH_output);
	$DH_output = str_replace("%DH_name_des%",$DH_name_des,$DH_output);	
	$DH_input_html  = $DH_html_path . 'foot.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output = str_replace("%footjs%",$DH_js,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'foot.html';
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen foot success !</br>\n";
}

function dh_gen_share_head($DH_html_path,$DH_home_url)
{
	global $DH_input_path,$DH_name,$DH_name_des;

	$DH_share_output_path = $DH_input_path.'top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'head.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output = str_replace("%DH_name%",$DH_name,$DH_output);
	$DH_output = str_replace("%DH_name_des%",$DH_name_des,$DH_output);		
	$DH_output_file = $DH_share_output_path. 'head.html';
	dh_file_put_contents($DH_output_file,$DH_output);		
	echo "gen head success !</br>\n";
}

function dh_gen_share_meta($DH_home_url)
{
	global $DH_html_path,$DH_input_path,$DH_name,$DH_name_des;

	$DH_share_output_path = $DH_input_path.'top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
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
}


function dh_gen_side($DH_home_url)
{
	global $DH_html_path,$DH_index_url,$DH_output_path,$DH_input_path;

	$DH_share_output_path = $DH_input_path.'top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'side.html';
	$DH_side = dh_file_get_contents($DH_input_html);
	$DH_input_html  = $DH_html_path . 'side_each.html';
	$DH_side_each = dh_file_get_contents($DH_input_html);
	//广告
//	$DH_side_ad= str_replace("%title%",'广告',$DH_side_each);
//	$DH_side_ad= str_replace("%more%",'',$DH_side_ad);
//	$DH_side_ad= str_replace("%content%",'',$DH_side_ad);
	
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
	
	$tongji.="\n".'<li><span class="lt2v0">影视总数:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select max(id) from page").' 部</span></li>';

	$tongji.="\n".'<li><span class="lt2v0">资源总数:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select count(*) from link").' 部</span></li>';	
	
	$datetoday =date("Y-m-d");
	$datetoday = date("Y-m-d  H:i:s",strtotime($datetoday));
	$tongji.="\n".'<li><span class="lt2v0">今日影视:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select count(*) from page where updatetime >= '$datetoday'").' 部</span></li>';

	$tongji.="\n".'<li><span class="lt2v0">今日资源:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select count(*) from link where updatetime >= '$datetoday'").' 个</span></li>';
	
	$tongji.="\n".'<li><span class="lt2v0">正在上映:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select count(*) from page where mstatus=3").' 部</span></li>';

	$tongji.="\n".'<li><span class="lt2v0">马上上映:</span>'.'<span class="rt2v0 cred">'.dh_mysql_get_count("select count(*) from page where mstatus=2").' 部</span></li>';

	$tongji = '<ul>'.$tongji.'</ul>';
	$DH_side_tongji= str_replace("%content%",$tongji,$DH_side_tongji);
	
	//友情链接
	//$DH_side_fl= str_replace("%title%",'友情链接',$DH_side_each);	
	//$DH_side_fl= str_replace("%content%",'',$DH_side_fl);	
	
//	$DH_side_content=$DH_side_ad.$DH_side_hotmovie.$DH_side_hottv.$DH_side_tongji.$DH_side_fl;	
	$DH_side_content=$DH_side_hotmovie.$DH_side_hottv.$DH_side_tongji;	

	$DH_side= str_replace("%side_content%",$DH_side_content,$DH_side);
	$DH_output_file = $DH_share_output_path. 'side.html';
	dh_file_put_contents($DH_output_file,$DH_side);	
	echo "gen side success !</br>\n";
	
//	$DH_input_html  = $DH_html_path . 'cse.html';
//	$DH_output = dh_file_get_contents($DH_input_html);
//	$DH_output = setshare($DH_output,'page.js');	
//	$DH_output_file = $DH_output_path. 'cse.html';
//	dh_file_put_contents($DH_output_file,$DH_output);	
	
}

function dh_gen_search($DH_home_url,$searchname)
{
	global $DH_search_output_path,$DH_search_input_path;
	$DH_input_html = $DH_search_input_path. $searchname;
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = setshare($DH_output,'');
	$DH_output = str_replace("输入影视名...",'<?php echo $q ?>',$DH_output);
	$DH_output = str_replace('<form name="f1"  target="_blank"','<form name="f1"', $DH_output);
	
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_search_output_path. $searchname;
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen search success !</br>\n";		
}

function dh_get_movie_type_ul($sql)
{
	global $DH_html_url,$linkway;
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

function setshare($DH_output_content,$js)
{
	global $DH_home_url,$DH_input_path,$DH_html_path,$DH_name,$DH_name_des;
	$DH_share_output_path = $DH_input_path.'top/';
	$DH_input_html  = $DH_share_output_path . 'meta.html';
	$DH_output_meta = dh_file_get_contents("$DH_input_html");
	$DH_input_html  = $DH_share_output_path . 'side.html';
	$DH_output_side = dh_file_get_contents("$DH_input_html");
	$DH_input_html  = $DH_share_output_path . 'head.html';
	$DH_output_head = dh_file_get_contents("$DH_input_html");	
	$DH_input_html  = $DH_share_output_path . 'foot.html';
	$DH_output_foot = dh_file_get_contents("$DH_input_html");
	
	$DH_output_content = str_replace("%meta%",$DH_output_meta,$DH_output_content);
	$DH_output_content = str_replace("%side%",$DH_output_side,$DH_output_content);
	$DH_output_content = str_replace("%head%",$DH_output_head,$DH_output_content);
	$DH_output_content = str_replace("%foot%",$DH_output_foot,$DH_output_content);		
	$DH_output_content = str_replace("%DH_name%",$DH_name,$DH_output_content);
	$DH_output_content = str_replace("%DH_name_des%",$DH_name_des,$DH_output_content);
	
	if($js!='')
	{
		$DH_input_html  = $DH_html_path.$js;
		$DH_js = dh_file_get_contents($DH_input_html);
		$DH_js = str_replace("%home%",$DH_home_url,$DH_js);
		$myPacker = new compressJS($DH_js);	
		$DH_js = $myPacker->pack();
		$DH_output_content = str_replace("%$js%",$DH_js,$DH_output_content);	
	}
	
	$DH_input_html  = $DH_html_path . 'foot.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output_content = str_replace("%footjs%",$DH_js,$DH_output_content);	
	
	return $DH_output_content;
}
?>
