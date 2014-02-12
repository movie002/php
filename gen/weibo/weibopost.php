<?php
session_start();

include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );


//if( isset($_REQUEST['text']) ) 

$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done
$uid_get = $c->get_uid();
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息

$text=getweibotext();
$ret = $c->update( $text );	//发送微博



if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) 
{
	echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
}
else
{
	echo "<p>发送成功</p>";
}

if( is_array( $ms['statuses'] ) )
{
	foreach( $ms['statuses'] as $item ) 
	{
		$item['text'];
	}
}


function getweibotext()
{
	
}
?>