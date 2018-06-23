<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Type extends Validate{
     
    //验证规则
    protected $rule=[
    	'type_name'=>'require|unique:type',
    ];

    //验证提示信息
    protected $message=[
        'type_name.require'=>'商品类型名称必填',
        'type_name.unique'=>'商品类型名称重复',
    ];

    //验证场景
    protected $scene=[
        'add'=>['type_name'],
        'upd'=>['type_name']
    ];
}