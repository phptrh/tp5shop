<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Auth extends Validate{
     
    //验证规则
    protected $rule=[
    	'auth_name'=>'require|unique:auth',
        'auth_c'=>'require',
    	'auth_a'=>'require'
    ];

    //验证提示信息
    protected $message=[
        'auth_name.require'=>'权限名称必填',
    	'auth_name.unique'=>'权限名称重复',
        'auth_c.require'=>'控制器名必填',
    	'auth_a.require'=>'控制器方法名必填'
    ];

    //验证场景
    protected $scene=[
        'add'=>['auth_name','auth_c','auth_a'],
    	'upd'=>['auth_name','auth_c','auth_a'],
        'ding'=>['auth_name']
    ];
}