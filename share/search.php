<?php
	//require_once('360safe/360webscan.php'); // 注意文件路径
	//if(is_file('360safe/360webscan.php'))
	//{
	//	require_once('360safe/360webscan.php');
	//}
  $pagenum = 10;
	$q='';
	$p=1;
	if( isset($_GET['q']))
	{
		$q = htmlspecialchars($_GET['q']);
	}
 if( isset($_GET['p']))
 {
   	$p = $_GET['p'];
 }	

 function dh_pagenum_link($link,$page)
 {
 	return $link.$page;
 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>搜索结果-%DH_name%</title>
	<meta name="keywords" content="%DH_name%中包含 <?php echo $active ?> 搜索结果" />
	<meta name="description" content="%DH_name%中包含 <?php echo $active ?> 的搜索结果的展示" />
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
				<div style="border-bottom:1px solid #ccc;padding:0 0 10px 5px;font-weight:500">您的位置： <a href="%home%" title="回到首页"> 首页</a> >> <span class="cred">"<?php echo $q ?>"</span> 的搜索结果 尝试更精确的 <a href="http://s.yingsoso.com/s.php?q=<?php echo $q ?>">影粉搜搜的搜索结果</a> </div>	
			</div>		
			<div id="content"  class="width_3 listcontent">
				<ul class="f12px">
					<?php
						if($q=='')
						{
							echo "</br>".'  <div style="text-align:center">关键词不可以没有哦！请更换关键词重新搜索，谢谢！</div>'."</br>";
						}
						else
						{
							require("../php/genv/common.php");
							require("../php/common/base.php");
							require("../php/common/dbaction.php");
							require("../php/config.php");
							require("../php/genv/config.php");
							require("../php/common/page_navi.php");
							$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
							mysql_select_db($dbname, $conn) or die('选择数据库失败');
							mysql_query("set names utf8;");
							
							$DH_input_html  = $DH_html_path . 'list_each.html';
							$DH_output_content = dh_file_get_contents("$DH_input_html");
							
              $beginnum=($p-1)*$pagenum;
							$sql="select * from page where title like '%$q%' or aka like '%$q%' order by updatetime desc  limit $beginnum , $pagenum";
							$sqlcount="select count(*) from page where title like '%$q%' or aka like '%$q%'";
							
							$results=dh_mysql_query($sql);
							$count=0;
							if($results)
							{	
								$liout='';
								while($row = mysql_fetch_array($results))
								{	
									$count ++;
									$page_path = output_page_path($DH_html_url,$row['id']);
									if($q!='')
									{
										$DH_output_content_page = dh_replace_snapshot('list',$row,$DH_output_content,true);
										//$page_path = output_page_path($DH_html_url,$row['id']);
										$DH_output_content_page = str_replace("%title_link%",$page_path,$DH_output_content_page);	
										echo $DH_output_content_page;
									}
								}
							}
              
						    $results=dh_mysql_query($sqlcount);
							$counts = mysql_fetch_array($results);
							$count = $counts[0];

                            $DH_search_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?q='.$_GET['q'].'&p=';
                            $pages=ceil($count/$pagenum);
                            $pagenavi = dh_pagenavi(5,$pages,$DH_search_url,$p);
                            echo '<div class="page_navi">'.$pagenavi.'</div>';
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
