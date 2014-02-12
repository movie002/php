<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_list
/// 函数作用：产生静态的index.html页面
/// 函数作者: DH
/// 作者地址: http://linkyou.org/ 
/////////////////////////////////////////////////////

header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
include("../config.php");
include("common.php");

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

dh_gen_index();

function dh_gen_index()
{
	global $DH_html_path,$DH_output_path,$DH_output_path,$conn,$pagecount;
	$DH_input_html  = $DH_html_path . 'index.html';
	$DH_output_content = dh_file_get_contents("$DH_input_html");

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
	
	//整理onlylink里面的文章
	$DH_index_cats .= dh_gen_buttom($DH_index_cat,$DH_index_cat_each_down);	
	
	return $DH_index_cats;
}

function dh_gen_top($DH_index_cat,$DH_index_cat_each)
{
	global $DH_index_url,$conn,$linktype,$DH_html_url,$DH_output_path;		
	$DH_index_cat_ins = str_replace("%catname%",'',$DH_index_cat);
	$DH_index_cat_eachs	='';
	
	//先找出预告片
	$DH_index_cat_each_ins = str_replace("%cat_each_title%","精彩预告",$DH_index_cat_each);
	$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$DH_index_url.'yugao'.'/1.html',$DH_index_cat_each_ins);
	$DH_index_cat_each_ins = str_replace("%cat_each_anchor%",'o_7',$DH_index_cat_each_ins);	
	
	$sql="select * from page where trail =0 order by updatetime desc limit 0,8";
	$results=mysql_query($sql,$conn);	
	if($results)
	{
		$liout='<div class="titleClass">
					<div class="tprev">&nbsp;◄</div>
					<div class="tnext">&nbsp;&nbsp;►</div>
					<ul>';
		$imgi=1;
		while($row = mysql_fetch_array($results))
		{
			$htmlpath = output_page_path($DH_html_url,$row['id']);				
			//按次的img的地址
			$imgnum=$imgi%5+1;
			$imgurl='http://img'.$imgnum.'.douban.com/lpic/'.$row['imgurl'];
			$lieach = "\n".'<li><a href="'.$htmlpath.'"><img src="'.$imgurl.'" width="120" height="180" ></a></li>';
			$liout.= $lieach;
			$imgi++;
		}
		$liout.="\n".'</ul></div>';
		$DH_index_cat_each_ins = str_replace("%index_cat_each%",$liout,$DH_index_cat_each_ins);		
	}
	$DH_index_cat_eachs .='<div style="width:50%;float:left">'.$DH_index_cat_each_ins.'</div>';
	
//	$DH_index_cat_each_ins = str_replace("%cat_each_title_url%","",$DH_index_cat_each);
//	$DH_index_cat_each_ins = str_replace("%cat_each_title_more%","",$DH_index_cat_each);
	
	//最上面右侧内容
	$liout ="\n<div class=\"TabTitle\"> \n <ul id=\"myTabt\">";
	$liout .="\n".'<li class="active" onclick="nTabs(this,0);">最新预告片</li>';
	$liout .="\n".'<li class="normal" onclick="nTabs(this,1);">大陆票房</li>';	
	$liout .="\n".'<li class="normal" onclick="nTabs(this,2);">欧美票房</li>';		
	$liout .="\n".'<li class="normal" onclick="nTabs(this,3);">香港票房</li>';	
	$liout .="\n</ul>\n</div>";	

	$DH_index_cat_each_ins = str_replace("%cat_each_title%",$liout,$DH_index_cat_each);	
	
	$liout ='<div class="TabContent">';		
		$liout .="\n".'	<div id="myTabt_Content0" >';
		$sql="select * from page where trail =0 order by updatetime desc limit 0,7";		
		$liout .="\n".dh_get_movie_type_ul($sql);
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
			$liout .="\n".'	<div id="myTabt_Content'.($topkey+1).'" class="none">';
			$liout .="\n".$DH_top_Content;
			$liout .="\n".'</div>';			
		}	
		
	$liout .='</div>';
	
	$DH_index_cat_each_ins = str_replace("%index_cat_each%",$liout,$DH_index_cat_each_ins);	
	$DH_index_cat_eachs .='<div style="width:49%;float:left">'.$DH_index_cat_each_ins.'</div>';
	
	$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
	return $DH_index_cat_ins;
}

function dh_gen_movie($DH_index_cat,$DH_index_cat_each)
{
	global $DH_index_url,$DH_html_url,$conn,$linkway,$movietype,$moviecountry,$linktype;	
	$DH_index_cats='';
	foreach($movietype as $movietype_index=>$movietype_name)
	{
		echo "</br>\n".$movietype_name.":";
		$catname = '<div class="post-topic-area">'.$movietype_name.'<div class="anchor"> <a name="movie'.$movietype_index.'" id="movie'.$movietype_index.'" >&nbsp;</a></div></div>';
		$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);	
		$DH_index_cat_eachs = '';
		foreach($moviecountry as $moviecountry_index=>$moviecountry_name)
		{	
			echo "  ".$moviecountry_name;
			$result = '<div class="topic_each">';			
			$result .="<div class=\"TabTitle\" style=\"position:absolute;top:0;left:70px;z-index:2;\"> \n <ul id=\"myTab$movietype_index$moviecountry_index\">";
			$result .='<li class="active" onclick="nTabs(this,0);">最新</li>';
			$result .='<li class="normal" onclick="nTabs(this,1);">热门</li>';
			$result .='<li class="normal" onclick="nTabs(this,2);">经典</li>';			
			$result .="</ul>\n</div>";	
			
			$DH_index_cat_each_ins = str_replace("%cat_each_title%",$moviecountry[$moviecountry_index],$DH_index_cat_each);	

			//最新
			$result .= "<div class=\"TabContent\">\n";
			$result .= '<div id="myTab'.$movietype_index.$moviecountry_index.'_Content0">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and trail <>0 and DATE_SUB(CURDATE(), INTERVAL 6 MONTH) <= date(pubdate) order by updatetime desc limit 0,5 ";
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_l/1.html';			
			$DH_index_cat_each_insl = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
			$DH_index_cat_each_insl = str_replace("%cat_each_anchor%",$movietype_index.'_'.$moviecountry_index,$DH_index_cat_each_insl);				
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ul($sql),$DH_index_cat_each_insl);
			$result .= "\n</div>";
			//热门			
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content1" class="none">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and trail <>0 and DATE_SUB(CURDATE(), INTERVAL 6 MONTH) <= date(pubdate) and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime) order by hot desc limit 0,5 ";
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_h/1.html';			
			$DH_index_cat_each_insh = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);				
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ul($sql),$DH_index_cat_each_insh);
			$result .= "\n</div>";
			//经典			
			$result .= "\n".'<div id="myTab'.$movietype_index.$moviecountry_index.'_Content2" class="none">';
			$sql="select * from page where cattype = $movietype_index and catcountry = $moviecountry_index and trail <>0 and DATE_SUB(CURDATE(), INTERVAL 6 MONTH) > date(pubdate) order by updatetime desc limit 0,5 ";
			$link = $DH_index_url.$movietype_index.'_'.$moviecountry_index.'_c/1.html';			
			$DH_index_cat_each_insc = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);		
			$result .= str_replace("%index_cat_each%",dh_get_movie_type_ul($sql),$DH_index_cat_each_insc);			
			$result .= "\n</div>\n</div>\n</div>";			
			$DH_index_cat_eachs .= $result;	
		}
		if($movietype_index == 0)
		{//如果是电影，增加onlylink中的 4,1
			$linktypearray= array(4,0);			
			foreach($linktypearray as $typekey=>$linktype_index)
			{			
				echo "  ".$linktype[$linktype_index];
				$sql="select * from onlylink where type = $linktype_index order by updatetime desc limit 0,5";				
				$link = $DH_index_url.'o'.$linktype_index.'/1.html';		
				$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
				$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
				$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ul($sql),$DH_index_cat_each_ins);		
				$DH_index_cat_eachs .= '<div style="width:49%;float:left; margin:0 0 0px 1px; ">'.$DH_index_cat_each_ins.'</div>';
			}			
		}
		if($movietype_index == 1)
		{//如果是电视剧集，增加onlylink中的 4,1
			$linktypearray= array(5,1);			
			foreach($linktypearray as $typekey=>$linktype_index)
			{	
				echo "  ".$linktype[$linktype_index];
				$sql="select * from onlylink where type = $linktype_index order by updatetime desc limit 0,5";				
				$link = $DH_index_url.'o'.$linktype_index.'/1.html';					
				$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
				$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
				$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ul($sql),$DH_index_cat_each_ins);		
				$DH_index_cat_eachs .= '<div style="width:49%;float:left; margin:0 0 0px 1px; ">'.$DH_index_cat_each_ins.'</div>';	
			}			
		}		
		$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
		$DH_index_cats .= $DH_index_cat_ins;
	}
	return $DH_index_cats;
}

function dh_gen_buttom($DH_index_cat,$DH_index_cat_each)
{
	global $DH_index_url,$DH_html_url,$conn,$linkway,$linktype;	
	$DH_index_cats='';
	$linkwayarray= array(1);
	foreach($linkwayarray as $waykey=>$linkway_index)
	{
		echo "</br>\n".$linkway[$linkway_index].":";
		$catname = '<div class="post-topic-area"> '.$linkway[$linkway_index].'<div  class="anchor"><a id="cat_o_1" name="cat_o_1">&nbsp;</a></div></div>';
		$DH_index_cat_ins = str_replace("%catname%",$catname,$DH_index_cat);
		$DH_index_cat_eachs = '';
		$linktypearray= array(2,3);	
		foreach($linktypearray as $typekey=>$linktype_index)
		{	
			echo "  ".$linktype[$linktype_index];
			$sql="select * from onlylink where type = $linktype_index order by updatetime desc limit 0,5";				
			$link = $DH_index_url.'o'.$linktype_index.'/1.html';
			$DH_index_cat_each_ins = str_replace("%cat_each_title%",$linktype[$linktype_index],$DH_index_cat_each);	
			$DH_index_cat_each_ins = str_replace("%cat_each_title_url%",$link,$DH_index_cat_each_ins);
			$DH_index_cat_each_ins = str_replace("%index_cat_each%",dh_get_onlylink_type_ul($sql),$DH_index_cat_each_ins);		
			$DH_index_cat_eachs .='<div style="width:49%;float:left">'. $DH_index_cat_each_ins.'</div>';			
		}
		$DH_index_cat_ins = str_replace("%index_cat_each%",$DH_index_cat_eachs,$DH_index_cat_ins);
		$DH_index_cats .= $DH_index_cat_ins;
	}
	return $DH_index_cats;
}

function dh_get_onlylink_type_ul($sql)
{
	global $conn;
	$recs=mysql_query($sql,$conn);
	if($recs)
	{						
		$liout='<ul>';
		$reccount=0;
		while($row = mysql_fetch_array($recs))
		{
			$updatef = date("m-d",strtotime($row['updatetime']));
			$lieach ="\n".'<li><a href="'.$row['link'].'" target="_blank">'.$row['title'].'</a><span class="moviemeta">  ('.$row['author'].')</span><span class="moviedate">'.$updatef.'</span></li>';
			$liout.= $lieach;
			$reccount++;					
		}
		if($reccount==0)
			continue;
		$liout.= '</ul>';	
	}
	return $liout;
}

function dh_get_movie_type_div($sql)
{

}

function dh_get_movie_type_ul($sql)
{
	global $DH_html_url,$conn,$linkway;
	$recs=mysql_query($sql,$conn);
	$liout="";
	if($recs)
	{									
		$liout="<ul>";
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
			//下载方式
			$sqllinks = "select way from link t where t.mediaid = '".$row['mediaid']."' group by way";
			$reslinks=mysql_query($sqllinks,$conn);	
			$way='';
			if($reslinks)
			{
				while($rowlinks = mysql_fetch_array($reslinks))
				{
					$waynum = $rowlinks['way'];
					$way.=$linkway[$waynum].'|';
				}
				$way = substr($way,0,strlen($way)-1);
			}					
			$lieach = "\n".'<li><span class="moviename"><a href="'.$htmlpath.'" target="_blank">'.$row['title'].'</a><span class="moviemeta"> ['.$country.':'.$type.'] ('.$way.')</span></span><span class="moviedate">'.$update.'</span></li>';
			//$lieach = dh_replace_snapshot(1,$row,$lieach);
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
