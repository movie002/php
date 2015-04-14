<?php

//将多说的留言根据最新的文章进行整理
//原理是threads利用题目的比较来确定并重新分类,posts利用thread_id需找threads
//thread是文章，posts是留言
//原生的导出的export.json不能原封不动导入，需要的thread和post需要经过整理，具体看导入格式的说明
//每次导入的条数也是有限制的，1000个最大
//生成之后注意去掉最后的,号

require("config.php");
require("../common/scan.php");
require("../common/base.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

//取出已经搞定的日期和数目
$countpath=$DH_src_path.'tmp/count';
$content = dh_file_get_contents("$countpath");
preg_match('/<date>(.*?)<\/date><count>(.*?)<\/count>/s',$content,$match);
//print_r($match);
$begindate=$match[1];
$todaydate=date("YmdH");
$begincount=$match[2];


$lists=array();
$lists_num=array();
$pages=array();
scan_dir($DH_src_path.'pages');
krsort($lists);
gen_lists_num();
//print_r($lists);
//print_r($lists_num);
comment($lists,$lists_num);


function comment($lists,$lists_num)
{
	global $DH_home_url,$DH_index_url,$DH_html_path,$DH_output_path,$DH_output_html_path,$DH_src_path;
	$DH_input_html  = $DH_src_path . 'export.json';
	$DH_input_content = dh_file_get_contents("$DH_input_html");
	
	//preg_match('/"threads":\[(.*?)\]/s',$DH_input_content,$match);
	//print_r($match[1]);
	
	preg_match_all('/{"thread_id":"(.*?)","likes":"(.*?)","views":"(.*?)","thread_key":"(.*?)","title":"(.*?)","url":"(.*?)","author_key":"(.*?)","author_id":"(.*?)"}/s',$DH_input_content,$match2);
	//preg_match_all('/{"thread_id":"(.*?)"/s',$match[1],$match2);	
	//print_r($match2);
	
	//处理成中文
	foreach($match2[5] as $key=>$ucode)
	{
		echo unicode2utf8($ucode);
		//$match2[5][$key] = unicode2utf8($ucode);
		//利用标题确定新的id
		echo ' --> '.get_id(unicode2utf8($ucode),$lists,$lists_num);
		echo "\n";
		$id=get_id(unicode2utf8($ucode),$lists,$lists_num);
		$match2[4][$key]=$id;
		$url = output_page_path('http://dhblog.org/html/',$id);
		$url = str_replace('/','\/',$url);
		$match2[6][$key]=$url;
	}
	print_r($match2);
	gen_threads($match2);
	gen_posts($match2,1000);
}

function unicode2utf8($str)
{
	if(!$str) 
		return $str;
	$decode = json_decode($str);
	if($decode)
		return $decode;
	$str = '["' . $str . '"]';
	$decode = json_decode($str);
	if(count($decode) == 1)
	{
		return $decode[0];
	}
	return $str;
}

function get_id($name,$lists,$lists_num)
{
	foreach($lists as $key=>$list)
	{
		preg_match('/<\_T>(.*?)<\/\_T>/s',$list,$match);
		if($match[1]==$name)
			return $lists_num[$key];
	}
	return -1;
}

function gen_threads($match)
{
	$all='{"generator":"duoshuo","version":"0.1","threads":[';
	$sizematch=sizeof($match[1]);
	foreach($match[1] as $key=>$eachmatch)
	{
		if($key!=$sizematch-1)
		{
			$all.='{"thread_key":"'.$match[4][$key].'","title":"'.$match[5][$key].'","url":"'.$match[6][$key].'","author_key":"'.$match[7][$key].'"},';
		}
		else
		{
			$all.='{"thread_key":"'.$match[4][$key].'","title":"'.$match[5][$key].'","url":"'.$match[6][$key].'","author_key":"'.$match[7][$key].'"}]}';
		}
	}
	dh_file_put_contents('thread.json',$all);
}

function gen_posts($matchx,$count)
{
	print_r($matchx[1]);
	
	global $DH_home_url,$DH_index_url,$DH_html_path,$DH_output_path,$DH_output_html_path,$DH_src_path;
	$DH_input_html = $DH_src_path . 'export.json';
	$DH_input_content = dh_file_get_contents("$DH_input_html");
	
	//preg_match('/"threads":\[(.*?)\]/s',$DH_input_content,$match);
	//print_r($match[1]);
	
	preg_match_all('/{"post_id":"(.*?)","thread_id":"(.*?)","message":"(.*?)","created_at":"(.*?)","likes":"(.*?)","reposts":"(.*?)","ip":"(.*?)","author_id":"(.*?)","author_email":"(.*?)","author_name":"(.*?)","author_url":"(.*?)","author_key":"(.*?)"}/s',$DH_input_content,$match);
	//print_r($match2);

	$all='{"generator":"duoshuo","version":"0.1","posts":[';
	$i=0;
	foreach($match[1] as $key=>$eachmatch)
	{
		$threadid=array_search($match[2][$key],$matchx[1],true);
		//echo "threadidserach:".$match[2][$key]."-->".$threadid."\n";
		if($threadid==false)
			continue;
		$threadrealid=$matchx[4][$threadid];
		//echo "threadrealid:".$threadrealid."\n";
		if($threadrealid==-1)
			continue;		
		$i++;
		if($i%$count==0)
		{	
			$all.='{"post_key":"'.$i.'","thread_key":"'.$threadrealid.'","message":"'.$match[3][$key].'","created_at":"'.$match[4][$key].'","likes":"'.$match[5][$key].'","reposts":"'.$match[6][$key].'","ip":"'.$match[7][$key].'","author_id":"'.$match[8][$key].'","author_email":"'.$match[9][$key].'","author_name":"'.$match[10][$key].'","author_url":"'.$match[11][$key].'","author_key":"'.$match[12][$key].'"}]}';
			dh_file_put_contents('posts'.$i.'.json',$all);
			$all='{"generator":"duoshuo","version":"0.1","posts":[';
		}
		else
		{
			$all.='{"post_key":"'.$i.'","thread_key":"'.$threadrealid.'","message":"'.$match[3][$key].'","created_at":"'.$match[4][$key].'","likes":"'.$match[5][$key].'","reposts":"'.$match[6][$key].'","ip":"'.$match[7][$key].'","author_id":"'.$match[8][$key].'","author_email":"'.$match[9][$key].'","author_name":"'.$match[10][$key].'","author_url":"'.$match[11][$key].'","author_key":"'.$match[12][$key].'"},';
		}
	}
	$all.=']}';
	dh_file_put_contents('posts'.$i.'.json',$all);
}
