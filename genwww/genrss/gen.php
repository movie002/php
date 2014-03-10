<?php
function gen_rss($lists,$num)
{
	global $DH_src_path,$DH_output_path,$DH_home_url,$DH_html_url;
	$i=0;
	
	$DH_input_html  = $DH_src_path . 'genrss/rss.xml';
	$DH_rss = dh_file_get_contents("$DH_input_html");	

	$DH_input_html  = $DH_src_path . 'genrss/rss_item.xml';
	$DH_rss_item = dh_file_get_contents("$DH_input_html");	
	
	$all='';
	$list_count=count($lists);
		
	foreach($lists as $key=>$list)
	{
		$i++;
		if($i>=$num)
			break;
		$pagesindex=$list_count - $i;
		$htmlpath = output_page_path($DH_html_url,$pagesindex);
		$item_each = str_replace("%link%",$htmlpath,$DH_rss_item);
		$timetmp = strtotime($key.'00');
		$updatetime = date("r", $timetmp);		
		$item_each = str_replace("%pubDate%",$updatetime,$item_each);
		preg_match('/<\_T>(.*?)<\/\_T>/s',$list,$matchT);
		$item_each = str_replace("%title%",$matchT[1],$item_each);
		preg_match('/<\_c>(.*?)<\/\_c>/s',$list,$matchc);
		$item_each = str_replace("%cat%",$matchc[1],$item_each);
		preg_match('/<\_b><\!\-\-(.*?)\-\-><\/\_b>/s',$list,$matchb);
		$item_each = str_replace("%description%",$matchb[1],$item_each);	
		
		$all.=$item_each;		
	}
	$DH_rss = str_replace("%items%",$all,$DH_rss);
	$DH_rss = str_replace("%lastBuildDate%",date("r",time()),$DH_rss);	
	$DH_output_file = $DH_output_path.'rss.xml';
	dh_file_put_contents($DH_output_file,$DH_rss);	
}
?>
