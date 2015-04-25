<?php
require("../../config.php");
require("../../common/base.php");
require("../../common/curl.php");
require("../../common/dbaction.php");


require("bbs.1lou.com.php");
require("www.5281520.com.php");
require("www.bt5156.com.php");
require("www.bttiantang.com.php");
require("www.dy2018.com.php");
require("www.ed2000.com.php");
require("www.etdown.net.php");
require("www.piaohua.com.php");
require("www.somag.net.php");
require("www.mp4ba.com.php");
require("www.dy558.com.php");
require("www.quanji.com.php");
require("www.icili.com.php");
require("www.ygdy8.net.php");
require("www.vvtor.com.php");
//require("www.hd180.com.php");
require("www.6vhao.com.php");
require("www.66bt.cc.php");

header('Content-Type:text/html;charset= UTF-8'); 
date_default_timezone_set('PRC');
set_time_limit(3600);
$conn=mysql_connect ($dbip, $dbuser, $dbpasswd) or die('数据库服务器连接失败：'.mysql_error());
mysql_select_db($dbname, $conn) or die('选择数据库失败');
mysql_query("set names utf8;");

$id=0;
if(isset($_REQUEST['id']))
	$id = $_REQUEST['id'];
echo 'this id is: '.$id."\n";

//bbs_1ou_com_php();
//www_bt5156_com_php();
if($id==0||$id==1){echo "</br>id: 1   ";www_5281520_com_php();}
if($id==0||$id==2){echo "</br>id: 2   ";www_bttiantang_com_php();}
if($id==0||$id==3){echo "</br>id: 3   ";www_dy2018_com_php();}
if($id==0||$id==4){echo "</br>id: 4   ";www_ed2000_com_php();}
if($id==0||$id==5){echo "</br>id: 5   ";www_etdown_net_php();}
if($id==0||$id==6){echo "</br>id: 6   ";www_piaohua_com_php();}
if($id==0||$id==7){echo "</br>id: 7   ";www_mp4ba_com_php();}
if($id==0||$id==8){echo "</br>id: 8   ";www_dy558_com_php();}
if($id==0||$id==9){echo "</br>id: 9   ";www_quanji_com_php();}
if($id==0||$id==10){echo "</br>id: 10   ";www_icili_com_php();}
if($id==0||$id==11){echo "</br>id: 11   ";www_somag_net_php();}
if($id==0||$id==12){echo "</br>id: 12   ";www_ygdy8_net_php();}
if($id==0||$id==13){echo "</br>id: 13   ";www_vvtor_com_php();}
//if($id==0||$id==14){echo "</br>id: 14   ";www_hd180_com_php();}
if($id==0||$id==15){echo "</br>id: 15   ";www_6vhao_com_php();}
if($id==0||$id==16){echo "</br>id: 16   ";www_66bt_cc_php();}

mysql_close($conn);
?>
