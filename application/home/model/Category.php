<?php
namespace app\home\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
class Category extends Model {
     
    //表主键 
    protected $pk="cat_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;
    
    public function getFamilyCats($cat_id){
    	$cats=$this->select()->toArray();
    	return $this->_getFamilyCats($cats,$cat_id);
    }
    private function _getFamilyCats($data,$cat_id){
    	static $lists=[];
    	foreach($data as $k=>$cat){
    		if($cat['cat_id']==$cat_id){
    			$lists[]=$cat;
    			unset($cat[$k]);
    			$this->_getFamilyCats($data,$cat['pid']);
    		}
    	}
    	return array_reverse($lists);
    }
    //获取当前cat_id下的所有子类id
    public function getCatSonsId($cat_id){
    	$cats=$this->select()->toArray();
    	return $this->_getCatSonsId($cats,$cat_id);
    }
    private function _getCatSonsId($data,$cat_id){
    	static $sonid=[];
    	foreach($data as $k=>$cat){
    		if($cat['pid']==$cat_id){
    			$sonid[]=$cat['cat_id'];
    			unset($data[$k]);
    			$this->_getCatSonsId($data,$cat['cat_id']);
    		}
    	}
    	return $sonid;
    }
}