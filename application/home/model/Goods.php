<?php
namespace app\home\model;  //定义模型的命名空间  
use think\Model;  // 引入核心模型命名空间
use think\Db;
class Goods extends Model {
     
    //表主键 
    protected $pk="goods_id";

    //时间戳自动维护
    protected $autoWriteTimestamp=true;

    public function getTypeGoods($type,$limit=5){
    	$condition=['is_delete'=>0,'is_sale'=>1];
    	switch($type){
    		case 'is_hot':$condition['is_hot']=1;$request=$this->where($condition)->order('goods_id desc')->limit($limit)->select()->toArray();break;
    		case 'is_new':$condition['is_new']=1;$request=$this->where($condition)->order('goods_id desc')->limit($limit)->select()->toArray();break;
    		case 'is_best':$condition['is_best']=1;$request=$this->where($condition)->order('goods_id desc')->limit($limit)->select()->toArray();break;
    		case 'is_crazy':$request=$this->where($condition)->order('goods_price asc')->limit($limit)->select()->toArray();break;
    		case 'is_guess':$request=$this->where($condition)->order('rand()')->limit($limit)->select()->toArray();break;
    		default:$request=$this->where($condition)->limit($limit)->select()->toArray();break;
    	}
    	return $request;
    }
    //添加浏览记录
    public function addHistoryGoods($goods_id){
        $history=cookie('history')?cookie('history'):[];
        if($history){
            //将浏览商品的goods_id加入数组
            array_unshift($history, $goods_id);
            //去除重复的goods_id
            $history=array_unique($history);
            //最多存入5个记录
            if(count($history)>5){
                array_pop($history);
            }
        }else{
            $history[]=$goods_id;
        }
        cookie('history',$history);
        return $history;
    }
    public function getCartGoodsData(){
        $cart=new \cart\Cart();
        $cartInfo=$cart->getCart();
        //cartData中的goods_id和goods_attr_ids信息取出
        $cartData=[];
        foreach($cartInfo as $k=>$data){
            $arr=explode('-',$k);
            $cartData[]=[
                    'goods_id'=>$arr[0],
                    'goods_attr_ids'=>$arr[1]?:'',
                    'goods_number'=>$data
            ];
        }
        //循环取出商品信息
        foreach($cartData as $k=>$data){
            $cartData[$k]['goodsInfo']=Db::name('goods')->find($data['goods_id']);
            $cartData[$k]['attrInfo']=Db::name('goods_attr')
                                        ->alias('t1')
                                        ->field("sum(t1.attr_price) as attr_price,GROUP_CONCAT(t2.attr_name,':',t1.attr_value  separator '<br>') as singleAttr")
                                        ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
                                        ->where('goods_attr_id','in',$data['goods_attr_ids'])
                                        ->find();
        }
        return $cartData;
    }
}