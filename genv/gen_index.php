<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_list
/// 函数作用：产生静态的index.html页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
require("../config.php");
require("config.php");
require("../common/curl.php");
require("../common/base.php");
require("../common/dbaction.php");
require("common.php");
require("../common/compressJS.class.php");
require("movie.mtime.com.boxoffice.php");
require("../share/share.php");
set_time_limit(600);

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败'.mysql_error());
mysql_query("set names utf8;");

readerdirect();
dh_gen_share($DH_home_url);
dh_gen_side($DH_home_url);
dh_gen_index();

function dh_gen_index()
{
	global $DH_home_url,$DH_html_path,$DH_html_url,$DH_output_path,$conn,$pagecount,$DH_name,$DH_name_des;
	$DH_input_html  = $DH_html_path . 'index.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");

	$DH_output_content = setshare($DH_output_content,'index.js');
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
	$DH_output_content = str_replace("%DH_name%",$DH_name,$DH_output_content);
	$DH_output_content = str_replace("%DH_name_des%",$DH_name_des,$DH_output_content);	
	
	$topnews="今日更新（";
	$datetoday =date("Y-m-d");
	$datetoday = date("Y-m-d  H:i:s",strtotime($datetoday));
	$topnews.="影视".dh_mysql_get_count("select count(*) from page where updatetime >= '$datetoday'")."部";
	$topnews.="，在线下载".dh_mysql_get_count("select count(*) from link where updatetime >= '$datetoday' and (linkway=6 or linkway=7)")."个";
	$topnews.="，资讯影评".dh_mysql_get_count("select count(*) from link where updatetime >= '$datetoday' and (linkway=1 or linkway=2)")."条）";
	$topnews.="全部资源（";
	$topnews.="影视".dh_mysql_get_count("select max(id) from page")."部";
	$topnews.="，在线下载".dh_mysql_get_count("select count(*) from link  where linkway=6 or linkway=7")."个";
	$topnews.="，资讯影评".dh_mysql_get_count("select count(*) from link where linkway=1 or linkway=2")."条）";
	
	$topnews.=" <a href=\"http://www.movie002.com/update.html\">今日资源更新>></a>";
	
	$DH_output_content = str_replace("%topnews%",$topnews,$DH_output_content);
	$DH_index_cats = dh_get_index_cats();	
	$DH_output_content = str_replace("%index_cats%",$DH_index_cats,$DH_output_content);
	$DH_output_file = $DH_output_path.'index.html';
	dh_file_put_contents($DH_output_file,$DH_output_content);		
}

function dh_get_index_cats()
{
	global $DH_index_url,$DH_archivers_url,$DH_html_url,$DH_html_path,$conn,$linkway,$linktype;	

	$DH_index_cats='';	
	$DH_input_html  = $DH_html_path . 'index_cat.html';
	$DH_index_cat = dh_file_get_contents($DH_input_html);	
	$DH_input_html  = $DH_html_path . 'index_cat_each.html';
	$DH_index_cat_each = dh_file_get_contents($DH_input_html);
	
	//整理动态链接的文章
	$DH_index_cats .= dh_gen_top($DH_index_cat,$DH_index_cat_each);	
	//整理影视的类别
	$DH_index_cats .= dh_gen_movie($DH_index_cat);
	return $DH_index_cats;
}

function dh_gen_top($DH_index_cat,$DH_index_cat_each)
{
	global $DH_home_url,$imgurlputl,$DH_index_url,$conn,$linktype,$DH_html_url,$DH_input_path;	
	$DH_index_cat_ins = str_replace("%catname%",'',$DH_index_cat);
	$DH_index_cat_ins = str_replace("%catnum%",'',$DH_index_cat_ins);
	$DH_index_cat_eachs	='';
		
	$liouttitle ="\n<div class=\"TabTitle lt2v0\"> \n <ul id=\"myTaba\">";
	$liouttitle .="\n".'<li class="active" onmouseover="nTabs(this,0);">正在上映</li>';
	$liouttitle .="\n".'<li class="normal" onmouseover="nTabs(this,1);">马上登陆</li>';
	$liouttitle .="\n</ul>\n</div>";	

	$DH_index_cat_each1 = str_replace("%cat_each_title%","&nbsp;",$DH_index_cat_each);
	
	$sql="select count(*) from page where mstatus=3";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);	
	$sql="select * from page where mstatus=3 order by hot desc limit 0,5";
	$DH_index_cat_each_ins1 = str_replace("%index_cat_each%",dh_get_movie_type_img($sql),$DH_index_cat_each1);
	$DH_index_cat_each_ins1 = str_replace("%cat_each_title_more%",'更多正在上映  ('.$count[0].' 部)>>',$DH_index_cat_each_ins1);
	$DH_index_cat_each_ins1 = str_replace("%cat_each_title_url%",$DH_index_url.'1_o/1.html',$DH_index_cat_each_ins1);
	
	$liout = '<div id="myTaba_Content0">'.$DH_index_cat_each_ins1.'</div>';
	
	$sql="select count(*) from page where mstatus=2";
	$results=dh_mysql_query($sql);	
	$count = mysql_fetch_array($results);		
	$sql="select * from page where mstatus=2 order by hot desc limit 0,5";
	$DH_index_cat_each_ins2 = str_replace("%index_cat_each%",dh_get_movie_type_img($sql),$DH_index_cat_each1);
	$DH_index_cat_each_ins2 = str_replace("%cat_each_title_more%",'更多马上登陆 ('.$count[0].' 部) >>',$DH_index_cat_each_ins2);
	$DH_index_cat_each_ins2 = str_replace("%cat_each_title_url%",$DH_index_url.'1_i/1.html',$DH_index_cat_each_ins2);	
	$liout .= '<div id="myTaba_Content1"  class="none">'.$DH_index_cat_each_ins2.'</div>';
	
	$liout ='<div class="TabContent">'.$liouttitle.$liout.'</div>';
	$DH_index_cat_eachs .='<div style="float:left;position:relative;width:62%;margin: 0 auto 0 8px">'.$liout.'</div>';
	
	//最上面右侧内容
	$liouttitle ="\n<div class=\"TabTitle\"> \n <ul id=\"myTabt\">";
	$liouttitle .="\n".'<li class="active" onclick="nTabs(this,0);">大陆票房</li>';	
	$liouttitle .="\n".'<li class="normal" onclick="nTabs(this,1);">欧美票房</li>';		
	$liouttitle .="\n".'<li class="normal" onclick="nTabs(this,2);">香港票房</li>';	
	$liouttitle .="\n".'<li class="normal" onclick="nTabs(this,3);">台湾票房</li>';	
	$liouttitle .="\n</ul>\n</div>";

	$DH_index_cat_each_ins = str_replace("%cat_each_title%",$liouttitle,$DH_index_cat_each);
	$DH_index_cat_each_ins = str_replace("%cat_each_title_more%",'',$DH_index_cat_each_ins);
	$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",'#',$DH_index_cat_each_ins);
	
	$toparray= array(1,0,2,3);
	$liouteachs='';
	foreach($toparray as $topkey=>$top_index)			
	{
		$DH_top  = $DH_input_path . 'top/'.$top_index.'.top';
		if($topkey==0)
			$liouteach ="\n".' <div id="myTabt_Content'.($topkey).'">'."\n".dh_file_get_contents($DH_top)."\n".'</div>';
		else
			$liouteach ="\n".' <div id="myTabt_Content'.($topkey).'" class="none">'."\n".dh_file_get_contents($DH_top)."\n".'</div>';
		$liouteachs .= $liouteach;
	}
	
	$liout ='<div class="TabContent">'.$liouteachs.'</div>';
	$DH_index_cat_each_ins = str_replace("%index_cat_each%",$liout,$DH_index_cat_each_ins);	
	$DH_index_cat_eachs .='<div style="width:36%;float:right;margin-right:10px">'.$DH_index_cat_each_ins.'</div>';
	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_gen_movie($DH_index_cat)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	
	//规划好每个类别的头部，电影和电视都是一样的
	$DH_input_html  = $DH_html_path . 'index_cat_title.html';
	$DH_index_cat_title = dh_file_get_contents($DH_input_html);	
	
	$DH_index_cats='';
	foreach($movietype as $movietype_index=>$movietype_name)
	{
		$DH_index_cats_each='';
		$liout='';
		//只取电影、电视
		if($movietype_index<1)
				continue;
		echo "</br>\n".$movietype_name.":";
		$i=0;
		
		$sql="select count(*) from page where cattype=$movietype_index";
		$results=dh_mysql_query($sql);
		$count = mysql_fetch_array($results);	
		$DH_index_cat_each = str_replace("%catnum%",'共'.$count[0].'部',$DH_index_cat);

		//热门资源
		$DH_index_cats_each.="\n".'<div id="myTab'.$movietype_index.'_Content'.$i.'" style="overflow:hidden;z-index:2;">'.dh_get_hot($DH_index_cat_each,$movietype_index)."\n</div>\n";
		$liout .="\n".'<li class="active" onclick="nTabs(this,'.$i.');">热门资源</li>';	
		$i++;	
		
		if($movietype_index==1||$movietype_index==2)
		{
			//超清资源
			$DH_index_cats_each.="\n".'<div id="myTab'.$movietype_index.'_Content'.$i.'" class="none" style="overflow:hidden;z-index:2;">'.dh_get_high($DH_index_cat_each,$movietype_index)."\n</div>\n";;
			$liout .="\n".'<li class="normal" onclick="nTabs(this,'.$i.');">超清资源</li>';
			$i++;
		}
		//最新在线+最新下载		
		$DH_index_cats_each.="\n".'<div id="myTab'.$movietype_index.'_Content'.$i.'" class="none" style="overflow:hidden;z-index:2;">'.dh_get_new($DH_index_cat_each,$movietype_index)."\n</div>\n";
		$liout .='<li class="normal" onclick="nTabs(this,'.$i.');">最新资源</li>';
		$i++;
		//最新影评+最新新闻
		$DH_index_cats_each.="\n".'<div id="myTab'.$movietype_index.'_Content'.$i.'" class="none" style="overflow:hidden;z-index:2;font-size:12px">'.dh_get_link2($DH_index_cat_each,$movietype_index)."\n</div>\n";;
		$liout .="\n".'<li class="normal" onclick="nTabs(this,'.$i.');">资讯/影评</li>';
		$i++;
		if($movietype_index==1)
		{		
			//预告/购票/活动
			$DH_index_cats_each.="\n".'<div id="myTab'.$movietype_index.'_Content'.$i.'" class="none" style="overflow:hidden;z-index:2;font-size:12px">'.dh_get_link($DH_index_cat_each,$movietype_index)."\n</div>\n";
			$liout .='<li class="normal" onclick="nTabs(this,'.$i.');">预告/购票/活动</li>';
		}
				
		$liouttitle ="\n<div class=\"TabTitle TabTitlex\"> \n <ul id=\"myTab$movietype_index\">";
		$liouttitle .=$liout;
		$liouttitle .="\n</ul>\n</div>";		
		$DH_index_cats .= "\n".'<div class="TabContent"><div class="anchor"><a name="amyTab'.$movietype_index.'" id="amyTab'.$movietype_index.'">&nbsp;</a></div>'.$liouttitle.$DH_index_cats_each.'</div>';
	}
	return $DH_index_cats;	
}

function dh_get_new($DH_index_cat,$movietype_index)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and l.linkway=6 order by l.updatetime desc limit 0,8";
	$list = dh_get_movie_type_onlylink($sql);	
	$topic_list = "\n<div class=\"title_list f12px\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_download/1.html';

	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_download/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title">最新下载</div><div class="topic-add-comment f12px">'.$more.'<a href="'.$link.'">全部</a></div></div>';	
	$result1 ="\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";
	
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and l.linkway=7 order by l.updatetime desc limit 0,8";	
	$list = dh_get_movie_type_onlylink($sql);
	
	$topic_list = "\n<div class=\"title_list f12px\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_online/1.html';
	
	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_online/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}	
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title">最新在线</div><div class="topic-add-comment f12px">'.$more.'<a href="'.$link.'">全部</a></div></div>';		
	$result2 = "\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";
	
	$DH_index_cat_eachs=$result2.$result1;	
	$DH_index_cat_ins = str_replace("%catname%",$movietype[$movietype_index],$DH_index_cat);
	$DH_index_cat_ins = str_replace("%more%",'',$DH_index_cat_ins);
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_get_hot($DH_index_cat,$movietype_index)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	if($movietype_index==1||$movietype_index==2)
	{
		$sql="select * from page where cattype = $movietype_index order by hot desc limit 0,3";
		$result1 = '<div class="col3 bb">'.dh_get_movie_type_list($sql,'index_list_each3.html','big').'</div>';
		$sql="select * from page where cattype = $movietype_index order by hot desc limit 3,12";		
	}
	else
	{
		$sql="select * from page where cattype = $movietype_index order by hot desc limit 0,2";
		$result1 = '<div class="col2 bb">'.dh_get_movie_type_list($sql,'index_list_each2.html','big').'</div>';	
		$sql="select * from page where cattype = $movietype_index order by hot desc limit 2,12";
	}		
	$list = dh_get_movie_type_block($sql,4);
	$result2='<div class="col4">'.$list.'</div>';
	
	$DH_index_cat_eachs=$result1.$result2;		
	$DH_index_cat_ins = str_replace("%catname%",$movietype[$movietype_index],$DH_index_cat);
	
	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		$more=$movietype[$movietype_index].'_热门资源子类: ';
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_h/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}
	$more.='<a href="'.$DH_index_url.$movietype_index.'_h/1.html">全部</a> ';
	$DH_index_cat_ins = str_replace("%more%",$more,$DH_index_cat_ins);	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_get_high($DH_index_cat,$movietype_index)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	$sql="select * from page where cattype = $movietype_index and ziyuan>0 and quality>=6 order by updatetime desc limit 0,2";
	$result1 ='<div class="col2 bb">'. dh_get_movie_type_list($sql,'index_list_each2.html','big').'</div>';
	
	$sql="select * from page where cattype = $movietype_index and ziyuan>0 and quality>=6 order by updatetime desc limit 2,12";				
	$list = dh_get_movie_type_block($sql,3);
	$result2='<div class="col4">'.$list.'</div>';	
	$DH_index_cat_eachs=$result1.$result2;		
	$DH_index_cat_ins = str_replace("%catname%",$movietype[$movietype_index],$DH_index_cat);
	
	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		$more=$movietype[$movietype_index].'_高清资源子类: ';
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_c/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}
	$more.='<a href="'.$DH_index_url.$movietype_index.'_c/1.html">全部</a> ';
	$DH_index_cat_ins = str_replace("%more%",$more,$DH_index_cat_ins);
	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_get_link2($DH_index_cat,$movietype_index)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and l.linkway=2 order by l.updatetime desc limit 0,10";
	$list = dh_get_movie_type_onlylink($sql);	
	$topic_list = "\n<div class=\"link_list\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_yp/1.html';

	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		$more='子类：';
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_yp/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}	
	$more.='<a href="'.$DH_index_url.$movietype_index.'_yp/1.html">全部</a> ';
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title f14px">最新影评</div><div class="topic-add-comment f12px">'.$more.'</div></div>';	
	$result1 ="\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";
	
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and l.linkway=1 order by l.updatetime desc limit 0,10";
	$list = dh_get_movie_type_onlylink($sql);	
	$topic_list = "\n<div class=\"link_list\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_zx/1.html';
	
	$more='';
	if($movietype_index==1||$movietype_index==2)
	{
		$more='子类: ';
		foreach($moviecountry as $key=>$eachcountry)
		{
			if($key===0)continue;
			$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_zx/1.html">'.$eachcountry.'</a> &nbsp;';
		}
	}
	$more.='<a href="'.$DH_index_url.$movietype_index.'_zx/1.html">全部</a> ';
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title f14px">最新资讯</div><div class="topic-add-comment f12px">'.$more.'</div></div>';	
	$result2 ="\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";	

	$DH_index_cat_eachs=$result1.$result2;	
	$DH_index_cat_ins = str_replace("%catname%",$movietype[$movietype_index],$DH_index_cat);
	$DH_index_cat_ins = str_replace("%more%",'',$DH_index_cat_ins);
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_get_link($DH_index_cat,$movietype_index)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and l.linkway=3 order by l.updatetime desc limit 0,10";
	$list = dh_get_movie_type_onlylink($sql);
	$topic_list = "\n<div class=\"link_list\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_yg/1.html';
	
	$more='';
	foreach($moviecountry as $key=>$eachcountry)
	{
		if($key===0)continue;
		$more.='<a href="'.$DH_index_url.$movietype_index.'_'.$key.'_yg/1.html">'.$eachcountry.'</a> &nbsp;';
	}
	$more.='<a href="'.$DH_index_url.$movietype_index.'_yg/1.html">全部</a> ';	
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title f14px">预告·花絮</div><div class="topic-add-comment f12px">'.$more.'</div></div>';	
	$result1 ="\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";
	
	$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and (l.linkway=4 or l.linkway=5) order by l.updatetime desc limit 0,10";
	$list = dh_get_movie_type_onlylink($sql);	
	$topic_list = "\n<div class=\"link_list\">\n $list \n</div>";
	$link = $DH_index_url.$movietype_index.'_gp/1.html';
	
	$topic_title = "\n".'<div class="topic_title"><div class="second_title f14px">活动·购票</div><div class="topic-add-comment f12px">'.'<a href="'.$link.'">全部</a></div></div>';	
	$result2 ="\n<div class=\"topic_each\">".$topic_title.$topic_list."\n</div>";	

	$DH_index_cat_eachs=$result1.$result2;	
	$DH_index_cat_ins = str_replace("%catname%",$movietype[$movietype_index],$DH_index_cat);
	$DH_index_cat_ins = str_replace("%more%",'',$DH_index_cat_ins);
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_get_movie_type_onlylink($sql)
{
	global $DH_html_url,$conn,$linkway;
	$recs=dh_mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{									
		$liout="<ol>";
		while($row = mysql_fetch_array($recs))
		{
			$htmlpath = output_page_path($DH_html_url,$row['pageid']);
			$update = date("m-d",strtotime($row['updatetime']));
			$titlea='';
			$title=$row['title'];
			if(mb_strlen($title,'UTF-8')>20)
			{
				$titlea='title="'.$title.'"';
				$title=mb_substr($title,0,20,'UTF-8').'...';
			}
			$lieach = "\n".'<li><span class="line1"><span class="f12px">['.$linkway[$row['linkway']].']</span> <a href="'.$row['link'].'" target="_blank" '.$titlea.' rel="nofollow" >'.$title.'['.$row['author'].']</a><span class="f12px"> (<a href="'.$htmlpath.'"target="_blank">汇总页面</a>)</span></span><span class="rt5v2">'.$update.'</span></li>';
			$liout.= $lieach;
		}
		$liout.= "\n</ol>";	
	}
	else
	{
		echo "查询返回0条记录！ <br>\n";
	}
	return $liout;	
}

function dh_get_movie_type_img($sql)
{
	global $DH_html_url,$conn,$linkway;
	$recs=dh_mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{									
		while($row = mysql_fetch_array($recs))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);
			$update = date("m-d",strtotime($row['updatetime']));
			$titlea='';
			$title=$row['title'];
			if(mb_strlen($title,'UTF-8')>20)
			{
				$titlea='title="'.$title.'"';
				$title=mb_substr($title,0,20,'UTF-8').'...';
			}
			
			$simgurl = $row['imgurl'];
			$src=$htmlpath;
			$img_src='http://img3.douban.com/view/photo/thumb/public/p'.$simgurl.'.jpg';
			
			if(!empty($simgurl[0])&&$simgurl[0]=='s')
			{			
				$src=$htmlpath;
				$img_src='http://img3.douban.com/mpic/'.$simgurl;			
			}
			
			$imgeach = '<imgdao link_src="'.$src.'" img_src="'.$img_src.'" style="width:108px;height:215px;margin:4px" src_width="108px" src_height="180px" alt="'.$title.'的海报" frm_height="220px" name="'.$title.'"><span></span></imgdao>';
			$liout.= $imgeach;
		}
	}
	else
	{
		echo "查询返回0条记录！ <br>\n";
	}
	return '<div style="margin:15px 0 0 0">'.$liout.'</div>';	
}

function dh_get_movie_type_block($sql,$i)
{
	global $DH_html_url,$conn,$linkway,$linkquality;
	$recs=dh_mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{									
		$liout="<ol>";
		while($row = mysql_fetch_array($recs))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);
			$update = date("m-d",strtotime($row['updatetime']));
			$titlea='';
			$title=$row['title'];
			if(mb_strlen($title,'UTF-8')>20)
			{
				$titlea='title="'.$title.'"';
				$title=mb_substr($title,0,20,'UTF-8').'...';
			}
			
			$country='';
			preg_match('/<g>(.*?)<\/g>/',$row['meta'],$match);
			if(!empty($match[1]))
			{	
				$country = $match[1];
			}
			$type="";
			preg_match('/<t>(.*?)<\/t>/',$row['meta'],$match);
			if(!empty($match[1]))
			{	
				$type = $match[1];
			}
			$linkqualitymeta = '';
			if($row['quality']>=1)
				$linkqualitymeta = '['.$linkquality[$row['quality']].'] ';
			$lieach = "\n".'<li><div><span class="line1 w100pre">'.$i.' <a href="'.$htmlpath.'" target="_blank" '.$titlea.'>'.$title.'</a> <span class="hotmeta1">'.$linkqualitymeta.$update.'</span></span></div><div class="hotmeta1">['.$country.':'.$type.'] <span class="cred">'.$row['hot'].'</span></div></li>';
			$liout.= $lieach;
			$i++;
		}
		$liout.= "\n</ol>";	
	}
	else
	{
		echo "查询返回0条记录！ <br>\n";
	}
	return $liout;	
}

function dh_get_movie_type_list($sql,$list_html,$type)
{
	global $DH_html_url,$conn,$linkway,$DH_html_path;
	$recs=dh_mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{			
		$DH_input_html  = $DH_html_path .$list_html;
		$DH_output_content = dh_file_get_contents("$DH_input_html");
		$liout='<ol class="f12px">';
		while($row = mysql_fetch_array($recs))
		{			
			$DH_output_content_page = dh_replace_snapshot($type,$row,$DH_output_content,false);
			$DH_output_content_page = dh_replace_content(1,$row,$DH_output_content_page);
			$liout.= $DH_output_content_page;
		}
		$liout.= "\n</ol>";	
	}
	else
	{
		echo "查询返回0条记录！ <br>\n";
	}
	return $liout;	
}
?>
