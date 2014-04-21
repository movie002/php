<?php
require("base.php");
require("filtetitle.php");
require("filter_quality.php");

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
	//消除空格和.以防止对比出错

	$title = preg_replace('/[\s|.|,|，|·]/','',$title);
	$aka = preg_replace('/[\s|.|,|，|·]/','',$aka);
	
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
	
	echo ' // each aka:';		
	foreach($akas as $eachaka)
	{
		if(!(strstr($title,$eachaka)===false))
		{
			$seachres=array_search($eachaka,$titles);
			if (!($seachres===false))
			{	
				echo $eachaka.' in titles +1, ';
				$rate +=1;
			}
			else
			{
				echo $eachaka.'  in titles part +0.5, ';
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
			echo " movietype diff $movietype1 vs $movietype2";
			return false;			
		}
		if($movietype1!=1 && $movietype2==1)
		{
			echo " movietype diff  $movietype1 vs $movietype2";
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
			echo " moviecountry diff $moviecountry1 vs $moviecountry2";
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
					echo ' year diff $movieyear1int vs $movieyear2int:'.$diff;
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