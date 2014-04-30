<?php
require("../config.php");
require("x11.php");
require("../common/base.php");
require("../common/dbaction.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getonlylink();
mysql_close($conn);

function getonlylink()
{
	$d=2;
	if( isset($_REQUEST['d']))
	{
		$d = $_REQUEST['d'];
	}
	$datebegin = getupdatebegin($d);
	$sql="select l.*,a.* from onlylink l,author a where l.updatetime > '$datebegin' and l.author=a.name";

	//全部重新计算link
	if(isset($_REQUEST['reget']))
	{
		$sql="select l.*,a.* from link l,author a WHERE l.author = a.name";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['aid']))
	{
		$aid = $_REQUEST['aid'];
		$sql="select l.*,a.* from link l,author a where a.id = $aid and l.author=a.name";
	}	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	$i=0;
	while($row = mysql_fetch_array($results))
	{
		//print_r($row);
		echo "\n".$i.":";
		$i++;
		//对linktype不符合的选项，不予处理
		$author=$row['author'];
		$title=$row['title'];
		$link=$row['link'];
		$cat=$row['cat'];
		$updatetime=$row['updatetime'];			
		
		$linkway = testneed($row['clinkway'],$link,$title,$cat);
		if($linkway<0)
		{
			//linktype 不对，说明有问题不大
			echo "linkway=$linkway % title=$title link=$link cat=$cat -> linkway error 失败，请查明原因！</br> \n";
			continue;
		}		
		echo $row['title'];
		$mtitle=testtitle($row['ctitle'],$row['title']);
		echo "\nres:".$mtitle."\n";
		//$mtitle=trimtitle($mtitle);
		if($mtitle<0||$mtitle==='')
		{
			//问题很大
			echo "mtitle=$mtitle % title=$title link=$link cat=$cat -> mtitle error 失败，请查明原因！</br> \n";
			continue;
		}
		
		$movietype = testneed($row['cmovietype'],$link,$title,$cat);
		if($movietype<0)
		{
			//问题很大
			echo "movietype=$movietype % title=$title link=$link cat=$cat -> movietype error 失败，请查明原因！</br> \n";
			continue;
		}		
		
		$linktype = testneed($row['clinktype'],$link,$title,$cat);
		$movietype = testneed($row['cmovietype'],$link,$title,$cat);
		$moviecountry = testneed($row['cmoviecountry'],$link,$title,$cat);
		$linkquality = testneed($row['clinkquality'],$link,$title,$cat);
		$linkdownway = testneed($row['clinkdownway'],$link,$title,$cat);			
			
		$movieyear = 0;
		preg_match('/((19|20|18)[0-9]{2,2})/',$title,$match);
		if(!empty($match[1]))
			$movieyear=$match[1];

		addorupdateonlylink($author,$title,$link,$cat,$updatetime,$linkquality,$linkway,$linktype,$linkdownway,$mtitle,$moviecountry,$movieyear,$movietype);
	}
}

function testneed($need,$link,$title,$cat)
{
	$ret = 0;
	if($need==''||$need==null)
		return $ret;
	preg_match_all('/<c>(.*?)<\/c>/',$need,$match);
	//print_r($match);
	if(!empty($match[1]))
	{
		foreach ($match[1] as $contain)
		{
			list($case,$pat,$ret) = split('[%]', $contain);
			//echo $case.' ' .$pat.' '.$ret ." </br> \n";
			$match1 = array();
			switch ($case)
			{
				case 'c':
				{
					//echo 'c';
					preg_match('/'.$pat.'/i',$cat,$match1);
					break;
				}
				case 't':
				{
					//echo 't';
					preg_match('/'.$pat.'/i',$title,$match1);
					break;
				}
				case 'l':
				{
					//echo 'l';
					preg_match('/'.$pat.'/i',$link,$match1);
					break;
				}			
				default:
				{
					echo 'error in testneed to get '.$contain.' method';
					return -1;
				}
			}
			//print_r($match1);
			if(!empty($match1))
			{
				return $ret;
			}
		}
	}
	return $ret;
}

//提取标题
function testtitle($ctitle,$title)
{
	$result = '';
	$ltitle = $title;
	if($ctitle==''||$ctitle==null)
		return $title;
	preg_match_all('/<c>(.*?)<\/c>/',$ctitle,$match);
	//print_r($match);	
	if(!empty($match[1]))
	{
		foreach ($match[1] as $pattern)
		{
			//list($case,$pat) = split('[%]',$pattern);
			list($case,$pat,$ret) = split('[%]',$pattern);
			//echo $case.' ' .$pat ." </br> \n";
			switch ($case)
			{
				case 'g':
				{
					//echo 'g';
					preg_match('/'.$pat.'/',$ltitle,$match1);
					//print_r($match1);
					if(!empty($match1[1]))
					{
						$firsttitle = getfirsttitle($match1[1]);
						if(trim($firsttitle)!='')
							$result .= trim($firsttitle).'/';
						//$ltitle = preg_replace('/'.$pat.'/','',$ltitle);
						//echo '***'.$ltitle.'***';
					}
					else
					{
						if($ret==0)
						{
							if($result=='')
							{
								echo " error in testtitle !</br>\n";
								return -1;
							}
						}
					}
					break;
				}
				case 'f':
				{
					//echo 'f';
					$ltitle = preg_replace('/'.$pat.'/i','',$ltitle);				
					break;
				}
				case 'x':
				{
					return x11($title);
				}					
				default:
				{
					echo 'error in testtitle';
					return -1;
				}
			}	
		}
		if($result=='')
		{
			//分割
			//$titles=preg_split("/[\/]+/s", $ltitle);	
			//print_r($titles);
			//return $titles[0];
			return $ltitle;
		}
		else
		{
			//$results=preg_split("/[\/]+/", $result);
			//print_r($results);
			//return $results[0];
			return substr($result,0,strlen($result)-1);
		}
	}
	return -2;
}
?>
