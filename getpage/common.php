<?php

function getmoviemeta($row,&$mtitle,&$moviecountry,&$movieyear,&$movietype,$link,$title,$cat)
{
	//echo $row['title'];
	$mtitle=testtitle($row['ctitle'],$title);
	//$mtitle=x11($title);
	//echo "\n res: $mtitle \n";
	//$mtitle=trimtitle($mtitle);
	if($mtitle<0||$mtitle==='')
	{
		//问题很大
		echo "mtitle=$mtitle % title=$title link=$link cat=$cat -> mtitle error 失败，请查明原因！</br> \n";
		return -1;
	}
	
	$movietype = testneed($row['cmovietype'],$link,$title,$cat);
	//echo "\n x: $movietype \n";
	if($movietype < 0)
	{
		//问题很大
		echo "movietype=$movietype % title=$title link=$link cat=$cat -> movietype error 失败，请查明原因！</br> \n";
		return -1;
	}		
			
	$moviecountry = testneed($row['cmoviecountry'],$link,$title,$cat);	
		
	$movieyear = 0;
	preg_match('/((19|20|18)[0-9]{2,2})/',$title,$match);
	if(!empty($match[1]))
		$movieyear=$match[1];	
	return 0;
}

function getlinkmeta($row,&$linkway,&$linktype,&$linkquality,&$linkdownway,$link,$title,$cat)
{
	$linkway = testneed($row['clinkway'],$link,$title,$cat);
	if($linkway<0)
	{
		//linktype 不对，说明有问题不大
		echo "linkway=$linkway % title=$title link=$link cat=$cat -> linkway error 失败，请查明原因！</br> \n";
		return -1;
	}
	$linktype = testneed($row['clinktype'],$link,$title,$cat);
	$linkquality = testneed($row['clinkquality'],$link,$title,$cat);
	$linkdownway = testneed($row['clinkdownway'],$link,$title,$cat);
	return 0;
}

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
				default:
				{
					echo 'error in testneed to get '.$contain.' method';
					return -l;
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
						$tmp = changetitle($match1[1]);
						$result .= $tmp.'/';
						$ltitle = $result;
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
					return x11($title);
				}					
				default:
				{
					echo 'error in testtitle';
					return -1;
				}
			}
			if($ret==0)//说明需要结束了
				break;
		}		
		if($case=='g')
		{
			if(trim($result)=='')
				return -1;		
			return substr($result,0,strlen($result)-1);
		}
		if(trim($result)=='')
			return -1;	
		return $ltitle;	
	}
	return -2;
}

function changetitle($title)
{
	$title =  str_replace('\'','`',$title);
	//s01之后的不要
	$title = preg_replace('/(S[0-9]+.*?)$/si','/',$title);
	echo "\n mm1:".$title."\n";
	//e(p)01之后的不要
	$title = preg_replace('/((EP|E)[0-9]+.*?)$/si','',$title);
	echo "mm2:".$title."\n";
	//括号之后的不需要
	$title = preg_replace('/([\(|\（].*?)$/su','',$title);
	echo "mm3:".$title."\n";
	//年份之后的不要
	//$title = preg_replace('/((19|20|18)[0-9]{2,2})/si','/',$title);	
	
	//$qulity=array('高清','蓝光','720p','1080p','1080i','1280','1024','枪版','抢先','WEBRip','BRrip','HDTV','HDTVrip','BluRay','x264','AC3','AAC','576p','BD','mp4','avi','mkv','rmvb');	
	//foreach ($qulity as $eachlist)
	//{
	//	$title = preg_replace('/('.$eachlist.'.*?)$/si','',$title);
	//}

	//用一下的数字充当间隔
	$number=array('720p','1080p','1080i','1280','1024','WEBRip','BRrip','HDTV','HDTVrip','BluRay','x264','AC3','AAC','576p','BD','mp4','avi','mkv','rmvb','HDRip');	
	foreach ($number as $eachlist)
		$title = preg_replace('/('.$eachlist.')/si','/',$title);
	//用容量大小作为间隔
	$title = preg_replace('/([0-9.]+[mg])/si','/',$title);		
	//用存数字作为间隔
	$title = preg_replace('/([0-9.]+)/','/',$title);
	
	//echo "mm4:".$title."\n";
	//中文和英文分开
	$title = preg_replace('/([a-zA-Z\s.\.\_\-`]+)/s','/$1/',$title);
	echo "mm5:".$title."\n";
	//$title = preg_replace('/([a-zA-Z\s\.\_\-`])([^a-zA-Z\s\.\_\-`])/','$1/$2',$title);
	//echo "mm6:".$title."\n";
	//：，！:,分开
	$title = preg_replace('/(：|，|！|:|,|!|－|·|\+)/su','/',$title);
	echo "mm7:".$title."\n";
	$title = preg_replace('/([\/|-|]+)/s','/',$title);
	echo "mm7:".$title."\n";	
	$title = preg_replace('/(★|◆|×|●|\*)/su','/',$title);
	echo "mm8:".$title."\n";
	$title = preg_replace('/(Ⅰ)/su','1/',$title);
	$title = preg_replace('/(Ⅱ)/su','2/',$title);
	$title = preg_replace('/(Ⅲ)/su','3/',$title);
	return $title;
}
?>