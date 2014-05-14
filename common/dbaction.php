<?php
function dh_mysql_query($sql)
{
	$rs = mysql_query($sql);
	$mysql_error = mysql_error();
	if($mysql_error)
	{
		echo 'dh_mysql_query error info:'.$mysql_error.'</br>';
		echo $sql;
		return null;
	}
	return $rs;
}

function insertcelebrity($celebritys)
{
	preg_match_all('/\[(.*?)\]/s',$celebritys,$match);
//	print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $celebrity)
		{
			list($name, $id) = split('[|]', $celebrity);
			$name=trim($name);
			$id=trim($id);
			$name = str_replace("'","\'",$name);
			$sql="insert into celebrity(id,name) values('$id','$name') ON DUPLICATE KEY UPDATE name = '$name',fail=0";
			$sqlresult=dh_mysql_query($sql);
		}
	}	
}

function setupdatetime($change,$newdate,$authorid)
{
	if($change)
	{
		$sql="update author set updatetime='$newdate' where id = $authorid;";
		echo $sql."</br>\n";
		$result=dh_mysql_query($sql);		
	}
}

function setupdatetime2($change,$newdate,$authorname)
{
	if($change)
	{
		$sql="update author set updatetime='$newdate' where name = '$authorname';";
		echo $sql."</br>\n";
		$result=dh_mysql_query($sql);		
	}
}

function insertonlylink($rssinfo)
{
	return insertonlylink2($rssinfo->author,$rssinfo->title,$rssinfo->link,$rssinfo->cat,$rssinfo->update);
}

function insertonlylink2($author,$title,$link,$cat,$update)
{
	//清理title的空格
	$title = trim($title);
	//清理title中的'为"
	$title = str_replace('\'','\'',$title);
	$title = str_replace('"','\"',$title);
	
	$datenow = date("Y-m-d H:i:s",strtotime("+1 days"));
	if($update > $datenow)
		$update = $datenow;
		
	$sql="insert into onlylink(author,title,link,cat,updatetime) values ('$author','$title','$link','$cat','$update') ON DUPLICATE KEY UPDATE author= '$author',title='$title',cat='$cat',updatetime='$update'";
	$sqlresult=dh_mysql_query($sql);
	echo $title.' = '.$link.' = '.$cat." = ".$update." ->插入onlylink成功</br> \n";	
}

function addorupdateonlylink($author,$title,$link,$cat,$updatetime,$mtitle,$moviecountry,$movieyear,$movietype)
{
	//清理title的空格
	$title = trim($title);
	//清理title中的'和"
	$title = str_replace('\'','\'',$title);
	$title = str_replace('"','\"',$title);
	
	$datenow = date("Y-m-d H:i:s",strtotime("+1 days"));
	if($updatetime > $datenow)
		$updatetime = $datenow;

	$sql="insert into onlylink(author,title,link,cat,mtitle,moviecountry,movieyear,movietype,updatetime) values('$author','$title','$link','$cat','$mtitle',$moviecountry,$movieyear,$movietype,'$updatetime')ON DUPLICATE KEY UPDATE mtitle='$mtitle',moviecountry=$moviecountry,movieyear='$movieyear',movietype=$movietype,updatetime='$updatetime'";
	echo $sql."</br>\n";
	$sqlresult=dh_mysql_query($sql);

	//global $movietype,$moviecountry,$linkquality,$linkway,$linktype,$linkdownway;
	//echo $movietype[$movietype].'|'.$moviecountry[$moviecountry].$movieyear.'|'.$mtitle." >> ".$title.'|'.$link.'|'.$cat.'|'.$updatetime." -> 插入onlylink成功！</br> \n";
}

function addorupdatelink($pageid,$author,$title,$link,$cat,$linkquality,$linkway,$linktype,$linkdownway,$updatetime,$input=null)
{
	if($input==null)
		$sql="insert into link(pageid,author,title,link,cat,linkquality,linkway,linktype,linkdownway,updatetime) values($pageid,'$author','$title','$link','$cat',$linkquality,$linkway,$linktype,$linkdownway,'$updatetime')ON DUPLICATE KEY UPDATE pageid=$pageid,linkquality=$linkquality,linkway=$linkway,linktype=$linktype,linkdownway=$linkdownway,updatetime='$updatetime',remove=null";
	else
		$sql="insert into link(pageid,author,title,link,cat,linkquality,linkway,linktype,linkdownway,updatetime,input) values($pageid,'$author','$title','$link','$cat',$linkquality,$linkway,$linktype,$linkdownway,'$updatetime','$input')ON DUPLICATE KEY UPDATE pageid=$pageid,linkquality=$linkquality,linkway=$linkway,linktype=$linktype,linkdownway=$linkdownway,updatetime='$updatetime',input='$input',remove=null";
	echo $sql."</br>\n";
	$sqlresult=dh_mysql_query($sql);
}


function insertlink($rssinfo,$linktype)
{
	//增加link
	$sql="insert into link(author,title,link,cat,linktype,,updatetime) values ('$rssinfo->author',\"$rssinfo->title\",'$rssinfo->link','$rssinfo->cat',$linktype,0,'$rssinfo->update') ON DUPLICATE KEY UPDATE cat='$rssinfo->cat',linktype=$linktype,updatetime='$rssinfo->update'";
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
	echo $sql;
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
	echo $sql;
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

function getdbpageid($title,$mtitle,$country,$year,$type,$maxrate)
{
	//global $movietype,$moviecountry;
	$ctitles = processtitle($mtitle);
	//print_r($ctitles);
	//continue;
	//主标题和副标题都需要出现在page的title或者aka中
	//$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where title like '%$mtitle%' or aka like '%$mtitle%' order by updatetime desc;";
	$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where false ";
	foreach($ctitles as $ctitle)
		$sqlpage .= " or title like '%$ctitle%'";
	$sqlpage .= 'order by pubdate desc limit 0,4';
	
	//echo $sqlpage;
	echo $title.' : '.$mtitle;
	$resultspage=dh_mysql_query($sqlpage);
	$pageid=getdbpageid_com($sqlpage,$title,$mtitle,$country,$year,$type,$maxrate);
	if($pageid>-1)
		return $pageid;
		
	$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where false ";
	foreach($ctitles as $ctitle)
		$sqlpage .= " or aka like '%$ctitle%' ";
	$sqlpage .= 'order by pubdate desc limit 0,4';
	$pageid=getdbpageid_com($sqlpage,$title,$mtitle,$country,$year,$type,$maxrate);
	if($pageid>-1)
		return $pageid;
	return -1;
}

function getdbpageid_com($sqlpage,$title,$mtitle,$country,$year,$type,$maxrate)
{
	$pageid=-1;
	$resultspage=dh_mysql_query($sqlpage);
	while($rowpage = mysql_fetch_array($resultspage))
	{	
		echo "</br>\n -> ".$rowpage['id'].' '.$rowpage['title'].' '.$rowpage['aka'].' '.$rowpage['cattype'].' '.$rowpage['catcountry'].' '.$rowpage['pubdate'];
		//拼出一个综合akas
		if($rowpage['aka']=='')
			$akaall=$rowpage['title'];
		else
			$akaall=$rowpage['title'].'/'.$rowpage['aka'];					
		if(!c_movietype($type,$rowpage['cattype']))
			continue;
		if(!c_moviecountry($country,$rowpage['catcountry']))
			continue;
		if(!c_movieyear($year,$rowpage['pubdate'],2,$akaall))
			continue;
		$rate = c_title($mtitle,$akaall);
		$rate += c_title_com($title,$akaall);
		echo ' rate: '.$rate;
		if($rate>=3)
		{
			$pageid=$rowpage['id'];	
			break;
		}						
		if($rate>$maxrate)
		{
			$maxrate = $rate;
			$pageid=$rowpage['id'];
		}
	}
	echo "</br>\n<<<".$pageid.">>></br>\n";
	return $pageid;
}
?>