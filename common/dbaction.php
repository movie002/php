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

//给count(*)的sql去除count(*)
function dh_mysql_get_count($sql)
{
	$results=dh_mysql_query($sql);
	$count = mysql_fetch_array($results);
	return $count[0];
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

function setupdatetime($change,$newdate,$authorname)
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
	$title = str_replace('\'','`',$title);
	$title = str_replace('"','`',$title);
	
	$datenow = date("Y-m-d H:i:s",strtotime("+1 days"));
	if($update > $datenow)
		$update = $datenow;
		
	$sql="insert into onlylink(author,title,link,cat,updatetime) values ('$author','$title','$link','$cat','$update') ON DUPLICATE KEY UPDATE author= '$author',title='$title',cat='$cat',updatetime='$update'";
	//echo $sql."\n";
	$sqlresult=dh_mysql_query($sql);
	echo $title.' = '.$link.' = '.$cat." = ".$update." ->插入onlylink成功</br> \n";	
}

function updateonlylink($author,$title,$link,$cat,$updatetime,$mtitle,$moviecountry,$movieyear,$movietype)
{
	//清理title的空格
	$title = trim($title);
	//清理title中的'和"
	$title = str_replace('\'','`',$title);
	$title = str_replace('"','`',$title);
	
	$title = str_replace('"','`',$title);
	
	$datenow = date("Y-m-d H:i:s",strtotime("+1 days"));
	if($updatetime > $datenow)
		$updatetime = $datenow;

	$sql="insert into onlylink(author,title,link,cat,mtitle,moviecountry,movieyear,movietype,updatetime) values('$author','$title','$link','$cat','$mtitle',$moviecountry,$movieyear,$movietype,'$updatetime')ON DUPLICATE KEY UPDATE mtitle='$mtitle',moviecountry=$moviecountry,movieyear='$movieyear',movietype=$movietype,updatetime='$updatetime'";
	echo $sql."</br>\n";
	$sqlresult=dh_mysql_query($sql);

	//global $movietype,$moviecountry,$linkquality,$linkway,$linktype,$linkdownway;
	//echo $movietype[$movietype].'|'.$moviecountry[$moviecountry].$movieyear.'|'.$mtitle." >> ".$title.'|'.$link.'|'.$cat.'|'.$updatetime." -> 插入onlylink成功！</br> \n";
}

function addorupdatelink($pageid,$author,$title,$link,$cat,$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,$updatetime,$input=null)
{
	if($input==null)
	{
		$sql="insert into link(pageid,author,title,link,cat,linkquality,linkway,linktype,linkdownway,linkvalue,updatetime) values($pageid,'$author','$title','$link','$cat',$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,'$updatetime')ON DUPLICATE KEY UPDATE pageid=$pageid,author='$author',title='$title',cat='$cat',linkquality=$linkquality,linkway=$linkway,linktype=$linktype,linkdownway=$linkdownway,linkvalue=$linkvalue,updatetime='$updatetime',remove=null";
		echo $sql."\n";
	}
	else
		$sql="insert into link(pageid,author,title,link,cat,linkquality,linkway,linktype,linkdownway,linkvalue,updatetime,input) values($pageid,'$author','$title','$link','$cat',$linkquality,$linkway,$linktype,$linkdownway,$linkvalue,'$updatetime','$input')ON DUPLICATE KEY UPDATE pageid=$pageid,author='$author',title='$title',cat='$cat',linkquality=$linkquality,linkway=$linkway,linktype=$linktype,linkdownway=$linkdownway,linkvalue=$linkvalue,updatetime='$updatetime',input='$input',remove=null";

	$sqlresult=dh_mysql_query($sql);
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

function getdbpageid($title,$mtitle,$country,$year,$type,$maxrate)
{
	echo " ".$mtitle;
	//global $movietype,$moviecountry;
	$ctitles = processtitle($mtitle);
	//print_r($ctitles);
	if(empty($ctitles))
		return -1;
	//continue;
	//主标题和副标题都需要出现在page的title或者aka中
	//$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where title like '%$mtitle%' or aka like '%$mtitle%' order by updatetime desc;";
	$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where false ";
	foreach($ctitles as $ctitle)
		$sqlpage .= " or title like '%$ctitle%'";
	$sqlpage .= 'order by hot desc limit 0,4';
	echo "\n".$sqlpage;
	$resultspage=dh_mysql_query($sqlpage);
	$pageid=getdbpageid_com($sqlpage,$title,$mtitle,$country,$year,$type,$maxrate);
	if($pageid>-1)
		return $pageid;
		
	$sqlpage = "select id,title,aka,catcountry,cattype,updatetime,pubdate from page where false ";
	foreach($ctitles as $ctitle)
		$sqlpage .= " or aka like '%$ctitle%' ";
	$sqlpage .= 'order by pubdate desc limit 0,4';
	echo "\n".$sqlpage;
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
		echo "</br>\n -> ".$rowpage['id'].' '.$rowpage['title'].' '.$rowpage['aka'].' '.$rowpage['cattype'].'/'.$rowpage['catcountry'].'/'.$rowpage['pubdate']." --> ";
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
		echo '=> rate: '.$rate;
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