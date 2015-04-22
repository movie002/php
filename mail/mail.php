<?php
function postmail($to,$sub,$zipfile,$body)
{
    //$to 表示收件人地址 $subject 表示邮件标题 $body 表示邮件正文
    //error_reporting(E_ALL);
    //error_reporting(E_STRICT);
    date_default_timezone_set("Asia/Shanghai");//设定时区东八区
    require_once('class-phpmailer.php');	
	$mail = new PHPMailer(); //new一个PHPMailer对象出来
    $mail->CharSet ="UTF-8";//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug  = 1; // 启用SMTP调试功能 1 = errors and messages  2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = "ssl";                 // 安全协议
    $mail->Host       = "smtp.zoho.com";      // SMTP 服务器
    $mail->Port       = 465;                   // SMTP服务器的端口号
    $mail->Username   = "a@movie002.com";  // SMTP服务器用户名
    $mail->Password   = "vina125114";        // SMTP服务器密码
    $mail->SetFrom('a@movie002.com', 'movie002.com');
    $mail->AddReplyTo("a@movie002.com","movie002.com");
    $mail->Subject    = $sub;
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer! - From movie002.com"; // optional, comment out and test
	//$body = eregi_replace("[\]",'',$body); //对邮件内容进行必要的过滤
	$mail->Body='您好！:</bt>'.$body;
//    $mail->MsgHTML($body);
    $address = $to;
    $mail->AddAddress($address, "收件人名称");
	if($zipfile!='')
		$mail->AddAttachment($zipfile);      // attachment 		
    if(!$mail->Send())
	{
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
	else
	{
        echo "Message sent!恭喜，邮件发送成功！";
    }
}
