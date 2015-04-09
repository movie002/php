<?php
	//require_once('360safe/360webscan.php'); // 注意文件路径
	if(is_file('360safe/360webscan.php'))
	{
		require_once('360safe/360webscan.php');
	}
	
	$active='';
	$q='';
	$aid='';
	if( isset($_REQUEST['q']))
	{
		$q = htmlspecialchars($_REQUEST['q']);
		$active=$q;
	}
	if( isset($_REQUEST['aid']))
	{
		$aid = htmlspecialchars($_REQUEST['aid']);
		$active='资源网站 '.$aid.' 的最新资源列表';
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>搜索结果-电影小二网</title>
	<meta name="keywords" content="二手电影网中包含 <?php echo $active ?> 搜索结果" />
	<meta name="description" content="二手电影网中包含 <?php echo $active ?> 的搜索结果的展示" />
	%meta%		
</head>

<body>
	<div id="wrapper" class="width_1">
		<div id="header">
			%head%
		</div>
		<!-- header -->
		<div id="middle" class="width_2">
			<div id="title">
				<div style="border-bottom:1px solid #ccc;padding:0 0 10px 5px;font-weight:500">您的位置： <a href="%home%" title="回到首页"> 首页</a> >> <span class="cred">"<?php echo $active ?>"</span> 的搜索结果</div>				
			</div>		
			<div id="content"  class="width_3 listcontent">
				<ul class="f12px">
					<?php
						if($active=='')
						{
							echo "</br>".'  <div style="text-align:center">关键词不可以没有哦！请更换关键词重新搜索，谢谢！</div>'."</br>";
						}
						else
						{
							require("../php/genv/common.php");
							require("../php/common/base.php");
							require("../php/common/dbaction.php");
							require("../php/config.php");
							$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
							mysql_select_db($dbname, $conn) or die('选择数据库失败');
							mysql_query("set names utf8;");
							
							$DH_input_html  = $DH_html_path . 'list_each.html';
							$DH_output_content = dh_file_get_contents("$DH_input_html");
							
							if($aid!='')
								$sql="select l.link,l.title,l.updatetime,l.author,l.pageid,l.linkquality ,l.linkway,p.hot,p.catcountry,p.cattype from link l,page p where l.pageid=p.id and l.author like '$aid' order by l.updatetime desc  limit 0,60";
							if($q!='')
								$sql="select * from page where title like '%$q%' or aka like '%$q%' order by updatetime desc  limit 0,15";
							
							$results=dh_mysql_query($sql);
							$count=0;
							if($results)
							{	
								$liout='';
								while($row = mysql_fetch_array($results))
								{	
									$count ++;
									$page_path = output_page_path($DH_html_url,$row['pageid']);
									if($q!='')
									{
										$DH_output_content_page = dh_replace_snapshot('list',$row,$DH_output_content,true);
										//$page_path = output_page_path($DH_html_url,$row['id']);
										$DH_output_content_page = str_replace("%title_link%",$page_path,$DH_output_content_page);	
										echo $DH_output_content_page;
									}
									else
									{
										$updatef = date("m-d",strtotime($row['updatetime']));
										$lieach = '<li><span>'.$countrymeta.'</span> <span class="width90pre">【'.$linkway[$row['linkway']].'】<a href="'.$row['link'].'" target="_blank">'.$row['title'].'['.$row['author'].']</a></span> <span class="rt100v2"><a href="'.$page_path.'" target="_blank" rel="nofollow">汇总页面</a></span><span class="rt60v2">'.$row['hot'].' </span> <span class="rt5v2" > '.$updatef.'</span></li>';
										echo $lieach;
									}
								}
							}
							mysql_close($conn);
							if($count==0)
								echo "</br>".'  <div style="text-align:center">好像没有相关资源哦！请更换关键词重新搜索，或者耐心等待，只要不断关注电影小二网，就会第一时间得到资源，您不会失望哦！</div>'."</br>";

								echo '';
						}
					?>
				</ul>
			</div>
			<!-- content -->
			<div id="sidebar" class="clearfix">
				%side%
			</div>
		<!-- middle -->
		<div id="footer">
			%foot%
		</div>
	</div>
	<!-- wrapper -->
	<script type="text/javascript">
	%footjs%
	window.onload = function ()
	{
		// 公共的函数
		document.getElementById('submittext').focus();
		startTime();
		loadimg();
		showImgs();
		cnzz();
	};
	</script>	
</body>
</html>