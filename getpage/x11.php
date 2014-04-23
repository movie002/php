<?php  

//子集应该在父类之后
$country=array('中国','中國','大陆',"香港","台湾","澳门","国产",'港台','日剧',"日本",'日韩',"韩剧","韩国",'欧美','美国','美國',"德国","法国",'法國',"美剧","朝鲜","澳大利亚","意大利","西班牙","越南","泰国","柬埔寨","印度",'印语',"巴基斯坦","英国","阿根廷","加拿大","俄罗斯",'俄羅斯','苏联','蘇聯',"中日","中俄",'中美','匈牙利','捷克','瑞典','南斯拉夫','西德','东德','美法',"国语","中字","中英双字","中英字",'簡繁','简繁','繁體','繁体','简体','外挂','外掛','中文','英语','粵語','粤语','无字','無字','内嵌','马来西亚','内地','泰语','字','语','中','美','日','韩','国','英','台','港','粤');
$qulity=array('高清','蓝光','720p','1080p','1080i','DVD','1280','1024','mkv','MKV','枪版','抢先','TS','BD','bd','HD','RMVB','AVI','avi','迅雷','下载','BT','MP4','快传','百度网盘','电驴','网盘','立体','原盘','HDTV','TV','3D','WMV','mp3','mv','百度云');
$type=array("记录",'紀錄','纪录',"动作",'動作',"情色","爱情","动作惊悚","历史","恐怖","励志","首发","青春","偶像","魔幻","惊悚","喜剧","悬疑","奇幻","科幻","剧情","冒险","经典","推理",'凶杀','史诗','综艺','其他','其它','犯罪','传记','影视','战争','动画','歌舞','体育','重口味','R级');
$other=array('应求',"观看","首映","首款","首发","网络","独播","暑期必看","配音","最新","高分","热门",'未分级版',"版本","版","精彩","先行","制作","官方","字幕","漢化","汉化","月番","月新番","新番","动漫","电影","电视",'電視',"未删减版","主要","演员","主演","主打","大片",'系列','合集',"片",'剧集','剧','年','贴','连载','更新中','更新','重现','注意','原创','求片','左右','半宽','上下','再造','TOP','BTV','TSKS','KS','TVB','SUBPIG','OST','TSJS','BBC','票房','最强','分辨率','每集','集');
$del_word=array("限制级","限制","R级","三级","四级","禁片","出品",'回馈','重发','+');
//$smbol=array('★','◆','×','●');
$mustnot=array('字幕','制作');

//第几季的对应关系
//$season1=array('01'=>'第一季','02'=>'第二季','03'=>'第三季','04'=>'第四季','05'=>'第五季','06'=>'第六季','07'=>'第七季','08'=>'第八季','09'=>'第九季','10'=>'第十季','11'=>'第十一季','12'=>'第十二季');
$season2=array('01'=>'第1季','02'=>'第2季','03'=>'第3季','04'=>'第4季','05'=>'第5季','06'=>'第6季','07'=>'第7季','08'=>'第8季','09'=>'第9季','10'=>'第10季','11'=>'第11季','12'=>'第12季');


/* 使用示例 */  
//header('Content-Type:text/html;charset= UTF-8'); 
//$test = "《痞子大逃亡/真相与结果》蓝光高清1024MKV版迅雷下载[美国动作惊悚电影]"; 
//$test = "[痞子大逃亡/真相与结果]蓝光高清1024MKV版迅雷下载[美国动作惊悚电影]"; 
//$result = x11($test);
//print_r($result);


function x11($title)
{
	if($title=='')
		return -1;
		
	//第一步: 大致的提取标题，利用分割符号对标题处理		
	preg_match('/《(.*?)》/',$title,$match);
	//print_r($match);
	if(!empty($match[1]))//如果有《》，不处理
	{
		return $match[1];
	}
	
	//「」 [] 【】等括号提取标题
	$title = preg_replace('/(【|】|\[|\]|「|」|《|》)/','/',$title);
	$duanarray = array();
	insertarray($duanarray,$title);	
	print_r($duanarray);
	$subtitle = judgetitle($duanarray);
	if($subtitle===false)
		 return -1;
	//echo $subtitle."\n";
	return $subtitle;
}

function insertarray(&$duanarray,$title)
{
//	echo "\n".$title;
	if(trim($title)=='')
		return;
		
	//存数字不要
	preg_match('/^[0-9\-\s\.gmbpx年]+$/si',$title,$match);
	//print_r($match);
	if(!empty($match[0]))
		return;	
		
	//按照/分开
	$titles=preg_split("/(\/|★|◆|×|●)/", $title);
	if(count($titles)>1)
	{
		foreach($titles as $eachtitle)
			insertarray($duanarray,$eachtitle);
		return;
	}
		
	//先判断是否是全部英文
	preg_match('/^[a-zA-Z0-9\s\.\_\-\'\(\)]+$/',$title,$match);
//	print_r($match);
	if(!empty($match[0]))
	{		
		$firsttitle = getfirsttitle($match[0]);
		$firsttitle = str_replace('.',' ',$firsttitle);
		$firsttitle = str_replace('_',' ',$firsttitle);
		if(trim($firsttitle)!='')
			array_push($duanarray,trim($firsttitle));	
		return;
	}
	//再测试是否全部中文
	preg_match('/[a-zA-Z]/si',$title,$match);
	if(empty($match[0]))
	{
		$titles=preg_split("/(\s|\.)/", $title);
		foreach($titles as $eachtitle)
		{
			if(trim($eachtitle)!='')
			{
				//在插入之前挑选一下
				$firsttitle= getfirsttitle($eachtitle);
				if(trim($firsttitle)!='')
					array_push($duanarray,trim($firsttitle));
			}
		}
		return;
	}
	echo "\ncande:".$title."\n";
//	return;
	//这个是中英文夹杂的情况，比较难办，中英文分开
//	preg_match_all('/([a-zA-Z0-9\s\.\_\-\'\(\)]+)/',$title,$matchs);
//	if(!empty($matchs[0]))
//		foreach($matchs[1] as $eachtitle)
//			insertarray($duanarray,trim($eachtitle));
//	preg_match_all('/([^a-zA-Z]+)/',$title,$matchs);	
//	if(!empty($matchs[0]))
//		foreach($matchs[1] as $eachtitle)
//			insertarray($duanarray,trim($eachtitle));

	//字母处理 将 e R 处理成 e.R b 便于分开处理
	$title = preg_replace('/([a-zA-Z])[\s|\.]([a-zA-Z])/','$1_$2',$title);
	//echo "--> ".$title;	
	//$title= getfirsttitle($title);
	$titles=preg_split("/(\s|\.)/", $title);
	//print_r($titles);
	if(count($titles)<=1)
	{
		$titles=preg_split("/(\.)/", $title);
		if(count($titles)<=1)
		{
			array_push($duanarray,trim($title));
			return;
		}
	}
	foreach($titles as $eachtitle)
	{
		if(trim($eachtitle)!='')
		{
			//在插入之前挑选一下
			$firsttitle= getfirsttitle($eachtitle);
			if(trim($firsttitle)!='')
				insertarray($duanarray,trim($firsttitle));
		}
	}
}

//对每个标题，判断是否是关键词段，根据情况决定是否采用
function judgetitle($titlearray)
{
	//print_r($titlearray);
	global $country,$qulity,$type,$other,$del_word,$mustnot;
	$titlenext=0;
	$titlereturn='';
	foreach($titlearray as $innertitle)
	{
		$replacecount=0;
		//$innertitle = getfirsttitle($innertitle);
		$innertitleneed = $innertitle;
		
		preg_match('/[0-9]+/si',$innertitleneed,$match);
		//print_r($match);
		if(!empty($match[0]))
		{
			$innertitleneed = preg_replace('/[0-9]+/si','',$innertitleneed);
			$replacecount++;		
		}
		
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
			$innertitleneed = str_ireplace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($qulity as $eachlist)
		{
			$innertitleneed = str_ireplace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($type as $eachlist)
		{
			$innertitleneed = str_ireplace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($other as $eachlist)
		{
			$innertitleneed = str_ireplace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		foreach ($del_word as $eachlist)
		{
			$innertitleneed = str_ireplace($eachlist,"",$innertitleneed,$i);
			$replacecount+=$i;
		}
		//全部是数字和字母的组合不要，不符合中国国情
		//$innertitleneed = preg_replace('/[a-zA-Z0-9\s\.\/\']+/','',$innertitleneed);
		$innertitleneed = preg_replace('/\(.*?\)/','',$innertitleneed);
		$innertitleneed = preg_replace('/（.*?）/','',$innertitleneed);
		//如果被削的一点没有了，说明不是标题
		//if(trim($innertitleneed)==='')
		//	continue;
		
		$wordslast = mb_strlen($innertitleneed,'UTF-8');
		//echo ' replacecount:'.$replacecount;
		//echo ' mdstrlen:'.$wordslast;
		//echo ' ****'.$innertitleneed.'**** ';		
	
		if($replacecount>=3)
			continue;		
		if($replacecount>=2)
			if($wordslast<4)
				continue;
		if($replacecount>=1)
			if($wordslast<2)
				continue;
		//如果没有这些词语，就说明是标题，并且不需要再找了
		$titlenext++;
		if($titlenext==1)
			$titlereturn.=$innertitle;
		else
			$titlereturn.='/'.$innertitle;
		//echo $titlereturn;
		if($titlenext>2)		
			return $titlereturn;
	}
	return $titlereturn;
}

//利用标点符号、第几季、特殊字体、中英文等提取标题
function getfirsttitle($title)
{
	global $season2;
	//echo $title;
	$firsttitle=$title;
	preg_match('/^(.*?)[\(|\（]/us',$firsttitle,$match1);
	//print_r($match1);
	if(!empty($match1[1]))
	{
		$firsttitle=$match1[1];
	}

	preg_match('/^(.*?)((19|20|18)[0-9]{2,2})/s',$firsttitle,$match1);
	if(!empty($match1[0]))
	{
		//有年份
		$firsttitle=$match1[1];
	}	
	
	preg_match('/^(.*?)(EP|E)[0-9]+/si',$firsttitle,$match1);
	//print_r($match1);
	if(!empty($match1[0]))
	{
		$firsttitle=$match1[1];
	}
	
	preg_match('/^(.*?)([第|前|后|下|全][0-9\-零一二三四五六七八九十]+[季|部|集|话|回|話])/us',$firsttitle,$match1);	
	//print_r($match1);
	if(!empty($match1[0]))
	{
		$firsttitle = $match1[1];
	}	
	
	preg_match('/^(.*?)S([0-9]+)/si',$firsttitle,$match1);
	if(!empty($match1[0]))
	{
		//得到season信息
		$firsttitle=$match1[1];
	}
	
	return trim($firsttitle);
}
?>  