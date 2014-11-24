<?php
//require("base.php");
//require("filtetitle.php");
//require("filter_quality.php");

//判断电影的性质

function trimtitle($ctitle)
{
	//preg_match('/第[一二三四五六七八九十]+(季|部|集|话)$/s',$ctitle,$match);
	//print_r($match);
	
	$ctitle = preg_replace('/(上集|下集|续集)$/','',$ctitle);	
	$ctitle = preg_replace('/(第|全)[零一二三四五六七八九十]+(季|部|集|话|回)$/s','',$ctitle);
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
	$ctitle1 = preg_replace('/[1-9]$/','',$ctitle);
	
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

function filtertitle($ctitle)
{
	echo $ctitle.'-->';
	$del_word=array('预告片','先行','剧场版','电影版','完整版','完结','收藏版','动画版','未删减','标准','&amp;','&','!','！','·',',','，',':','：','+');
	$qulity=array('高清','蓝光','720p','1080p','DVD','1280','1024','mkv','MKV','枪版','抢先','TS','BD','HD','RMVB','AVI','avi','迅雷','下载','BT','MP4','mp4');
	foreach ($del_word as $eachlist)
		$ctitle = str_replace($eachlist,"-",$ctitle);
		
	foreach ($qulity as $eachlist)
		$ctitle = str_replace($eachlist,"(",$ctitle);
		
	$ctitle = str_replace('Ⅱ','2',$ctitle);
	$ctitle = str_replace('II','2',$ctitle);	
	$ctitle = str_replace('—','-',$ctitle);	
	
	//标题的提取技巧，提取(之前的作为标题
	//preg_match('/^(.*?)[\(|\（]/us',$title,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		$title = $match[1];
	}
	
	echo $ctitle.'!';
	return $ctitle;
}

function getnumber($title)
{
	preg_match('/([1-9])$/',$title,$match);
	if(!empty($match[1]))
		return $match[1];
	else
	{
		preg_match('/([1-9])\//',$title,$match);
		if(!empty($match[1]))
			return $match[1];
	}
	return -1;
}

//找出搜寻的字段
function processtitle($ctitle)
{
	//$rettitles = array();
	//insertarray($rettitles,$ctitle);	
	//print_r($rettitles);
	//return $rettitles;
	
	$titles=preg_split("/[\/]+/s",$ctitle);	
	$rettitles = array();
	foreach($titles as $key=>$eachtitle)
	{
		//取两个
		//if($key>1)
		//	break;
		//$eachtitle = filtertitle($eachtitle);
		$eachtitle = trimtitle($eachtitle);
		if($eachtitle!='')
			array_push($rettitles,$eachtitle);	
	}
	return $rettitles;	
}


//比对两个标题的相似度
// title: 456/der/使命感
// aka: 456/ddd/前进着
// 比较标题的相似度

function c_title($title,$aka)
{
	//消除空格和.以防止对比出错

	$title = preg_replace('/[\s|.|,|，|·]/su','',$title);
	$aka = preg_replace('/[\s|.|,|，|·]/su','',$aka);
	
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
	
	//print_r($titles);
	//print_r($akas);
	//一个由数字，一个没有数字，减分
	$titlenum=getnumber($title);
	if($titlenum!=-1)
	{
		echo ' titlenum==='.$titlenum;	
		if((strstr($aka,$titlenum)===false))
		{
			echo ' num is '.$titlenum.' in akas +0.5, ';
			$rate-=0.2;
		}
	}
	
	echo '// each title:';		
	foreach($titles as $eachtitle)
	{
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
	
//	echo ' // each aka:';		
//	foreach($akas as $eachaka)
//	{
//		if(!(strstr($title,$eachaka)===false))
//		{
//			$seachres=array_search($eachaka,$titles);
//			if (!($seachres===false))
//			{	
//				echo $eachaka.' in titles +1, ';
//				$rate +=1;
//			}
//			else
//			{
//				echo $eachaka.'  in titles part +0.5, ';
//				$rate +=0.5;
//			}
//		}
//	}

	return 	$rate;
}

//titles 里面的标题和 title的相似度
function c_title_com($title,$akasall)
{
	$akas=processtitle($akasall);
	//print_r($akas);
	if(empty($akas))
	{
		echo 'akas is empty!';
		return 0;
	}
	$rate=0;
	foreach($akas as $eachaka)
	{
		if(!(strstr($title,$eachaka)===false))
		{
			echo $eachaka.' in alltitle +1, ';
			$rate += 1;
		}
	}
	return $rate;
}

function c_movietype($movietype1,$movietype2)
{
	//movietype需要一致性
	if($movietype1>0)
	{
		//电影 --> 电影、动漫
		if($movietype1==1 && ($movietype2==1 || $movietype2==4))			
			return true;
			
		//电视剧 --> 电视剧、综艺、动漫
		if($movietype1=2 && ($movietype2==2 || $movietype2==3 || $movietype2==4))
			return true;
			
		//综艺 --> 综艺、电视剧
		if($movietype1=3 && ($movietype2==3 || $movietype2==2))
			return true;
			
		//动漫  --> 电影、电视剧、动漫
		if($movietype1=4 && ($movietype2==1 || $movietype2==2 || $movietype2==4))
			return true;
			
		echo " movietype diff $movietype1 vs $movietype2";
		return false;
	}
	return true;
}

function c_moviecountry($moviecountry1,$moviecountry2)
{
	//moviecountry需要一致性
	if($moviecountry1>0 && $moviecountry1!=4 && $moviecountry2!=4)
	{
		if($moviecountry1!=$moviecountry2)
		{
			echo " moviecountry diff $moviecountry1 vs $moviecountry2";
			return false;
		}
	}
	return true;
}

function c_movieyear($movieyear1,$movieyear2,$years,$aka)
{
	if($movieyear1<1900)
		return true;
	if($movieyear2 ==='0000-00-00')
		return true;
	//如果akas含有year，则不需要比较year
	if(strstr($aka,$movieyear1)===false)
	{
		//两个year必须在合理的范围之内
		$movieyear1int = date("Y",strtotime($movieyear1.'-00-00'));		
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
					echo " year diff $movieyear1int vs $movieyear2int:".$diff;
					return false;						
				}
			}
		}
	}
	return true;
}
?>