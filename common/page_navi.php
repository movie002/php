<?php
# 分页
function dh_pagenavi($range,$max_page,$link,$paged=1)
{
	$output = '';
	$output=$output."<a href='" . dh_pagenum_link($link,1) . "' class='extend' title='跳转到首页'> 返回首页 </a>";	
	if($paged != 1)
	{
		$output=$output."<a href='" . dh_pagenum_link($link,$paged-1) . "' class='extend' title='上一页'> 上一页 </a>";
	}
	if($max_page > $range)
	{
		if($paged < $range)
		{	
			for($i = 1; $i <= ($range + 1); $i++)
			{
				$output=$output."<a href='" . dh_pagenum_link($link,$i) ."'";
				if($i==$paged)
				$output=$output." class='current'";
				$output=$output.">".$i."</a>";
			}
			$output=$output." ... ";
		}
		elseif($paged >= ($max_page - ceil(($range/2))))
		{
			$output=$output." ... ";
			for($i = $max_page - $range; $i <= $max_page; $i++)
			{
				$output=$output."<a href='" . dh_pagenum_link($link,$i) ."'";
				if($i==$paged)
				$output=$output." class='current'";
				$output=$output.">".$i."</a>";
			}
		}
		elseif($paged >= $range && $paged < ($max_page - ceil(($range/2))))
		{
			$output=$output." ... ";
			for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++)
			{
				$output=$output."<a href='" . dh_pagenum_link($link,$i) ."'";
				if($i==$paged) 
				$output=$output." class='current'";
				$output=$output.">".$i."</a>";
			}
			$output=$output." ... ";
		}
	}
	else
	{
		for($i = 1; $i <= $max_page; $i++)
		{
			$output=$output."<a href='" . dh_pagenum_link($link,$i) ."'";
			if($i==$paged)
			$output=$output." class='current'";
			$output=$output.">".$i."</a>";
		}
	}
	if($paged != $max_page)
	{
		$output=$output."<a href='" . dh_pagenum_link($link,$paged+1) . "' class='extend' title='下一页'> 下一页 </a>";
	}
	$output=$output. "<a href='" . dh_pagenum_link($link,$max_page) . "' class='extend' title='跳转到最后一页'> 最后一页($max_page 页) </a>";
	return $output;
}
?>