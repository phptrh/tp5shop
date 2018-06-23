<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Category;
use app\home\model\Goods;
class IndexController extends Controller {
    
	public function index(){
		$cats=Category::where(['pid'=>0,'is_show'=>1])->select();
		$catdata=Category::select()->toArray();
		$lists=[];
		foreach($catdata as $cat){
			$lists[$cat['cat_id']]=$cat;
		}
		$children=[];
		foreach($catdata as $cat){
			$children[$cat['pid']][]=$cat['cat_id'];
		}
		//获取推荐热卖商品
		$goodsModel=new Goods();
		$is_hot=$goodsModel->getTypeGoods('is_hot');
		$is_new=$goodsModel->getTypeGoods('is_new');
		$is_best=$goodsModel->getTypeGoods('is_best');
		$is_crazy=$goodsModel->getTypeGoods('is_crazy');
		$is_guess=$goodsModel->getTypeGoods('is_guess');
		return $this->fetch('',[
					'cats'=>$cats,
					'lists'=>$lists,
					'children'=>$children,
					'is_hot'=>$is_hot,
					'is_new'=>$is_new,
					'is_best'=>$is_best,
					'is_crazy'=>$is_crazy,
					'is_guess'=>$is_guess,
				]);
	}
}