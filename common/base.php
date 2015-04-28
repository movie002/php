<?php
//删除单词  
function delete_word( &$name, $word )  
{  
	$name = str_replace($word,"",$name);  
}  
  
//剪裁单词,若包含，返回TRUE  
function cut_word( &$name, $word)  
{  
	if( strstr($name,$word) )  
	{  
		delete_word($name,$word); 
		return TRUE;      
	}  
	return FALSE;  
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

function getupdatebegin($day,$table='link')
{
	//默认设置成120天之前
	//$day = 365;
	$sql="select max(updatetime) from ".$table;
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


//echo getrealtime("2013年6月21日 4:56:39")."</br>\n";
//echo getrealtime("2013年 6-21 4:56:39")."</br>\n";
//echo getrealtime("2013-6-21 4:56:39")."</br>\n";
//echo getrealtime("2013年 六月21日 4:56:39")."</br>\n";
//echo getrealtime("六月21日 4:56:39")."</br>\n";
//echo getrealtime("6月二十一日")."</br>\n";


function getrealtime($timebuf)
{
	$timetobe = date("Y-m-d H:i:s",strtotime($timebuf));
	$basetime = date("Y-m-d H:i:s",strtotime("1970-01-01 08:00:00"));
	//echo $timetobe;
    if($timetobe==$basetime)
    {
        $timerealyear = formatyear($timebuf);
        $timerealdate = formatdate($timebuf);
        $timerealtime = formattime($timebuf);
        $timereal = $timerealyear."-".$timerealdate." ".$timerealtime;

	    echo $timereal;
	    $timetobe = date("Y-m-d H:i:s",strtotime($timereal));
    }

	$datenow = date("Y-m-d H:i:s",strtotime("+1 day"));
	//$datenow = date("Y-m-d H:i:s");
	if($timetobe > $datenow)
		return $datenow;
	return $timetobe;
}

function formatyear($timebuf)
{
	preg_match('/(20[0-9]{2})[\-年]/us',$timebuf,$match);
	//print_r($match);
    if(!empty($match[1]))
        return $match[1];
    return date("Y",time());
}

function formattime($timebuf)
{
	preg_match('/([0-9]+:[0-9]+:[0-9]+)/s',$timebuf,$match);
	//print_r($match);
    if(!empty($match[1]))
       return $match[1]; 

    return date("H:i:s",time());
}

function formatdate($timebuf)
{
    preg_match('/([0-9]+\-[0-9]+)/s',$timebuf,$match);
	//print_r($match);
    if(!empty($match[1]))
    {
        return $match[1];
    }

    preg_match('/([0-9]+)月([0-9]+)日/us',$timebuf,$match1);
	//print_r($match1);
    if(!empty($match1[1]))
    {
        $timereal = $match1[1]."-".$match1[2]; 
        return $timereal;
    }

	$cn=array("一","二","三","四","五","六","七","八","九","十",
	          "十一","十二","十三","十四","十五","十六","十七","十八","十九","二十",
	          "二十一","二十二","二十三","二十四","二十五","二十六","二十七","二十八","二十九","三十","三十一");
	$num=array(1,2,3,4,5,6,7,8,9,10,
                    11,12,13,14,15,16,17,18,19,20,
                    21,22,23,24,25,26,27,28,29,30,31);

    preg_match('/([一二三四五六七八九十0-9]+)月([一二三四五六七八九十0-9]+)日/us',$timebuf,$match2);
	//print_r($match2);
    if(!empty($match2[1]))
	{
        if($match2[1]>0 && $match2[1]<13)
            $mouth=$match2[1];
        else
        {
		    foreach($cn as $key=>$eachmouth)
		    {
		    	if($match2[1]==$eachmouth)
		    	{
		    		$mouth = $num[$key];	
		    		break;
		    	}
		    }
        }    

        if($match2[2]>0 && $match2[2]<32)
            $dated=$match2[2];
        else
        {
		    foreach($cn as $key=>$eachdate)
		    {
		    	if($match2[2]==$eachdate)
		    	{
		    		$dated = $num[$key];
		    		break;
		    	}
		    }
        }    
       return $mouth."-".$dated;
	}
    return date("m-d",time());
}

function ftrim2($str)
{
    $str = trim($str);
    $str = str_replace('　', ' ', $str); //将全角空格转换为半角空格
    $str = preg_replace('/^\s*|\s*$/is', '', $str); //将开头或结尾的一个或多个半角空格转换为空  
    return $str;
}
//从一个相对路径到一个绝对路径
function getnewurl($base,$after)
{
	$lasturl=$after;
	$after = trim($after);
	if($after[0]!='h')
	{
		$lasturl = $base.$after;
	}
	return $lasturl;
}
?>
