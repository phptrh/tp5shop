<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Attribute extends Model {
     
    //表主键 
    protected $pk="attr_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;

}