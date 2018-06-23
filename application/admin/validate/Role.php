<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Role extends Validate{
     
    //验证规则
    protected $rule=[
    	'role_name'=>'require|unique:role',
    ];

    //验证提示信息
    protected $message=[
        'role_name.require'=>'角色名称必填',
        'role_name.unique'=>'角色名称重复',
    ];

    //验证场景
    protected $scene=[
        'add'=>['role_name'],
        'upd'=>['role_name']
    ];
}