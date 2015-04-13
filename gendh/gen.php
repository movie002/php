<?php

include("../config.php");
include("../common/base.php");
include("../common/compressJS.class.php");
require("../share/share.php");
require("config.php");



//利用本url建立share文件
dh_gen_share($DH_dh_url);

$DH_input_html  = 'index.tpl.html';
$DH_output = dh_file_get_contents($DH_input_html);
$DH_output3 = dh_file_get_contents($DH_input_html);

$DH_input_html  = 'site.xml';
$DH_output_site = dh_file_get_contents($DH_input_html);

$replacecontent1=array('1','1','1','1','1','2','2','2','2','2','3','3','3','3','3','3','4','4','4','4','4');
$replacecontent2=array('0','1','2','3','4','0','1','2','3','4','0','1','2','3','4','5','0','1','2','3','4');

foreach ($replacecontent1 as $key=>$eachrc)
{
	$DH_output = getcontent2($DH_output_site,$replacecontent1[$key],$replacecontent2[$key],$DH_output);
	//$DH_output = str_replace("%content".$replacecontent1[$key].$replacecontent2[$key]."%",getcontent2($DH_output_site,$replacecontent1[$key],$replacecontent2[$key]),$DH_output);
	$DH_output3 = getcontent3($DH_output_site,$replacecontent1[$key],$replacecontent2[$key],$DH_output3);
}

//使用share文件补充自己的制定部分
$DH_output = setshare($DH_output,'index.js');
$DH_output3 = setshare($DH_output3,'index.js');
$DH_output = str_replace("%home%",$DH_dh_url,$DH_output);
$DH_output3 = str_replace("%home%",$DH_dh_url,$DH_output3);
$DH_output = str_replace("%cname%",'max',$DH_output);
$DH_output3 = str_replace("%cname%",'index',$DH_output3);
$DH_output = str_replace("%title%",$DH_dh_title.'(美观版)',$DH_output);
$DH_output3 = str_replace("%title%",$DH_dh_title.'(精简版)',$DH_output3);

$DH_output_file =$DH_dh_path.'max.html';
dh_file_put_contents($DH_output_file,$DH_output);
$DH_output_file =$DH_dh_path.'index.html';
dh_file_put_contents($DH_output_file,$DH_output3);

function getcontent($DH_output_site)
{
	$ret = preg_match_all('/<r><1>(.*?)<\/1><2>(.*?)<\/2><3>(.*?)<\/3><4>(.*?)<\/4><5>(.*?)<\/5><6>(.*?)<\/6><7>(.*?)<\/7><8>(.*?)<\/8><\/r>/s',$DH_output_site,$match);
	//print_r($match);

	$DH_output_content='';

	foreach ($match[0] as $key=>$eachmatch)
	{
		if(empty($match[7][$key]))
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[4][$key].'favicon.ico"/> <a href="'.$match[4][$key].'" target="_blank" >'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
		else
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[7][$key].'"/> <a href="'.$match[4][$key].'" target="_blank" >'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
	}
	return $DH_output_content;
}

function getcontent1($DH_output_site,$r1)
{
	$ret = preg_match_all('/<r><1>('.$r1.')<\/1><2>(.*?)<\/2><3>(.*?)<\/3><4>(.*?)<\/4><5>(.*?)<\/5><6>(.*?)<\/6><7>(.*?)<\/7><8>(.*?)<\/8><\/r>/s',$DH_output_site,$match);
	//print_r($match);

	$DH_output_content='';

	foreach ($match[0] as $key=>$eachmatch)
	{
		if(empty($match[7][$key]))
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[4][$key].'favicon.ico"/> <a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
		else
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[7][$key].'"/> <a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
	}
	return $DH_output_content;
}

function getcontent2($DH_output_site,$r1,$r2,$DH_output)
{
	$ret = preg_match_all('/<r><1>('.$r1.')<\/1><2>('.$r2.')<\/2><3>(.*?)<\/3><4>(.*?)<\/4><5>(.*?)<\/5><6>(.*?)<\/6><7>(.*?)<\/7><8>(.*?)<\/8><\/r>/s',$DH_output_site,$match);
	//print_r($match);

	$DH_output_content='';
	$count=0;
	foreach ($match[0] as $key=>$eachmatch)
	{
		$count++;
		if(empty($match[7][$key]))
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[4][$key].'favicon.ico"/> <a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
		else if($match[7][$key]=='null')
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="./images/6000.png"/> <a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
		else
			$DH_output_content.='<li><div class="siteall"><img width="16" height="16" data-src="'.$match[7][$key].'"/> <a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
	}
	//return $DH_output_content;
	$DH_output = str_replace("%content".$r1.$r2."%",$DH_output_content,$DH_output);
	$DH_output = str_replace("%".$r1.$r2."%",$count,$DH_output);
	return $DH_output;
}

function getcontent3($DH_output_site,$r1,$r2,$DH_output)
{
	$ret = preg_match_all('/<r><1>('.$r1.')<\/1><2>('.$r2.')<\/2><3>(.*?)<\/3><4>(.*?)<\/4><5>(.*?)<\/5><6>(.*?)<\/6><7>(.*?)<\/7><8>(.*?)<\/8><\/r>/s',$DH_output_site,$match);
	//print_r($match);

	$DH_output_content='';
	$count=0;
	foreach ($match[0] as $key=>$eachmatch)
	{
		$count++;
			$DH_output_content.='<li><div class="siteall"><a href="'.$match[4][$key].'" target="_blank" rel="nofollow">'.$match[5][$key].'</a></div><div class="hotmeta1">'.$match[6][$key].'</div></li>';
	}
	//return $DH_output_content;
	$DH_output = str_replace("%content".$r1.$r2."%",$DH_output_content,$DH_output);
	$DH_output = str_replace("%".$r1.$r2."%",$count,$DH_output);
	return $DH_output;
}
?>
