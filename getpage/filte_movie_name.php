<?php  
///* 使用示例 */  
//header('Content-Type:text/html;charset= UTF-8'); 
//include("common.php");
//$test = "《痞子大逃亡/真相与结果》蓝光高清1024MKV版迅雷下载[美国动作惊悚电影]"; 
////$test = "超凡蜘蛛侠 (The Amazing Spider-Man) 删节片段"; 
////$result = filte_movie_name($test);
//$result = get_movie_name($test,'<c>《(.*?)》</c><c>[(.*?)]</c>');
//print_r($result);

//	preg_match_all('/.*?/','',$match); 
//	print_r($match);

//echo testneed('<c>l%(\/tiyu\/|\/zongyi\/|\/dongman\/|\/jilupian\/)%1</c><c>t%.*?%0</c>','http://www.370kk.com/tiyud/25054/','9【33】8','足球事');

class FilterResult
{
	var $start_name;
	var $type;
	var $movie_type;
	var $quality;
	var $code=1;
	var $country=0;
	var $year='1000';
	var $language;
	var $sub;
	var $play;
	var $limit;
	var $session;
	var $lesson;
	var $second_name;
	var $end_name;
}

function get_movie_name(&$result,$name,$contain)
{
	$result = filte_movie_name($result,$name);
	//需要得到的：下载类型，影片类型，品质，语言，集数
	$result->start_name = $name;//记录影片原名
	preg_match_all('/<c>(.*?)<\/c>/',$contain,$match);
	//print_r($match);
	if(!empty($match[1]))
	{	
		foreach ($match[1] as $title)
		{
			//list($pre,$aft) = split('[|]', $title);
			//echo $pre.$aft."</br>\n";
			$patern='/'.$title.'/';
			preg_match($patern,$name,$match1);
			if(!empty($match1[1]))
			{	
				$result->end_name = $match1[1];
				break;
			}		
		}			
	}
	return $result;
} 
 
//处理电影名  
function filte_movie_name(&$result,$name)  
{  
	//需要得到的：下载类型，影片类型，品质，语言，集数
	$result = new FilterResult();  
	$result->start_name = $name;//记录影片原名  
	
	//垃圾单词  
	$del_word=array("观看","首映","首款","首发","网络","独播","暑期必看","配音","最新","经典","高分","热门","版本","版","精彩","先行","制作","官方");  	  
	//删除垃圾单词  
	for($i=0;$i<count($del_word);$i++)  
	{  
		delete_word( $name, $del_word[$i] );  
	}  
	
	///影片类别
	$del_word=array("电视剧","电影","纪录片","剧集","综艺");  	  
	//删除垃圾单词  
	for($i=0;$i<count($del_word);$i++)  
	{  
		delete_word( $name, $del_word[$i] );  
	}  
	
	//电影种类
	$del_word=array("动作","情色","爱情","惊悚","动作惊悚","历史","恐怖","励志","首发","青春","偶像","魔幻","惊悚","喜剧","悬疑","奇幻","科幻","剧情");  
	//删除垃圾单词  
	for($i=0;$i<count($del_word);$i++)  
	{  
		delete_word( $name, '('.$del_word[$i].')' ); 
		delete_word( $name, '（'.$del_word[$i].'）' );
		delete_word( $name, '【'.$del_word[$i].'】' ); 		
		delete_word( $name, '['.$del_word[$i].']' ); 		
		delete_word( $name, $del_word[$i].'片' ); 
		delete_word( $name, $del_word[$i].'剧' ); 		
	}  

	//影片分级
	$del_word=array("限制级","限制","R级","三级","四级","禁片");  	  
	//删除垃圾单词  
	for($i=0;$i<count($del_word);$i++)  
	{  
		delete_word( $name, $del_word[$i] );  
	}
	
	country_word($name,$result);
	play_word($name,$result);
	type_word($name,$result);
	quality_word($name,$result);
	year_word($name,$result);
	code_word($name,$result);
	
	///语言 
	$language_word=array("韩语","日语","国粤语","国语","中文","中日双语","中英双语","国粤双语","英语","粤语");	
	foreach ($language_word as $language_word_each)
	{
		if( cut_word($name,$language_word_each))  
		{  
			$result->language .= $language_word_each.'|';  
		} 		
	}	
	$result->language= substr($result->language,0,strlen($result->language)-1);
	
	///字幕
	$sub_word=array("中字","中英双字","中英字");
	foreach ($sub_word as $sub_word_each)
	{
		if( cut_word($name,$sub_word_each))  
		{  
			$result->sub .= $sub_word_each.'|';  
		} 		
	}	
	$result->sub= substr($result->sub,0,strlen($result->sub)-1);

	//提取资源别名 
	preg_match_all('|/(([^<>]+)/)|',$name,$second_name_result);  
	preg_match_all('|（([^<>]+)）|',$name,$second_name_result2);  
	if(!empty($second_name_result[1])&&!empty($second_name_result2[1]))
	{
		$second_name_context= $second_name_result[1][0];  
		//大于3个中文字符，6个英文字符，认为是别名  
		if( strlen($second_name_context) >= 6 )  
		{  
			$result->second_name = $second_name_context;  
			delete_word( $name, $result->second_name );  
		}  
		$second_name_context= $second_name_result2[1][0];  
		if( strlen($second_name_context) >= 6 )  
		{  
			$result->second_name = $second_name_context;  
			delete_word( $name, $result->second_name );  
		}  
	}

	//去掉多余符号  
	
	$name = trim($name);
	delete_word($name,"片");
	delete_word($name,"剧");	
	delete_word($name,"《》");  
	delete_word($name,"（）"); 
	delete_word($name,"()");  
	delete_word($name,"[]");  
	delete_word($name,"【】");  
	delete_word($name,"“”");  
	delete_word($name,'""');  
	delete_word($name,'+'); 
	delete_word($name,'《'); 
	delete_word($name,'》'); 	
	  
	//去除左右空格  
	$name = trim($name);  
  
	$result->end_name = $name;  
	return $result;   
}   

//播放下载类型
function  play_word(&$name,&$result)
{
	//下载类型	
	$play_word=array(array("迅雷","下载"),array("下载","下载"),array("BT","下载"),array("BT","下载"),array("种子","下载"),array("快车","下载"),array("网盘","下载"),array("在线","在线"),array("观看","在线"),array("百度影音","在线"),array("优酷","在线"),array("土豆","在线"));
	foreach ($play_word as $play_word_each)
	{
		if( cut_word($name,$play_word_each[0]))  
		{  
			if(!ereg($play_word_each[1],$result->play))
			{
				$result->play .= $play_word_each[1].'|';  
			}
		} 		
	}
	$result->play= substr($result->play,0,strlen($result->play)-1);  	
}

//类型
function  type_word(&$name,&$result)
{
	//影片类型  
	$type_word=array(array("未删减","未删减"),array("未删截","未删减"),array("完整","未删减"),array("正片","未删减"),array("删减","删减"),array("删截","删减"),array("导演剪辑","导演剪辑"),array("特辑","特辑"),array("预告","预告"),array("宣传片","宣传片"),array("预告","预告"));
	foreach ($type_word as $type_word_each)
	{
		if( cut_word($name,$type_word_each[0]))  
		{  
			if(!ereg($type_word_each[1],$result->type))
			{
				$result->type .= $type_word_each[1].'|';  
			}
		} 		
	}
	$result->type= substr($result->type,0,strlen($result->type)-1);  
}	

//质量
function  quality_word(&$name,&$result)
{
	///品质  
	$quality_word=array(array("高清","高清"),array("HD","高清"),array("720p","高清"),array("1080p","高清"),array("BD","蓝光"),array("蓝光","蓝光"),array("枪版","枪版"),array("抢先","枪版"),array("TS","枪版"),array("DVD","DVD"),array("1280","DVD"),array("1024","DVD"),array("MKV","高清"),array("mkv","高清"));
	foreach ($quality_word as $quality_word_each)
	{
		if( cut_word($name,$quality_word_each[0]))  
		{  
			if(!ereg($quality_word_each[1],$result->quality))
			{
				$result->quality .= $quality_word_each[1].'|';  
			}
		} 		
	}	
	$result->quality= substr($result->quality,0,strlen($result->quality)-1);
}
//国家
function  country_word(&$name,&$result)
{	
	$result->country=get_moviecountry($name);
	//国家
	$del_word=array("韩国","日本","中国","大陆","香港","台湾","澳门","美国","欧美","德国","法国","日剧","韩剧","美剧","朝鲜","澳大利亚","意大利","西班牙","越南","泰国","柬埔寨","印度","巴基斯坦","英国","阿根廷","加拿大","俄罗斯","国产");	
	//删除垃圾单词  
	for($i=0;$i<count($del_word);$i++)  
	{  
		delete_word( $name, $del_word[$i] );  
	}
}
//年份
function  year_word(&$name,&$result)
{	
	//去除年份	
	$match='';
	preg_match('/((19|20|18)[0-9]{2,2})/',$match);
	if(!empty($match[1]))
	{
		$result->year=$match[1];
	}	
	$name = preg_replace('/((19|20|18)[0-9]{2,2})/','',$name);
}

//电影电视剧的判断
function  code_word(&$name,&$result)
{	
	//提取电视剧集数  
	preg_match_all('/第([^<>]{1,4})(集|话|季|部)/',$name,$lesson_result); 
	if(!empty($lesson_result[1]))
	{
		//print_r($lesson_result);
		$result->lesson=$lesson_result[1][0];
		if( cut_word( $name, $lesson_result[0][0] ))  
		{  
			//$result->session =  $result->lesson;  
		}
		$result->code = "电视剧";  		
	}  
	preg_match_all('/([0-9]{1,4})集/',$name,$lesson_result); 
	if(!empty($lesson_result[1]))
	{
		//print_r($lesson_result);
		$result->lesson=$lesson_result[1][0];
		if( cut_word( $name, $lesson_result[0][0] ))  
		{  
			//$result->session =  $result->lesson;  

		}
		$result->code = "电视剧";  	
	}  

	if(cut_word( $name,'全集'))
	{
		$result->code = "电视剧"; 
		$result->lesson =  "全集";  
	}	

	preg_match_all('/((19|20|18)[0-9]{6,8})/',$name,$lesson_result); 
	if(!empty($lesson_result[1]))
	{
		//print_r($lesson_result);
		$result->lesson=$lesson_result[1][0];
		if( cut_word( $name, $lesson_result[0][0] ))  
		{  
			//中文转阿拉伯数字  
			$result->lesson =  $result->lesson;  
			$result->code = "电视剧";  
		}
	} 
	
	preg_match_all('/([0-9]{2,3})/',$name,$lesson_result); 
	if(!empty($lesson_result[1]))
	{
		//print_r($lesson_result);
		$result->lesson=$lesson_result[1][0];
		if( cut_word( $name, $lesson_result[0][0] ))  
		{  
			//$result->session =  $result->lesson;  

		}
		$result->code = "电视剧";  	
	} 
	$result->code = get_movietype($result->code);
}

?>  