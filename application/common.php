<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
error_reporting(E_ERROR);
//发送短信函数
function sendSms($to,$datas,$tempId)
{
	include_once("../extend/sendSms/CCPRestSmsSDK.php");
	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
	$accountSid= '8a216da863cde6f90163f185cbb70e4c';
	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
	$accountToken= '04542e5ab57241ca86a395b7e12a74cb';
	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
	$appId='8a216da863cde6f90163f185cc150e53';
	//请求地址
	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
	//生产环境（用户应用上线使用）：app.cloopen.com
	$serverIP='app.cloopen.com';
	//请求端口，生产环境和沙盒环境一致
	$serverPort='8883';
	//REST版本号，在官网文档REST介绍中获得。
	$softVersion='2013-12-26';
     // 初始化REST SDK
     //global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     // 发送模板短信
    // echo "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     return json($result);
}

//发送邮件
function sendEmail($to,$title,$content){
	// 实例化
	include "../extend/sendEmail/class.phpmailer.php";
	$pm = new PHPMailer();

	// 服务器相关信息
	$pm->Host = 'smtp.163.com'; // SMTP服务器
	$pm->IsSMTP(); // 设置使用SMTP服务器发送邮件
	$pm->SMTPAuth = true; // 需要SMTP身份认证
	$pm->Username = 'trh9644'; // 登录SMTP服务器的用户名
	$pm->Password = 'trh9644'; //授权码 登录SMTP服务器的密码

	// 发件人信息
	$pm->From = 'trh9644@163.com';
	$pm->FromName = '左宗主';

	// 收件人信息
	$pm->AddAddress($to); // 添加一个收件人
	//$pm->AddAddress('wangwei2@itcast.cn', 'wangwei2'); // 添加另一个收件人

	$pm->IsHTML(true); //支持富文本html
	$pm->CharSet = 'utf-8'; // 内容编码
	$pm->Subject = $title; // 邮件标题
	$pm->MsgHTML($content); // 邮件内容
	if($pm->Send()){
	   return true;
	}else {
	   return false;
	}
}