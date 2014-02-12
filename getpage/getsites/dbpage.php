<?php

function getdbpageid($input,$link,&$maxrate)
{
	global $movietype,$moviecountry;
	$ctitles = processtitle($input->title);
	//print_r($ctitles);
	//continue;
	//主标题和副标题都需要出现在page的title或者aka中
	$sqlpage = "select id,title,aka,catcountry,cattype,pubdate from page where false ";
	foreach($ctitles as $title)
		$sqlpage .= " or title like '%$title%' or aka like '%$title%' ";
	$sqlpage .= 'order by pubdate desc;';
	//echo $sqlpage;
	$resultspage=dh_mysql_query($sqlpage);
	if($resultspage)
	{
		$i=0;$pageid=-1;
		while($rowpage = mysql_fetch_array($resultspage))
		{	
			if($i>4) //如果结果太多，就不对了
				break;
			$i++;
			echo "</br>\n -> ".$rowpage['title'].' '.$rowpage['aka'].' '.$movietype[$rowpage['cattype']].' '.$moviecountry[$rowpage['catcountry']].' '.$rowpage['pubdate'];
			//拼出一个综合akas
			if($rowpage['aka']=='')
				$akaall=$rowpage['title'];
			else
				$akaall=$rowpage['title'].'/'.$rowpage['aka'];					
			if(!c_movietype($input->type,$rowpage['cattype']))
				continue;
			if(!c_moviecountry($input->country,$rowpage['catcountry']))
				continue;
			if(!c_movieyear($input->year,$rowpage['pubdate'],2,$akaall))
				continue;
			$rate = c_title($input->title,$akaall);
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
		echo '<<<'.$pageid.'>>>';
		return $pageid;
		if($pageid>=0)
		{
			echo " true";
			$sqlupdate = "update link set pageid = ". $pageid.", lstatus=".$maxrate." where link = '".$link."'" ;
			//echo $sqlupdate;
			dh_mysql_query($sqlupdate);
			if($input->type==3)
			{
				$sqlupdate = "update page set updatetime = '".$row['updatetime']."' where id = '".$pageid."';";
				echo "\n".$sqlupdate;
				dh_mysql_query($sqlupdate);
			}
			return true;
		}
		return false;
	}
	return -1;
}