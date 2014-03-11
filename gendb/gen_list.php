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
require("../common/common_gen.php");
require("../common/base.php");
require("../common/share.php");
require("../common/page_navi.php");
require("../common/compressJS.class.php");

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
	$deep = '../../';
	$DH_output_content = str_replace("%deep%",$deep,$DH_output_content);
	$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);	
	$DH_output_content = str_replace("%weibo%",'',$DH_output_content);	

	if (!file_exists($DH_output_index_path))  
		mkdir($DH_output_index_path,0777);
	
	//找出要更新的分类
	foreach($movietype as $keytype=>$movietypeeach)	
	{
		if($keytype==0)
			continue;
			
		//生成 全部最新
		$path = $keytype.'_t/';		
		$cat =$movietypeeach. '[全部最新]';			
		$sqlc="select count(*) from link l,page p where l.pageid=p.id and p.cattype = $keytype and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(l.updatetime)";
		$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkquality ,l.linkway,p.hot,p.catcountry,p.cattype from link l,page p where l.pageid=p.id and p.cattype = $keytype and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(l.updatetime) order by l.updatetime desc";
		dh_gen_each_file_onlylink($sqlc,$sql,$DH_output_content,$path,$cat,'t');		
		//生成 精选最新
		$path = $keytype.'_l/';		
		$cat =$movietypeeach. '[精选最新]';			
		$sqlc="select count(*) from page where cattype=$keytype and ziyuan>0 and ( hot>5 or DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(pubdate)) and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime)";	
		$sql="select * from page where cattype=$keytype and ziyuan>0  and  ( hot>5 or DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(pubdate)) and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime) order by updatetime desc";
		dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','l');
		//生成 近日热门
		$path = $keytype.'_h/';		
		$cat = $movietypeeach.'[近日热门]';
		$sqlc="select count(*) from page where cattype=$keytype and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) and hot>=10";	
		$sql="select * from page where cattype=$keytype and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) and hot>=10 order by hot desc";
		dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','h');		
			
		//再生成按国家的
		switch ($keytype)
		{
			case 1:
			{
				foreach($moviecountry as $keycountry=>$moviecountryeach)
				{
					if($keycountry==0)
						continue;
					echo $movietypeeach.'_'.$moviecountryeach."</br>\n";
					//$catpre = dh_get_catname($keytype,$keycountry);
					////生成 正在上映
					//$path = $keytype.'_'.$keycountry.'_o/';		
					//$cat = $catpre.'[正在上映]';
					//$catlink=' <a href="'.$DH_index_url.$keytype.'_o/1.html">'.$movietype[$keytype].'[正在上映]</a> >> ';
					//$sqlc="select count(*) from page p where mstatus=3 and cattype = $keytype and catcountry = $keycountry";
					//$sql="select * from page p where mstatus=3 and cattype = $keytype and catcountry = $keycountry order by hot desc";
					//dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,$catlink,'o');
					//生成 正在上映
					$path = $keytype.'_'.$keycountry.'_o/';		
					$cat = '正在上映 ('.$moviecountryeach.')';
					$catlink=' <a href="'.$DH_index_url.'1_o/1.html">[正在上映]</a> >> ';
					$sqlc="select count(*) from page p where mstatus=3 and cattype = $keytype and catcountry = $keycountry";
					$sql="select * from page p where mstatus=3 and cattype = $keytype and catcountry = $keycountry order by hot desc";
					dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,$catlink,'o');
					//生成 马上登陆
					$path = $keytype.'_'.$keycountry.'_i/';		
					$cat = '马上登陆 ('.$moviecountryeach.')';
					$catlink=' <a href="'.$DH_index_url.'1_o/1.html">[马上登陆]</a> >> ';
					$sqlc="select count(*) from page p where mstatus=2 and cattype = $keytype and catcountry = $keycountry";
					$sql="select * from page p where mstatus=2 and cattype = $keytype and catcountry = $keycountry order by hot desc";
					dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,$catlink,'i');
				}
			}
			case 2:
			{
				//生成 高清资源
				$path = $keytype.'_c/';		
				$cat = $movietypeeach.'[高清资源]';			
				$sqlc="select count(*) from page where cattype=".$keytype." and ziyuan>0 and quality>=5 ";	
				$sql="select * from page where cattype=".$keytype." and ziyuan>0 and quality>=5 order by updatetime desc";				
				dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','c');
			
				foreach($moviecountry as $keycountry=>$moviecountryeach)
				{
					if($keycountry==0)
						continue;
					echo $movietypeeach.'_'.$moviecountryeach."</br>\n";
					$catpre = dh_get_catname($keytype,$keycountry);
					//生成 高清资源
					$path = $keytype.'_'.$keycountry.'_c/';		
					$cat = $catpre.'[超清资源]';			
					$sqlc="select count(*) from page where cattype=".$keytype." and catcountry=".$keycountry." and ziyuan>0 and quality>=5 ";	
					$sql="select * from page where cattype=".$keytype." and catcountry=".$keycountry." and ziyuan>0 and quality>=5 order by updatetime desc";				
					dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','c');
				}
			}
			case 3:
			case 4:
			{
				foreach($moviecountry as $keycountry=>$moviecountryeach)
				{
					if($keycountry==0)
						continue;
					echo $movietypeeach.'_'.$moviecountryeach."</br>\n";
					$catpre = dh_get_catname($keytype,$keycountry);
					//生成 全部最新
					$path = $keytype.'_'.$keycountry.'_t/';		
					$cat = $catpre.'[全部最新]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_t/1.html">'.$movietype[$keytype].'[全部最新]</a> >> ';
					$sqlc="select count(*) from link l,page p where l.pageid=p.id and p.cattype = $keytype and p.catcountry = $keycountry and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(l.updatetime)";
					$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkquality ,l.linkway,p.hot,p.catcountry,p.cattype from link l,page p where l.pageid=p.id and p.cattype = $keytype and p.catcountry = $keycountry  and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(l.updatetime) order by l.updatetime desc";
					dh_gen_each_file_onlylink($sqlc,$sql,$DH_output_content,$path,$cat,$catlink);
					//生成 精选最新
					$path = $keytype.'_'.$keycountry.'_l/';		
					$cat = $catpre.'[精选最新]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_l/1.html">'.$movietype[$keytype].'[精选最新]</a> >> ';		
					$sqlc="select count(*) from page where cattype=".$keytype." and catcountry=".$keycountry." and ziyuan>0 and hot>5 and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime)";	
					$sql="select * from page where cattype=".$keytype." and catcountry=".$keycountry." and ziyuan>0  and hot>5 and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime) order by updatetime desc";				
					dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,$catlink);
					//生成 近日热门
					$path = $keytype.'_'.$keycountry.'_h/';		
					$cat = $catpre.'[近日热门]';
					$catlink=' <a href="'.$DH_index_url.$keytype.'_h/1.html">'.$movietype[$keytype].'[近日热门]</a> >> ';
					$sqlc="select count(*) from page where cattype=".$keytype." and catcountry=".$keycountry." and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) and hot>=10";	
					$sql="select * from page where cattype=".$keytype." and catcountry=".$keycountry." and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) and hot>=10 order by hot desc";
					dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,$catlink,'h');
				}				
				break;
			}		
			default:
				echo 'gen list type error!';
		}
		
		//对电影和电视，生成影评和资讯
		if($keytype>2)
			continue;
			
		$path = $keytype.'_yp/';		
		$cat =$movietypeeach. '[最新影评]';			
		$sqlc="select count(*) from link2 l,page p where l.pageid=p.id and p.cattype = $keytype and l.linktype=2 and DATE_SUB(CURDATE(), INTERVAL 2 WEEK) <= date(l.updatetime)";
		$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,8 as linkway,p.hot,p.catcountry,p.cattype from link2 l,page p where l.pageid=p.id and p.cattype = $keytype and l.linktype=2 and DATE_SUB(CURDATE(), INTERVAL 2 WEEK) <= date(l.updatetime) order by l.updatetime desc";
		dh_gen_each_file_onlylink($sqlc,$sql,$DH_output_content,$path,$cat,'','nolink');
		
		$path = $keytype.'_zx/';		
		$cat =$movietypeeach. '[最新资讯]';		
		$sqlc="select count(*) from link2 l,page p where l.pageid=p.id and p.cattype = $keytype and l.linktype=1 and DATE_SUB(CURDATE(), INTERVAL 2 WEEK) <= date(l.updatetime)";
		$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,7 as linkway,p.hot,p.catcountry,p.cattype from link2 l,page p where l.pageid=p.id and p.cattype = $keytype and l.linktype=1 and DATE_SUB(CURDATE(), INTERVAL 2 WEEK) <= date(l.updatetime) order by l.updatetime desc";
		dh_gen_each_file_onlylink($sqlc,$sql,$DH_output_content,$path,$cat,'','nolink');		
	}	

	//得到 正在上映
	echo "正在上映</br>\n";
	$path = '1_o/';	
	$cat = '正在上映';
	$sqlc="select count(*) from page where mstatus=3";
	$sql="select * from page where  mstatus=3 order by hot desc";
	dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','o');
	//得到 马上登陆
	echo "马上登陆</br>\n";
	$path = '1_i/';	
	$cat = '马上登陆';
	$sqlc="select count(*) from page where mstatus=2";
	$sql="select * from page  where mstatus=2 order by hot desc";
	dh_gen_each_file($sqlc,$sql,$DH_output_content,$path,$cat,'','i');
}

function dh_gen_each_file($sqlc,$sql,$DH_catlist,$path,$cat,$catlink='',$needcountrytype=false)
{
	global $DH_html_url,$DH_html_path,$DH_output_index_path,$DH_index_url,$conn,$pagecount;
	$outputpath = $DH_output_index_path.$path;
	if (!file_exists($outputpath))  
	{   
		mkdir($outputpath,0777);
	}
	
	$DH_input_html  = $DH_html_path . 'list_each.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");
	
	$DH_catlist_new = str_replace("%cat%",$cat,$DH_catlist);
	$DH_catlist_new = str_replace("%catlink%",$catlink,$DH_catlist_new);
	
	$results=dh_mysql_query($sqlc);			
	$count = mysql_fetch_array($results);
	echo '共'.$count[0]."篇/".$pagecount;
	$pages=ceil($count[0]/$pagecount);
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
			$DH_output_file = $outputpath.$catpage.'.html';
			dh_file_put_contents($DH_output_file,$DH_catlist_new2);			
		}
	}	
}

function dh_gen_each_file_onlylink($sqlc,$sql,$DH_catlist,$path,$cat,$catlink='',$needcountrytype='no')
{
	global $DH_html_url,$DH_index_url,$conn,$pagecount,$DH_output_index_path,$linkquality,$linkway,$moviecountry;
	$pagecountonly = $pagecount*4;

	$outputpath = $DH_output_index_path.$path;
	if (!file_exists($outputpath))  
	{   
		mkdir($outputpath,0777);
	}
	
	$results=dh_mysql_query($sqlc);			
	$count = mysql_fetch_array($results);
	echo '共'.$count[0]."篇/".$pagecount;
	$pages=ceil($count[0]/$pagecountonly);
	echo '/共'. $pages. "页</br>\n";
	
	$DH_catlist_new = str_replace("%cat%",$cat,$DH_catlist);
	$DH_catlist_new = str_replace("%catlink%",$catlink,$DH_catlist_new);
	
	$count=0;
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$liouttop='<li><span class="colorred vtop">类型</span> <span class="width90pre" style="margin-left:50px">资源名</span><span class="rt100v2">来源</span> <span class="rt60v2">热度</span> <span class="rt5v2 colorred" >日期</span></li>';
		$liout = $liouttop;
		while($row = mysql_fetch_array($results))
		{
			$count++;
			$htmlpath = output_page_path($DH_html_url,$row['pageid']);	
			$updatef = date("m-d",strtotime($row['updatetime']));
			$countrymeta='';
			//echo "****".$needcountrytype."****\n";
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
			$lieach = '<li><span>'.$countrymeta.'</span> <span class="width90pre">【'.$linkway[$row['linkway']].'】<a href="'.$htmlpath.'" target="_blank">'.$row['title'].'</a></span> <span class="rt100v2"><a href="'.$row['link'].'" target="_blank" rel="nofollow">'.$row['author'].'</a></span><span class="rt60v2">'.$row['hot'].' </span> <span class="rt5v2" > '.$updatef.'</span></li>';			
			$liout.= $lieach;
			if($count%$pagecountonly==0)
			{
				$catpage = ceil($count/$pagecountonly);
				$pagenavi = dh_pagenavi(5,$pages,$DH_index_url.$path,$catpage);
				echo 'genpage:'.$catpage."</br>\n";				
				$DH_catlist_new2= str_replace("%pagenavi%",$pagenavi,$DH_catlist_new);
				$DH_catlist_new2= str_replace("%list_each%",$liout,$DH_catlist_new2);
				$DH_catlist_new2 = str_replace("%num%",$catpage,$DH_catlist_new2);
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
			$DH_output_file = $outputpath.$catpage.'.html';
			dh_file_put_contents($DH_output_file,$DH_catlist_new2);			
		}
	}	
}
?>