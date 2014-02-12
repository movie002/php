<?php
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
	var	$pubdate='0000-00-00 00:00:00';
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

function insertcelebrity($celebritys)
{
	preg_match_all('/\[(.*?)\]/s',$celebritys,$match);
//	print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $celebrity)
		{
			list($name, $id) = split('[|]', $celebrity);
			$name=trim($name);
			$id=trim($id);
			$name = str_replace("'","\'",$name);
			$sql="insert into celebrity(id,name) values('$id','$name') ON DUPLICATE KEY UPDATE name = '$name'";
			$sqlresult=dh_mysql_query($sql);
		}
	}	
}

function setupdatetime($change,$newdate,$authorid)
{
	if($change)
	{
		$sql="update author set updatetime='$newdate' where id = $authorid;";
		echo $sql."</br>\n";
		$result=dh_mysql_query($sql);		
	}
}
?>