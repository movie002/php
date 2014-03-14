<?php  
///* 使用示例 */  
//header('Content-Type:text/html;charset= UTF-8'); 
//include("common.php");
//$test = "《痞子大逃亡/真相与结果》蓝光高清1024MKV版迅雷下载[美国动作惊悚电影]"; 
////$test = "超凡蜘蛛侠 (The Amazing Spider-Man) 删节片段"; 
////$result = filte_movie_name($test);
//$result = get_movie_name($test,'<c>《(.*?)》</c><c>[(.*?)]</c>');
//print_r($result);

//	preg_match_all('/.*?/','',$match); 
//	print_r($match);

function filter_ed2000($title)
{
	//第一步: 大致的提取标题，利用分割符号对标题处理
	$subtitle = gettitle($title);
	if($subtitle===false)
		 return -1;
	return $subtitle;
}
	
//利用《》<> [] 【】等括号提取标题
function gettitle($title)
{
	$duanarray = array();
	//如果是《》<> 就一定是title
	preg_match('/《(.*?)》/',$title,$match);
	//print_r($match);
	if(!empty($match[1]))//如果有《》，不处理
	{
		//return $match[1];
		insertarray($duanarray,$match[1]);
		return judgetitle($duanarray);
	}
	preg_match('/＜(.*?)＞/',$title,$match);
	//print_r($match);
	if(!empty($match[1]))//如果有《》，不处理
	{
		//return $match[1];
		insertarray($duanarray,$match[1]);
		return judgetitle($duanarray);
	}
	preg_match('/<(.*?)>/',$title,$match);
	//print_r($match);
	if(!empty($match[1]))//如果有《》，不处理
	{
		//return $match[1];
		insertarray($duanarray,$match[1]);
		return judgetitle($duanarray);
	}
		
	
	//如果有[]or【】就按照剔除方式处理
	//分段 xxx[yyy]zzz【ddd】bbb  一共五段
	while($title!='')
	{
		//echo $title. "\n";	
		preg_match('/(【|\[)/',$title,$match);
		//print_r($match);
		if(empty($match[0]))
		{
			insertarray($duanarray,$title);
			break;
		}		
		//echo $title;
		preg_match('/^(.*?)(\[|【)/',$title,$match);
		//print_r($match);
		if(!empty($match[1]))
		{
			insertarray($duanarray,$match[1]);
			$title = str_replace($match[1],'',$title);
		}
		
		//echo $title;
		preg_match('/^\[(.*?)\]/',$title,$match);
		//print_r($match);
		if(!empty($match[1]))
		{	
			insertarray($duanarray,$match[1]);
			$title = str_replace($match[0],'',$title);
		}
		else
		{		
			echo $title;
			preg_match('/^【(.*?)】/',$title,$match);
			//print_r($match);
			if(!empty($match[1]))
			{
				insertarray($duanarray,$match[1]);
				$title = str_replace($match[0],'',$title);
			}
			else
			{
				//有问题了 有【[没有尾部
				$title = preg_replace('/^(【|\[)/','',$title);
			}
		}
		$title=trim($title);
	}
	//print_r($duanarray);
	return judgetitle($duanarray);
}

function insertarray(&$duanarray,$title)
{
	//中文标题中需要用空格分隔，英文标题不使用空格分隔
	$titles=preg_split("/(_|-|:|：|\\\|\/|\.|,|，)/", $title);
	//print_r($titles);
	foreach($titles as $eachtitle)
	{
		//判断是否为全部英文
		preg_match('/^[a-zA-Z0-9\s\.\/\']+$/',$eachtitle,$match);
		//print_r($match);
		if(empty($match[0]))
		{//中文			
			$innertitleneed = getfirsttitle($eachtitle);
			//echo $innertitleneed;
			$innertitleneed = preg_replace('/[a-zA-Z0-9\s\.\/\']+/','_',$innertitleneed);
			echo ' >> '.$innertitleneed;
			
			//利用特殊符号分隔
			$innertitleneed = preg_replace('/★|◆|×|●/us','_',$innertitleneed);
			//echo "\n".$innertitleneed."\n";
			$zhongtitles = preg_split("/_/",$innertitleneed);
			//print_r($zhongtitles);
			foreach ($zhongtitles as $eachzhongtitles)
			{				
				$wordslast = mb_strlen($eachzhongtitles,'UTF-8');
				if($wordslast<=1)
					continue;	
				array_push($duanarray,$eachzhongtitles);								
			}
		}
		else
		{//英文
			$firsttitle = getfirsttitle($eachtitle);
			$wordslast = mb_strlen($firsttitle,'UTF-8');
			if($wordslast<=2)
				continue;	
			array_push($duanarray,$firsttitle);				
		}		
	}
}

//子集应该在父类之后
$country=array('中国','中國','大陆',"香港","台湾","澳门","国产",'港台','日剧',"日本",'日韩',"韩剧","韩国",'欧美','美国','美國',"德国","法国",'法國',"美剧","朝鲜","澳大利亚","意大利","西班牙","越南","泰国","柬埔寨","印度","巴基斯坦","英国","阿根廷","加拿大","俄罗斯",'俄羅斯','苏联','蘇聯',"中日","中俄",'中美','匈牙利','捷克','瑞典','南斯拉夫','西德','东德','美法');
$qulity=array('高清','蓝光','720p','1080p','DVD','1280','1024','mkv','MKV','枪版','抢先','TS','BD','HD','RMVB','AVI','avi','迅雷','下载','BT','MP4','mp4','快传','百度网盘','电驴','BT','bt','网盘');
$type=array("记录",'紀錄','纪录',"动作","情色","爱情","动作惊悚","历史","恐怖","励志","首发","青春","偶像","魔幻","惊悚","喜剧","悬疑","奇幻","科幻","剧情","冒险","经典","推理",'凶杀','史诗','综艺','其他','其它','犯罪','传记','影视','战争','动画');
$other=array('应求',"观看","首映","首款","首发","网络","独播","暑期必看","配音","最新","经典","高分","热门",'未分级版',"版本","版","精彩","先行","制作","官方","字幕","漢化","汉化","月番","月新番","新番","动漫","电影","电视",'電視',"未删减版","主要","演员","主演","主打","大片",'系列','合集',"片",'剧集','剧','年','贴','连载','更新中','更新','重现','注意');
$del_word=array("限制级","限制","R级","三级","四级","禁片","国语","中字","中英双字","中英字","出品",'回馈','重发','簡繁','简繁','繁體','繁体','简体','外挂','外掛','中文','英语','TVB','粵語','粤语','无字','無字');
//$smbol=array('★','◆','×','●');

$mustnot=array('字幕','制作');

//对每个标题，判断是否是关键词段，根据情况决定是否采用
function judgetitle($titlearray)
{
	//print_r($titlearray);
	global $country,$qulity,$type,$other,$del_word,$mustnot;
	foreach($titlearray as $innertitle)
	{
		$replacecount=0;
		//$innertitle = getfirsttitle($innertitle);
		$innertitleneed = $innertitle;
		
		$mustrootpass=true;
		foreach ($mustnot as $eachlist)
		{
			if(strstr($innertitleneed,$eachlist))
			{
				$mustrootpass = false;
				break;
			}
		}
		if($mustrootpass===false)
			continue;
		
		foreach ($country as $eachlist)
		{
			$innertitleneed = str_replace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($qulity as $eachlist)
		{
			$innertitleneed = str_replace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($type as $eachlist)
		{
			$innertitleneed = str_replace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($other as $eachlist)
		{
			$innertitleneed = str_replace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($del_word as $eachlist)
		{
			$innertitleneed = str_replace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		//全部是数字和字母的组合不要，不符合中国国情
		$innertitleneed = preg_replace('/[a-zA-Z0-9\s\.\/\']+/','',$innertitleneed);
		$innertitleneed = preg_replace('/\(.*?\)/','',$innertitleneed);
		$innertitleneed = preg_replace('/（.*?）/','',$innertitleneed);
		//如果被削的一点没有了，说明不是标题
		if(trim($innertitleneed)==='')
			continue;
		
		$wordslast = mb_strlen($innertitleneed,'UTF-8');
		//echo ' replacecount:'.$replacecount;
		//echo ' mdstrlen:'.$wordslast;
		//echo ' ****'.$innertitleneed.'**** ';
		if($replacecount>=3)
			continue;				
		if($replacecount>=2)
			if($wordslast<=1)
				continue;
		if($replacecount>=1)
			if($wordslast<=0)
				continue;
		//如果没有这些词语，就说明是标题，并且不需要再找了
		return $innertitle;		
	}
	//如果一个都没有，说明没有标题，说明失败了
	return false;
}


//利用标点符号、第几季、特殊字体、中英文等提取标题
function getfirsttitle($title)
{
	//echo $title;
	preg_match('/^(.*?)[\(|\（]/us',$title,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$title = $match[1];
	}

//	preg_match('/(.*?)(第|前|后|下)?[零一二三四五六七八九十]+(季|部|集|话|回|話)/',$title,$match);
	preg_match('/^(.*?)[第|前|后|下][0-9\-零一二三四五六七八九十]+[季|部|集|话|回|話]/us',$title,$match);	
//	print_r($match);
	if(!empty($match[1]))
	{
		//$title =  $match[1].'_'.$match[2];
		$title =  $match[1];
	}
	return trim($title);
}
?>  