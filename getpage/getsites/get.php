<?php 
require("getsites/v360.php");
require("getsites/v2345.php");
require("getsites/vbaidu.php");
require("getsites/www.cili.so.php");
require("getsites/bt.shousibaocai.com.php");
require("getsites/yugaopian.com.php");
require("getsites/gewara.php");
require("getsites/wangpiao.php");
require("getsites/mtime.php");
require("getsites/m1905.php");


function getallsites($title,$aka,$cattype,$updatetime,$ids,$pageid)
{
	//get_m1905($title,$aka,$cattype,$updatetime,$ids,$pageid);
	//get_mtime($title,$aka,$cattype,$updatetime,$ids,$pageid);
	get_v360($title,$aka,$cattype,$updatetime,$pageid);
	//get_v2345($title,$aka,$cattype,$updatetime,$pageid);
	//get_vbaidu($title,$aka,$cattype,$updatetime,$pageid);
	//get_cili($title,$aka,$cattype,$updatetime,$pageid);
	//get_shousibaocai($title,$aka,$cattype,$updatetime,$pageid);
	//get_yugaopian($title,$aka,$cattype,$updatetime,$pageid);	
}
?>  