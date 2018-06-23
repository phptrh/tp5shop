<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use think\Db;
use app\home\model\Goods;
class CartController extends Controller {
    //添加购物车商品
	public function ajaxAddCart(){
		if(request()->isAjax()){
   			if(!session('member_id')){
   				return json(['code'=>-1,'message'=>'请先登录']);
   			}
   			$goods_id=input('goods_id');
   			$goods_attr_ids=input('goods_attr_ids');
   			$goods_number=input('goods_number');
   			$cart=new \cart\Cart;
   			$cart->addCart($goods_id,$goods_attr_ids,$goods_number);
   			return json(['code'=>200,'message'=>'添加成功,请到购物车结算']);
		}
	}		
	//购物车列表
	public function cartList(){
		if(!session('member_id')){
			$this->error("请先登录",'home/public/login');
		}
		$goodsModel=new Goods();
		$cartData=$goodsModel->getCartGoodsData();
		return $this->fetch('',['cartData'=>$cartData]);
	}
	//删除购物车商品
	public function ajaxDelCart(){
		if(request()->isAjax()){
  			$goods_id=input('goods_id');
  			$goods_attr_ids=input('goods_attr_ids');
  			$cart=new \cart\Cart();
  			$result=$cart->delCart($goods_id,$goods_attr_ids);
  			if($result){
  				return json(['code'=>200,'message'=>'删除成功']);
  			}else{
  				return json(['code'=>-1,'message'=>'删除失败']);
  			}
		}
	}	
	//清空购物车
	public function chearCart(){
		if(request()->isAjax()){
			$cart=new \cart\Cart();
  			$result=$cart->chearCart();
  			if($result){
  				return json(['code'=>200,'message'=>'清空购物车成功']);
  			}else{
  				return json(['code'=>-1,'message'=>'清空购物车失败']);
  			}
		}
	}
	//更改购物车商品数量
	public function changeCartNum(){
		$goods_id=input('goods_id');
		$goods_attr_ids=input('goods_attr_ids');
		$goods_number=input('goods_number');
		$cart=new \cart\Cart();
		$result=$cart->changeCartNum($goods_id,$goods_attr_ids,$goods_number);
		if($result){
			return json(['code'=>200,'message'=>'修改成功']);
		}else{
			return json(['code'=>-1,'message'=>'修改失败']);
		}
	}
}