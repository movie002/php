<?php
function www_quanji_com_php()
{
	$authorname='全集网';
	print_r($authorname);
	
	$authorurl='http://www.quanji.com/';
	
	$urlx = 'http://www.quanji.com/';
	$url = array($urlx);
	$urlcat= array('');
	print_r($url);
	
	
	foreach ($url as $key=>$eachurl)
	{
		$change = true;
		$i=0;
		while($change&&$i<1)
		{
			$i++;
			$trueurl = $eachurl;
				
			$buff = geturl($trueurl,$authorname);
			//如果失败，就使用就标记失败次数
			if(!$buff)
				continue;
				
			$rssinfo = new rssinfo();
			$rssinfo->author = $authorname;
			echo "crawl ".$trueurl." </br>\n";
			//print_r($buff);
			preg_match_all('/<li><p>(.*?)<\/p>【.*?】<a href="(.*?)">(.*?)<\/a><\/li>/s',$buff,$match0);		
			//print_r($match0);
			if(empty($match0[2]))
			{
				echo 'preg buff error no result!';
				continue;
			}
			foreach ($match0[2] as $key2=>$div)			
			{	
				$matchdate=str_replace('<em>',"",$match0[1][$key2]); 
				$matchdate=str_replace('</em>',"",$matchdate);				
				$rssinfo->update =date('Y-').$matchdate;			
				
				//$rssinfo->cat = trim($urlcat[$key]).trim($match3[1][$key2]);
				$rssinfo->link = $authorurl.trim($match0[2][$key2]);
				$rssinfo->title = trim($match0[3][$key2]);
				//print_r($rssinfo);
				insertonlylink($rssinfo);
			}
		}
	}
}
?>
