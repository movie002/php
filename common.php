<?php
require("base.php");
require("filtetitle.php");
require("filter_quality.php");

function insertall($clinktype,$rssinfo)
{
	$linktype = testneed($clinktype,$rssinfo->link,$rssinfo->title,$rssinfo->cat);
	echo $linktype.' = ';
	switch($linktype)
	{
		case -1:
		case -2:
		{
			echo " error get ".$rssinfo->author." testneed </br>  \n";
			break;
		}
		case 0:
		{
			echo " no get ".$rssinfo->author." testneed </br>  \n";
			break;					
		}
		case 1://资讯
		case 3://评论
		case 5://综艺
		case 6://电影、电视
		case 8://动漫
		{
			insertlink($rssinfo,$linktype);
			break;
		}
		default:
		{
			//insertonlylink($rssinfo,$linktype);
			echo " no get type:".$linktype." in ".$rssinfo->author." testneed </br>  \n";
			break;
		}				
	}	
}

function insertonlylink($rssinfo)
{
	//清理title的空格
	$rssinfo->title = trim($rssinfo->title);
	//清理title中的"为'
	$rssinfo->title = str_replace('"','\'',$rssinfo->title);
	
	$datenow = date("Y-m-d H:i:s",strtotime("+1 days"));
	if($rssinfo->update > $datenow)
		$rssinfo->update = $datenow;
	$sql="insert into onlylink(author,title,link,cat,updatetime) values ('$rssinfo->author',\"$rssinfo->title\",'$rssinfo->link','$rssinfo->cat','$rssinfo->update') ON DUPLICATE KEY UPDATE author= '$rssinfo->author',title=\"$rssinfo->title\",cat='$rssinfo->cat',updatetime='$rssinfo->update'";
	$sqlresult=dh_mysql_query($sql);
	echo $rssinfo->title.' = '.$rssinfo->link.' = '.$rssinfo->cat." = ".$rssinfo->update." ->onlylink成功</br> \n";	
}

function insertlink($rssinfo,$linktype)
{
	//增加link
	$sql="insert into link(author,title,link,cat,linktype,lstatus,updatetime) values ('$rssinfo->author',\"$rssinfo->title\",'$rssinfo->link','$rssinfo->cat',$linktype,0,'$rssinfo->update') ON DUPLICATE KEY UPDATE cat='$rssinfo->cat',linktype=$linktype,updatetime='$rssinfo->update'";
	$sqlresult=dh_mysql_query($sql);
	echo $rssinfo->title.' = '.$rssinfo->link.' = '.$rssinfo->cat." -> link成功</br> \n";
}

function updatelink($title,$link,$contain)
{
	//提取名字等
	$result = new FilterResult();
	if($contain=='')
	{
		$result = filte_movie_name($result,$title);		
	}
	else
	{
		$result = get_movie_name($result,$title,$contain);
	}
	//增加link
	$sql="update link set ctitle=\"$result->end_name\", ccountry=$result->country,cyear=$result->year,cmovietype=$result->code,lstatus=1 where link='$link';";
	$sqlresult=dh_mysql_query($sql);	
	echo $title.' '.$link."--> 修改link数据库成功！</br> \n";		
}

function updatepage($douban_result,$updatetime)
{
	global $linkquality,$linkway,$linktype;
	$id1='';
	$id2='';
	$id3='';
	if($douban_result->mediaid!='')
	{
		$id1 .="mediaid,";
		$id2 .="'$douban_result->mediaid',";
		$id3 .="mediaid='$douban_result->mediaid',";
	}
//	if($douban_result->mtimeid!='')
//	{
//		$id1 .="mtimeid,";
//		$id2 .="'$douban_result->mtimeid',";
//		$id3 .="mtimeid='$douban_result->mtimeid',";
//	}
//	if($douban_result->m1905id!='')
//	{
//		$id1 .="m1905id,";
//		$id2 .="'$douban_result->m1905id',";
//		$id3 .="m1905id='$douban_result->m1905id',";
//	}	
	$sql="insert into page(".$id1."ids,title,aka,meta,summary,imgurl,simgurl,cattype,catcountry,pubdate,updatetime) values(".$id2."'$douban_result->ids',\"$douban_result->title\",\"$douban_result->aka\",'$douban_result->meta',\"$douban_result->summary\",'$douban_result->imgurl','$douban_result->simgurl',$douban_result->type,$douban_result->country,'$douban_result->pubdate','$updatetime') ON DUPLICATE KEY UPDATE ".$id3." ids='$douban_result->ids',title=\"$douban_result->title\",aka=\"$douban_result->aka\",meta='$douban_result->meta',summary=\"$douban_result->summary\",imgurl='$douban_result->imgurl',simgurl='$douban_result->simgurl',cattype=$douban_result->type,catcountry=$douban_result->country,pubdate='$douban_result->pubdate',updatetime='$updatetime'";
	$sqlresult=dh_mysql_query($sql);
}

function updatepage2($douban_result,$mstatus)
{
	global $linkquality,$linkway,$linktype;	
	$today = date("Y-m-d H:i:s");
	
	$id1='';
	$id2='';
	$id3='';
	if($douban_result->mediaid!='')
	{
		$id1 .="mediaid,";
		$id2 .="'$douban_result->mediaid',";
		$id3 .="mediaid='$douban_result->mediaid',";
	}
	//if($douban_result->mtimeid!='')
	//{
	//	$id1 .="mtimeid,";
	//	$id2 .="'$douban_result->mtimeid',";
	//	$id3 .="mtimeid='$douban_result->mtimeid',";
	//}
	//if($douban_result->m1905id!='')
	//{
	//	$id1 .="m1905id,";
	//	$id2 .="'$douban_result->m1905id',";
	//	$id3 .="m1905id='$douban_result->m1905id',";
	//}
	
	$sql="insert into page(".$id1."ids,title,aka,meta,summary,imgurl,simgurl,cattype,catcountry,pubdate,mstatus,updatetime) values(".$id2."'$douban_result->ids',\"$douban_result->title\",\"$douban_result->aka\",'$douban_result->meta',\"$douban_result->summary\",'$douban_result->imgurl','$douban_result->simgurl',$douban_result->type,$douban_result->country,'$douban_result->pubdate',$mstatus,'$today') ON DUPLICATE KEY UPDATE ".$id3." ids='$douban_result->ids',title=\"$douban_result->title\",aka=\"$douban_result->aka\",meta='$douban_result->meta',summary=\"$douban_result->summary\",imgurl='$douban_result->imgurl',simgurl='$douban_result->simgurl',cattype=$douban_result->type,catcountry=$douban_result->country,pubdate='$douban_result->pubdate',mstatus=$mstatus";
	$sqlresult=dh_mysql_query($sql);
}

function insertpagelink($douban_result,$updatetime,$mediaid)
{
	if($douban_result->doubantrail!='')
	{
		$sql="insert into link(author,title,link,cat,linktype,linkway,lstatus,updatetime) values ('豆瓣预告',\"$douban_result->doubantrail\",'$douban_result->doubantrailurl','预告',3,2,0,'$updatetime') ON DUPLICATE KEY UPDATE title=\"$douban_result->doubantrail\",updatetime='$updatetime'";
		$sqlresult=dh_mysql_query($sql);
		$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->doubantrailurl'" ;
		$sqlresult=dh_mysql_query($sql);
	}
	if($douban_result->doubansp!='')
	{
		$sql="insert into link(author,title,link,cat,linktype,linkway,lstatus,updatetime) values ('豆瓣播放',\"$douban_result->doubansp\",'$douban_result->doubanspurl','在线',3,4,0,'$updatetime') ON DUPLICATE KEY UPDATE title=\"$douban_result->doubansp\",updatetime='$updatetime'";
		$sqlresult=dh_mysql_query($sql);
		$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->doubanspurl'" ;
		$sqlresult=dh_mysql_query($sql);
	}
	if($douban_result->m1905trail!='')
	{
		$sql="insert into link(author,title,link,cat,linktype,linkway,lstatus,updatetime) values ('m1905预告',\"$douban_result->m1905trail\",'$douban_result->m1905trailurl','在线',3,2,0,'$updatetime') ON DUPLICATE KEY UPDATE title=\"$douban_result->m1905trail\",updatetime='$updatetime'";
		$sqlresult=dh_mysql_query($sql);
		$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->m1905trailurl'" ;
		$sqlresult=dh_mysql_query($sql);
	}
	if($douban_result->m1905sp!='')
	{
		$sql="insert into link(author,title,link,cat,linktype,linkway,lstatus,updatetime) values ('m1905视频花絮',\"$douban_result->m1905sp\",'$douban_result->m1905spurl','在线',3,2,0,'$updatetime') ON DUPLICATE KEY UPDATE title=\"$douban_result->m1905sp\",updatetime='$updatetime'";
		$sqlresult=dh_mysql_query($sql);
		$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->m1905spurl'" ;
		$sqlresult=dh_mysql_query($sql);
	}
	if($douban_result->mtimetrail!='')
	{
		$sql="insert into link(author,title,link,cat,linktype,linkway,lstatus,updatetime) values ('时光网预告',\"$douban_result->mtimetrail\",'$douban_result->mtimetrailurl','在线',3,2,0,'$updatetime') ON DUPLICATE KEY UPDATE title=\"$douban_result->mtimetrail\",updatetime='$updatetime'";
		$sqlresult=dh_mysql_query($sql);
		$sql = "update link set pageid = (select id from page where mediaid='$mediaid') where link = '$douban_result->mtimetrailurl'" ;
		$sqlresult=dh_mysql_query($sql);
	}
}

function update360link($resultlast,$author,$url,$title,$quality,$pageid=-1)
{
	if($pageid==-1)
	{
		$sql="insert into link(author,title,link,linkway,linkquality,updatetime) values('$author',\"$title\",'$url',4,$quality,'$resultlast->updatetime')ON DUPLICATE KEY UPDATE title=\"$title\",linkquality=$quality,updatetime='$resultlast->updatetime'";
		echo $sql."</br>\n";
		$sqlresult=dh_mysql_query($sql);		
		$sql="update link set pageid = (select id from page where mediaid='$resultlast->mediaid') where link = '$url' and pageid is NULL" ;
		$sqlresult=dh_mysql_query($sql);
	}
	else
	{
		$sql="insert into link(pageid,author,title,link,linkway,linkquality,updatetime) values($pageid,'$author',\"$title\",'$url',4,$quality,'$resultlast->updatetime')ON DUPLICATE KEY UPDATE title=\"$title\",updatetime='$resultlast->updatetime'";
		echo $sql."</br>\n";
		$sqlresult=dh_mysql_query($sql);		
	}	
}

//从已知pageid或者mediaid插入link,不需要后续的处理
function insertsiteslink($updatetime,$mediaid,$author,$title,$url,$way,$type,$quality,$onlinetype,$downtype,$property,$pageid=-1)
{
	if($pageid==-1)
	{
		$sql="insert into link(author,title,link,linkway,linktype,linkquality,linkonlinetype,linkdowntype,linkproperty,updatetime) values('$author',\"$title\",'$url',$way,$type,$quality,$onlinetype,$downtype,$property,'$updatetime')ON DUPLICATE KEY UPDATE author='$author',title=\"$title\",linkway=$way,linktype=$type,linkquality=$quality,linkonlinetype=$onlinetype,linkdowntype=$downtype,linkproperty=$property,updatetime='$updatetime'";
		echo $sql."</br>\n";
		$sqlresult=dh_mysql_query($sql);		
		$sql="update link set pageid = (select id from page where mediaid='$mediaid') where link = '$url' and pageid is NULL" ;
		$sqlresult=dh_mysql_query($sql);
	}
	else
	{
		$sql="insert into link(pageid,author,title,link,linkway,linktype,linkquality,linkonlinetype,linkdowntype,linkproperty,updatetime) values($pageid,'$author',\"$title\",'$url',$way,$type,$quality,$onlinetype,$downtype,$property,'$updatetime')ON DUPLICATE KEY UPDATE author='$author',title=\"$title\",linkway=$way,linktype=$type,linkquality=$quality,linkonlinetype=$onlinetype,linkdowntype=$downtype,linkproperty=$property,updatetime='$updatetime'";
		echo $sql."</br>\n";
		$sqlresult=dh_mysql_query($sql);		
	}	
}

//判断电影的性质
function testneed($need,$link,$title,$cat)
{
	$ret = 0;
	if($need==''||$need==null)
		return $ret;
	preg_match_all('/<c>(.*?)<\/c>/',$need,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $contain)
		{
			list($case,$pat,$ret) = split('[%]', $contain);
			//echo $case.' ' .$pat.' '.$ret ." </br> \n";
			$match1 = array();
			switch ($case)
			{
				case 'c':
				{
					//echo 'c';
					preg_match('/'.$pat.'/i',$cat,$match1);
					break;
				}
				case 't':
				{
					//echo 't';
					preg_match('/'.$pat.'/i',$title,$match1);
					break;
				}
				case 'l':
				{
					//echo 'l';
					preg_match('/'.$pat.'/i',$link,$match1);
					break;
				}
				case 'q':
				{
					switch ($pat)
					{
						case 'c':
						{
							$filterret = filter_quality_each($cat);							
							if($filterret != -1 )
							{
								$ret = $filterret;
								$match1 = array('ok');
							}
							break;
						}					
						case 'l':
						{
							$filterret = filter_quality_each($link);							
							if($filterret != -1 )
							{
								$ret = $filterret;
								$match1 = array('ok');
							}
							break;
						}					
						case 't':
						{
							$filterret = filter_quality_each($title);							
							if($filterret != -1 )
							{
								$ret = $filterret;
								$match1 = array('ok');
							}
							break;
						}
						case 's':
						{
							$filterret = filter_quality_size($title);							
							if($filterret != -1 )
							{
								$ret = $filterret;
								$match1 = array('ok');
							}
							break;
						}
						default:
							echo "error in testneed to get x method </br> \n";
					}
					break;
				}				
				default:
				{
					echo 'error in testneed to get ?? method';
					return -1;
				}
			}
			//print_r($match1);
			if(!empty($match1))
			{
				return $ret;
			}
		}
	}
	return $ret;
}

//提取标题
function testtitle($ctitle,$title)
{
	$result = '';
	$ltitle = $title;
	if($ctitle==''||$ctitle==null)
		return $title;
	preg_match_all('/<c>(.*?)<\/c>/',$ctitle,$match);
	//print_r($match);	
	if(!empty($match[1]))
	{
		foreach ($match[1] as $pattern)
		{
			//list($case,$pat) = split('[%]',$pattern);
			list($case,$pat,$ret) = split('[%]',$pattern);
			//echo $case.' ' .$pat ." </br> \n";
			switch ($case)
			{
				case 'g':
				{
					//echo 'g';
					preg_match('/'.$pat.'/',$ltitle,$match1);
					//print_r($match1);
					if(!empty($match1[1]))
					{
						$result .= $match1[1].'/';
						//$ltitle = preg_replace('/'.$pat.'/','',$ltitle);
						//echo '***'.$ltitle.'***';
					}
					else
					{
						if($ret==0)
						{
							echo " error in testtitle !</br>\n";
							return -1;
						}
						//return -1;
					}
					break;
				}
				case 'f':
				{
					//echo 'f';
					$ltitle = preg_replace('/'.$pat.'/i','',$ltitle);				
					break;
				}
				case 'x':
				{
					return filter_ed2000($title);
				}					
				default:
				{
					echo 'error in testtitle';
					return -1;
				}
			}	
		}
		if($result=='')
		{
			//分割
			//$titles=preg_split("/[\/]+/s", $ltitle);	
			//print_r($titles);
			//return $titles[0];
			return $ltitle;
		}
		else
		{
			//$results=preg_split("/[\/]+/", $result);
			//print_r($results);
			//return $results[0];
			return substr($result,0,strlen($result)-1);
		}
	}
	return -2;
}

function trimtitle($ctitle)
{
	//preg_match('/第[一二三四五六七八九十]+(季|部|集|话)$/s',$ctitle,$match);
	//print_r($match);
	
	$ctitle = preg_replace('/(上集|下集|续集)$/','',$ctitle);	
	$ctitle = preg_replace('/第[零一二三四五六七八九十]+(季|部|集|话|回)$/s','',$ctitle);
	$ctitle = preg_replace('/[零一二三四五六七八九十]+(季|部|集|话)$/s','',$ctitle);
	$ctitle = preg_replace('/第[0-9]+(季|部|集|话)$/s','',$ctitle);
	$ctitle = preg_replace('/[0-9]+(季|部|集|话)$/s','',$ctitle);
	$ctitle = preg_replace('/(\(.*?\))$/','',$ctitle);
	$ctitle = preg_replace('/(（.*?）)$/','',$ctitle);
	$ctitle = preg_replace('/(「.*?」)$/','',$ctitle);	
	$ctitle = preg_replace('/(【.*?】)$/','',$ctitle);
	$ctitle = preg_replace('/(\[.*?\])$/','',$ctitle);	
	$ctitle = preg_replace('/(电影版|剧场版)/s','',$ctitle);
	$ctitle = preg_replace('/完结$/','',$ctitle);
	$ctitle = preg_replace('/(\,|\!|，|。|！|\·|\.)$/s','',$ctitle);
	$ctitle1 = preg_replace('/(19|20|18)[0-9]{2,2}$/','',$ctitle);
	
	if(trim($ctitle1)!='')
		$ctitle = $ctitle1;
		
//	$ctitle = preg_replace('/^第([^<>]{1,4})(季|部)/s','',$ctitle);
	$ctitle = preg_replace('/^(\(.*?\))/','',$ctitle);	
	$ctitle = preg_replace('/^(（.*?）)/','',$ctitle);
	$ctitle = preg_replace('/^(电影版|剧场版)/i','',$ctitle);	
//	$ctitle = preg_replace('/^完结/','',$ctitle);	
//	$ctitle = preg_replace('/^(上集|下集)/','',$ctitle);
	$ctitle1 = preg_replace('/^(19|20|18)[0-9]{2,2}/','',$ctitle);
	if(trim($ctitle1)!='')
		$ctitle = $ctitle1;
	
	$ctitle = trim($ctitle);
	return $ctitle;
}

//function filtertitle($ctitle)
//{
//	//再进行仔细判断，仔细判断标题的一致性
//	//去除 第几季，第几部，但是在后面比较的时候，需要从新加回来	
//	$ctitle = preg_replace('/第([^<>]){1,4}(季|部)/s','',$ctitle);
//	$ctitle = preg_replace('/Season [0-9]{1,4}/i','',$ctitle);
//	//去除(港) (台) 等
//	$ctitle = preg_replace('/(\(.*?\))/i','',$ctitle);
//	//去除（.*?\） 等
//	$ctitle = preg_replace('/(（.*?）)/','',$ctitle);	
//	//去除电影版，剧场版
//	$ctitle = preg_replace('/(电影版|剧场版)/i','',$ctitle);
//	$ctitle = preg_replace('/完结/i','',$ctitle);
//	//$ctitle = preg_replace('/,|!|，|。|！|·|./','',$ctitle);
//	$ctitle = preg_replace('/(\,|\!|，|。|！|\·|\.|\?)/','',$ctitle);	
//	//去除 ,
//	//$ctitle = preg_replace('/,|·/i','',$ctitle);
//	//去除 上集 下集
//	$ctitle = preg_replace('/(上集|下集)/','',$ctitle);
//	//去除1
//	//$ctitle = preg_replace('/1|一/i','',$ctitle);
//	//去除年份
//	$ctitle1 = preg_replace('/(19|20|18)[0-9]{2,2}/','',$ctitle);
//	if(trim($ctitle1)!='')
//		$ctitle = $ctitle1;
//	return $ctitle;
//}

function filtertitle($ctitle)
{
	$del_word=array('预告片','先行','剧场版','电影版','完整版','完结','收藏版','动画版','未删减','标准','&amp;','&','!','！','·',',','，',':','：','+');
	$qulity=array('高清','蓝光','720p','1080p','DVD','1280','1024','mkv','MKV','枪版','抢先','TS','BD','HD','RMVB','AVI','avi','迅雷','下载','BT','MP4','mp4');
	foreach ($del_word as $eachlist)
		$ctitle = str_replace($eachlist,"-",$ctitle);
		
	foreach ($qulity as $eachlist)
		$ctitle = str_replace($eachlist,"(",$ctitle);
		
	$ctitle = str_replace('Ⅱ','2',$ctitle);
	$ctitle = str_replace('II','2',$ctitle);	
	$ctitle = str_replace('—','-',$ctitle);	
	return $ctitle;
}

//找出搜寻的字段
function processtitle($ctitle)
{
	$ctitle=filtertitle($ctitle);
	$rettitles = array();
	insertarray($rettitles,$ctitle);
	//print_r($rettitles);
	//return $rettitles;
	
	$titles=preg_split("/[\/]+/s",$ctitle);	
	$rettitles = array();
	foreach($titles as $key=>$eachtitle)
	{
		//取两个
		if($key>1)
			break;
		$eachtitle = trimtitle($eachtitle);
		array_push($rettitles,$eachtitle);	
	}
	return $rettitles;	
}

//严格拆封，只对/拆分.用于比较两者是否相同
function processtitle1($ctitle)
{
	$ctitle=filtertitle($ctitle);
	$titles=preg_split("/[\/]+/s",$ctitle);	
	$rettitles = array();
	foreach($titles as $key=>$eachtitle)
	{
		$eachtitle = trimtitle($eachtitle);
		array_push($rettitles,$eachtitle);	
	}
	return $rettitles;
}

//不严格拆封 对:也拆分,比较细的拆分,且不能替换中间。用于数据库查找
function processtitledb($ctitle)
{
//	$ctitle=filtertitle($ctitle);
	//如果将:拆封之后没有找到，说明不对	
	$rettitles = array();
	$titles=preg_split("/[\/]+/s",$ctitle);
	//print_r($titles);
	foreach($titles as $key=>$eachtitle)
	{
		//只判断两个标题即可
		if($key>1)
			break;
		//去除头尾空格
		$eachtitle = trimtitle($eachtitle);
		$eachtitles= preg_split("/:|：|\,|\!|！/",$eachtitle);
		//print_r($eachtitles);
		//只取前面部分
		$eacheachtitles = trim($eachtitles[0]);
		if($eacheachtitles!='')
			array_push($rettitles,$eacheachtitles);
		 
		// foreach($eachtitles as $eacheachtitles)
		// {
			// $eacheachtitles = trim($eacheachtitles);
			// if($eacheachtitles!='')
				// array_push($rettitles,$eacheachtitles);
		// }		
	}
	return $rettitles;
}

function processtitlesearch($ctitle)
{
	$rettitles = array();
	$titles=preg_split("/[\/]+/s",$ctitle);
	//print_r($titles);
	foreach($titles as $key=>$eachtitle)
	{
		//只判断两个标题即可
		if($key>1)
			break;
		//去除头尾空格
		$eachtitle = trimtitle($eachtitle);
		$eachtitles= preg_replace("/:|：|\,|\!|！/",$eachtitle);
		array_push($rettitles,$eachtitles);		
	}
	return $rettitles;
}

//强比较
function compare($ctitle,$aka,$movietype1,$movietype2,$moviecountry1,$moviecountry2,$movieyear1,$movieyear2)
{
	if(!comparemust($movietype1,$movietype2,$moviecountry1,$moviecountry2,$movieyear1,$movieyear2,2))
		return false;
	//得到标题的分离
	//如果有第几季，第几季一定要在aka中出现
//	preg_match('/第[^<>]{1,4}季|部/',$ctitle,$match);
//	if(!empty($match[0]))
//	{
//		if(strstr($aka,$match[0])===false)
//		{
//			echo 'aka no '.$match[0];
//			return false;
//		}
//	}
	//print_r($match);
	//如果每个标题都有在aka中，说明正确
	//否则，需要一个标题完整出现在akas中
	$titletest=true;
	//$ctitle=filtertitle($ctitle);
	$ctitles = processtitledb($ctitle);
	$aka1 = filtertitle($aka);
	//print_r($ctitles);
	echo ' >> aka1: '.$aka1;
	foreach($ctitles as $eachtitle)
	{
		echo ' each title '.$eachtitle .' ';
	    //echo ' strstr: '.strstr($aka1,$eachtitle).';';		
		if(strstr($aka1,$eachtitle)===false)
		{
			$titletest = false;
		}
	}
	
	if(!$titletest)
	{
		echo 'akas 不包含所有的titles, 第一次比较结果是 false | ';
		//$ctitles = processtitle0($ctitle);
		//print_r($ctitles);		
		$akas =  processtitle1($aka);
		print_r($akas);
		print_r($ctitles);
		foreach($ctitles as $eachtitle)
		{
			//echo '***'.array_search($eachtitle,$akas).'***';
			$seachres=array_search($eachtitle,$akas);
			if (!($seachres===false))
			{	
				echo $eachtitle.' in akas';
				return true;
			}
		}
		echo '没有一个 title 准确出现在akas中';
		return false;
	}
	echo ' 第一次比较结果是 true';
	return true;
}

function c_title($title,$aka)
{
	$rate=0;
	$titles=processtitle($title);
	if(empty($titles))
	{
		echo 'titles is empty!';
		return $rate;
	}
	$akas=processtitle($aka);
	if(empty($akas))
	{
		echo 'akas is empty!';
		return $rate;
	}
	
	foreach($titles as $eachtitle)
	{
		echo ' each title '.$eachtitle .' ';	
		if(!(strstr($aka,$eachtitle)===false))
		{
			$seachres=array_search($eachtitle,$akas);
			if (!($seachres===false))
			{	
				echo $eachtitle.' in akas +1, ';
				$rate +=1;
			}
			else
			{
				echo $eachtitle.' in akas part +0.5,';
				$rate +=0.5;
			}
		}
	}
	foreach($akas as $eachaka)
	{
		echo ' // each aka '.$eachaka .' ';	
		if(!(strstr($title,$eachaka)===false))
		{
			$seachres=array_search($eachaka,$titles);
			if (!($seachres===false))
			{	
				echo $eachaka.' in akas +1, ';
				$rate +=1;
			}
			else
			{
				echo $eachaka.'  in akas part +0.5, ';
				$rate +=0.5;
			}
		}
	}	
	return 	$rate;
}

//弱比较
function compare2($ctitle,$aka,$movietype1,$movietype2,$moviecountry1,$moviecountry2,$movieyear1,$movieyear2)
{
	if(!comparemust($movietype1,$movietype2,$moviecountry1,$moviecountry2,$movieyear1,$movieyear2,3))
		return false;
	
	//只要有一个标题符合，就符合！
	$ctitles = processtitle0($ctitle);
	$aka1 = filtertitle($aka);	
	foreach($ctitles as $key=>$eachtitle)
	{
		//比较之前，两者都去除标点符号等
		//寻找，只要包含在akas中，即可	
		if(!(strstr($aka1,$eachtitle)===false))
		{
			echo $eachtitle.' is ok ';
			return true;
		}
		else
			echo $eachtitle.' no contain ! next-- ';
	}
	return false;
}

function c_movietype($movietype1,$movietype2)
{
	//movietype需要一致性
	if($movietype1>0)
	{
		//电影一定要和电影对应
		if($movietype1==1 && $movietype2!=1)
		{
			echo ' movietype diff ';
			return false;			
		}
		if($movietype1!=1 && $movietype2==1)
		{
			echo ' movietype diff ';
			return false;			
		}
		//电视剧 综艺 动漫 都属于电视剧
	}
	return true;
}

function c_moviecountry($moviecountry1,$moviecountry2)
{
	//moviecountry需要一致性
	if($moviecountry1>0)
	{
		//2 表示 欧美，4表示其他,这两者可以通用
		if(($moviecountry1==2 && $moviecountry2==4) || ($moviecountry1==4 && $moviecountry2==2))
		{
			//这种情况是允许的
		}
		else if($moviecountry1!=$moviecountry2)
		{
			echo ' moviecountry diff ';
			return false;
		}
	}
	return true;
}

function c_movieyear($movieyear1,$movieyear2,$years,$aka)
{
	if($movieyear1<=0)
		return true;
	if($movieyear2 ==='1970-01-01')
		return true;
	//如果akas含有year，则不需要比较year
	if(strstr($aka,$movieyear1)===false)
	{
		//两个year必须在合理的范围之内		
		$movieyear1int = date("Y",strtotime($movieyear1));		
		$movieyear2int = date("Y",strtotime($movieyear2));
		$thisyear= date("Y");
		$maxyear = $thisyear+3;//电影提前三年公布
		$minyear = 1905;
		if($movieyear1>=$minyear && $movieyear1<=$maxyear)
		{
			if($movieyear2int>=$minyear && $movieyear2int<=$maxyear)
			{
				$diff = $movieyear1int-$movieyear2int;					
				if ($diff>$years||$diff<-$years)
				{
					echo ' year diff:'.$diff;
					return false;						
				}
			}
		}
	}
	return true;
}

function comparemust($movietype1,$movietype2,$moviecountry1,$moviecountry2,$movieyear1,$movieyear2,$years,$aka)
{
	//年份、国家、类型必须要按照规定
	if($movieyear1>0)
	{
		//如果akas含有year，则不需要比较year
		if(strstr($aka,$movietype1)===false)
		{
			//两个year必须在合理的范围之内					
			$movieyear2int = date("Y",strtotime($movieyear2));
			$thisyear= date("Y");
			$maxyear = $thisyear+3;//电影提前三年公布
			$minyear = 1905;
			if($movieyear1>=$minyear && $movieyear1<=$maxyear)
			{
				if($movieyear2int>=$minyear && $movieyear2int<=$maxyear)
				{
					$diff = $movieyear1-$movieyear2int;					
					if ($diff>$years||$diff<-$years)
					{
						echo ' year diff:'.$diff;
						return false;						
					}
				}
			}
		}	
	}
	//movietype需要一致性
	if($movietype1>0)
	{
		//电影一定要和电影对应
		if($movietype1==1 && $movietype2!=1)
		{
			echo ' movietype diff ';
			return false;			
		}
		if($movietype1!=1 && $movietype2==1)
		{
			echo ' movietype diff ';
			return false;			
		}
		//电视剧 综艺 动漫 都属于电视剧
	}
	//moviecountry需要一致性
	if($moviecountry1>0)
	{
		//2 表示 欧美，4表示其他,这两者可以通用
		if(($moviecountry1==2 && $moviecountry2==4) || ($moviecountry1==4 && $moviecountry2==2))
		{
			//这种情况是允许的
		}
		else if($moviecountry1!=$moviecountry2)
		{
			echo ' moviecountry diff ';
			return false;
		}
	}
	return true;
}
?>