<?php
function scan_dir($dir)
{
	//echo $dir.":\n";		
	$files = scandir($dir,1);
	//print_r($files);
	foreach($files as $key=>$file)
	{
		//echo "search ".$file."\n";
		if(strcmp($file,'.')===0)
			continue;
		if(strcmp($file,'..')===0)
			continue;
		$down = $dir.'/'.$file;
		if(is_dir($down))
		{
			scan_dir($down);
		}
		else
		{
			echo $down."\n";
			get_entry($down);
		}
	}
	return;
}

function get_entry($filename)
{
	global $begindate,$lists,$pages,$todaydate;
	$content = dh_file_get_contents("$filename");
	//echo $content;
	preg_match_all('/<\_e>(.*?)<\/\_e>/s',$content,$entrys);
	//print_r($entrys);
	foreach($entrys[1] as $key=>$entry)
	{
		//取得date
		preg_match('/<\_d>(.*?)<\/\_d>/s',$entry,$match);	
		
		//print_r($match);
		if(!empty($match[1]))
		{
			//如果不到现在的发布日期，不处理
			if($match[1]>$todaydate)
				continue;
		
			if($match[1]>$begindate)
				$pages[$match[1]]=$entry;
			
			preg_match('/<\_b>\<!\-\-(.*?)\-\-\><\/\_b>/s',$entry,$match1);
			//print_r($match1);
			if(!empty($match1[1]))
			{
				$eachentry = trim($match1[1]);
				//print_r($match1);
				//去除<h>
				$eachentry = preg_replace( '/<h1>(.*?)<\/h1>/s',"",$eachentry);
				$eachentry = preg_replace( '/<h2>(.*?)<\/h2>/s',"",$eachentry);
				//去除回车键
				$eachentry = preg_replace( '/\n/s',"",$eachentry);
				$eachentry = preg_replace( '/\r/s',"",$eachentry);
				//去除<***>
				$eachentry = preg_replace( '/<(.*?)>/s',"",$eachentry);	
				$eachentry =  str_replace("%tab%",'',$eachentry);
				if(mb_strlen($eachentry,'UTF-8')>128)
				{
					$x = mb_substr($eachentry,0,128,'UTF-8');
					$eachentry = preg_replace( '/<\_b>(.*?)<\/\_b>/s',"<_b><!-- $x --></_b>",$entry);
				}
				else
				{
					$eachentry = preg_replace( '/<\_b>(.*?)<\/\_b>/s',"<_b><!-- $eachentry --></_b>",$entry);
				}
				//echo $eachentry;
				$lists[$match[1]]=$eachentry;
			}			
		}		
	}
}

function gen_lists_num()
{
	global $lists,$lists_num;
	$i=0;
	$count=count($lists);
	foreach($lists as $key=>$list)
	{		
		$lists_num[$key]=$count-$i;
		$i++;
	}
}
?>