<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Attribute extends Validate{
     
    //验证规则
    protected $rule=[
    	'attr_name'=>'require|unique:attribute',
        'type_id'=>'require',
    	'attr_values'=>'require'
    ];

    //验证提示信息
    protected $message=[
        'attr_name.require'=>'属性名称必填',
    	'attr_name.unique'=>'属性名称重复',
        'type_id.require'=>'所属商品类型必填',
    	'attr_values.require'=>'属性值必填'
    ];

    //验证场景
    protected $scene=[
        'add'=>['attr_name','type_id'],
        'upd'=>['attr_name','type_id'],
    	'liebiao'=>['attr_name','type_id','attr_values'],
    ];
}