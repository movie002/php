<?php  
/////* 使用示例 */  
//header('Content-Type:text/html;charset= UTF-8'); 
//$title = "《痞子大逃亡/真相与结果》蓝光高清1024MKV版迅雷下载[美国动作惊悚电影]";
//$result = filter_quality($title,'','');
//print_r($result);
//
////$linkquality=array('未知','抢先','修正','普清','高清','超清','三维');

function filter_quality($title,$cat,$link)
{
	$ret = filter_quality_size($title);
	if($ret != -1)
		return $ret;		
	if($title != '')
	{
		$ret = filter_quality_each($title);
		if($ret != -1)
			return $ret;
	}
	if($cat != '')
	{
		$ret = filter_quality_each($title);
		if($ret != -1)
			return $ret;
	}		
	if($link != '')
	{
		$ret = filter_quality_each($title);
		if($ret != -1)
			return $ret;
	}
	return -1;
}

function filter_quality_size($case)
{
	preg_match('/([0-9][0-9\.]+)[g|G]/s',$case,$match);
	if(!empty($match[1]))
	{
		if($match[1]>2.0)
		{
			echo $match[0];
			return 5;
		}
	}
	return -1;
}

function filter_quality_each($case)
{
	$qulity1=array('CAM','枪版','抢先','TS','TD');
	$qulity2=array('修正');
	$qulity3=array('720p','DVD','1280','1024','HD');
	$qulity4=array('高清','1080p','BD');
	$qulity5=array('蓝光');
	$qulity6=array('3D');
	foreach($qulity1 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 1;
		}
	}
	foreach($qulity2 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 2;
		}			
	}
	foreach($qulity3 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 3;
		}	
	}	
	foreach($qulity4 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 4;
		}	
	}
	foreach ($qulity5 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 5;
		}		
	}
	foreach($qulity6 as $eachlist)
	{
		if(!(stristr($case,$eachlist)===false))
		{
			echo $eachlist;
			return 6;
		}		
	}
	return -1;
}

//先说结果，一般情况
//BluRay = BDRip > HDDVDRip > WEB-DL > HDRip(HDTV) > HR-HDTV > DVDRip > R5 > DVDScr > TVRip > TC > TS > CAM
//下面详细说明
//1.BluRay(BDRip)
//蓝光转制，效果最好，一般电影720p 4.4g，1080p 10g左右。
//2.HDDVDRip
//跟BDRip差不多，但是现在很少有hddvd了。
//3.WEB-DL
//一般是iTunes下载，算是官方版本，视频质量和BDRip差不多，但是音频码率一般只有128kbps，一般看足够了。想要更好的音响效果，建议还是下BDRip。
//例如昨天新出的钢铁侠3高清，就是这个版本。
//Iron.Man.3.2013.720p.WEB-DL.H264-WEBiOS.mkv
//4.HDRip(HDTV)
//高清电视片源转制，可能有水印，有台标，而且中间会有剪辑，因为电视会插播广告。一般最新美剧都是这个。
//5.HR-HDTV
//1080p的一半540p，在文件大小和影片质量上取得比较好的平衡，人人影视发布了很多hrhdtv的美剧。
//———————分割线以上都是高清——————-
//6.DVDRip
//顾名思义，DVD转制，被各种高清惯的感觉dvd清晰度完全不能忍。
//7.R5
//俄罗斯版dvdtip，发布一般比dvdrip早，但可能是俄罗斯语。
//8.DVDScr
//预览版dvd，比dvdrip发布早，但画质稍差。
//9.TVRip
//非高清电视转制，现在很少见了，老动画很多是这种。
//10.TC
//胶片直接拷贝，画质还可以，亮度不足。
//11.TS
//效果比较好的枪版，建议别下。
//12.CAM
//真正的枪版，经常能看到走动人头或者观众的笑声。。。
//想要影院级效果请直接忽略本文去下蓝光原盘。
//?>  