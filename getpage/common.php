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

function getlinkmeta($row,&$linkway,&$linktype,&$linkquality,&$linkdownway,&$linkvalue,$link,$title,$cat)
{
	$linkway = testneed($row['clinkway'],$link,$title,$cat);
	$linktype = testneed($row['clinktype'],$link,$title,$cat);
	$linkquality = testneed($row['clinkquality'],$link,$title,$cat);
	$linkdownway = testneed($row['clinkdownway'],$link,$title,$cat);
	$linkvalue = testneed($row['clinkvalue'],$link,$title,$cat);
	if($linkway<0||$linktype<0||$linkquality<0||$linkvalue<0)
	{
		//linktype 不对，说明有问题不大
		echo "linkway=$linkway % title=$title link=$link cat=$cat -> linkway error 失败，请查明原因！</br> \n";
		return -1;
	}
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
			list($case,$pat,$ret) = explode('%', $contain);
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
			list($case,$pat,$ret) = explode('%',$pattern);
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
	$title =  str_replace('_',' ',$title);
	$title =  str_replace('.',' ',$title);
	
//处理数字	
	//s01之后的不要
	$title = preg_replace('/(S[0-9]+.*?)$/si','/',$title);
	echo "\n mm1:".$title."\n";
	//e(p)01之后的不要
	$title = preg_replace('/((EP|E)[0-9]+.*?)$/si','',$title);
	echo "mm2:".$title."\n";
	//第几季第几集之后的不要
	$title = preg_replace('/([第|前|后|下|全][0-9\-零一二三四五六七八九十]+[季|部|集|话|回|話].*?)$/sui','',$title);
	echo "mm3:".$title."\n";	
	//括号之后的不需要
	//$title = preg_replace('/([\(|\（].*?)$/su','',$title);
	//echo "mm3:".$title."\n";
	//年份之后的不要
	$title = preg_replace('/((19|20|18)[0-9]{2,2}年)/si','/',$title);
	$title = preg_replace('/((19|20|18)[0-9]{2,6})/si','/',$title);
	$title = preg_replace('/((19|20|18)[0-9]{2,2}[年]{0,1})/si','/',$title);
	//用特殊标志充当间隔
	$number=array('1280x720','1280','1024','WEBRip','BRrip','HDTV','HDTVrip','BluRay','DVDscr','x264','AC3','AAC','576p','480P','720p','1080p','1080i','BDrip','BD','mp4','mp3','avi','mkv','rmvb','HDRip','BIG5','ts','WEB');	
	foreach ($number as $eachlist)
		$title = preg_replace('/([^a-z]'.$eachlist.'[^a-z])/sui','/',$title);		
	//1024x768类似的去掉
	$title = preg_replace('/([0-9]+x[0-9]{3,4})/si','/',$title);	
	echo "mm4:".$title."\n";	
	//用容量大小作为间隔
	$title = preg_replace('/[^a-z]([0-9\.\s]+(GB|MB|G|M))[^a-z]/sui','/',$title);		
	//用纯数字作为间隔
	$title = preg_replace('/([^a-z])([0-9\sⅠⅡⅢ]+)([^a-z])/su','$1/$3',$title);
	echo "mm41:".$title."\n";	
	
	//处理中文和英文分开
	$title = preg_replace("/([0-9a-zA-Z\s`]+)/",'/$1/',$title);
	echo "mm5:".$title."\n";
	//$title = preg_replace('/([a-zA-Z\s\.\_\-`])([^a-zA-Z\s\.\_\-`])/','$1/$2',$title);
	//echo "mm6:".$title."\n";
	//：，！:,分开
	
	//处理符号	
	$title = preg_replace('/(：|，|！|:|,|!|－|、|·|\+)/su','/',$title);
	echo "mm7:".$title."\n";	
	$title = preg_replace('/(★|◆|×|●|•|°|\*)/su','/',$title);
	echo "mm8:".$title."\n";
	$title = preg_replace('/([\/|\-|【|】|\[|\]|「|」|《|》|\(|（|\)|）]+)/su','/',$title);
	echo "mm9:".$title."\n";

	return trim($title," \t\n\r\0\x0B\/.");
}

function gettitlearray($title)
{
	$duanarray = array();
	$titles=preg_split("/(\/)/", $title);
	//print_r($titles);
	if(count($titles)>0)
	{
		foreach($titles as $eachtitle)
		{			 
			$trimtitle=trim($eachtitle," \t\n\r\0\x0B.`");
			echo $trimtitle.'--';
			if($trimtitle!='')
				array_push($duanarray,$trimtitle);		
		}
	}
	return $duanarray;
}
?>
