<?php
function dh_replace_link($sql,$row,$DH_output_content)
{
	global $linktype,$linkway,$linkquality,$linkdownway;
	$num = 5;//内部分页的分页内条数
	$maxnum = 25;//最多显示的条目
	$DH_output_content_page ='';
	$downloadlinks = '';
	$downloadlinks0 = '';$num00=0;
	$downloadlinks1 = '';$num01=0;
	$downloadlinks2 = '';$num02=0;
	$downloadlinks3 = '';$num03=0;
	$onlinelinks = '';$num1=0;	
	$tailer = '';$num2=0;
	$zixun='';$num3=0;
	$yingping='';$num4=0;		
	$jhblinks= '';

	global $DH_html_path;
	$DH_input_html  = $DH_html_path . 'innerpage.html';
	$innerpage = dh_file_get_contents($DH_input_html);
	
	$linkstop1 = '<div class="listall"><div class="listlink">&nbsp;&nbsp;清晰度 原文地址</div><div class="rt0v5">来源网站 资源类型 更新时间</div></div>';	
	$linkstop2 = '<div class="listall"><div class="listnum"></div> <div class="listlink">&nbsp;&nbsp;&nbsp;原文地址</div><div class="rt0v5">来源网站 更新时间</div></div>';
    	$searchcat='';	
	$reslinks=dh_mysql_query($sql);	
	while($rowlinks = mysql_fetch_array($reslinks))
	{
		$way = $rowlinks['linkway'];
		$quality = $rowlinks['linkquality'];
		if($quality=='')
			$quality = 0;			
		$type = $rowlinks['linktype'];
		if($type=='')
			$type = 0;
		
		$updatetime = date("Ymd",strtotime($rowlinks['updatetime']));
		$title = str_replace("'","`",$rowlinks['title']);
		$title = str_replace("\"","`",$title);
		//echo $title."\n";
		
		$linkseach1 = '<li class="listall"><div class="listlink">%num% <span class="lqc'.$quality.'">['.$linkquality[$quality].']</span> <a href = "'. $rowlinks['link'] . '" target = "_blank" title="点击访问链接:'.$title.'('.$rowlinks['author'].')" rel="nofollow">'.$title. '</a></div><div class="lqc3 rt0v5"> '.$rowlinks['author'].' <span class="c'.$type.'">'.$linktype[$type].'</span> <a title="点击删除" href="javascript:deleteurl(\''.$title.'\',\''.$rowlinks['link'].'\')">'.$updatetime.'</a></div></li>';
		$linkseach2 = '<li class="listall"><div class="listlink">%num% <a href = "'. $rowlinks['link'] . '" target = "_blank" title="点击访问链接:'.$title.'('.$rowlinks['author'].')" rel="nofollow">'.$title. '</a></div><div class="lqc3 rt0v5"> '.$rowlinks['author'].' <a title="点击删除"  href="javascript:deleteurl(\''.$title.'\',\''.$rowlinks['link'].'\')">'.$updatetime.'</a></div></li>';			
		switch ($way)
		{
			case 1://资讯
				$num3++;
				if($num3<=$maxnum)
					$zixun .= str_replace('%num%',$num3,$linkseach2);
				break;
			case 2://评论
				$num4++;
				if($num4<=$maxnum)
					$yingping .= str_replace('%num%',$num4,$linkseach2);		
				break;
			case 3://预告花絮
				$num2++;
				if($num2<=$maxnum)
					$tailer .= str_replace('%num%',$num2,$linkseach1);
				break;
			case 6://下载
				{
					switch($rowlinks['linkdownway'])
					{
						case 0:
							$num00++;
							if($num00<=$maxnum)
								$downloadlinks0 .= str_replace('%num%',$num00,$linkseach1);
							break;
						case 1:
							$num01++;
							if($num01<=$maxnum)							
								$downloadlinks1 .= str_replace('%num%',$num01,$linkseach1);
							break;
						case 2:
							$num02++;
							if($num02<=$maxnum)							
								$downloadlinks2 .= str_replace('%num%',$num02,$linkseach1);
							break;
						case 3:
							$num03++;
							if($num03<=$maxnum)							
								$downloadlinks3 .= str_replace('%num%',$num03,$linkseach1);
							break;								
						default:
							echo $rowlinks['title']."-->".$rowlinks['link']."-->".$way."</br>\n";
							print_r(" error get linkproperty on dh_replace_link </br>  \n");
					}
					break;
				}
			case 7://在线
				$num1++;
				if($num1<=$maxnum)
					$onlinelinks .= str_replace('%num%',$num1,$linkseach1);
				break;
			case 8://影片信息
			//	$jhblinks .= '<a href = "'. $rowlinks['link'] . '" target = "_blank" title="'.$rowlinks['title'].'的链接" rel="nofollow">['.$rowlinks['author']. ']</a>';
				break;					
			default:
				echo $rowlinks['title']."-->".$rowlinks['link']."-->".$way."</br>\n";
				print_r(" error get type on dh_replace_link </br>  \n");
		}			
	}
	
	$downloadlinkstitle='';
	$downloadlinksi=-1;
	if($num01>0)
	{
		$downloadlinksi++;
		$downloadlinkstitle.='<li class="active" onclick="nTabs(this,'.$downloadlinksi.');">'.$linkdownway[1].'('.$num01.')</li>';		
		$downloadlinks.='<div id="dl_Content'.$downloadlinksi.'" style="overflow:hidden;z-index:2;"> <div class="anchor"><a name="adownloadlinks1" id="adownloadlinks1">&nbsp;</a></div>'.$linkstop1.getlinksmore($num02,$num,'downloadlinks1',$downloadlinks1,$innerpage).'</div>';
	}
	if($num02>0)
	{
		$activetitle='normal';$activelink='';		
		if($downloadlinksi==-1)
			$activetitle='active';		
		else
			$activelink='class="none"';
			
		$downloadlinksi++;
		$downloadlinkstitle.='<li class="'.$activetitle.'" onclick="nTabs(this,'.$downloadlinksi.');">'.$linkdownway[2].'('.$num02.')</li>';		
		$downloadlinks.='<div id="dl_Content'.$downloadlinksi.'"'.$activelink.' style="overflow:hidden;z-index:2;"><div class="anchor"><a name="adownloadlinks2" id="adownloadlinks2">&nbsp;</a></div>'.$linkstop1.getlinksmore($num02,$num,'downloadlinks2',$downloadlinks2,$innerpage).'</div>';
	}
	if($num03>0)
	{
		$activetitle='normal';$activelink='';		
		if($downloadlinksi==-1)
			$activetitle='active';
		else
			$activelink='class="none"';	
			
		$downloadlinksi++;
		$downloadlinkstitle.='<li class="'.$activetitle.'" onclick="nTabs(this,'.$downloadlinksi.');">'.$linkdownway[3].'('.$num03.')</li>';	
		$downloadlinks.='<div id="dl_Content'.$downloadlinksi.'" '.$activelink.' style="overflow:hidden;z-index:2;"> <div class="anchor"><a name="adownloadlinks3" id="adownloadlinks3">&nbsp;</a></div>'.$linkstop1.getlinksmore($num03,$num,'downloadlinks3',$downloadlinks3,$innerpage).'</div>';
	}	
	if($num00>0)
	{
		$activelink='';$activetitle='normal';
		if($downloadlinksi==-1)
			$activetitle='active';
		else
			$activelink='class="none"';
		
		$downloadlinksi++;
		$downloadlinkstitle.='<li class="'.$activetitle.'" onclick="nTabs(this,'.$downloadlinksi.');">'.$linkdownway[0].'('.$num00.')</li>';		
		$downloadlinks.='<div id="dl_Content'.$downloadlinksi.'" '.$activelink.' style="overflow:hidden;z-index:2;"><div class="anchor"><a name="adownloadlinks0" id="adownloadlinks0">&nbsp;</a></div>'.$linkstop2.getlinksmore($num00,$num,'downloadlinks0',$downloadlinks0,$innerpage).'</div>';
	}

	$downloadlinkstitle = '<div class="TabTitle" style="padding:5px 0 5px 10px"><ul id="dl">'.$downloadlinkstitle.'</ul></div>';
	$downloadlinks = $downloadlinkstitle.$downloadlinks;
	$num0 = $num00+$num01+$num02+$num03;
	$DH_output_content_page = str_replace('%num0x%',$num0,$DH_output_content);
	if($num0<=0)
		$downloadlinks='<div>'.$linkstop1.'<div class="showhide">更多资源,敬请期待</div></div>';
	
	$DH_output_content_page = str_replace('%num1x%',$num1,$DH_output_content_page);

	$onlinelinks=$linkstop1.getlinksmore($num1,$num,'onlinelinks',$onlinelinks,$innerpage);
	$tailer=$linkstop1.getlinksmore($num2,$num,'tailer',$tailer,$innerpage);
	$zixun=$linkstop2.getlinksmore($num3,$num,'zixun',$zixun,$innerpage);
	$yingping=$linkstop2.getlinksmore($num4,$num,'pinglun',$yingping,$innerpage);
	
	$DH_output_content_page = str_replace('%zixun%',$zixun,$DH_output_content_page);
	$DH_output_content_page = str_replace('%downloadlinks%',$downloadlinks,$DH_output_content_page);
	$DH_output_content_page = str_replace('%tailer%',$tailer,$DH_output_content_page);		
	$DH_output_content_page = str_replace('%onlinelinks%',$onlinelinks,$DH_output_content_page);	
	$DH_output_content_page = str_replace('%yingping%',$yingping,$DH_output_content_page);		
	$DH_output_content_page = str_replace('%jhblinks%',$jhblinks,$DH_output_content_page);

	$codetitle=rawurlencode($row['title']);
	$codetitlegbk = rawurlencode(iconvbuffgbk($row['title']));
	$DH_output_content_page = str_replace('%codetitle%',$codetitle,$DH_output_content_page);	
	$DH_output_content_page = str_replace('%codetitlegbk%',$codetitlegbk,$DH_output_content_page);

	return $DH_output_content_page;
}

function dh_replace_content($count,$row,$DH_output_content)
{
//	preg_match_all('/\[(.*?)\]/',$row['simgurl'],$match);
//	$replace='';
//	if(!empty($match[1]))
//	{
//		$replace='<h3>剧情简介：</h3><div>%summary%</div>';
//		foreach ($match[1] as $simgurl)
//		{
//			$simgurlnop=substr($simgurl,1,strlen($simgurl)-5);	
//			$replace .= '<img src="http://img3.douban.com/img/celebrity/large/1354449251.64.jpg"/>';	
//		}
//	}

	$simgurls=preg_split("/[|]+/",$row['simgurl']);
	$replace='';
	$replacediv='';
	foreach($simgurls as $key=>$simgurl)
	{		
		if($simgurl=='')
			continue;
		$replacediv .= '<imgdao style="margin:5px" link_src="http://movie.douban.com/photos/photo/'.$simgurl.'/" img_src="http://img3.doubanio.com/view/photo/albumicon/public/p'.$simgurl.'.jpg" style="width:100px;height:100px" src_width="100px" src_height="100px" alt="'.$row['title'].'的剧照"><span></span></imgdao>';
	}	
	$DH_output_content_page = str_replace("%simgs%",$replacediv,$DH_output_content);
	
	//导演等
	$celeimg_array=array();
	$celes='';
	getcele('/<d>(.*?)<\/d>/',$celeimg_array,$celes,$row['meta'],'导演');
	$DH_output_content_page = str_replace("%directors%",$celes,$DH_output_content_page);	
	//主演
	$celes='';
	getcele('/<c>(.*?)<\/c>/',$celeimg_array,$celes,$row['meta'],'演员');
	$DH_output_content_page = str_replace("%casts%",$celes,$DH_output_content_page);

//	print_r($celeimg_array);
	//将演员组成
	$celeimg_all='';
	$people='';
	$celeimg_length=count($celeimg_array);
	$width='100px';
	$height='150px';
	if($celeimg_length > 6)
	{
		$width='90px';
		$height='135px';		
	}
	if($celeimg_length > 7)
	{
		$width='80px';
		$height='120px';		
	}	
	foreach ($celeimg_array as $key=>$celeimg_each)
	{
		if(empty($celeimg_each[2]))
			$imgeach='http://img3.doubanio.com/pics/celebrity-default-medium.gif';
		else
			$imgeach='http://img3.douban.com/img/celebrity/medium/'.$celeimg_each[2];
		$celeimg_all.=	'<li style="width:'.$width.'; height:'.$height.'"><a href="http://movie.douban.com/celebrity/'.$celeimg_each[0].'/" target="_blank" rel="nofollow"><img style="width:'.$width.'; height:'.$height.'" data-src="'.$imgeach.'" alt="'.$celeimg_each[1].'的影人图片" width="'.$width.'" height="'.$height.'"/><span class="celeimg_title">'.$celeimg_each[3].'</span></a><div class="celeimg_name">'.$celeimg_each[1].'</div></li>';
		if($key==0)
			$people.=$celeimg_each[1];
		else
			$people.='/'.$celeimg_each[1];
	}
	$celeimg_all = '<div id="pageClass"><ul>'.$celeimg_all.'</ul></div>';		
	//替换演员列表
	$DH_output_content_page = str_replace("%cpics%",$celeimg_all,$DH_output_content_page);	
	
	$lengthpeople=mb_strlen($people,'UTF-8');
	if($lengthpeople>33)
	{
		$people = '<span style="font-size:12px">'.$people.'</span>';
	}	
	$DH_output_content_page = str_replace("%people%",$people,$DH_output_content_page);
	
	//豆瓣 时光 评分等
	$codetitle=rawurlencode($row['title']);
	$replace = '';
	$rating='';
	$review='';
	$news='<a href="http://news.mtime.com/search/tag/index.html?t='.$codetitle.'" target="_blank" rel="nofollow">查找时光影讯</a>';;
	$trailer='';
	$photos='';
	$piaozi='';
	
	preg_match('/<r1>(.*?)<\/r1>/s',$row['ids'],$match);
	if(!empty($match[1]))
	{
		$rating = $match[1];
	}
	
	if($row['mediaid']!='')
	{
		$replace.='<span style="font-size: 12px;"><a href="http://movie.douban.com/subject/'.$row['mediaid'].'" target="_blank" rel="nofollow">豆瓣:'.$rating.'</a></span>';
		$review.='<a href="http://movie.douban.com/subject/'.$row['mediaid'].'/reviews" target="_blank" rel="nofollow">豆瓣影评</a>';
		$trailer.='<a href="http://movie.douban.com/subject/'.$row['mediaid'].'/trailer" target="_blank" rel="nofollow">豆瓣预告</a>';
		$photos.='<a href="http://movie.douban.com/subject/'.$row['mediaid'].'/all_photos" target="_blank" rel="nofollow">豆瓣剧照</a>';
		$piaozi.='<a href="http://movie.douban.com/subject/'.$row['mediaid'].'/cinema/" target="_blank" rel="nofollow">豆瓣购票</a>';
	}
	
	$rating='';
	preg_match('/<r2>(.*?)<\/r2>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$rating = $match[1];
		
	
	preg_match('/<2>(.*?)<\/2>/s',$row['ids'],$match);
	if(!empty($match[1]))
	{
		$replace.='/<a href="http://www.m1905.com/mdb/film/'.$match[1].'/" target="_blank" rel="nofollow">m1905:'.$rating.'</a>';
		$review.='/<a href="http://www.m1905.com/mdb/film/'.$match[1].'/review/" target="_blank" rel="nofollow">m1905影评</a>';
		$trailer.='/<a href="http://www.m1905.com/mdb/film/'.$match[1].'/prevue/" target="_blank" rel="nofollow">m1905预告</a>';
		$photos.='/<a href="http://www.m1905.com/mdb/film/'.$match[1].'/still/" target="_blank" rel="nofollow">m1905剧照</a>';
	}
	$rating='';
	preg_match('/<r3>(.*?)<\/r3>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$rating = $match[1];
		
	$mtimeid='';	
	preg_match('/<3>(.*?)<\/3>/s',$row['ids'],$match);
	if(!empty($match[1]))
	{
		$mtimeid=$match[1];
		$replace.='/<a href="http://movie.mtime.com/'.$match[1].'/" target="_blank" rel="nofollow">时光网:'.$rating.'</a>';
		$review.='/<a href="http://movie.mtime.com/'.$match[1].'/comment.html" target="_blank" rel="nofollow">时光影评</a>';
		$news.='/<a href="http://movie.mtime.com/'.$match[1].'/news.html" target="_blank" rel="nofollow">时光影讯</a>';
		$trailer.='/<a href="http://movie.mtime.com/'.$match[1].'/trailer.html" target="_blank" rel="nofollow">时光预告</a>';
		$photos.='/<a href="http://movie.mtime.com/'.$match[1].'/posters_and_images/" target="_blank" rel="nofollow">时光剧照</a>';
		
	}
	preg_match('/<m>(.*?)<\/m>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$replace.='/<a href="http://www.imdb.com/title/'.$match[1].'/" target="_blank" rel="nofollow">IMDB链接</a>';			
	
	$replace.=' 总分:'.$row['hot'];
	//影片的网站链接
	//preg_match('/<b>(.*?)<\/b>/',$row['meta'],$match);
	//if(!empty($match[1]))
	//{	
	//	$replace .= ' <a href="'.$match[1].'" target="_blank" >[官方网站]</a> </span>';
	//}	
	$DH_output_content_page = str_replace("%jhb%",$replace,$DH_output_content_page);
	$DH_output_content_page = str_replace("%review%",$review,$DH_output_content_page);
	$DH_output_content_page = str_replace("%news%",$news,$DH_output_content_page);
	$DH_output_content_page = str_replace("%trailer%",$trailer,$DH_output_content_page);
	$DH_output_content_page = str_replace("%photos%",$photos,$DH_output_content_page);
	
	$updatetime = date("Ymd",strtotime($row['updatetime']));
	
	//出品公司
	$pubcompany='';
	preg_match('/<pc>(.*?)<\/pc>/s',$row['meta'],$match);
	if(!empty($match[1]))
	{	
		$pubcompany.="由$match[1]公司出品";
	}	
	$DH_output_content_page = str_replace("%pubcompany%",$pubcompany,$DH_output_content_page);	

	//处理购票
	$buyticket='<li class="listall"><div class="listnum"></div> <div class="listlink">&nbsp;&nbsp;&nbsp;原文地址</div><div class="lqc3 rt0v5">来源网站 更新时间</div></li>';
	$ticketnum=0;
	if($row['mstatus']==3)
	{
		if($row['mediaid']!='')
		{
			$ticketnum++;
			$buyticket .='<li class="listall"><div class="listlink">'.$ticketnum.' <a href = "http://movie.douban.com/subject/'.$row['mediaid'].'/cinema/" target = "_blank" title="豆瓣购票链接" rel="nofollow">豆瓣购票链接</a></div><div class="lqc3 rt0v5">豆瓣 '.$updatetime.'</div></li>';
		}
		preg_match('/<4>(.*?)<\/4>/s',$row['ids'],$match1);
		//print_r($match1);
		if(!empty($match1[1]))
		{
			$ticketnum++;
			$buyticket .='<li class="listall"><div class="listlink">'.$ticketnum.' <a href = "http://www.gewara.com/movie/'.$match1[1].'" target = "_blank" title="格瓦拉购票链接" rel="nofollow">格瓦拉购票链接</a></div><div class="lqc3 rt0v5">格瓦拉 '.$updatetime.'</div></li>';
		}
		
		preg_match('/<5>(.*?)<\/5>/s',$row['ids'],$match1);
		//print_r($match1);
		if(!empty($match1[1]))
		{
			$ticketnum++;
			$buyticket .='<li class="listall"><div class="listlink">'.$ticketnum.' <a href = href="http://wangpiao.com/movie/'.$match1[1].'/?showulcinfo=1" target = "_blank" title="网票网购票链接" rel="nofollow">网票网购票链接</a></div><div class="lqc3 rt0v5">网票网 '.$updatetime.'</div></li>';
		}	
	
		if($mtimeid!='')
		{
			$ticketnum++;
			$buyticket .='<li class="listall"><div class="listlink">'.$ticketnum.' <a href = href="http://theater.mtime.com/movie/'.$mtimeid.'/" target = "_blank" title="时光网购票链接" rel="nofollow">时光网购票链接</a></div><div class="lqc3 rt0v5">时光网 '.$updatetime.'</div></li>';			
		}	
		//$codetitle=rawurlencode($row['title']);
		$piaozi.='/<a href="http://www.gewara.com/newSearchKey.xhtml?skey='.$codetitle.'" target="_blank" rel="nofollow">搜索格瓦拉</a>';
		$piaozi.='/<a href="http://www.wangpiao.com/Search/Index/0/'.$codetitle.'/1" target="_blank" rel="nofollow">搜索网票</a>';
		$piaozi.='/<a href="http://film.spider.com.cn/shanghai-search/?keyword='.$codetitle.'" target="_blank" rel="nofollow">搜索蜘蛛网</a>';
		$piaozi.='/<a href="http://www.hipiao.com/" target="_blank" rel="nofollow">登录哈票</a>';
	}
	else
		$piaozi='不在购票时间内';
	$buyticket='<div>'.$buyticket.'</div>'.'<div class="showhide">更多资源,敬请期待</div>';
	
	$ofsite='';
	preg_match('/<b>(.*?)<\/b>/s',$row['meta'],$match);
	if(!empty($match[1]))	
		$ofsite = ' <a href="'.$match[1].'" target="_blank" rel="nofollow">[官方网站]</a> </span>';
	$DH_output_content_page = str_replace("%ofsite%",$ofsite,$DH_output_content_page);
	
	$DH_output_content_page = str_replace("%buyticket%",$buyticket,$DH_output_content_page);	
	$DH_output_content_page = str_replace("%ticketnum%",$ticketnum,$DH_output_content_page);
	$DH_output_content_page = str_replace("%piaozi%",$piaozi,$DH_output_content_page);
	
	$DH_output_content_page = str_replace("%thisupdatetime%",'最后更新:'.$updatetime,$DH_output_content_page);
	return $DH_output_content_page;
}

function dh_replace_snapshot($type='middle',$row,$DH_output_content,$needcountrytype=false)
{	
	global $conn,$linkway,$DH_html_url,$DH_html_path,$linkquality,$moviecountry,$DH_index_url;
	$DH_output_content_page = str_replace("%title%",$row['title'],$DH_output_content);
	$aka='';
	//if($row['aka'])
	//{
	//	$aka='<b>影片别名:</b> '.$row['aka'];
	//}
	//$DH_output_content_page = str_replace("%aka%",$aka,$DH_output_content_page);
	$DH_output_content_page = str_replace("%aka%",$row['aka'],$DH_output_content_page);
	//$imgposter = str_replace('spic','mpic',$row['imgurl']);
	//$imgnum=$count%5+1;
	//$imgposter =$imgurl='http://img'.$imgnum.'.douban.com/mpic/'.$row['imgurl'];
	
	$width='80px';
	$height='120px';
	if($type=='big')
	{
		$width='100px';
		$height='148px';
	}
	if($type=='small')
	{
		$width='40px';
		$height='60px';
	}
	
	$countrymeta='';
	if($needcountrytype===false)
		$countrymeta='';
	else
	{
		//$countrymeta='['.$moviecountry[$row['catcountry']].']';
		$countrymeta=' [<a href="'.$DH_index_url.$row['cattype'].'_'.$row['catcountry'].'_'.$needcountrytype.'/1.html">'.$moviecountry[$row['catcountry']].'</a>] ';
	}
	$DH_output_content_page = str_replace("%moviemeta%",$countrymeta,$DH_output_content_page);
	
	//如果是没有资源是 无资源 有预告片是 [预告片] 有资源是按照资源的清晰度
	$way='';
	if($row['ziyuan']<=0)
		$way='[暂无资源]';
	else
	{		
		if($row['quality']>=5)
			$way.='[<a href="'.$DH_index_url.$row['cattype'].'_'.$row['catcountry'].'_c/1.html">'.$linkquality[$row['quality']].'</a>]';
		else
		{
			if($row['quality']<0)
				$way='[未知]';
			else
				$way='['.$linkquality[$row['quality']].']';
		}
	}
	$DH_output_content_page = str_replace("%way%",$way,$DH_output_content_page);
	
	$simgurl=$row['imgurl'];	
	$imgposter='';
	$page_path = output_page_path($DH_html_url,$row['id']);
	if($simgurl=='' || $simgurl[0]=='s')
	{
		if($simgurl=='')
			$simgurlneed='http://img3.douban.com/pics/movie-default-medium.gif';
		else
			$simgurlneed='http://img3.douban.com/mpic/'.$simgurl;
		$imgposter ='<a href="'.$page_path.'" target="_blank" title="'.$row['title'].'"><img style="width:'.$width.';height:'.$height.'" alt="'.$row['title'].'的海报" data-src="'.$simgurlneed.'" width="'.$width.'" height="'.$height.'"/></a>';
	}
	else
	{
		$imgposter = '<imgdao link_src="'.$page_path.'" img_src="http://img3.doubanio.com/view/photo/thumb/public/p'.$simgurl.'.jpg" style="width:'.$width.';height:'.$height.'" src_width="'.$width.'" src_height="'.$height.'" alt="'.$row['title'].'的海报"><span></span></imgdao>';
	}	
	$DH_output_content_page = str_replace("%imgposter%",$imgposter,$DH_output_content_page);
	
	$DH_output_content_page = str_replace("%title_link%",$page_path,$DH_output_content_page);
	
	//电视剧显示集数
	if($row['cattype']=='3')
	{
		preg_match('/<e>(.*?)<\/e>/',$row['meta'],$match);
		if(!empty($match[1]))
		{	
			$replace .= '&nbsp; <b>集数：</b>'.$match[1];
		}
	}
			
	//数字化的发型日期
	$updatetime=date("m-d",strtotime($row['updatetime']));
	$DH_output_content_page = str_replace("%updatetime%",$updatetime,$DH_output_content_page);	
	
	//发行日期
	preg_match('/<p>(.*?)<\/p>/',$row['meta'],$match);
	$pubdate='';
	if(!empty($match[1]))
	{	
		$pubdate = $match[1];
		$pubdate = str_replace(" ",'',$pubdate);
	}		
	//影片长度
	preg_match('/<i>(.*?)<\/i>/',$row['meta'],$match);
	$time='';
	if(!empty($match[1]))
	{	
		$time = $match[1];
		$time = str_replace(" ",'',$time);
	}
	$lengthall=mb_strlen($time,'UTF-8')+mb_strlen($pubdate,'UTF-8');
	if($lengthall>50)
	{
		$pubdate = '<span style="font-size:12px">'.$pubdate.'</span>';
		$time = '<span style="font-size:12px">'.$time.'</span>';
	}

	$DH_output_content_page = str_replace("%pubdate%",$pubdate,$DH_output_content_page);
	$DH_output_content_page = str_replace("%pubdate2%",$row['pubdate'],$DH_output_content_page);	
	$DH_output_content_page = str_replace("%time%",$time,$DH_output_content_page);	
	
	//国家
	preg_match('/<g>(.*?)<\/g>/',$row['meta'],$match);
	$replace='';
	if(!empty($match[1]))
	{	
		$replace = $match[1];
		$replace = str_replace(" ",'',$replace);
	}
	$DH_output_content_page = str_replace("%country%",$replace,$DH_output_content_page);
	
	//语言
	preg_match('/<l>(.*?)<\/l>/',$row['meta'],$match);
	$replace='';
	if(!empty($match[1]))
	{	
		$replace = $match[1];
		$replace = str_replace(" ",'',$replace);
	}
	$DH_output_content_page = str_replace("%language%",$replace,$DH_output_content_page);	

	//类别
	preg_match('/<t>(.*?)<\/t>/',$row['meta'],$match);
	if(!empty($match[1]))
	{	
		$replace = $match[1];
		$replace = str_replace(" ",'',$replace);
	}
	$DH_output_content_page = str_replace("%tags%",$replace,$DH_output_content_page);	

	$DH_output_content_page = str_replace("%rating%",$row['hot'],$DH_output_content_page);
	global $moviestatus;
	$mstatus='';
	if($row['mstatus']==2)
		$mstatus = '[<a href="'.$DH_index_url.'1_i/1.html">'.$moviestatus[$row['mstatus']].'</a>]';	
	if($row['mstatus']==3)
		$mstatus = '[<a href="'.$DH_index_url.'1_o/1.html">'.$moviestatus[$row['mstatus']].'</a>]';	
	$DH_output_content_page = str_replace("%mstatus%",$mstatus,$DH_output_content_page);
	
	// $sqllinks = "select way from link t where t.mediaid = '".$row['mediaid']."' group by way";
	// $reslinks=mysql_query($sqllinks,$conn);	
	// $way='';
	// if($reslinks)
	// {
		// while($rowlinks = mysql_fetch_array($reslinks))
		// {
			// $waynum = $rowlinks['way'];
			// $way.=$linkway[$waynum].'|';
		// }
		// $way = substr($way,0,strlen($way)-1);
	// }	
	// $DH_output_content_page = str_replace("%way%",$way,$DH_output_content_page);

	$DH_output_content_page = str_replace('%num1%',$row['ziyuan'],$DH_output_content_page);
	$DH_output_content_page = str_replace('%num2%',$row['yugao'],$DH_output_content_page);
	$DH_output_content_page = str_replace('%num3%',$row['yingping'],$DH_output_content_page);	
	$DH_output_content_page = str_replace('%num4%',$row['zixun'],$DH_output_content_page);

	return $DH_output_content_page;
}

function getcele($patten,&$celeimg,&$celes,$meta,$title)
{
	preg_match($patten,$meta,$match);
	if(!empty($match[1]))
	{	
		$celes='';
		preg_match_all('/\[(.*?)\]/',$match[1],$match2);
		if(!empty($match2[1]))
		{
			foreach ($match2[1] as $key=>$eachmatch)
			{
				if($key>5)
					break;
				list($name,$id) = preg_split('[|]', $eachmatch);
				if($id)
				{
					$celes .= '<a href="http://movie.douban.com/celebrity/'.$id.'/" target="_blank" rel="nofollow">'.trim($name).'</a>'.'/';
					//数据库中寻找响应的pic
					$celeimgeach=array($id,$name,'',$title);
					$sql="select pic from celebrity where id='".$id."'";;
					$results=dh_mysql_query($sql);
					if($results)
					{
						$pic = mysql_fetch_array($results);
						$celeimgeach[2]=$pic[0];
					}
					array_push($celeimg,$celeimgeach);
				}
				else
				{
					$celes .= trim($name).'/';
				}
			}
			$celes = substr($celes,0,strlen($celes)-1);
		}
	}
}

function getlinksmore($num0,$num,$id,$content,$innerpage)
{
	$tmplinks='<div id="'.$id.'">'.$content.'</div>';
	if($num0 <= $num)
		$tmplinks.='<div class="showhide">更多资源,敬请期待</div>';
	else
	{
		$innerpagetmp = str_replace("%pagesNav%",$id.'p1',$innerpage);
		$innerpagetmp = str_replace("%pageP%",$id.'p2',$innerpagetmp);
		$innerpagetmp = str_replace("%pagesA%",$id.'p3',$innerpagetmp);
		$innerpagetmp = str_replace("%pageN%",$id.'p4',$innerpagetmp);
		$innerpagetmp = str_replace("%liid%",$id,$innerpagetmp);
		$innerpagetmp = str_replace("%num%",$num,$innerpagetmp);
		$tmplinks.=$innerpagetmp;
	}
	return $tmplinks;
}

?>
