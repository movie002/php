<?php
$dbip='localhost';
$dbuser='root';
$dbpasswd='root';
$dbname='dhmedia';

date_default_timezone_set ('Asia/Shanghai');

//每页显示的记录的个数
$pagecount=15;
//预留几页的空间
//$pagebgen=2;

#$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/';
$DH_output_path= '/srv/db/';
$DH_input_path= '/srv/php/';
$DH_author_path= '/srv/www/';
$DH_home_url= 'http://db.movie002.com/';

//$DH_output_path= $_SERVER['DOCUMENT_ROOT'] . '/movie/';
//$DH_input_path= $_SERVER['DOCUMENT_ROOT'] . '/movie002/';
//$DH_home_url= 'http://127.0.0.1/movie/';

$DH_src_path= $DH_input_path. 'gen/';
$DH_html_path= $DH_src_path . 'html/';
$DH_output_html_path = $DH_output_path.'html/';
$DH_output_index_path = $DH_output_path.'index/';
$DH_html_url= $DH_home_url.'html/';
$DH_index_url= $DH_home_url.'index/';
$DH_name= '二手电影网-影视资源导航';

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

$movielist=array('即将登陆','最新资源','一周热门','一月热门','高清资源');

//$movietype=array('','电影大片','电视节目','综艺娱乐','动漫资源');//综艺和动漫剧集归结为电视节目，动漫电影归结为电影
$movietype=array('','电影','电视剧','综艺','动漫');//综艺和动漫剧集归结为电视节目，动漫电影归结为电影
//$moviecountry=array('','华语影视','欧美风格','日韩潮流','其他国家');
$moviecountry=array('','华语','欧美','日韩','其他');
$moviestatus=array('','出预告片','马上登陆','正在上映','放映结束','出售碟片','经典影片');

//所有的链接类型，除了影视资源,都在onlylink表中
$linktype=array('','影视资讯','热门评论','影视资源','购票链接','影视资料','相关活动');
//在link表中的资源
$linkway=array('未知','网站','预告','花絮','在线','下载','综合','资讯','影评');
$linkonlinetype=array('','优酷在线','搜狐影视','百度影音','PPlive','风行','土豆','爱稀奇','PPS');
$linkdowntype=array('未知类型','迅雷资源','FTP资源','BT  资源','磁力链接','电驴资源','网盘资源','其他资源');
$linkquality=array('未知','抢先','修正','普清','高清','超清','三维');
$linkstatus=array('','得到链接','链接修正','内部得到mediaid','爬取得到mediaid','修正爬取得到mediaid','修正内部得到mediaid','得到src','结束');
$linkproperty=array('未知资源','直接下载','跳转下载','登陆下载','积分下载');

//$linkquality=array('','精致','高清','清晰','抢先','');
//$linkway=array('','滚动更新','即时动态','下载','在线','在线下载','影片信息','影片资讯');
//$linktype=array('','影视动态','电视前沿','娱乐新闻','热门评论','微电影世界','综艺节目','','预告');
//$toptype=array('预告片','正在上映','马上登陆','票房纪录');

function dh_get_catname($type,$country)
{
	if($type==='o')
	{
		echo 'get type: '.$type.'：预告片';
		return '预告片';
	}
	global $movietype,$moviecountry;
	$cat =$moviecountry[$country].$movietype[$type];
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

function get_moviecountry($name)
{
	if(strstr($name,"大陆") ||strstr($name,"中国") ||strstr($name,"香港")  ||strstr($name,"台湾") ||strstr($name,"澳门"))
		return 1;			
	if(strstr($name,"美国") ||strstr($name,"USA")|| strstr($name,"法国")|| strstr($name,"德国")|| strstr($name,"俄罗斯")|| strstr($name,"英国"))
		return 2;
	if(strstr($name,"日本")||strstr($name,"韩国")||strstr($name,"朝鲜"))
		return 3;
	return 4;
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


function dh_mysql_query($sql)
{
	$rs = mysql_query($sql);
	$mysql_error = mysql_error();
	if($mysql_error)
	{
		echo 'dh_mysql_query error info:'.$mysql_error.'</br>';
		echo $sql;
		return null;
	}
	return $rs;
}

function output_page_path($basepath,$id) 
{	
	global $DH_page_store_deep,$DH_page_store_count;
	$result=$basepath;
	$useid = $id;
	for($i= $DH_page_store_deep-1;$i>0;$i--)
	{
		$cut = pow($DH_page_store_count,$i);
		$mod = floor($useid/$cut);
		$result .= $mod .'/';
		if (!file_exists($result))  
		{   
			mkdir($result,0777);
		}
		$useid = $useid%$cut;
	}
	$result .=$useid.'.html';
	return $result;	
}

function dh_file_get_contents($filename) 
{
	$fh = fopen($filename, 'rb', false)or die("Can not open file: $filename.\n");
	clearstatcache();
	if ($fsize = @filesize($filename)) {
		$data = fread($fh, $fsize);
	} else {
		$data = '';
		while (!feof($fh)) {
			$data .= fread($fh, 8192);
		}
	}
	fclose($fh);
	return $data;
}

function dh_file_put_contents($filename, $content) {
	
	// Open the file for writing
	$fh = @fopen($filename, 'wb', false)or die("Can not open file: $filename.\n");
	// Write to the file
	$ext=strrchr($filename,'.');
//	if ($ext=='.html')
//		$content = higrid_compress_html($content);
	@fwrite($fh, $content);
	// Close the handle
	@fclose($fh);
}

function compress_html($string)
{
	$pattern = array ( 
	"/> *([^ ]*) *</", //去掉注释标记 
	"/[\s]+/", 
	"/<!--[^!]*-->/", 
	"/\" /", 
	"/ \"/", 
	"'/\*[^*]*\*/'" 
	); 
	$replace = array ( 
	">\\1<", 
	" ", 
	"", 
	"\"", 
	"\"", 
	"" 
	); 
	$string = preg_replace($pattern, $replace, $string);
	$string = str_replace("\r\n", '', $string); //清除换行符 
	$string = str_replace("\n", '', $string); //清除换行符 
	$string = str_replace("\t", '', $string); //清除制表符
	return $string;
}

function higrid_compress_html($higrid_uncompress_html_source ) 
{ 
	$chunks = preg_split( '/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE ); 
	$higrid_uncompress_html_source = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记 
	foreach ( $chunks as $c ) 
	{ 
		if ( strpos( $c, '<pre' ) !== 0 ) 
		{ 
			//[higrid.net] remove new lines & tabs 
			$c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c ); 
			// [higrid.net] remove extra whitespace 
			$c = preg_replace( '/\\s{2,}/', ' ', $c ); 
			// [higrid.net] remove inter-tag whitespace 
			$c = preg_replace( '/>\\s</', '><', $c ); 
			// [higrid.net] remove CSS & JS comments 
			$c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c ); 
		} 
		$higrid_uncompress_html_source .= $c; 
	} 
	return $higrid_uncompress_html_source; 
} 

function getupdatebegin($day)
{
	//默认设置成120天之前
	//$day = 365;
	$sql="select max(updatetime) from page";
	$res = dh_mysql_query($sql);	
	$updatetime = mysql_fetch_array($res);
	$timenow = strtotime($updatetime[0]);
	$timebegin = $timenow - $day*24*3600;
	$datebegin = date("Y-m-d",$timebegin);
	$datebegin = date("Y-m-d H:i:s",strtotime($datebegin));
	echo $updatetime[0].' -> '.$datebegin."</br>\n";
	return $datebegin;
}

function iconvbuff($buff)
{
	mb_detect_order('UTF-8,gbk,gb2312,cp950,cp936');
	$encoding = mb_detect_encoding($buff);
	echo $encoding;
	//$buff = mb_convert_encoding($buff, "UTF-8", $encoding);
	$buff = iconv($encoding,"UTF-8//IGNORE", $buff);
	//print_r($buff);
	return $buff;
}

function iconvbuffgbk($buff)
{
	mb_detect_order('UTF-8,gbk,gb2312,cp950,cp936');
	$encoding = mb_detect_encoding($buff);
	//echo $encoding;
	//$buff = mb_convert_encoding($buff, "UTF-8", $encoding);
	$buff = iconv($encoding,"GBK//IGNORE", $buff);
	//print_r($buff);
	return $buff;
}
function getrealtime($timebuf)
{
	$datenow = date("Y-m-d H:i:s");
	if($timebuf > $datenow)
		return $datenow;
	return $timebuf;
}

function ftrim2($str)
{
    $str = trim($str);
    $str = str_replace('　', ' ', $str); //将全角空格转换为半角空格
    $str = preg_replace('/^\s*|\s*$/is', '', $str); //将开头或结尾的一个或多个半角空格转换为空  
    return $str;
}
?>