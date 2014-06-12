<?php

function getmoviemeta($row,&$mtitle,&$moviecountry,&$movieyear,&$movietype,$link,$title,$cat)
{
	//echo $row['title'];
	$mtitle=testtitle($row['ctitle'],$title);
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
						$result .= changetitle($match1[1]).'/';
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
			{
				if($result=='')
					return -1;		
				if($case=='g')
					return substr($result,0,strlen($result)-1);
				return $ltitle;				
			}		
		}		
		if(trim($result)=='')
			return -1;
		if($case=='g')
			return substr($result,0,strlen($result)-1);
		return $ltitle;	
	}
	return -2;
}

function changetitle($title)
{
	//中文和英文分开
	$title = preg_replace('/([^a-zA-Z\s\.\_\-])([a-zA-Z\s\.\_\-])/','$1/$2',$title);
	//：，！:,分开
	$title = preg_replace('/(：|，|！|:|,|!|－|\-|·)/su','/',$title);
	$title = preg_replace('/(★|◆|×|●|\*)/su','/',$title);
	
	$title = preg_replace('/(Ⅰ)/su','1/',$title);
	$title = preg_replace('/(Ⅱ)/su','2/',$title);
	$title = preg_replace('/(Ⅲ)/su','3/',$title);
	return $title;
}
?>