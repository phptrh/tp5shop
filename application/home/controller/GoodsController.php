<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Goods;
use app\home\model\Category;
use think\Db;
class GoodsController extends Controller {
    //商品详情显示
	public function detail(){
		$goods_id=input('goods_id','0','intval');
		$goodsData=Goods::find($goods_id);
		$catModel=new Category();
		//获取商品分类显示面包屑
		$fimalyCats=$catModel->getFamilyCats($goodsData['cat_id']);
		//将json格式图片路径转为数组
		$goodsData['goods_img']=json_decode($goodsData['goods_img']);
		$goodsData['goods_middle']=json_decode($goodsData['goods_middle']);
		$goodsData['goods_thumb']=json_decode($goodsData['goods_thumb']);
		//查询商品的单选属性遍历到模板中
		$condition=['goods_id'=>$goods_id,'attr_type'=>1];
		$single=Db::name('goods_attr')->alias('t1')
									  ->field('t1.*,t2.attr_name')
									  ->where($condition)
									  ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
									  ->select();
		//把有相同attr_id的属性分到一组
		$single_data=[];
		foreach($single as $attr){
			$single_data[$attr['attr_id']][]=$attr;
		}
		//查询商品的唯一属性遍历到模板中
		$condition=['goods_id'=>$goods_id,'attr_type'=>0];
		$onlyattr=Db::name('goods_attr')->alias('t1')
									  ->field('t1.*,t2.attr_name')
									  ->where($condition)
									  ->join('sh_attribute t2','t1.attr_id=t2.attr_id','left')
									  ->select();
		//获取浏览历史商品
		$goodsModel=new Goods();
		$history=$goodsModel->addHistoryGoods($goods_id);
		$history=implode(',',$history);
		$condition=['is_delete'=>0,'is_sale'=>1,'goods_id'=>['in',$history]];
		$historyGoods=$goodsModel->where($condition)->order("field(goods_id,$history)")->select()->toArray();
		return $this->fetch('',[
				'goodsData'=>$goodsData,
				'fimalyCats'=>$fimalyCats,
				'single_data'=>$single_data,
				'onlyattr'=>$onlyattr,
				'historyGoods'=>$historyGoods
			]);
	}
	
}