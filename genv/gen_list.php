<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_list
/// 函数作用：产生静态的最新的文章列表页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 
set_time_limit(120);

#需要使用的基础函数
require("../config.php");
require("config.php");
require("common.php");
require("../common/base.php");
require("../common/dbaction.php");
require("../share/share.php");
require("../common/page_navi.php");
require("../common/compressJS.class.php");

function dh_pagenum_link($link,$page)
{
	return $link.$page.'.html';
}

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");
dh_gen_list();
mysql_close($conn);

function dh_gen_list()
{
	global $DH_home_url,$DH_html_path,$DH_output_index_path,$DH_output_path,$conn,$pagecount,$movietype,$moviecountry,$linktype,$DH_index_url;
	$DH_input_html  = $DH_html_path . 'list.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	$DH_output_content = setshare($DH_output_content,'list.js');

	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);	
//	$DH_output_content = str_replace("%weibo%",'',$DH_output_content);	

	$datebegin = getupdatebegin(60);
	if (!file_exists($DH_output_index_path))  
		mkdir($DH_output_index_path,0777);
	
	//找出要更新的分类
	foreach($movietype as $keytype=>$movietypeeach)	
	{
		if($keytype==0)
			continue;
			
		$sql1="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkquality ,l.linkway,p.hot,p.catcountry,p.cattype from link l,page p where l.pageid=p.id and p.cattype = $keytype";
		$sqltime1=" '$datebegin' <= date(l.updatetime) order by l.updatetime desc";
			
		//生成按国家的
		switch ($keytype)
		{
			case 1:
			{
				foreach($moviecountry as $keycountry=>$moviecountryeach)
				{
					if($keycountry==0)
						continue;
					echo $movietypeeach.'_'.$moviecountryeach."</br>\n";
					$catpre = dh_get_catname($keytype,$keycountry);

					//生成 正在上映
					$path = $keytype.'_'.$keycountry.'_o/';		
					$cat = '正在上映 ('.$moviecountryeach.')';
					$catlink=' <a href="'.$DH_index_url.'1_o/1.html">[正在上映]</a> >> ';
					$sql="select * from page p where mstatus=3 and cattype = $keytype and catcountry = $keycountry order by hot desc";
					dh_gen_each_file($sql,$DH_output_content,$path,$cat,$catlink,'o');
					//生成 马上登陆
					$path = $keytype.'_'.$keycountry.'_i/';		
					$cat = '马上登陆 ('.$moviecountryeach.')';
					$catlink=' <a href="'.$DH_index_url.'1_o/1.html">[马上登陆]</a> >> ';
					$sql="select * from page p where mstatus=2 and cattype = $keytype and catcountry = $keycountry order by hot desc";
					dh_gen_each_file($sql,$DH_output_content,$path,$cat,$catlink,'i');
					//生成 预告花絮
					$path = $keytype.'_'.$keycountry.'_yg/';		
					$cat = $catpre.'[预告花絮]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_yg/1.html">'.$movietype[$keytype].'[预告花絮]</a> >> ';
					$sql=$sql1." and l.linkway=3 and  p.catcountry = $keycountry and ".$sqltime1;
					dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,$catlink,'yg');
				}
			}
			case 2:
			{			
				foreach($moviecountry as $keycountry=>$moviecountryeach)
				{
					if($keycountry==0)
						continue;
					echo $movietypeeach.'_'.$moviecountryeach."</br>\n";
					$catpre = dh_get_catname($keytype,$keycountry);
					//生成 超清资源
					$path = $keytype.'_'.$keycountry.'_c/';		
					$cat = $catpre.'[超清资源]';
					$sql="select * from page where cattype=".$keytype." and catcountry=".$keycountry." and ziyuan>0 and quality>=6 order by updatetime desc";				
					dh_gen_each_file($sql,$DH_output_content,$path,$cat,'','c');
					//生成 最新影评
					$path = $keytype.'_'.$keycountry.'_yp/';		
					$cat = $catpre.'[最新影评]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_yp/1.html">'.$movietype[$keytype].'[最新影评]</a> >> ';
					$sql=$sql1." and l.linkway=2 and  p.catcountry = $keycountry and ".$sqltime1;
					dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,$catlink,'yp');
					//生成 最新资讯
					$path = $keytype.'_'.$keycountry.'_zx/';		
					$cat = $catpre.'[最新资讯]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_zx/1.html">'.$movietype[$keytype].'[最新资讯]</a> >> ';
					$sql=$sql1." and l.linkway=1 and  p.catcountry = $keycountry and ".$sqltime1;
					dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,$catlink,'zx');
					//生成 最新下载
					$path = $keytype.'_'.$keycountry.'_download/';		
					$cat = $catpre.'[最新下载]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_download/1.html">'.$movietype[$keytype].'[最新下载]</a> >> ';
					$sql=$sql1." and l.linkway=6 and  p.catcountry = $keycountry and ".$sqltime1;
					dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,$catlink,'download');
					//生成 最新在线
					$path = $keytype.'_'.$keycountry.'_online/';		
					$cat = $catpre.'[最新在线]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_online/1.html">'.$movietype[$keytype].'[最新在线]</a> >> ';
					$sql=$sql1." and l.linkway=7 and  p.catcountry = $keycountry and ".$sqltime1;
					dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,$catlink,'online');
					//生成 近日热门
					$path = $keytype.'_'.$keycountry.'_h/';		
					$cat = $catpre.'[近日热门]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_h/1.html">'.$movietype[$keytype].'[近日热门]</a> >> ';	
					$sql="select * from page where cattype=".$keytype." and catcountry=".$keycountry." and hot>=10 order by hot desc";
					dh_gen_each_file($sql,$DH_output_content,$path,$cat,$catlink,'h');
				}
			}
			case 3:
			case 4:
				break;
			default:
				echo 'gen list type error!';
		}

		//生成全部资源
		//生成 最新下载
		$path = $keytype.'_download/';		
		$cat =$movietypeeach. '[最新下载]';			
		$sql=$sql1." and l.linkway=6 and ".$sqltime1;
		$needcountrytype = 'download';
		if($keytype>2) $needcountrytype = 'no';
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'',$needcountrytype);	
		//生成 最新在线
		$path = $keytype.'_online/';		
		$cat =$movietypeeach. '[最新在线]';			
		$sql=$sql1." and l.linkway=7 and ".$sqltime1;
		$needcountrytype = 'online';
		if($keytype>2) $needcountrytype = 'no';
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'',$needcountrytype);
		//生成 近日热门
		$path = $keytype.'_h/';		
		$cat = $movietypeeach.'[近日热门]';
		$sql="select * from page where cattype=$keytype and hot>=10 order by hot desc";
		$needcountrytype = 'h';
		if($keytype>2) $needcountrytype = false;
		dh_gen_each_file($sql,$DH_output_content,$path,$cat,'',$needcountrytype);
		//最新影评
		$path = $keytype.'_yp/';		
		$cat =$movietypeeach. '[最新影评]';
		$sql=$sql1." and l.linkway=2 and ".$sqltime1;
		$needcountrytype = 'yp';
		if($keytype>2) $needcountrytype = 'no';
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'',$needcountrytype);	
		//最新资讯
		$path = $keytype.'_zx/';		
		$cat =$movietypeeach. '[最新资讯]';	
		$sql=$sql1." and l.linkway=1 and ".$sqltime1;
		$needcountrytype = 'zx';
		if($keytype>2) $needcountrytype = 'no';
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'',$needcountrytype);	
		
		//对电影和电视生成影评和资讯,预告花絮,活动购票
		if($keytype>2)
			continue;
		//超清资源
		$path = $keytype.'_c/';		
		$cat = $movietypeeach.'[超清资源]';
		$sql="select * from page where cattype=".$keytype." and ziyuan>0 and quality>=6 order by updatetime desc";				
		dh_gen_each_file($sql,$DH_output_content,$path,$cat,'','c');
		
		//对电影生成预告花絮,活动购票
		if($keytype>1)
			continue;
		//预告花絮
		$path = $keytype.'_yg/';		
		$cat =$movietypeeach. '[预告花絮]';
		$sql=$sql1." and l.linkway=3 and ".$sqltime1;
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'','yg');
	
		//活动购票
		$path = $keytype.'_gp/';		
		$cat =$movietypeeach. '[活动购票]';	
		$sql=$sql1." and (l.linkway=4 or l.linkway=5) and ".$sqltime1;
		dh_gen_each_file_onlylink($sql,$DH_output_content,$path,$cat,'','gp');
		//得到 正在上映
		echo "正在上映</br>\n";
		$path = '1_o/';	
		$cat = '正在上映';
		$sql="select * from page where  mstatus=3 order by hot desc";
		dh_gen_each_file($sql,$DH_output_content,$path,$cat,'','o');
		//得到 马上登陆
		echo "马上登陆</br>\n";
		$path = '1_i/';	
		$cat = '马上登陆';
		$sql="select * from page  where mstatus=2 order by hot desc";
		dh_gen_each_file($sql,$DH_output_content,$path,$cat,'','i');		
	}	
}

function dh_gen_each_file($sql,$DH_catlist,$path,$cat,$catlink='',$needcountrytype=false)
{
	global $DH_html_url,$DH_html_path,$DH_output_index_path,$DH_index_url,$conn,$pagecount;
	$outputpath = $DH_output_index_path.$path;
	echo $outputpath;
	if (!file_exists($outputpath))  
	{   
		mkdir($outputpath,0777);
	}
	
	$DH_input_html  = $DH_html_path . 'list_each.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	
	$DH_catlist_new = str_replace("%cat%",$cat,$DH_catlist);
	$DH_catlist_new = str_replace("%catlink%",$catlink,$DH_catlist_new);
	
	$count=0;
	$results=dh_mysql_query($sql);			
	$count = mysql_num_rows($results);
	echo '共'.$count."篇/".$pagecount;
	$pages=ceil($count/$pagecount);
	echo '/共'. $pages. "页</br>\n";	
	$count=0;
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$liout='';
		while($row = mysql_fetch_array($results))
		{
			$count++;			
			$DH_output_content_page = dh_replace_snapshot('list',$row,$DH_output_content,$needcountrytype);
			$page_path = output_page_path($DH_html_url,$row['id']);
			$DH_output_content_page = str_replace("%title_link%",$page_path,$DH_output_content_page);			
			$liout.= $DH_output_content_page;
			if($count%$pagecount==0)
			{
				$catpage = $count/$pagecount;
				$pagenavi = dh_pagenavi(5,$pages,$DH_index_url.$path,$catpage);
				echo 'genpage:'.$catpage."</br>\n";				
				$DH_catlist_new2= str_replace("%pagenavi%",$pagenavi,$DH_catlist_new);
				$DH_catlist_new2= str_replace("%list_each%",$liout,$DH_catlist_new2);
				$DH_catlist_new2 = str_replace("%num%",$catpage,$DH_catlist_new2);
				$linkurl=dh_pagenum_link($DH_index_url.$path,$catpage);
				$DH_catlist_new2 = str_replace("%permalink%",$linkurl,$DH_catlist_new2);
				$DH_output_file = $outputpath.$catpage.'.html';
				dh_file_put_contents($DH_output_file,$DH_catlist_new2);
				$liout='';
			}
		}
		if($count%$pagecount!=0)
		{
			$catpage = ceil($count/$pagecount);
			$pagenavi = dh_pagenavi(5,$pages,$DH_index_url.$path,$catpage);
			echo 'genpage:'.$catpage."</br>\n";				
			$DH_catlist_new2= str_replace("%pagenavi%",$pagenavi,$DH_catlist_new);
			$DH_catlist_new2= str_replace("%list_each%",$liout,$DH_catlist_new2);
			$DH_catlist_new2 = str_replace("%num%",$catpage,$DH_catlist_new2);
			$linkurl=dh_pagenum_link($DH_index_url.$path,$catpage);
			$DH_catlist_new2 = str_replace("%permalink%",$linkurl,$DH_catlist_new2);
			$DH_output_file = $outputpath.$catpage.'.html';
			dh_file_put_contents($DH_output_file,$DH_catlist_new2);			
		}
	}	
}

function dh_gen_each_file_onlylink($sql,$DH_catlist,$path,$cat,$catlink,$needcountrytype='no')
{
	echo $cat;
	global $DH_html_url,$DH_index_url,$conn,$pagecount,$DH_output_index_path,$linkquality,$linkway,$moviecountry;
	$pagecountonly = $pagecount*4;
	
	$outputpath = $DH_output_index_path.$path;
	if (!file_exists($outputpath))  
	{   
		mkdir($outputpath,0777);
	}
	echo $outputpath;
	
	$DH_catlist_new = str_replace("%cat%",$cat,$DH_catlist);
	$DH_catlist_new = str_replace("%catlink%",$catlink,$DH_catlist_new);
	
	$count=0;
	$results=dh_mysql_query($sql);
	//echo $sql;
	$count = mysql_num_rows($results);
	//print_r($count);
	echo '共'.$count."篇/".$pagecountonly;
	$pages=ceil($count/$pagecountonly);
	echo '/共'. $pages. "页</br>\n";	
	$count=0;
	if($results)
	{	
		$liouttop='<li><span class="colorred vtop">类型</span> <span class="width90pre" style="margin-left:50px">资源名</span><span class="rt100v2">本站汇总</span> <span class="rt60v2">热度</span> <span class="rt5v2 colorred" >日期</span></li>';
		$liout = $liouttop;
		while($row = mysql_fetch_array($results))
		{
			$count++;
			$htmlpath = output_page_path($DH_html_url,$row['pageid']);	
			$updatef = date("m-d",strtotime($row['updatetime']));
			$countrymeta='';
			if($needcountrytype=='no')
			{
				$countrymeta='';
			}
			else if($needcountrytype=='nolink')
			{
				$countrymeta=' ['.$moviecountry[$row['catcountry']].'] ';
			}
			else
			{
				$countrymeta=' <a class="vtop" href="'.$DH_index_url.$row['cattype'].'_'.$row['catcountry'].'_'.$needcountrytype.'/1.html">['.$moviecountry[$row['catcountry']].']</a> ';
			}
			$lieach = '<li><span>'.$countrymeta.'</span> <span class="width90pre">【'.$linkway[$row['linkway']].'】<a href="'.$row['link'].'" target="_blank" rel="nofollow">'.$row['title'].'['.$row['author'].']</a></span> <span class="rt100v2"><a href="'.$htmlpath.'" target="_blank" >资源汇总</a></span><span class="rt60v2">'.$row['hot'].' </span> <span class="rt5v2" > '.$updatef.'</span></li>';			
			$liout.= $lieach;
			if($count%$pagecountonly==0)
			{
				$catpage = ceil($count/$pagecountonly);
				$pagenavi = dh_pagenavi(5,$pages,$DH_index_url.$path,$catpage);
				echo 'genpage:'.$catpage."</br>\n";		
				$DH_catlist_new2= str_replace("%pagenavi%",$pagenavi,$DH_catlist_new);
				$DH_catlist_new2= str_replace("%list_each%",$liout,$DH_catlist_new2);
				$DH_catlist_new2 = str_replace("%num%",$catpage,$DH_catlist_new2);
				$linkurl=dh_pagenum_link($DH_index_url.$path,$catpage);
				$DH_catlist_new2 = str_replace("%permalink%",$linkurl,$DH_catlist_new2);
				$DH_output_file = $outputpath.$catpage.'.html';
				dh_file_put_contents($DH_output_file,$DH_catlist_new2);
				$liout = $liouttop;
			}
		}
		if($count%$pagecountonly!=0)
		{
			$catpage = ceil($count/$pagecountonly);
			$pagenavi = dh_pagenavi(5,$pages,$DH_index_url.$path,$catpage);
			echo 'genpage:'.$catpage."</br>\n";				
			$DH_catlist_new2= str_replace("%pagenavi%",$pagenavi,$DH_catlist_new);
			$DH_catlist_new2= str_replace("%list_each%",$liout,$DH_catlist_new2);
			$DH_catlist_new2 = str_replace("%num%",$catpage,$DH_catlist_new2);
			$linkurl=dh_pagenum_link($DH_index_url.$path,$catpage);
			$DH_catlist_new2 = str_replace("%permalink%",$linkurl,$DH_catlist_new2);
			$DH_output_file = $outputpath.$catpage.'.html';
			dh_file_put_contents($DH_output_file,$DH_catlist_new2);			
		}
	}	
}
?>
