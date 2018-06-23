<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Category;
use app\home\model\Goods;
class CategoryController extends Controller {
    
	public function index(){
		$cat_id=input('cat_id');
		$catModel=new Category();
		$lists=$catModel->getFamilyCats($cat_id);
		//获取所有的分类
		$data=$catModel->select()->toArray();
		$cats=[];
		foreach($data as $cat){
			$cats[$cat['cat_id']]=$cat;
		}
		//获取所有分类的cat_id及其子类的cat_id
		$children=[];
		foreach($data as $cat){
			$children[$cat['pid']][]=$cat['cat_id'];
		}
		//获取当前cat_id下的所有子类id
		$sonid=$catModel->getCatSonsId($cat_id);
		$sonid[]=intval($cat_id);
		$condition=[
			'is_delete'=>0,
			'is_sale'=>1,
			'cat_id'=>['in',$sonid]
		];
		$goods=Goods::where($condition)->select();
		return $this->fetch('',[
				'lists'=>$lists,
				'cats'=>$cats,
				'children'=>$children,
				'goods'=>$goods
			]);
	}
}