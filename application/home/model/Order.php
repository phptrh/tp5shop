<?php
namespace app\home\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Order extends Model {
     
    //表主键 
    protected $pk="id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;

}