<?php
namespace app\admin\validate;  //定义模型的命名空间  
use think\Validate;  // 引入验证器类
class Order extends Validate{
     
    //验证规则
    protected $rule=[
        'company'=>'require',
    	'number'=>'require',
    ];

    //验证提示信息
    protected $message=[
        'company.require'=>'请选择物流公司',
        'number.require'=>'快递单号必填',
    ];

   
}