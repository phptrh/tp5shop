<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class User extends Validate{
     
    //验证规则
    protected $rule=[
        'username'=>'require|unique:user',
    	'role_id'=>'require',
    	'password'=>'require',
        'repassword'=>'require|confirm:password',
    	'captcha'=>'require|captcha'
    ];

    //验证提示信息
    protected $message=[
        'username.require'=>'用户名必填',
    	'role_id.require'=>'角色名必填',
    	'username.unique'=>'用户名重复',
        'password.require'=>'密码必填',
        'captcha.require'=>'验证码必填',
    	'captcha.captcha'=>'验证码错误',
    	'repassword.confirm'=>'两次密码不一致',
    	'repassword.require'=>'两次密码不一致'
    ];

    //验证场景
    protected $scene=[
    	'add'=>['username','password','repassword','role_id'],
        'upd'=>['username'=>'require|unique:user','role_id'],
    	'login'=>['username'=>'require','password'=>'require','captcha']
    ];
}