<?php
/////////////////////////////////////////////////////
/// 函数名称：gen 
/// 函数作用：产生foot head side文件
/// 函数作者: DH
/// 作者地址: http://dhblog.org/ 
/////////////////////////////////////////////////////

//header('Content-Type:text/html;charset= UTF-8'); 

#需要使用的基础函数
//include("config.php");
//include("compressJS.class.php");

//dh_gen_share(array(),20);

function dh_gen_share($lists)
{
	global $DH_html_path,$DH_index_url,$DH_output_path,$DH_input_path,$DH_home_url,$DH_page_store_deep,$conn,$DH_name_des;
	
	$DH_share_output_path = $DH_input_path.'gen/top/';
	if (!file_exists($DH_share_output_path))  
	{   
		mkdir($DH_share_output_path,0777);
	}	
	
	$DH_input_html  = $DH_html_path . 'foot.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);	
	$DH_output_file = $DH_share_output_path. 'foot.html';
	dh_file_put_contents($DH_output_file,$DH_output);
	echo "gen foot success !</br>\n";
	$DH_cse_foot=$DH_output;

	$DH_input_html  = $DH_html_path . 'head.html';
	$DH_output = dh_file_get_contents($DH_input_html);
	$DH_output = str_replace("%home%",$DH_home_url,$DH_output);
	$DH_output = str_replace("%name_des%",$DH_name_des,$DH_output);	
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
	
	$DH_input_html  = $DH_html_path . 'side_each.html';
	$DH_side_each = dh_file_get_contents($DH_input_html);
	
	$DH_input_html  = $DH_html_path . 'side_each2.html';
	$DH_side_each2 = dh_file_get_contents($DH_input_html);	
	//广告
//	$DH_side_ad= str_replace("%title%",'广告',$DH_side_each);
//	$DH_side_ad= str_replace("%more%",'',$DH_side_ad);
//	$DH_side_ad= str_replace("%content%",'',$DH_side_ad);
	$DH_input_html  = $DH_html_path . 'side_ad.html';
	$DH_side_ad = dh_file_get_contents($DH_input_html);	
	
	//热门文章
	$DH_side_hot= str_replace("%title%",'热门文章',$DH_side_each);
	$DH_side_hot= str_replace("%more%",'',$DH_side_hot);
	$DH_side_hot= str_replace("%content%",'<ul  class="ds-top-threads" data-range="weekly" data-num-items="5"></ul>',$DH_side_hot);	
	
	//最新评论
	$DH_side_pinglun= str_replace("%title%",'最新评论',$DH_side_each);
	$DH_side_pinglun= str_replace("%more%",'',$DH_side_pinglun);
	$DH_side_pinglun= str_replace("%content%",'<ul class="ds-recent-comments" data-num-items="5" data-show-avatars="1" data-show-time="1" data-show-title="0" data-show-admin="1" data-excerpt-length="20"></ul>
',$DH_side_pinglun);

	//最新访客
	$DH_side_visitor= str_replace("%title%",'最新访客',$DH_side_each);
	$DH_side_visitor= str_replace("%more%",'',$DH_side_visitor);
	$DH_side_visitor= str_replace("%content%",'<ul style="margin:8px 6px" class="ds-recent-visitors" data-num-items="10"></ul>',$DH_side_visitor);
	
	//网站统计
	$DH_side_tongji= str_replace("%title%",'网站统计',$DH_side_each2);
	$DH_side_tongji= str_replace("%more%",'',$DH_side_tongji);
	$diffecho = '';
	$datetoday = strtotime(date("Y-m-d"));
	$datebegin = strtotime('2011-02-08');
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
	$tongji='<li>运行时间: <span style="font-size:12px;color:#555;">'.$diff.'天</span></li>';
	
	$countpages=0;
	$countcats=0;
	$tags=array();
	$countupdate=0;
	
	dh_gen_public($lists,$countpages,$countcats,$tags,$countupdate);
	
	$tongji.='<li>博文总数: <span style="font-size:12px;color:#555;">'.$countpages.'篇</span></li>';
		
	$tongji.='<li>最近更新: <span style="font-size:12px;color:#555;">'.$countupdate.'篇</span></li>';
	
	$tongji.='<li>标签数目: <span style="font-size:12px;color:#555;">'.count($tags).'个</span></li>';
	
	$tongji.='<li>分类数目: <span style="font-size:12px;color:#555;">'.$countcats.'个</span></li>';

	$tongji = "<ul>".$tongji.'</ul>';
	
	//$tongji = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style type="text/css">display:inline;float:left;overflow:hidden;width:110px;text-align:left;border:1px solid #f7f3ed;border-bottom:1px solid #ddd;margin:0;padding:5px 0 5px 5px;</style></head><body leftmargin="0"	topmargin="0" style="background-color:transparent"><ul>'.$tongji.'</ul></body></html>';
	
	//$tongji = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><style type="text/css">ul li{list-style: none;display:inline;float:left;overflow:hidden;width:110px;text-align:left;border:1px solid #f7f3ed;border-bottom:1px solid #ddd;margin:0;padding:5px 0 5px 5px;}</style></head><body leftmargin="0" topmargin="0" style="background-color:transparent;font-size:14px"><ul style="padding:0">'.$tongji.'</ul></body></html>';
	
	//$DH_output_file=$DH_input_path.'tongji.html';
	//dh_file_put_contents($DH_output_file,$tongji);	
		
	//$tongjiframe='<iframe allowtransparency="true" src="'.$DH_home_url.'tongji.html" frameBorder="0" scrolling="no" height="390px"></iframe>';
	
	//$DH_side_tongji= str_replace("%content%",$tongjiframe,$DH_side_tongji);
	
	//友情链接
	$DH_side_fl= str_replace("%title%",'友情链接',$DH_side_each);
	$DH_side_fl= str_replace("%content%",'',$DH_side_fl);
	
	
	$DH_input_html  = $DH_html_path . 'side_dong.html';
	$DH_side_dong = dh_file_get_contents($DH_input_html);
	$tagsall='';
	foreach($tags as $key=>$tag)
	{
		$urlcode = rawurlencode($key);
		$urlcode = 't'.str_replace("%",'',$urlcode);	
		$tagsall.="<a href=\"$DH_index_url$urlcode/1.html\" title=\"共有文章 $tag 篇\" target=\"_blank\">$key</a>";
	}
	$DH_side_dong= str_replace("%tags%",$tagsall,$DH_side_dong);
	$DH_side_dong= str_replace("%tongji%",$tongji,$DH_side_dong);
	
	$DH_output_file=$DH_input_path.'side_dong.html';
	dh_file_put_contents($DH_output_file,$DH_side_dong);	

	$dongframe='<iframe allowtransparency="true" src="'.$DH_home_url.'side_dong.html" frameBorder="0" scrolling="no" width="240px" height="500px"></iframe>';	
	
	$DH_side=$DH_side_ad.$DH_side_hot.$DH_side_pinglun.$DH_side_visitor.$dongframe;	
	
	$DH_output_file = $DH_share_output_path. 'side.html';
	dh_file_put_contents($DH_output_file,$DH_side);	
	echo "gen side success !</br>\n";
	
	echo "gen cse success !</br>\n";	
}


function dh_gen_public($lists,&$countpages,&$countcats,&$tags,&$countupdate)
{
	$cats=array();
	foreach($lists as $key=>$list)
	{		
		preg_match('/<\_c>(.*?)<\/\_c>/s',$list,$matchc);
		if(!empty($matchc[1]))
		{
			if(empty($cats[$matchc[1]]))
				$cats[$matchc[1]]=1;
			else
				$cats[$matchc[1]]++;
		}

		preg_match_all('/<\_t>(.*?)<\/\_t>/s',$list,$matchts);
		if(!empty($matchts[1]))
		{
			foreach($matchts[1] as $tag)
			{
				if(empty($tags[$tag]))
					$tags[$tag]=1;
				else
					$tags[$tag]++;
			}
		}
		
		preg_match('/<\_d>(.*?)<\/\_d>/s',$list,$matchd);
		$dateThis = date("y-m-d",strtotime($matchd[1].'00'));
		$dateNow=date("y-m-d",strtotime("-7 day"));
		//echo $dateThis." --> ".$dateNow;
		if($dateThis >= $dateNow )
			$countupdate++;
	}
	
	ksort($tags);
	
	//print_r($cats);
	//print_r($tags);
	
	$countpages=count($lists);
	$countcats=count($cats);
}
?>