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
require("../curl.php");
require("common.php");
require("compressJS.class.php");

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

dh_gen_index();

function dh_gen_index()
{
	global $DH_home_url,$DH_html_path,$DH_output_path,$DH_output_path,$conn,$pagecount;
	$DH_input_html  = $DH_html_path . 'index.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");

	$DH_share_output_path = $DH_output_path.'top/';
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
	
	$DH_input_html  = $DH_html_path . 'index.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output_content = str_replace("%indexjs%",$DH_js,$DH_output_content);	

	$DH_input_html  = $DH_html_path . 'foot.js';
	$DH_js = dh_file_get_contents($DH_input_html);
	$DH_js = str_replace("%home%",$DH_home_url,$DH_js);	
	$myPacker = new compressJS($DH_js);	
	$DH_js = $myPacker->pack();
	$DH_output_content = str_replace("%footjs%",$DH_js,$DH_output_content);
	
	$deep = './';
	$DH_output_content = str_replace("%deep%",$deep,$DH_output_content);
	
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
	
	$DH_index_cat_each_down = str_replace("%cat_each_title_more%","更多...",$DH_index_cat_each);
	
	//整理动态链接的文章
	$DH_index_cat_each_top = str_replace("%cat_each_title_more%","",$DH_index_cat_each);
	$DH_index_cat_each_top = str_replace("%%","",$DH_index_cat_each_top);
	$DH_index_cat_each_top = str_replace("%cat_each_title_url%","",$DH_index_cat_each_top);	
	$DH_index_cats .= dh_gen_top($DH_index_cat,$DH_index_cat_each_top);
		
	//整理影视的类别
	$DH_index_cats .= dh_gen_movie($DH_index_cat,$DH_index_cat_each_down);
	$DH_index_cats .= dh_gen_tv($DH_index_cat,$DH_index_cat_each_down);	
	$DH_index_cats .= dh_gen_zydm($DH_index_cat,$DH_index_cat_each_down);	
	//整理onlylink里面的文章
	//$DH_index_cats .= dh_gen_buttom($DH_index_cat,$DH_index_cat_each_down);	
	
	return $DH_index_cats;
}

function dh_gen_top($DH_index_cat,$DH_index_cat_each)
{
	global $DH_home_url,$imgurlputl,$DH_index_url,$conn,$linktype,$DH_html_url,$DH_output_path;	
	$DH_index_cat_ins = str_replace("%catname%",'',$DH_index_cat);
	$DH_index_cat_eachs	='';
	
	//先找出热门
//	$DH_index_cat_each_ins = str_replace("%cat_each_title%","精彩预告",$DH_index_cat_each);
	$DH_index_cat_each_ins = str_replace("%cat_each_title%","",$DH_index_cat_each);
	$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$DH_index_url.'yugao'.'/1.html',$DH_index_cat_each_ins);
	$DH_index_cat_each_ins = str_replace("%cat_each_anchor%",'o_7',$DH_index_cat_each_ins);	
	
	$sql="select * from page where DATE_SUB(CURDATE(), INTERVAL 6 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime) and hot>=10 order by hot desc limit 0,9";
	$results=mysql_query($sql,$conn);	
	if($results)
	{
		$liout='<div class="titleClass">
					<ul>';
		while($row = mysql_fetch_array($results))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);				
			//按次的img的地址
			if($row['imgurl']=='')
				continue;
			if($row['imgurl'][0]=='s')
				$imgurl='http://img3.douban.com/lpic/'.$row['imgurl'];
			else
			{
				$imgurls='http://img3.douban.com/view/photo/thumb/public/p'.$row['imgurl'].'.jpg';
				$filename = 'p'.$row['imgurl'].'.jpg';
				$filenamedir = $DH_output_path.'spic/'.$filename;
				echo $filenamedir.' --> ';
				//如果存在这个文件，就不用爬了，如果没有，就到豆瓣上面去获取
				if(!file_exists($filenamedir))
				{
					sleep(2);
					$filename = getImage($imgurls,$DH_output_path.'spic/');
					echo 'get' . $filename;
				}
				echo "</br>\n";
				$imgurl=$DH_home_url.'spic/'.$filename;
			}	
			$lieach = "\n".'<li class="imglargeli"><a href="'.$htmlpath.'"><img class="imglarge" data-src="'.$imgurl.'" alt="'.$row['title'].'的海报" width="114px" height="170px" ></a><div style="text-align:center;color:#447fa8;font-size:12px">'.$row['title'].'</div></li>';
			$liout.= $lieach;
		}
		$liout.="\n".'</ul></div>';
		//$DH_index_cat_each_ins = str_replace("%index_cat_each%",$liout,$DH_index_cat_each_ins);		
	}
	$DH_index_cat_eachs .='<div style="width:49%;float:left;position:relative;">
	<div class="tprev"><div style="margin:14px 0">◄</div></div>
	<div class="tnext"><div style="margin:14px 0">&nbsp;►</div></div>'.$liout.'</div>';
	
	//最上面右侧内容
	$liout ="\n<div class=\"TabTitle\"> \n <ul id=\"myTabt\">";
	$liout .="\n".'<li class="normal" onclick="nTabs(this,0);">即将登陆</li>';
	$liout .="\n".'<li class="active" onclick="nTabs(this,1);">正在上映</li>';
	$liout .="\n".'<li class="normal" onclick="nTabs(this,2);">大陆票房</li>';	
	$liout .="\n".'<li class="normal" onclick="nTabs(this,3);">欧美票房</li>';		
	$liout .="\n".'<li class="normal" onclick="nTabs(this,4);">香港票房</li>';	
	$liout .="\n</ul>\n</div>";	

	$DH_index_cat_each_ins = str_replace("%cat_each_title%",$liout,$DH_index_cat_each);	
	
	$liout ='<div class="TabContent">';		
	$liout .="\n".'	<div id="myTabt_Content0" class="none">';
	$sql="select * from page where mstatus=2 order by pubdate limit 0,6";		
	$liout .="\n".dh_get_movie_type_ol($sql,true);
	//$liout = str_replace('</ul>','',$liout);
	//$liout .="<li><span class = \"moviedate\"><a href=\"\"> 更多...</a> </span></li>";
	//$liout .="\n</ul>";
	$liout .='<div><a href="'.$DH_index_url.'o_7/1.html'.'" target="_blank"> 更多 ... </a> </div>';
	$liout .="\n".'</div>';

	$liout .='<div class="TabContent">';		
	$liout .="\n".'	<div id="myTabt_Content1" >';
	$sql="select * from page where mstatus=3 order by hot desc limit 0,6";		
	$liout .="\n".dh_get_movie_type_ol($sql,true);
	//$liout = str_replace('</ul>','',$liout);
	//$liout .="<li><span class = \"moviedate\"><a href=\"\"> 更多...</a> </span></li>";
	//$liout .="\n</ul>";
	$liout .='<div><a href="'.$DH_index_url.'o_7/1.html'.'" target="_blank"> 更多 ... </a> </div>';
	$liout .="\n".'</div>';
	
	$toparray= array(1,0,2);	
	foreach($toparray as $topkey=>$top_index)			
	{
		$DH_top  = $DH_output_path . 'top/'.$top_index.'.top';
		$DH_top_Content = dh_file_get_contents($DH_top);			
		$liout .="\n".'	<div id="myTabt_Content'.($topkey+2).'" class="none">';
		$liout .="\n".$DH_top_Content;
		$liout .="\n".'</div>';			
	}	
		
	$liout .='</div>';
	
	$DH_index_cat_each_ins = str_replace("%index_cat_each%",$liout,$DH_index_cat_each_ins);	
	$DH_index_cat_eachs .='<div style="width:49%;float:right;margin-right:5px">'.$DH_index_cat_each_ins.'</div>';
	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_gen_movie($DH_index_cat,$DH_index_cat_each)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;	
	$DH_index_cats='';
	
	$DH_input_html  = $DH_html_path . 'index_cat_title.html';
	$DH_index_cat_title = dh_file_get_contents($DH_input_html);
	
	$liout ='<li class="normal" onclick="nTabs(this,0);">全部最新</li>';
	$liout .="\n".'<li class="active" onclick="nTabs(this,1);">精选最新</li>';	
	$liout .="\n".'<li class="normal" onclick="nTabs(this,2);">近日热门</li>';		
	$liout .="\n".'<li class="normal" onclick="nTabs(this,3);">高清资源</li>';
	$DH_index_cat_title = str_replace("%cat_titles%",$liout,$DH_index_cat_title);	

	foreach($movietype as $movietype_index=>$movietype_name)
	{
		//只取电影
		if($movietype_index!=1)
				continue;
		echo "</br>\n".$movietype_name.":";
		$catname = '<div class="post-topic-area">'.$movietype_name.'<div class="anchor"> <a name="movie'.$movietype_index.'" id="movie'.$movietype_index.'" >&nbsp;</a></div></div>';
		$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);	
		$DH_index_cat_eachs = '';
		foreach($moviecountry as $moviecountry_index=>$moviecountry_name)
		{	
			if($moviecountry_index==0)
				continue;
			echo "  ".$moviecountry_name;
			//title
			$result= str_replace("%movietype_index%",$movietype_index,$DH_index_cat_title);			
			$result= str_replace("%moviecountry_index%",$moviecountry_index,$result);		
			$DH_index_cat_each_ins = str_replace("%cat_each_title%",$moviecountry[$moviecountry_index],$DH_index_cat_each);	
			//全部最新
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content0" class="none">';
			$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkway from link l,page p where l.pageid=p.id and p.cattype = $movietype_index and p.catcountry = $moviecountry_index order by l.updatetime desc limit 0,".$index_list_count;
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_t/1.html';	
			$DH_index_cat_each_insl = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_onlylink($sql),$DH_index_cat_each_insl);
			$result .= "\n</div>\n";			
			//精选最新
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content1">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and ziyuan>0  and hot>5 order by updatetime desc limit 0,".$index_list_count;
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_l/1.html';			
			$DH_index_cat_each_insl = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);	
			$DH_index_cat_each_insl = str_replace("%cat_each_anchor%",$movietype_index.'_'.$moviecountry_index,$DH_index_cat_each_insl);			
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insl);
			$result .= "\n</div>";
			//近日热门			
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content2" class="none">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and DATE_SUB(CURDATE(), INTERVAL 12 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) order by hot desc limit 0,".$index_list_count;
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_h/1.html';			
			$DH_index_cat_each_insh = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insh);
			$result .= "\n</div>";
			//高清资源			
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content3" class="none">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and ziyuan>0 and quality>=5 order by updatetime desc limit 0,".$index_list_count;
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_c/1.html';			
			$DH_index_cat_each_insc = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);		
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insc);			
			$result .= "\n</div>";
			//TabContent
			$result = "\n<div class=\"TabContent\">".$result."\n</div>";
			//topic_each
			$result = "\n<div class=\"topic_each\">".$result."\n</div>";
			
			$DH_index_cat_eachs .= $result;	
		}
//		if($movietype_index == 0)
//		{//如果是电影，增加onlylink中的 4,1
//			$linktypearray= array(4,0);			
//			foreach($linktypearray as $typekey=>$linktype_index)
//			{			
//				echo "  ".$linktype[$linktype_index];
//				$sql="select * from link where type = $linktype_index order by updatetime desc limit 0,5";				
//				$link = $DH_index_url.'o'.$linktype_index.'/1.html';		
//				$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
//				$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
//				$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ol($sql),$DH_index_cat_each_ins);		
//				$DH_index_cat_eachs .= '<div class="topic_each">'.$DH_index_cat_each_ins.'</div>';
//			}			
//		}
//		if($movietype_index == 1)
//		{//如果是电视剧集，增加onlylink中的 4,1
//			$linktypearray= array(5,1);			
//			foreach($linktypearray as $typekey=>$linktype_index)
//			{	
//				echo "  ".$linktype[$linktype_index];
//				$sql="select * from onlylink where type = $linktype_index order by updatetime desc limit 0,5";				
//				$link = $DH_index_url.'o'.$linktype_index.'/1.html';					
//				$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
//				$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
//				$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ol($sql),$DH_index_cat_each_ins);		
//				$DH_index_cat_eachs .= '<div class="topic_each">'.$DH_index_cat_each_ins.'</div>';	
//			}			
//		}		
		$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
		$DH_index_cats .= $DH_index_cat_ins;
	}
	return $DH_index_cats;
}

function dh_gen_tv($DH_index_cat,$DH_index_cat_each)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;	
	$DH_index_cats='';
	
	$DH_input_html  = $DH_html_path . 'index_cat_title.html';
	$DH_index_cat_title = dh_file_get_contents($DH_input_html);
	$liout ='<li class="active" onclick="nTabs(this,0);">最新资源</li>';
	$liout .="\n".'<li class="normal" onclick="nTabs(this,1);">近日热门</li>';		
	$liout .="\n".'<li class="normal" onclick="nTabs(this,2);">高清资源</li>';
	$DH_index_cat_title = str_replace("%cat_titles%",$liout,$DH_index_cat_title);	
	//只取电视
	$movietype_index=2;
	$movietype_name=$movietype[$movietype_index];
	echo "</br>\n".$movietype_name.":";
	$catname = '<div class="post-topic-area">'.$movietype_name.'<div class="anchor"> <a name="movie'.$movietype_index.'" id="movie'.$movietype_index.'" >&nbsp;</a></div></div>';
	$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);	
	$DH_index_cat_eachs = '';
	foreach($moviecountry as $moviecountry_index=>$moviecountry_name)
	{	
		if($moviecountry_index==0)
			continue;
		echo "  ".$moviecountry_name;
		//title
		$result= str_replace("%movietype_index%",$movietype_index,$DH_index_cat_title);			
		$result= str_replace("%moviecountry_index%",$moviecountry_index,$result);		
		$DH_index_cat_each_ins = str_replace("%cat_each_title%",$moviecountry[$moviecountry_index],$DH_index_cat_each);	
		//最新资源
		$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content0">';
		$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and ziyuan>0 order by updatetime desc limit 0,".$index_list_count;
		$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_l/1.html';			
		$DH_index_cat_each_insl = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);	
		$DH_index_cat_each_insl = str_replace("%cat_each_anchor%",$movietype_index.'_'.$moviecountry_index,$DH_index_cat_each_insl);			
		$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insl);
		$result .= "\n</div>";
		//近日热门			
		$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content1" class="none">';
		$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and DATE_SUB(CURDATE(), INTERVAL 12 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) order by hot desc limit 0,".$index_list_count;
		$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_h/1.html';			
		$DH_index_cat_each_insh = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
		$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insh);
		$result .= "\n</div>";
		//高清资源			
		$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content2" class="none">';
		$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and ziyuan>0 and quality>=5 order by updatetime desc limit 0,".$index_list_count;
		$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_c/1.html';			
		$DH_index_cat_each_insc = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);		
		$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insc);			
		$result .= "\n</div>";
		//TabContent
		$result = "\n<div class=\"TabContent\">".$result."\n</div>";
		//topic_each
		$result = "\n<div class=\"topic_each\">".$result."\n</div>";			
		$DH_index_cat_eachs .= $result;	
	}
	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	$DH_index_cats .= $DH_index_cat_ins;	
	return $DH_index_cats;
}

function dh_gen_zydm($DH_index_cat,$DH_index_cat_each)
{
	global $DH_html_path,$DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype,$index_list_count;	
	$DH_index_cats='';
	
	$DH_input_html  = $DH_html_path . 'index_cat_title.html';
	$DH_index_cat_title = dh_file_get_contents($DH_input_html);
	$liout ='<li class="active" onclick="nTabs(this,0);">最新资源</li>';
	$liout .="\n".'<li class="normal" onclick="nTabs(this,1);">近日热门</li>';		
//	$liout .="\n".'<li class="normal" onclick="nTabs(this,2);">高清资源</li>';
	$DH_index_cat_title = str_replace("%cat_titles%",$liout,$DH_index_cat_title);	
	//只取综艺动漫
	$movietype_index=3;
	$movietype_name='综艺动漫';
	echo "</br>\n".$movietype_name.":";
	$catname = '<div class="post-topic-area">'.$movietype_name.'<div class="anchor"> <a name="movie'.$movietype_index.'" id="movie'.$movietype_index.'" >&nbsp;</a></div></div>';
	$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);	
	$DH_index_cat_eachs = '';
	foreach($movietype as $movietype_index=>$movietype_name)
	{	
		if($movietype_index<=2)
			continue;
		echo "  ".$movietype_name;
		//title
		$result= str_replace("%movietype_index%",$movietype_index,$DH_index_cat_title);			
		$result= str_replace("%moviecountry_index%",0,$result);		
		$DH_index_cat_each_ins = str_replace("%cat_each_title%",$movietype[$movietype_index],$DH_index_cat_each);	
		//最新资源
		$result .= "\n".'<div id="myTab'.$movietype_index.'0_Content0">';
		$sql="select * from page where cattype = $movietype_index order by updatetime desc limit 0,".$index_list_count;
		$link = $DH_index_url.$movietype_index.'_l/1.html';			
		$DH_index_cat_each_insl = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);	
		$DH_index_cat_each_insl = str_replace("%cat_each_anchor%",$movietype_index,$DH_index_cat_each_insl);
		$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insl);
		$result .= "\n</div>";
		//近日热门			
		$result .= "\n".'<div id="myTab'.$movietype_index.'0_Content1" class="none">';
		$sql="select * from page where cattype = $movietype_index and DATE_SUB(CURDATE(), INTERVAL 12 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(updatetime) order by hot desc limit 0,".$index_list_count;
		$link = $DH_index_url.$movietype_index.'_h/1.html';			
		$DH_index_cat_each_insh = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
		$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insh);
		$result .= "\n</div>";
		//高清资源			
		//$result .= "\n".'<div id="myTab'.$movietype_index.'0_Content2" class="none">';
		//$sql="select * from page where cattype = $movietype_index and quality>=5 order by updatetime desc limit 0,".$index_list_count;
		//$link = $DH_index_url.$movietype_index.'_c/1.html';			
		//$DH_index_cat_each_insc = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);		
		//$result .= str_replace("%index_cat_each%",dh_get_movie_type_ol($sql),$DH_index_cat_each_insc);			
		//$result .= "\n</div>";
		//TabContent
		$result = "\n<div class=\"TabContent\">".$result."\n</div>";
		//topic_each
		$result = "\n<div class=\"topic_each\">".$result."\n</div>";
		$DH_index_cat_eachs .= $result;
	}		
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	$DH_index_cats .= $DH_index_cat_ins;
	return $DH_index_cats;
}

function dh_gen_buttom($DH_index_cat,$DH_index_cat_each)
{
	global $DH_index_url,$DH_html_url,$conn,$linktype;	
	$DH_index_cats='';
	$linkwayarray= array(0,1);
	$linkdes=array('其他资源','链接资源');
	foreach($linkwayarray as $waykey=>$linkway_index)
	{
		echo "</br>\n".$linkdes[$linkway_index].":";
		$catname = '<div class="post-topic-area"> '.$linkdes[$linkway_index].'<div  class="anchor"><a id="cat_o_1" name="cat_o_1">&nbsp;</a></div></div>';
		$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);
		$DH_index_cat_eachs = '';
		if($waykey==0)
			$linktypearray= array(4,9);	
		else
			$linktypearray= array(2,3);	
		foreach($linktypearray as $typekey=>$linktype_index)
		{	
			echo "  ".$linktype[$linktype_index];
			$sql="select * from onlylink where type = $linktype_index order by updatetime desc limit 0,5";				
			$link = $DH_index_url.'o'.$linktype_index.'/1.html';
			$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
			$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
			$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ol($sql),$DH_index_cat_each_ins);		
			$DH_index_cat_eachs .='<div class="topic_each">'. $DH_index_cat_each_ins.'</div>';			
		}
		$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
		$DH_index_cats .= $DH_index_cat_ins;
	}
	return $DH_index_cats;
}

function dh_get_onlylink_type_ol($sql)
{
	global $conn;
	$recs=mysql_query($sql,$conn);
	if($recs)
	{						
		$liout='<ol>';
		$reccount=0;
		while($row = mysql_fetch_array($recs))
		{
			$updatef = date("m-d",strtotime($row['updatetime']));
			$lieach ="\n".'<li><a href="'.$row['link'].'" target="_blank">'.$row['title'].'</a><span class="moviemeta">  ('.$row['author'].')</span><span class="moviedate">'.$updatef.'</span></li>';
			$liout.= $lieach;
			$reccount++;					
		}
		if($reccount!=0)
			$liout.= '</ol>';	
	}
	return $liout;
}

function dh_get_movie_type_ol($sql,$needmovietype=false)
{
	global $DH_html_url,$conn,$linkquality,$movietype;
	$recs=mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{									
		$liout="<ol>";
		while($row = mysql_fetch_array($recs))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);
			//$author=$row['author'];
			$update = date("m-d",strtotime($row['updatetime']));
			$country='';
			preg_match('/<g>(.*?)<\/g>/',$row['meta'],$match);
			if(!empty($match[1]))
			{	
				$country = $match[1];
			}
			//类别
			$type="";
			preg_match('/<t>(.*?)<\/t>/',$row['meta'],$match);
			if(!empty($match[1]))
			{	
				$type = $match[1];
			}
			$movietypemeta='';
			if($needmovietype===true)
			{
				$movietypemeta='<span class="moviemeta">['.$movietype[$row['cattype']].']&nbsp;</span>';
			}
			//下载方式
//			$sqllinks = "select way from link t where t.mediaid = '".$row['mediaid']."' group by way";
//			$reslinks=mysql_query($sqllinks,$conn);	
//			$way='';
//			if($reslinks)
//			{
//				while($rowlinks = mysql_fetch_array($reslinks))
//				{
//					$waynum = $rowlinks['way'];
//					$way.=$linkway[$waynum].'|';
//				}
//				$way = substr($way,0,strlen($way)-1);
//			}

			$lieach = "\n".'<li><span class="moviename">'.$movietypemeta.' <a href="'.$htmlpath.'" target="_blank">'.$row['title'].'</a><span class="moviemeta"> ['.$country.':'.$type.']('.$linkquality[$row['quality']].')</span></span><span class="moviedate">'.$update.'</span></li>';
			//$lieach = dh_replace_snapshot(1,$row,$lieach);
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

function dh_get_movie_type_onlylink($sql)
{
	global $DH_html_url,$conn,$linkway;
	$recs=mysql_query($sql,$conn);
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
			$lieach = "\n".'<li><span class="moviename"><span class="moviemeta">['.$linkway[$row['linkway']].']</span> <a href="'.$htmlpath.'" target="_blank" '.$titlea.'>'.$title.'</a><span class="moviemeta"> (<a href="'.$row['link'].'">'.$row['author'].'的链接</a>)</span></span><span class="moviedate">'.$update.'</span></li>';
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
?>
