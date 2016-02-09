<?php
$dbip='movie002.com';
#$dbip='127.0.0.1';
$dbuser='root';
$dbpasswd='qazxsw';
$dbname='movie002';

date_default_timezone_set ('Asia/Shanghai');

//每页显示的记录的个数
$pagecount=15;
//预留几页的空间
//$pagebgen=2;

#$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/';


//$DH_output_path= '/srv/v/';
//$DH_input_path= '/srv/php/';
//$DH_dh_path= '/srv/dh/';
//$DH_home_url= 'http://v.movie002.com';

//$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/v/';
//$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/php/';
//$DH_dh_path= $_SERVER['DOCUMENT_ROOT'] . '/dh/';
//$DH_home_url= 'http://s.x2y4.com/';
//
//
//
//$DH_src_path= $DH_input_path. 'genv/';
//$DH_html_path= $DH_src_path . 'html/';
//$DH_output_html_path = $DH_output_path.'html/';
//$DH_output_index_path = $DH_output_path.'index/';
//$DH_html_url= $DH_home_url.'html/';
//$DH_index_url= $DH_home_url.'index/';
//$DH_name= '小二影视网';
//$DH_name_des= '小二影视网_影视资源(电影/电视剧等)搜索大全';

//页面显示的文章列表条目的个数
$DH_page_count_limit = 4;
//分页中能展示的页面数
$DH_page_range = 6;

//index中显示的每个index个数
$index_list_count = 8;

//页面存储的深度
$DH_page_store_deep = 3;
//每个深度的页面个数
$DH_page_store_count = 100;

//数据字典定义
///电影相关：
///类型：0:'' 1:电影大片 2:电视剧集
///国家：0:'' 1:华语影视 2:欧美大片 3:日韩风格 4:其他国家
///电影的状态：0:'' 1:出预告片 2:马上登陆 3:正在上映 4:放映结束 5:出售碟片 6:经典影片
///链接相关：
///链接类型：0:'' 1:影视资讯 2:热门评论 3:影视资源 4:购票链接 5:影视资料
///如果链接是影视资源，子类型：0:'' 1:网站 2:预告 3:花絮 4:在线 5:下载 6:综合
///如果是在线，子类型：0:'' 1:优酷在线 2:搜狐影视 3:百度影音 4:PPlive 5:风行 6:土豆 7:爱稀奇 8:PPS
///如果是下载，子类型：0:'' 1:迅雷资源 2:FTP资源 3:BT资源 4:磁力链接 5:电驴资源 6:其他资源
///清晰度：0:'' 1:未知 2:抢先 3:修正 4:普清 5:高清 6:超清 7:三维
///状态：0:'' 1:得到链接 2:链接修正 3:得到共同的电影标题 4:得到mediaid 5:得到src 6:结束
///下载链接属性：0:未知资源 1:直接下载 2:跳转下载 3:登陆下载

$movietype=array('','电影大全','电视剧大全','综艺节目大全','动画片大全');
$movietype2=array('','电影','电视','综艺','动画');
//综艺和动漫剧集归结为电视节目，动漫电影归结为电影
$moviecountry=array('','华语','欧美','日韩','其他');
$moviestatus=array('','出预告片','马上登陆','正在上映','放映结束','出售碟片','经典影片');

//所有的链接类型，除了影视资源,都在onlylink表中
$linkquality=array('未知','未知','抢先','修正','普清','高清','超清','三维');
#$linkway=array('未知类型','影视资讯','热门评论','预告花絮','相关活动','购票链接','下载链接','在线观看','影视资料');
$linkway=array('未知','影讯','影评','预告','活动','购票','下载','在线');
$linktype=array('未知资源','迅雷资源','FTP资源','BT 资源','磁力链接','电驴资源','网盘资源','网页在线','百度影音','快播资源','综合资源');
$linkdownway=array('未知资源','直接下载','跳转下载','登陆下载','积分下载');

function dh_get_catname($type,$country)
{
	if($type==='o')
	{
		echo 'get type: '.$type.'：预告片';
		return '预告片';
	}
	global $movietype2,$moviecountry;
	$cat =$moviecountry[$country].$movietype2[$type];
	return $cat;
}

function dh_get_catkeyword($type,$title)
{
	preg_match('/([a-z])/si',$title,$match);
	global $movietype2;
	if($type==1)
		$cat =$title.'电影,'.$title.'国语,'.$title.'完整版,'.$title.'预告片,'.$title.'影评';
	if($type==2)
		$cat =$title.'电视剧,'.$title.'全集,'.$title.'国语,'.$title.'电视剧全集';
	if($type==3)
		$cat =$title.'综艺,'.$title.'国语,'.$title.'全集';
	if($type==4)
		$cat =$title.'动漫,'.$title.'动画,'.$title.'国语,'.$title.'全集';
	return $cat;
}

//使用字符串获得id
function get_movietype($name)
{
	if(strstr($name,"电影")||strstr($name,"片"))
		return 1;
	if(strstr($name,"电视剧") || strstr($name,"剧集"))
		return 2;
	if(strstr($name,"动画") || strstr($name,"动漫"))
		return 4;
	if(strstr($name,"综艺"))
		return 3;		
	return 0;
}

function get_moviecountry($allname)
{
	$country = 0;
	$get=false;
	//将国家细化到各个国家
	$names=explode("/",$allname);
	//print_r($names);
	foreach($names as $key=>$name)
	{
		//echo 'need: '.$name.'/'.$country;	
		//$name=trim($name);
		if(strstr($name,"大陆") ||strstr($name,"中国") ||strstr($name,"香港")  ||strstr($name,"台湾") ||strstr($name,"澳门"))
		{
			if($country==1 || $country==0)
				$country = 1;
			else
				return 4;
			continue;
		}
		if(strstr($name,"美国") ||strstr($name,"USA")|| strstr($name,"法国")|| strstr($name,"德国")|| strstr($name,"英国")|| strstr($name,"UK")|| strstr($name,"爱尔兰")|| strstr($name,"加拿大")|| strstr($name,"加拿大")|| strstr($name,"加拿大")|| strstr($name,"加拿大"))
		{
			if($country==2 || $country==0)
				$country = 2;
			else
				return 4;
			continue;
		}
		
		if(strstr($name,"日本")||strstr($name,"韩国"))
		{
			if($country==3 || $country==0)
				$country = 3;
			else
				return 4;
			continue;
		}
		
		//如果一个国家不属于以上的，认为是其他国家
		if($country==4 || $country==0)
			$country = 4;
		else
			return 4;
	}
	return $country;
}

function get_quality($name)
{
	if(strstr($name,"1080p") ||strstr($name,"720p") ||strstr($name,"BD")  ||strstr($name,"HD"))
		return 4;			
	if(strstr($name,"TS") || strstr($name,"tc")|| strstr($name,"抢先")|| strstr($name,"俄罗斯")|| strstr($name,"英国"))
		return 1;
	if(strstr($name,"DVD"))
		return 3;
	if(strstr($name,"3D"))
		return 6;		
	return 0;
}

class rssinfo
{
	var $title;
	var $link;
	var $update;
	var $author;
	var $cat='';	
}

class SearchInput
{
	var $title;
	var $country;
	var $year;
	var $type;
}

class MovieResult
{
	var $title='';
	var $aka='';
	var $imgurl='';
	var $simgurl='';
	var $meta='';
	var	$pubdate='0000-00-00';
	var $country=0;
	var $type=0;
	var $summary='';
	
	var $ids='';
	var $mediaid='';
	var $doubantrail='';
	var $doubantrailurl='';
	var $doubansp='';
	var $doubanspurl='';
	
	var $m1905trail='';
	var $m1905trailurl='';
	var $m1905sp='';
	var $m1905spurl='';
	
	var $mtimetrail='';
	var $mtimetrailurl='';
}
?>
