<?php 
//处理电影名  
function get_shousibaocai($title,$aka,$type,$updatetime,$pageid=-1)
{ 
	echo " \n begin to get from shousibaocai:\n";	
	$name = rawurlencode($title);
	$url='http://bt.shousibaocai.com/list/'.$name.'/1';
	echo $url."\n";
	$buffer = get_file_curl($url);
	//echo $buffer;

	if(false==$buffer)
	{
		sleep(5);
		$buffer = get_file_curl($url);
		if(false==$buffer)
		{
			echo $title."搜索失败 </br>\n";
			return;
		}
	}
	//判断类型和名字
	$buffer = iconvbuff($buffer);
	//print_r($buffer);
	//preg_match_all('/<span class="highlight">(.*?)<\/span>([^>])<\/a>/s',$buffer,$match0);
	preg_match_all('/<a class="title" href="\/info[^>]+">(.*?)<\/a>/s',$buffer,$match0);
	//preg_match_all('/创建时间: (.*?)&nbsp;&nbsp;/s',$buffer,$match1);
	preg_match_all('/大小：(.*?[G|M|K]B)/s',$buffer,$match2);
	preg_match_all('/热度：(.*?)速度/s',$buffer,$match3);
	preg_match_all('/速度：<span style=".*?">(.*?)<\/span>/s',$buffer,$match4);	
	//preg_match_all('/<a class="title" href="magnet\:\?xt=urn\:btih\:(.*?)&amp/s',$buffer,$match5);
	preg_match_all('/magnet\:\?xt\=urn\:btih\:(.*?)\&amp/s',$buffer,$match5);
	//<a class="title" href="magnet:?xt=urn:btih:11a610b1b1988b62a66a9c43affb982d7e034077\&amp
	
	//print_r($match0);
	////print_r($match1);
	//print_r($match2);
	//print_r($match3);
	//print_r($match4);
	//print_r($match5);

	if(empty($match0[1]))
		return;
	
	$i = 0;
	foreach($match0[1] as $key=>$each)
	{	
		if($i>4)
			break;
		preg_match('/<span class="highlight">(.*?)<\/span>/s',$each,$matcht);
		if(empty($matcht))
			break;
		//print_r($matcht);
		$title = $matcht[1];
		echo $title;
		if(!strstr($title,$title))
			continue;
		$titlex = str_replace('<span class="highlight">','',trim($each));
		$titlex = str_replace('</span>','',$titlex);
		//$updatetime = date("Y-m-d H:i:s",strtotime($match1[1][$key]));
		$titlex .='[热度:'.$match3[1][$key].'/速度:'.$match4[1][$key].']('.$match2[1][$key].')';
		$url='magnet:?xt=urn:btih:'.$match5[1][$key];	
		addorupdatelink($pageid,'shousibaocai',$titlex,$url,'',4,6,4,1,0,$updatetime,1);
		$i++;
	}
}
?>  