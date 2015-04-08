<?php
/////////////////////////////////////////////////////
/// 函数名称：gen_update 
/// 函数作用：产生每天和每个礼拜的更新内容
/// 函数作者: DH
/// 作者地址: http://movie002.com 
/////////////////////////////////////////////////////


function dh_gen_update($cycle)
{
	global $movietype,$DH_html_url,$DH_input_path,$DH_output_path,$DH_home_url;
	$updatenew='';
	$upnew='<li><b>新资源</b> </li>';
	$upold='<li><b>增加资源(无更高质量)</b></li>';
	$upnewq='<li><b>增加资源(有更高质量)</b></li>';
	
	$date = date('Y-m-d 00:00:00',strtotime("-$cycle days"));
	//echo $date;
	$sql="select * from page where updatetime >= '$date' order by cattype";
	echo $sql."\n";
	$results=dh_mysql_query($sql);
	if($results)
	{	
		$DH_input_html  = $DH_input_path . 'share/tongji/update.html';
		$DH_output_content = dh_file_get_contents("$DH_input_html");
		$DH_output_content = setshare($DH_output_content,'static.js');
		$DH_output_content = str_replace("%date%",date('Y-m-d',time()),$DH_output_content);
		$DH_output_content = str_replace("%home%",$DH_home_url,$DH_output_content);
		$DH_output_content = setshare($DH_output_content,'');
		
		$listhtml='<li>&nbsp;<span class="lt2v0">ID</span><span class="lt45v2">资源名(点击查看)</span><span class="rt340v2">原资源数</span> <span class="rt260v2">现资源数</span><span class="rt140v2">原资源质量</span> <span class="rt60v2">现资源质量</span></li>';
	
		while($row = mysql_fetch_array($results))
		{
			//print_r($row);
			//echo $row['id']."\n";
			$sql1="select * from pagetmp where cycle=$cycle and id=".$row['id'];
			$results1=dh_mysql_query($sql1);
			if($results1)
			{
				$pagetmp = mysql_fetch_array($results1);
				if($pagetmp)
				{	
					//print_r($pagetmp);
					//如果资源多了，报告			
					if($row['ziyuan']>$pagetmp['ziyuan'])
					{
						$pageurl=output_page_path($DH_html_url,$row['id']);
						$tmp='<li>&nbsp;<span class="lt2v0">'.$row['id'].'</span> <span class="lt45v2"><a href="'.$pageurl.'" target="_blank"> ['.$movietype[$row['cattype']].']'.$row['title'].'</a></span><span class="rt340v2">'.$pagetmp['ziyuan'].'</span> <span class="rt260v2">'.$row['ziyuan'].'</span><span class="rt140v2">'.$pagetmp['quality'].'</span> <span class="rt60v2">'.$row['quality'].'</span></li>';
						
						if($pagetmp['ziyuan']==0)
						{
							$updatenew.='<br>《'.$row['title'].'》有资源了 <a href="'.$pageurl.'">资源链接</a></br>';
							//$upnew.=$row['id']." ".$row['title']." ".$row['ziyuan']."(".$row['quality'].") vs ".$pagetmp['ziyuan']."(".$pagetmp['quality'].") 有更高质量的资源</br>";
							$upnew.=$tmp;
						}
						else
						{
							if($row['quality']>$pagetmp['quality'])
							{
								$updatenew.='<br>'.$movietype[$row['cattype']].'《'.$row['title'].'》有高质量的资源了 <a href="'.$pageurl.'">资源链接</a> 资源日期:'.$row['updatetime'].'</br>';
								//$upnewq.=$row['id']." ".$row['title']." ".$row['ziyuan']."(".$row['quality'].") vs ".$pagetmp['ziyuan']."(".$pagetmp['quality'].") 有更高质量的资源</br>";
								$upnewq.=$tmp;
							}
							else
							{
								//$upold.=$row['id'].'<a href="'.$pageurl.'">'.$row['title']."</a>".$row['ziyuan']."(".$row['quality'].") vs ".$pagetmp['ziyuan']."(".$pagetmp['quality'].") 有新资源，但是没有高质量的资源</br>";
								$upold.=$tmp;
							}
						}
					}
				}
			}
		}
		print_r($updatenew);
		//print_r($up);
		$listhtml .= $upnew.$upnewq.$upold;
		$DH_output_content = str_replace("%newlist%",$listhtml,$DH_output_content);
		$DH_output_file = $DH_output_path . 'update.html';
		dh_file_put_contents($DH_output_file,$DH_output_content);
		
		if($updatenew!='')
		{
			$sub = "movie002.com 的每日资源提醒 ".gmdate('Ymd_H_i_s', time());
			postmail('zhonghua0747@163.com',$sub,'',$updatenew);
		}
	}
}

function dh_save($cycle)
{
	//删除上次的记录
	$sql="delete from pagetmp where cycle=$cycle";
	dh_mysql_query($sql);
	$sql="OPTIMIZE TABLE pagetmp";
	dh_mysql_query($sql);
	//插入最新的结果
	$sql="insert into pagetmp (select id,$cycle as cycle,quality,ziyuan,yugao,yingping,zixun,updatetime,hot from page where ziyuan>0 and ( hot>5 or DATE_SUB(CURDATE(), INTERVAL 2 MONTH) <= date(pubdate)) and DATE_SUB(CURDATE(), INTERVAL 1 MONTH) <= date(updatetime))";
	dh_mysql_query($sql);
}
?>
