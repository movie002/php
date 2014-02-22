<?php
//include("../config.php");

function dh_replace_link($sql,$row,$DH_output_content)
{
	global $linktype,$linkway,$linkquality,$linkdowntype,$linkonlinetype,$linkproperty;
	$reslinks=dh_mysql_query($sql);
	$num = 5;
	$numdownload = 3;
	
	if($reslinks)
	{
		$downloadlinks = '';
		$downloadlinks0 = '';
		$downloadlinks0_more = '';
		$num00=0;
		$downloadlinks1 = '';
		$downloadlinks1_more = '';
		$num01=0;
		$downloadlinks2 = '';
		$downloadlinks2_more = '';
		$num02=0;
		$downloadlinks3 = '';
		$downloadlinks3_more = '';
		$num03=0;
		
		$onlinelinks = '';
		$onlinelinks_more = '';
		$num1=0;
		$tailer = '';
		$tailer_more = '';
		$num2=0;
		
		$jhblinks= '';

		$linkstop = '<div class="listall" style="background:#CCC;"><div class="listnum"></div> <div class="listlink">清晰度 原文地址</div><div class="rt0v0">来源网站 资源类型 更新时间</div></div>';
		
		while($rowlinks = mysql_fetch_array($reslinks))
		{
			$way = $rowlinks['linkway'];			
			$onlinetype = $rowlinks['linkonlinetype'];
			if($onlinetype=='')
				$onlinetype = 0;
			$quality = $rowlinks['linkquality'];
			if($quality=='')
				$quality = 0;			
			$downtype = $rowlinks['linkdowntype'];
			if($downtype=='')
				$downtype = 0;
			
			$updatetime = date("Ymd",strtotime($rowlinks['updatetime']));
			$linkseach = '<div class="listall"><div class="listnum">%num% </div> <div class="listlink"><span class="lqc'.$quality.'">['.$linkquality[$quality].']</span> <a href = "'. $rowlinks['link'] . '" target = "_blank" title="'.$rowlinks['title'].' - '.$rowlinks['author'].'的链接" rel="nofollow">'.$rowlinks['title']. '</a></div><div class="lqc3 rt0v0"> '.$rowlinks['author'].' <span class="c'.$downtype.'">'.$linkdowntype[$downtype].'</span> <span class="c'.$onlinetype.'">'.$linkonlinetype[$onlinetype].'</span> '.$updatetime.'</div></div>';
			
			switch ($way)
			{
				case 2://预告
				case 3://花絮
					$num2++;
					if($num2 > $num)
						$tailer_more .= str_replace('%num%',$num2,$linkseach);
					else
						$tailer .= str_replace('%num%',$num2,$linkseach);
					break;
				case 5:
				case 6://下载
					{
						$clinkproperty = $rowlinks['linkproperty'];
						switch($clinkproperty)
						{
							case 0:
								$num00++;
								if($num00 > $numdownload)
									$downloadlinks0_more .= str_replace('%num%',$num00,$linkseach);
								else
									$downloadlinks0 .= str_replace('%num%',$num00,$linkseach);
								break;
							case 1:
								$num01++;
								if($num01 > $numdownload)
									$downloadlinks1_more .= str_replace('%num%',$num01,$linkseach);
								else
									$downloadlinks1 .= str_replace('%num%',$num01,$linkseach);
								break;
							case 2:
								$num02++;
								if($num02 > $numdownload)
									$downloadlinks2_more .= str_replace('%num%',$num02,$linkseach);
								else
									$downloadlinks2 .= str_replace('%num%',$num02,$linkseach);
								break;
							case 3:
								$num03++;
								if($num03 > $numdownload)
									$downloadlinks3_more .= str_replace('%num%',$num03,$linkseach);
								else
									$downloadlinks3 .= str_replace('%num%',$num03,$linkseach);
								break;								
							default:
								echo $rowlinks['title']."-->".$rowlinks['link']."-->".$way."</br>\n";
								print_r(" error get linkproperty on dh_replace_link </br>  \n");
						}
						break;
					}
				case 4://在线
					$num1++;			
					if($num1 > $num)
						$onlinelinks_more .= str_replace('%num%',$num1,$linkseach);
					else
						$onlinelinks .= str_replace('%num%',$num1,$linkseach);
					break;
				case 1://影片信息
					$jhblinks .= '<a href = "'. $rowlinks['link'] . '" target = "_blank" title="'.$rowlinks['title'].'的链接" rel="nofollow">['.$rowlinks['author']. ']</a>';
					break;					
				default:
					echo $rowlinks['title']."-->".$rowlinks['link']."-->".$way."</br>\n";
					print_r(" error get type on dh_replace_link </br>  \n");
			}			
		}
		
		if($num01>0)
		{
			$downloadlinks.='<div class="downloadlist">'.$linkproperty[1].'(资源数:'.$num01.')<div class="anchor"><a name="adownloadlinks1" id="adownloadlinks1">&nbsp;</a></div></div>'.$linkstop.$downloadlinks1;
		}
		if($num01 > $numdownload)
		{
			$showtext='[ << 展开(其余'.($num01-$numdownload).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num01-$numdownload).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num01-$numdownload).'个) ∧ ]';
			$downloadlinks.='<div class="showhide" id="downloadlinks1_t" name="downloadlinks1_t" onclick="showhide(\'downloadlinks1_t\',\'downloadlinks1\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="downloadlinks1" style="display:none;" class="showhide_more">'.$downloadlinks1_more.'<div onclick="hide(\'downloadlinks1_t\',\'downloadlinks1\',\''.$showtext.'\',\'adownloadlinks1\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		if($num02>0)$downloadlinks.='<div class="downloadlist">'.$linkproperty[2].'(资源数:'.$num02.')<div class="anchor"><a name="adownloadlinks2" id="adownloadlinks2">&nbsp;</a></div></div>'.$linkstop.$downloadlinks2;
		if($num02 > $numdownload)
		{
			$showtext='[ << 展开(其余'.($num02-$numdownload).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num02-$numdownload).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num02-$numdownload).'个) ∧ ]';		
			$downloadlinks.='<div class="showhide" id="downloadlinks2_t"  onclick="showhide(\'downloadlinks2_t\',\'downloadlinks2\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="downloadlinks2" style="display:none;" class="showhide_more">'.$downloadlinks2_more.'<div onclick="hide(\'downloadlinks2_t\',\'downloadlinks2\',\''.$showtext.'\',\'adownloadlinks2\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		if($num03>0)$downloadlinks.='<div class="downloadlist">'.$linkproperty[3].'(资源数:'.$num03.')<div class="anchor"><a name="adownloadlinks3" id="adownloadlinks3">&nbsp;</a></div></div>'.$linkstop.$downloadlinks3;
		if($num03 > $numdownload)
		{
			$showtext='[ << 展开(其余'.($num03-$numdownload).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num03-$numdownload).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num03-$numdownload).'个) ∧ ]';
			$downloadlinks.='<div class="showhide" id="downloadlinks3_t"  onclick="showhide(\'downloadlinks3_t\',\'downloadlinks3\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="downloadlinks3" style="display:none;" class="showhide_more">'.$downloadlinks3_more.'<div onclick="hide(\'downloadlinks3_t\',\'downloadlinks3\',\''.$showtext.'\',\'adownloadlinks3\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		if($num00>0)$downloadlinks.='<div class="downloadlist">'.$linkproperty[0].'(资源数:'.$num00.')<div class="anchor"><a name="adownloadlinks0" id="adownloadlinks0">&nbsp;</a></div></div>'.$linkstop.$downloadlinks0;
		if($num00 > $numdownload)
		{
			$showtext='[ << 展开(其余'.($num00-$numdownload).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num00-$numdownload).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num00-$numdownload).'个) ∧ ]';		
			$downloadlinks.='<div class="showhide" id="downloadlinks0_t"  onclick="showhide(\'downloadlinks0_t\',\'downloadlinks0\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="downloadlinks0" style="display:none;" class="showhide_more">'.$downloadlinks0_more.'<div onclick="hide(\'downloadlinks0_t\',\'downloadlinks0\',\''.$showtext.'\',\'adownloadlinks0\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		
		if(($num00+$num01+$num02) == 0)
		{
			$downloadlinks=' <div style="color:#777"> 暂无下载资源,可点击以上搜索试试,或收藏此页,如有资源会及时更新!</div>';
		}
		
		//echo $downloadlinks;
		if($num1 > 0) 
			$onlinelinks = $linkstop.$onlinelinks;
		else
		{
			$onlinelinks=' <div style="color:#777"> 暂无在线资源,可以点击以上搜索试试,或收藏此页,如有资源会及时更新!</div>';
		}
		if($num1 > $num)
		{
			$showtext='[ << 展开(其余'.($num1-$num).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num1-$num).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num1-$num).'个) ∧ ]';		
			$onlinelinks.='<div class="showhide" id="onlinelinks_t"  onclick="showhide(\'onlinelinks_t\',\'onlinelinks\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="onlinelinks" style="display:none;" class="showhide_more">'.$onlinelinks_more.'<div onclick="hide(\'onlinelinks_t\',\'onlinelinks\',\''.$showtext.'\',\'aonlinelinks\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}						
		if($num2 > $num)
		{
			$showtext='[ << 展开(其余'.($num2-$num).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num2-$num).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num2-$num).'个) ∧ ]';		
			$tailer.='<div class="showhide" id="tailer_t"  onclick="showhide(\'tailer_t\',\'tailer\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="tailer" style="display:none;" class="showhide_more">'.$tailer_more.'<div onclick="hide(\'tailer_t\',\'tailer\',\''.$showtext.'\',\'title_2\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		
		$DH_output_content_page = str_replace('%jhblinks%',$jhblinks,$DH_output_content);
		
		$shooter='';
		$codetitle=rawurlencode($row['title']);
		$codetitlegbk = rawurlencode(iconvbuffgbk($row['title']));
		if(($num00+$num01+$num02+$num03)>0)
		{
			$shooter='[字幕:<a href="http://shooter.cn/search/'.$codetitle.'" target="_blank" rel="nofollow">射手网</a>/<a href="http://www.yyets.com/search/index?keyword='.$codetitle.'" target="_blank" rel="nofollow">人人网</a>]';
		}		
		$DH_output_content_page = str_replace('%shooter%',$shooter,$DH_output_content_page);
		
		$soushou=' [搜索:<a href="http://www.lesou.org/search.php?wd='.$codetitle.'" target="_blank" rel="nofollow">乐搜</a>';
		$soushou.='/<a href="http://bt.shousibaocai.com/?s='.$codetitle.'" target="_blank" rel="nofollow">磁力</a>';
		$soushou.='/<a href="http://cililian.org/e/http_handler:search?q='.$codetitle.'" target="_blank" rel="nofollow">磁力链</a>';
		$soushou.='/<a href="http://donkey4u.com/search/'.$codetitle.'" target="_blank" rel="nofollow">电驴</a>';
		$soushou.='/<a href="http://wangpan.ed2kers.com/index.php?q='.$codetitle.'" target="_blank" rel="nofollow">网盘</a>';		
		$soushou.='/<a href="http://h31bt.com/bt.asp?key='.$codetitle.'" target="_blank" rel="nofollow">H31BT</a>';
		$soushou.='/<a href="http://www.kuaichuanmirror.com/search/'.$codetitle.'" target="_blank" rel="nofollow">有源</a>';
		$soushou.='/<a href="http://www.cili.so/search.php?act=result&keyword='.$codetitle.'" target="_blank" rel="nofollow">磁力搜索</a>';
		$soushou.='/<a href="http://www.torrentkitty.com/search/'.$codetitle.'" target="_blank" rel="nofollow">torrentkitty</a>';
		$soushou.='/<a href="http://sosoyi.com/d?s='.$codetitle.'" target="_blank" rel="nofollow">搜搜易</a>]';

		$DH_output_content_page = str_replace('%soushou%',$soushou,$DH_output_content_page);
		
		$search='试试视频搜索:';
		$search.='<a href="http://video.soso.com/v?query='.$codetitle.'" target="_blank" rel="nofollow">[soso]</a>';
		$search.='/<a href="https://www.google.com.hk/search?tbm=vid&hl=zh-CN&source=hp&q='.$codetitle.'" target="_blank" rel="nofollow">[google]</a>';
		$search.='/<a href="http://www.soku.com/v?keyword='.$codetitle.'" target="_blank" rel="nofollow">[soku]</a>';
		$search.='/<a href="http://v.sogou.com/v?query='.$codetitle.'" target="_blank" rel="nofollow">[sogou]</a>';
		
		$search.=' 搜索视频网站:';
		
		$search.='<a href="http://so.v.360.cn/index.php?kw='.$codetitle.'" target="_blank" rel="nofollow">[v360]</a>';		
		$search.='/<a href="http://v.baidu.com/v?word='.$codetitlegbk.'" target="_blank" rel="nofollow">[vbaidu]</a>';
		$search.='/<a href="http://so.v.2345.com/search_'.$codetitle.'" target="_blank" rel="nofollow">[v2345]</a>';		
		
		$DH_output_content_page = str_replace('%search%',$search,$DH_output_content_page);
	
	
		$DH_output_content_page = str_replace('%downloadlinks%',$downloadlinks,$DH_output_content_page);
		$DH_output_content_page = str_replace('%tailer%',$tailer,$DH_output_content_page);		
		$DH_output_content_page = str_replace('%onlinelinks%',$onlinelinks,$DH_output_content_page);
	}	
	return $DH_output_content_page;
}

function dh_replace_link2($sql,$row,$DH_output_content)
{
	global $linktype,$linkway;
	$reslinks=dh_mysql_query($sql);
	$num = 5;
	
	if($reslinks)
	{
		$onlylinks='';
		$onlylinks_more='';
		$num3=0;
		$yingping='';
		$yingping_more='';
		$num4=0;
				
		while($rowlinks = mysql_fetch_array($reslinks))
		{
			$type = $rowlinks['linktype'];
	
			$updatetime = date("Ymd",strtotime($rowlinks['updatetime']));
			$linkseach = '<div class="listall"><div class="listlink2">%num% <a href = "'. $rowlinks['link'] . '" target = "_blank" title="'.$rowlinks['title'].' - '.$rowlinks['author'].'的链接" rel="nofollow">'.$rowlinks['title']. '</a></div><div class="lqc3 rt0v0"> '.$rowlinks['author'].' '.$updatetime.'</div></div>';
			if($type==1)//影视资讯
			{
				$num3++;			
				if($num3 > $num)
					$onlylinks_more .= str_replace('%num%',$num3,$linkseach);
				else
					$onlylinks .= str_replace('%num%',$num3,$linkseach);		
				continue;
			}
			if($type==2)//影视评论
			{
				$num4++;			
				if($num4 > $num)
					$yingping_more .= str_replace('%num%',$num4,$linkseach);
				else
					$yingping .= str_replace('%num%',$num4,$linkseach);		
				continue;
			}
		}		
		
		if($num3 > $num)
		{
			$showtext='[ << 展开(其余'.($num3-$num).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num3-$num).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num3-$num).'个) ∧ ]';		
			$onlylinks.='<div class="showhide" id="onlylinks_t"  onclick="showhide(\'onlylinks_t\',\'onlylinks\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="onlylinks" style="display:none;" class="showhide_more">'.$onlylinks_more.'<div onclick="hide(\'onlylinks_t\',\'onlylinks\',\''.$showtext.'\',\'title_4\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		if($num4 > $num)
		{
			$showtext='[ << 展开(其余'.($num4-$num).'个) >> ]';
			$hidetext='[ >> 隐藏(其余'.($num4-$num).'个) << ]';
			$hidetext2='[ ∧ 隐藏(以上'.($num4-$num).'个) ∧ ]';		
			$yingping.='<div class="showhide" id="pinglun_t"  onclick="showhide(\'pinglun_t\',\'pinglun\',\''.$showtext.'\',\''.$hidetext.'\');">'.$showtext.'</div><div id="pinglun" style="display:none;" style="display:none;" class="showhide_more">'.$yingping_more.'<div onclick="hide(\'pinglun_t\',\'pinglun\',\''.$showtext.'\',\'title_3\')" style="text-align:center">'.$hidetext2.'</div></div>';
		}
		
		$DH_output_content_page = str_replace('%onlylinks%',$onlylinks,$DH_output_content);
		$DH_output_content_page = str_replace('%yingping%',$yingping,$DH_output_content_page);
	}	
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
		$replacediv .= '<imgdao style="margin:5px" link_src="http://movie.douban.com/photos/photo/'.$simgurl.'/" img_src="http://img3.douban.com/view/photo/albumicon/public/p'.$simgurl.'.jpg" style="width:100px;height:100px" src_width="100px" src_height="100px" alt="'.$row['title'].'的剧照"><span></span></imgdao>';
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
	foreach ($celeimg_array as $celeimg_each)
	{
		$celeimg_all.=	'<li style="width:'.$width.'; height:'.$height.'"><a href="http://movie.douban.com/celebrity/'.$celeimg_each[0].'/" target="_blank" rel="nofollow"><img style="width:'.$width.'; height:'.$height.'" data-src="http://img3.douban.com/img/celebrity/medium/'.$celeimg_each[2].'" alt="'.$celeimg_each[1].'的影人图片" width="'.$width.'" height="'.$height.'"/><span class="celeimg_title">'.$celeimg_each[3].'</span></a><div class="celeimg_name">'.$celeimg_each[1].'</div></li>';
	}
	$celeimg_all = '<div id="pageClass"><ul>'.$celeimg_all.'</ul></div>';		
	//替换演员列表
	$DH_output_content_page = str_replace("%cpics%",$celeimg_all,$DH_output_content_page);	
	
	//豆瓣 时光 评分等
	$replace = '';
	$rating='';
	preg_match('/<r1>(.*?)<\/r1>/s',$row['ids'],$match);
	if(!empty($match[1]))
	{
		$rating = $match[1];
	}
	if($row['mediaid']!='')
		$replace.='<span style="font-size: 12px;"><a href="http://movie.douban.com/subject/'.$row['mediaid'].'" target="_blank" rel="nofollow">豆瓣:'.$rating.'</a></span>';
	
	$rating='';
	preg_match('/<r2>(.*?)<\/r2>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$rating = $match[1];
		
	$mtimeid='';
	preg_match('/<2>(.*?)<\/2>/s',$row['ids'],$match);
	if(!empty($match[1]))
	{
		$mtimeid=$match[1];
		$replace.='/<a href="http://www.m1905.com/mdb/film/'.$match[1].'/" target="_blank" rel="nofollow">m1905:'.$rating.'</a>';
	}
	$rating='';
	preg_match('/<r3>(.*?)<\/r3>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$rating = $match[1];
		
	preg_match('/<3>(.*?)<\/3>/s',$row['ids'],$match);
	if(!empty($match[1]))
		$replace.='/<a href="http://movie.mtime.com/'.$match[1].'/" target="_blank" rel="nofollow">时光网:'.$rating.'</a>';
	
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
	$updatetime = date("Ymd",strtotime($row['updatetime']));
	
	//出品公司
	$pubcompany='';
	preg_match('/<pc>(.*?)<\/pc>/s',$row['meta'],$match);
	if(!empty($match[1]))
	{	
		$pubcompany.='--['.$match[1].'出品]';
	}	
	$DH_output_content_page = str_replace("%pubcompany%",$pubcompany,$DH_output_content_page);	

	//处理购票
	$buyticket='';
	if($row['mstatus']==3)
	{
		if($row['mediaid']!='')
			$buyticket .='<a href="http://movie.douban.com/subject/'.$row['mediaid'].'/cinema/" target="_blank" rel="nofollow">豆瓣购票</a>';
			
		preg_match('/<4>(.*?)<\/4>/s',$row['ids'],$match1);
		//print_r($match1);
		if(!empty($match1[1]))
		{
			$buyticket.='/<a href="http://www.gewara.com/movie/'.$match1[1].'" target="_blank" rel="nofollow">格瓦拉</a>';
		}
		
		preg_match('/<5>(.*?)<\/5>/s',$row['ids'],$match1);
		//print_r($match1);
		if(!empty($match1[1]))
		{
			$buyticket.='/<a href="http://wangpiao.com/movie/'.$match1[1].'/?showulcinfo=1" target="_blank" rel="nofollow">网票网</a>';
		}	
	
		if($mtimeid!='')
			$buyticket .='/<a href="http://theater.mtime.com/movie/'.$mtimeid.'/" target="_blank" rel="nofollow">时光网购票</a>';
	}
	else
	{
		$buyticket='暂无购票信息';	
	}

	$ofsite='';
	preg_match('/<b>(.*?)<\/b>/s',$row['meta'],$match);
	if(!empty($match[1]))	
		$ofsite = ' <a href="'.$match[1].'" target="_blank" rel="nofollow">[官方网站]</a> </span>';
	$DH_output_content_page = str_replace("%ofsite%",$ofsite,$DH_output_content_page);	
		
	$DH_output_content_page = str_replace("%buyticket%",$buyticket,$DH_output_content_page);
	
	$DH_output_content_page = str_replace("%thisupdatetime%",' -- 最后更新时间: '.$updatetime,$DH_output_content_page);
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
			$way='['.$linkquality[$row['quality']].']';
	}
	$DH_output_content_page = str_replace("%way%",$way,$DH_output_content_page);
	
	$simgurl=$row['imgurl'];	
	$imgposter='';
	$page_path = output_page_path($DH_html_url,$row['id']);
	if($simgurl!='' && $simgurl[0]=='s')
	{
		$imgposter ='<a href="'.$page_path.'" target="_blank" title="'.$row['title'].'"><img style="width:'.$width.';height:'.$height.'" alt="'.$row['title'].'的海报" data-src="http://img3.douban.com/mpic/'.$simgurl.'" width="'.$width.'" height="'.$height.'"/></a>';
	}
	else
	{
		$imgposter = '<imgdao link_src="'.$page_path.'" img_src="http://img3.douban.com/view/photo/thumb/public/p'.$simgurl.'.jpg" style="witdh:'.$width.';height:'.$height.'" src_width="'.$width.'" src_height="'.$height.'" alt="'.$row['title'].'的海报"><span></span></imgdao>';
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
				list($name,$id) = split('[|]', $eachmatch);
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

function setshare($DH_output_content,$js)
{
	global $DH_home_url,$DH_input_path,$DH_html_path;
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