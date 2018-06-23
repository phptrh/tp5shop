<?php
namespace app\admin\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Category extends Model {
     
    //表主键 
    protected $pk="cat_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    //商品分类的无限极分类
    public function getCatsSon(){
    	$cats=Category::select()->toArray();
    	return $this->_getCatsSon($cats);
    }
    protected function _getCatsSon($data,$pid=0,$deep=1){
    	static $lists=[];
    	foreach($data as $cat){
    		if($cat['pid']==$pid){
    			$cat['deep']=$deep;
    			$lists[$cat['cat_id']]=$cat;
    			$this->_getCatsSon($data,$cat['cat_id'],$deep+1);
    		}
    	}
    	return $lists;
    }
}