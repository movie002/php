<?php 
/* 使用示例 */   
//header('Content-Type:text/html;charset= UTF-8'); 
//require("../../config.php");
//require("../../curl.php");
//require("../common.php");
//
//$douban_input = new SearchInput();
//$douban_input->title='父亲的梦想';
//$douban_input->country='1';
//$douban_input->year='';
//$douban_input->type='1';
//$douban_result = new MovieResult();
//get_douban($douban_input,$douban_result);
//
//print_r($douban_result);

//处理电影名  
function get_douban($title,$country,$year,$type,&$resultlast)  
{ 
	//设置默认值
	echo $title.' '.$country.' '.$type.' '.$year."</br>\n";
	$titles=processtitle($title);
	//print_r($titles);
	$titlesearch = array();
	array_push($titlesearch,$titles[0]);
	if(count($titles)>1)
	{
		foreach($titles as $eachtitle)
			array_push($titlesearch,$eachtitle);
		$titlesearch[0]=implode('/',$titles);			
	}
	
	//print_r($titlesearch);
	$doubanresults=array();
	$maxrate=0;	
	foreach($titlesearch as $eachtitlesearch)
	{
		echo "</br>\n -----search-->".$eachtitlesearch .":</br>\n";
		$name = rawurlencode($eachtitlesearch);
		$buffer = get_file_curl('http://movie.douban.com/subject_search?search_text='.$name.'&cat=1002');
		//echo $buffer;
		if(false==$buffer)
		{
			echo $eachtitlesearch."搜索失败 </br>\n";
			continue;
		}
		preg_match_all("/<table width=\"100%\"[^>]*+>([^<]*+(?:(?!<\/?+table)<[^<]*+)*+)<\/table>/i",$buffer,$match);
		//print_r($match);
		
		foreach($match[1] as $key=>$list)
		{
			if($key>0)
				break;
			//需要每次sleep 2秒，以免豆瓣判断为爬虫
			$result = new MovieResult();
			//$result->init();
			getlist($result,$list);
			getdetail($result);	
			//print_r($result);
			//global $movietype,$moviecountry;
//			echo "\n  ---result".$key.' '.$result->title.' '.$result->aka.' '.$moviecountry[$result->country].' '.$movietype[$result->type].' '.$result->pubdate;	//需要比较是否符合
			echo "\n  ---result".$key.' '.$result->mediaid.' '.$result->title.' '.$result->aka.' '.$result->country.' '.$result->type.' '.$result->pubdate;	//需要比较是否符合
			//拼出一个综合akas
			if($result->aka=='')
				$akaall=$result->title;
			else
				$akaall=$result->title.'/'.$result->aka;
								
			if(!c_movietype($type,$result->type))
				continue;
			if(!c_moviecountry($country,$result->country))
				continue;
			if(!c_movieyear($year,$result->pubdate,2,$akaall))
				continue;
			$rate = c_title($title,$akaall);
				echo ' rate: '.$rate;
			if($rate>=2)
			{
				$resultlast = $result;
				return true;
			}						
			if($rate>$maxrate)
			{
				$maxrate = $rate;
				$resultlast = $result;
			}
		}
	}
	if($maxrate<=0)
		return false;
	else
		return true;
}

function getlist(&$result,$list)
{
	preg_match('/title="(.*?)"/',$list,$match2);
	$result->title = $match2[1];	
	//找出mediaid
	preg_match('/href="http:\/\/movie.douban.com\/subject\/(.*?)\/"/',$list,$match2);
	$result->mediaid = $match2[1];
	
	preg_match('/<img src="http:\/\/.*?.douban.com\/spic\/(.*?)"/',$list,$match2);
	if(!empty($match2[1]))
	{
		$result->imgurl = $match2[1];
	}
	else
	{
		preg_match('/<img src="http:\/\/img.*?\.douban.com\/view\/photo\/icon\/public\/p(.*?).jpg"/',$list,$match2);
		if(!empty($match2[1]))
		{
			$result->imgurl = $match2[1];
		}		
	}	
}

function getdetail(&$result)
{
	preg_match('/<1>(.*?)<\/1>/s',$result->ids,$match);
	if(empty($match[1]))
	{
		$result->ids .= '<1>'.$result->mediaid.'</1>';	
	}

	//访问网页，抽取预告片、剧照
	$buffer_2 = get_file_curl('http://movie.douban.com/subject/'.$result->mediaid.'/');
	if(false==$buffer_2)
	{
		echo "豆瓣页面访问失败\n";
	}
	//echo $buffer_2;
	$result->type = get_movietype('电影');
	$match = array();
	preg_match('/电视剧图片/',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[0]))
	{
		$result->type = get_movietype('电视剧');
	}

	preg_match('/property="v\:average">(.*?)</s',$buffer_2,$match2);
	//print_r($match2);
	if(!empty($match2[1]))
	{
		$rating=$match2[1];
		preg_match('/<span property="v\:votes">(.*?)<\/span>/s',$buffer_2,$match3);
		if(!empty($match3[1]))
		{
			$rating.='('.$match3[1].')';
		}
		$newr1 ='<r1>'.$rating.'</r1>';
		
		preg_match('/<r1>(.*?)<\/r1>/s',$result->ids,$match3);
		if(empty($match3[1]))
		{
			$result->ids .=$newr1;
		}
		else
		{
			$result->ids = preg_replace('/<r1>(.*?)<\/r1>/s',$newr1,$result->ids);;
		}		
		
	}	
	
	preg_match('/public\/p(.*?)\.jpg"/s',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
		$result->imgurl=$match[1];
	
	//得到电影图片
	$match = array();	
	preg_match_all('/<a href="http:\/\/movie.douban.com\/photos\/photo\/(.*?)\/"><img src="http:\/\/img3.douban.com\/view\/photo\/albumicon\/public\/.*?" alt="图片" \/><\/a>/',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $pic)
		{
			$result->simgurl.=$pic.'|';
		}
		$result->simgurl = substr($result->simgurl,0,strlen($result->simgurl)-1);
	}
	//别名
	$match = array();	
	preg_match('/<span property="v:itemreviewed">(.*?)<\/span>/',$buffer_2,$match);
	if(!empty($match[1]))
	{
		$akatitle = $match[1];
		delete_word($akatitle,$result->title);
		$result->aka = trim($akatitle);	
	}
	$match = array();
	preg_match('/<span class="pl">又名:<\/span>(.*?)<br\/>/',$buffer_2,$match);
	if(!empty($match[1]))
	{			
		$all_akas=preg_split("/[\/]+/", $match[1]);
		foreach ($all_akas as $key=>$eachaka)
			$all_akas[$key]=trim($eachaka);			
		//$runtime .=implode('/',$runtimes);
		if($result->aka=='')
			$result->aka .= implode('/',$all_akas);	
		else
			$result->aka .= '/'.implode('/',$all_akas);			
	}
	//imdb链接
	preg_match('/<m>(.*?)<\/m>/s',$result->ids,$match);
	if(empty($match[1]))
	{
		preg_match('/<span class="pl">IMDb链接:<\/span> <a href=".*?" target="_blank" rel="nofollow">(.*?)<\/a>/',$buffer_2,$match);
	    //print_r($match);
		if(!empty($match[1]))
		{
			$result->ids .= '<m>'.$match[1].'</m>';
		}		
	}

	//预告片
	$match = array();
	preg_match('/<a href="(.*?)">预告片([0-9]+)<\/a>/',$buffer_2,$match);
	//echo $buffer_2;
	//print_r($match);
	//$datenow=date("Y-m-d H:i:s");
	if(!empty($match[1]))
	{
		$result->doubantrail =  '《'.$result->title.'》豆瓣预告 ('.$match[2].'部)';
		$result->doubantrailurl = $match[1];
		//$title = $result->title.' 预告片('.$match[2].')';
		//$sql="insert into link(mediaid,author,title,link,quality,way,type,updatetime) values ('$result->mediaid','豆瓣预告','$title','$match[1]',2,3,7,'$datenow') ON DUPLICATE KEY UPDATE title='$title' , updatetime='$datenow'";
		//$sqlresult=dh_mysql_query($sql);
		//addorupdatelink($pageid,$author,$title,$link,$cat,$linkquality,$linkway,$linktype,$linkdownway,$updatetime,1);	
	}
	//全篇播放
	$match = array();
	preg_match('/<a class="complete_video_link" href=".*?url=(.*?)">点击播放<\/a>/',$buffer_2,$match);
	if(!empty($match[1]))
	{
		$result->doubansp =  '《'.$resultlast->title.'》豆瓣在线播放';
		$result->doubanspurl = $match[1];
		//$title = $result->title.' 优酷观看';
		//$sql="insert into link(mediaid,author,title,link,quality,way,type,updatetime) values ('$result->mediaid','豆瓣视频','$title','$match[1]',2,3,6,'$datenow') ON DUPLICATE KEY UPDATE title='$title' , updatetime='$datenow'";
		//$sqlresult=dh_mysql_query($sql);
	}
	//官方小站
	$match = array();
	preg_match('/http:\/\/site.douban.com\/([0-9]{1,10})\//',$buffer_2,$match);			
	if(!empty($match[1]))
	{
		$result->meta .= '<s>'.$match[1].'</s>';
	}
	//片长
	$match = array();
	preg_match('/<span property="v:runtime" content=".*?">(.*?)<\/span>(.*?)<br\/>/',$buffer_2,$match);			
	$runtime='';
	if(!empty($match[1]))
	{
		$runtime .= $match[1];
	}
	if(!empty($match[2]))
	{
		$runtimes=preg_split("/[\/]+/", $match[2]);
		foreach ($runtimes as $key=>$eachruntime)
			$runtimes[$key]=trim($eachruntime);			
		$runtime .=implode('/',$runtimes);
	}
	if($runtime!='')
		$result->meta .= '<i>'.$runtime.'</i>';
	//网站的网址
	$match = array();	
	preg_match('/官方网站:<\/span> <a href="(.*?)" rel="nofollow" target="_blank">/',$buffer_2,$match);		
	if(!empty($match[1]))
	{
		$result->meta .= '<b>'.$match[1].'</b>';
	}
	//剧情简介
	$match = array();
	preg_match('/<span property="v:summary".*?>(.*?)<\/span>/s',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{		
		$result->summary = '';
		$summarys=preg_split("/<br \/>/s", $match[1]);
		//print_r($summarys);
		foreach($summarys as $eachsummary)
		{
			$result->summary .= ftrim2(trim($eachsummary));
		}
		//精简为不大于256个字符
		if(mb_strlen($result->summary,'UTF-8')>255)
		{
			$result->summary = mb_substr($result->summary,0,252,'UTF-8');
			$result->summary .='...';
		}
	}
	//制片国家和地区
	$match = array();
	preg_match('/<span class="pl">制片国家\/地区:<\/span>(.*?)<br\/>/s',$buffer_2,$match);
	if(!empty($match[1]))
	{		
		$countrys=preg_split("/[\/]+/", $match[1]);
		$cresult='';
		foreach ($countrys as $key=>$country)
		{
			//以第一个为制片国家
			if($key==0)
			{
				$result->country = get_moviecountry($country);
			}
			$countrys[$key]=trim($country);
		}	
		$result->meta .='<g>'.implode("/",$countrys).'</g>';		
	}	
	//开始注入meta消息
	$match = array();	
	preg_match("/<span><span class='pl'>导演<\/span>: (.*?)<\/span>/s",$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{		
		$match2 = array();
		preg_match_all('/\/celebrity\/([0-9]{1,10})\/" rel="v:directedBy".*?>(.*?)<\/a>/',$buffer_2,$match2);		
		//print_r($match2);
		if(!empty($match2[1]))
		{
			$eachmatchs='';
			foreach($match2[1] as $key => $eachmatch)
			{
				if($key>1) break;
				$eachmatchs .= '['.$match2[2][$key].'|'.$eachmatch.']';
			}
			$result->meta .='<d>'.$eachmatchs.'</d>';
			insertcelebrity($eachmatchs);
		}		
	}
	
	$match = array();	
	preg_match("/<span><span class='pl'>主演<\/span>: (.*?)<\/span>/s",$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{		
		$match2 = array();$eachmatchs='';
		preg_match_all('/\/celebrity\/([0-9]{1,10})\/" rel=\"v:starring\".*?>(.*?)<\/a>/',$buffer_2,$match2);		
		//print_r($match2);
		if(!empty($match2[1]))
		{
			foreach($match2[1] as $key => $eachmatch)
			{
				if($key>5) break;
				$eachmatchs .= '['.$match2[2][$key].'|'.$eachmatch.']';
				
			}
			$result->meta .='<c>'.$eachmatchs.'</c>';
			//插入到cele表中
			insertcelebrity($eachmatchs);
		}		
	}	
	
	//上映日期
	$match = array();
	preg_match_all('/<span property="v:initialReleaseDate" content="(.*?)">.*?<\/span>/s',$buffer_2,$match);
	//print_r($match);
	//$result->pubdate =  date("Y-m-d H:i:s",strtotime('2038-01-19 03:14:07'));
	//$result->pubdate =  date("Y-m-d H:i:s");
	//print_r($result->pubdate);
	if(!empty($match[1]))
	{	
		$result->meta .= '<p>'.implode("/",$match[1]).'</p>';	
		$realdate=preg_replace('/\(.*?\)/','',$match[1][0]);
		$result->pubdate = date("Y-m-d H:i:s",strtotime($realdate));
	}
	else 
	{
		//如果是首播日期，就是电视剧
		preg_match('/<span class="pl">首播日期:<\/span> <span property="v:initialReleaseDate" content="(.*?)">(.*?)<\/span>(.*?)<br\/>/s',$buffer_2,$match);
		//print_r($match);
		if(!empty($match[1]))
		{	
			$pubdate=$match[2];
			$result->meta .= '<p>'.$pubdate.'</p>';
			$result->pubdate = date("Y-m-d H:i:s",strtotime($match[1]));
			$result->type = get_movietype('电视剧');
		}
		else //如果没有上映日期，定位3个月之前上映
		{
			//$result->pubdate = date('Y-m-d H:i:s',strtotime("-3 month"));
		}
	}
	//电影语言
	$match = array();
	preg_match('/<span class="pl">语言:<\/span>(.*?)<br\/>/',$buffer_2,$match);
	if(!empty($match[1]))
	{	
		$languages=explode('/',$match[1]);
		$langcount=count($languages);
		//print_r($languages);
		$languages = array_slice($languages,0,2);
		//print_r($languages);
		foreach($languages as $key=>$language)
			$languages[$key]=trim($language);
		$langlast=implode("/",$languages);
		if($langcount>2)
			$langlast.='...';
		$result->meta .='<l>'.$langlast.'</l>';
	}
	//电影类型
	$match = array();		
	preg_match_all('/<span property="v:genre">(.*?)<\/span>/',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{	
		$movie_type_all='<t>';
		foreach ($match[1] as $key=>$movie_type)
		{
			if($key>1)
				break;
			$movie_type_all .= $movie_type.'/';					
		}
		$movie_type_all = substr($movie_type_all,0,strlen($movie_type_all)-1);
		$movie_type_all .='</t>';
		$result->meta .= $movie_type_all;
		
		preg_match('/动画|动漫/',$movie_type_all,$match);
		//print_r($match);
		if(!empty($match[0]))
		{
			$result->type = get_movietype('动漫');
		}
	}
	//判断是否是综艺 
	preg_match('/<div class="tags-body">(.*?)<\/div>/s',$buffer_2,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		preg_match('/综艺/',$match[1],$match1);
		//print_r($match);
		if(!empty($match1[0]))
		{
			$result->type = get_movietype('综艺');
		}		
	}	
	
	
	//整理文字，以免数据库插入问题
	$result->meta = str_replace("\"","\\\"",$result->meta);
	$result->meta = str_replace("'","\\'",$result->meta);	
	$result->title = str_replace("\"","\\\"",$result->title);
	$result->title = str_replace("'","\\'",$result->title);
	$result->summary = str_replace("\"","\\\"",$result->summary);
	//豆瓣对summart自己做了过滤，不需要这里过滤了
	//$result->summary = str_replace("'","\\'",$result->summary);
	return $result; 	
}
?>  