<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Category extends Validate{
     
    //验证规则
    protected $rule=[
    	'cat_name'=>'require|unique:category',
        'pid'=>'require',
    ];

    //验证提示信息
    protected $message=[
        'cat_name.require'=>'分类名称必填',
    	'cat_name.unique'=>'分类名称重复',
        'pid.require'=>'父分类必填',
    ];

    //验证场景
    protected $scene=[
        'add'=>['cat_name','pid'],
        'upd'=>['cat_name','pid'],
    ];
}