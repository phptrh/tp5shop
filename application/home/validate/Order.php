<?php
namespace app\home\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Order extends Validate{
     
    //验证规则
    protected $rule=[
    	'receiver'=>'require',
        'address'=>'require',
        'tel'=>'require',
        'zcode'=>'require',
    ];

    //验证提示信息
    protected $message=[
        'receiver.require'=>'收货人必填',
        'address.require'=>'收货地址必填',
        'tel.require'=>'电话必填',
        'zcode.require'=>'邮编必填'
        
    ];

}