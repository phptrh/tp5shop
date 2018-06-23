<?php
namespace app\home\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Member extends Validate{
     
    //验证规则
    protected $rule=[
    	'username'=>'require|unique:member',
        'email'=>'require|email',
        'captcha'=>'require|captcha:2',
        'phone'=>'require|unique:member',
        'password'=>'require',
    	'repassword'=>'require|confirm:password'
    ];

    //验证提示信息
    protected $message=[
        'username.require'=>'用户名必填',
        'username.unique'=>'用户名重复',
        'email.email'=>'邮箱格式不正确',
        'email.require'=>'邮箱必填',
        'captcha.require'=>'验证码必填',
        'captcha.captcha'=>'验证码错误',
        'phone.require'=>'手机号必填',
        'phone.unique'=>'手机号占用',
        'password.require'=>'密码必填',
        'repassword.confirm'=>'两次密码不一致',
    ];

    //验证场景
    protected $scene=[
        'add'=>['username','email','password','repassword'=>'confirm'],
        'phone'=>['phone'],
        'login'=>['username'=>'require','password','captcha'],
        'email'=>['email']
    ];
}