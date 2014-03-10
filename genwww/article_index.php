<?php
function article_index($content) 
{
    $matches = array();
    $ul_li = '';
    $r = "/<(h[2-5])>([^<]+)<\/(h[2-5])>/im";
    if(preg_match_all($r, $content, $matches)) 
	{
        foreach($matches[2] as $num => $title) 
		{
			//if($num==0)
			if(true)
			{
				$content = str_replace($matches[0][$num], '<'.$matches[1][$num].' id="title_'.$num.'">'.$title.'</'.$matches[3][$num].'>', $content);
			}
			else
			{
				$content = str_replace($matches[0][$num], '<div id="content_title"><'.$matches[1][$num].' id="title_'.$num.'">'.$title.'</'.$matches[3][$num].'><span id="article_index_top"><a href="#article_index">top</a></span></div>', $content);
			}
			if($matches[1][$num] == 'h2')
				$ul_li .= '<li class="level2"><a href="#title_'.$num.'" title="'.$title.'">'.$title."</a></li>\n";
			else if($matches[1][$num] == 'h3')
				$ul_li .= '<li class="level3"><a href="#title_'.$num.'" title="'.$title.'">'.$title."</a></li>\n";
        }
		$ul_li .= '<li class="level2"><a href="#comment" title="留言">x. 留言</a></li>'."\n";
		
        $content = '<div id="article_index" style="right:10px;top:15px">
					<div id="index_title"><span id="the_index_title">正文索引</span><span id="show_index" onclick="showhide(\'show_index\',\'index_ul\',\'[ 展开 ]\',\'[ 隐藏 ]\');">[ 隐藏 ]</span></div>
					<div id="index_ul" style="display:block"><ul>' . $ul_li . '</ul></div></div>' . $content;
    }
    return $content;
}
?>