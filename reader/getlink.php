<?php
require("../config.php");
require("../common/common.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600); 

$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
dh_mysql_query("set names utf8;");
getlink();
mysql_close($conn);

function getlink()
{
//	$sql="select l.author,l.updatetime,l.link,l.title,l.cat,a.cmovietype,a.cmoviecountry,a.clinkquality,a.clinktype,a.clinkway,a.clinkonlinetype,a.clinkdowntype,a.clinkproperty,a.ctitle from link l,author a where l.author = a.name";	
	$sql="select l.author,l.updatetime,l.link,l.title,l.cat,a.cmovietype,a.cmoviecountry,a.clinkquality,a.clinktype,a.clinkway,a.clinkonlinetype,a.clinkdowntype,a.clinkproperty,a.ctitle from onlylink l,author a where l.author=a.name";

	//全部重新计算link
	if(isset($_REQUEST['reget']))
	{
		$sql="select l.author,l.updatetime,l.link,l.title,l.cat,a.cmovietype,a.cmoviecountry,a.clinkquality,a.clinktype,a.clinkway,a.clinkonlinetype,a.clinkdowntype,a.clinkproperty,a.ctitle from link l,author a WHERE l.author = a.name";
	}
	//只重新计算一个author的link
	if(isset($_REQUEST['aid']))
	{
		$aid = $_REQUEST['aid'];
		$sql="select l.author,l.updatetime,l.link,l.title,l.cat,a.cmovietype,a.cmoviecountry,a.clinkquality,a.clinktype,a.clinkway,a.clinkonlinetype,a.clinkdowntype,a.clinkproperty,a.ctitle from link l,author a where a.id = $aid and l.author=a.name";
	}	
	echo $sql."</br>\n";
	
	$results=dh_mysql_query($sql);	
	if($results)
	{	
		$i=0;
		while($row = mysql_fetch_array($results))
		{
			echo $i.":";
			$i++;
			//对linktype不符合的选项，不予处理
			$author=$row['author'];
			$title=$row['title'];
			$link=$row['link'];
			$cat=$row['cat'];
			$updatetime=$row['updatetime'];			
			
			$testlinktype = testneed($row['clinktype'],$link,$title,$cat);
			//echo $testlinktype.' ';
			if($testlinktype<0)
			{
				//linktype 不对，说明有问题不大
				echo "linktype=$testlinktype % title=$title link=$link cat=$cat -> linktype error 失败，请查明原因！</br> \n";
				continue;
			}
			//对title不符合的选项，不予处理
			$ctitle=testtitle($row['ctitle'],$row['title']);
			//$ctitle = filter_ed2000($title);
			if($ctitle<0||$ctitle==='')
			{
				//问题很大
				echo "ctitle=$ctitle % title=$title link=$link cat=$cat -> ctitle error 失败，请查明原因！</br> \n";
				continue;
			}
			
			$testmovietype = testneed($row['cmovietype'],$link,$title,$cat);
			//echo " *** ".$testmovietype." *** ";
			if($testmovietype<0)
			{
				//问题很大
				echo "movietype=$testmovietype % title=$title link=$link cat=$cat -> movietype error 失败，请查明原因！</br> \n";
				continue;
			}			
			$testmoviecountry = testneed($row['cmoviecountry'],$link,$title,$cat);
			$testlinkquality = testneed($row['clinkquality'],$link,$title,$cat);
			$testlinkway = testneed($row['clinkway'],$row['link'],$link,$title,$cat);
			$testlinkonlinetype = testneed($row['clinkonlinetype'],$link,$title,$cat);
			$testlinkdowntype = testneed($row['clinkdowntype'],$link,$title,$cat);			
			
			$movieyear = 0;
			preg_match('/((19|20|18)[0-9]{2,2})/',$title,$match);
			if(!empty($match[1]))
				$movieyear=$match[1];
	
			global $movietype,$moviecountry,$linkquality,$linkway,$linktype,$linkonlinetype,$linkdowntype,$linkproperty;	
			//资讯和资源插入link2表
			if($testlinktype==1||$testlinktype==2)
			{
				$sql="insert into link2(author,title,link,cat,linktype,ctitle,moviecountry,movieyear,movietype,updatetime) values('$author',\"$title\",'$link','$cat',$testlinktype,\"$ctitle\",$testmoviecountry,$movieyear,$testmovietype,'$updatetime')ON DUPLICATE KEY UPDATE linktype=$testlinktype,ctitle='$ctitle',moviecountry=$testmoviecountry,movieyear='$movieyear',movietype=$testmovietype,updatetime='$updatetime'";
				$sqlresult=dh_mysql_query($sql);
				echo $movietype[$testmovietype].'|'.$moviecountry[$testmoviecountry].'|'.$linktype[$testlinktype].'|'.$movieyear.'|'.$ctitle." >> ".$title.'|'.$link.'|'.$cat.'|'.$row['updatetime']." -> 插入link2 成功！</br> \n";				
			}
			else
			{
				$testlinkproperty = testneed($row['clinkproperty'],$link,$title,$cat);
				$sql="insert into link(author,title,link,cat,linkquality,linkway,linktype,linkonlinetype,linkdowntype,linkproperty,ctitle,moviecountry,movieyear,movietype,updatetime) values('$author',\"$title\",'$link','$cat',$testlinkquality,$testlinkway,$testlinktype,$testlinkonlinetype,$testlinkdowntype,$testlinkproperty,\"$ctitle\",$testmoviecountry,$movieyear,$testmovietype,'$updatetime')ON DUPLICATE KEY UPDATE linkquality=$testlinkquality,linkway=$testlinkway,linktype=$testlinktype,linkonlinetype=$testlinkonlinetype,linkdowntype=$testlinkdowntype,linkproperty=$testlinkproperty,ctitle='$ctitle',moviecountry=$testmoviecountry,movieyear='$movieyear',movietype=$testmovietype,updatetime='$updatetime'";
				$sqlresult=dh_mysql_query($sql);
				echo $movietype[$testmovietype].'|'.$moviecountry[$testmoviecountry].'|'.$linktype[$testlinktype].'|'.$linkquality[$testlinkquality].'|'.$linkway[$testlinkway].'|'.$linkonlinetype[$testlinkonlinetype].'|'.$linkdowntype[$testlinkdowntype].'|'.$linkproperty[$testlinkproperty].'|'.$movieyear.'|'.$ctitle." >> ".$title.'|'.$link.'|'.$cat.'|'.$row['updatetime']." -> 插入link成功！</br> \n";				
			}			
			$sql="delete from onlylink where link='".$row['link']."'";
			$sqlresult=dh_mysql_query($sql);
        }
	}
}