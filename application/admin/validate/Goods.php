<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Goods extends Validate{
     
    //验证规则
    protected $rule=[
    	'goods_name'=>'require|unique:goods',
        'goods_price'=>'require',
        'goods_number'=>'require|regex:\d+',
    	'cat_id'=>'require'
    ];

    //验证提示信息
    protected $message=[
        'goods_name.require'=>'商品名称必填',
    	'goods_name.unique'=>'商品名称重复',
        'goods_price.require'=>'商品价格必填',
        'goods_number.require'=>'商品库存必填',
        'goods_number.regex'=>'商品库存大于等于0',
        'cat_id.require'=>'商品分类必填',
    ];

    //验证场景
    protected $scene=[
        'add'=>['goods_name','goods_price','goods_number','cat_id'],
        'upd'=>['goods_name','goods_price','goods_number','cat_id'],
    ];
}