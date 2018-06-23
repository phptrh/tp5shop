<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Goods;
use app\home\model\Order;
use app\home\model\OrderGoods;
use think\Db;
class OrderController extends Controller {
	//我的订单页面,订单列表
    public function selfOrder(){
    	$member_id=session('member_id');
		if(!$member_id){
			$this->error("请先登录",url('home/public/login'));
		}
		$orderData=Order::where('member_id',$member_id)->select()->toArray();
    	return $this->fetch('',['orderData'=>$orderData]);
    }
    //订单页面
	public function orderInfo(){
		$member_id=session('member_id');
		if(!$member_id){
			$this->error("请先登录",url('home/public/login'));
		}
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Order',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			$postData['member_id']=$member_id;
			$this->_writeOrder($postData);
		
		}
		$goodsModel=new Goods();
		$cartData=$goodsModel->getCartGoodsData();
		return $this->fetch('',['cartData'=>$cartData]);
	}
	//将订单信息写入订单表和订单商品表
	private function _writeOrder($postData){
		$goodsModel=new Goods();
		$cartData=$goodsModel->getCartGoodsData();
		$totalPrice=0;
		//计算商品总价
		foreach($cartData as $cart){
			$totalPrice+=($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_price'])*$cart['goods_number'];
		}
		$postData['total_price']=$totalPrice;
		$postData['order_id']=date('Ymd').time().uniqid();
		$postData['total_price']=$totalPrice;
		//开始事务
		Db::startTrans();
		try{
		    $order=Order::create($postData);
		    if(!$order){
		    	throw new \Exception("订单表入库失败");
		    }
		    foreach($cartData as $cart){
		    	$data=[
		    		'order_id'=>$postData['order_id'],
		    		'goods_id'=>$cart['goods_id'],
		    		'goods_attr_ids'=>$cart['goods_attr_ids'],
		    		'goods_number'=>$cart['goods_number'],
		    		'goods_price'=>($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_price'])*$cart['goods_number']
		    	];
		    	$ordergoods=OrderGoods::create($data);
		    	$condition=[
		    		'goods_id'=>$cart['goods_id'],
		    		'goods_number'=>['>=',$cart['goods_number']]
		    	];
		    	$num=Goods::where($condition)->setDec('goods_number',$cart['goods_number']);
		    	if(!$ordergoods || !$num){
		    		throw new \Exception("订单商品入库失败或库存不足");
		    	}
		    }
		    // 提交事务
		    Db::commit(); 
		    $cart=new \cart\Cart();
		    $cart->chearCart();
		} catch (\Exception $e) {
		    // 回滚事务
		    Db::rollback();
		    $this->error($e->getMessage());
		}
		$this->_payMoney($postData['order_id'],$postData['total_price']);
	}
	//订单列表页面点击支付
	public function payMoney(){
		$id=input('id');
		$orderData=Order::find($id);
		if($orderData){
			$this->_payMoney($orderData['order_id'],$orderData['total_price']);
		}else{
			$this->error("支付异常,请稍后再试");
		}
	}
	//唤起支付页面
	private function _payMoney($order_id,$totalPrice,$title='京西商城在线支付',$content='不可描述'){
		$payData=[
			'WIDout_trade_no'=>$order_id,
			'WIDtotal_amount'=>$totalPrice,
			'WIDsubject'=>$title,
			'WIDbody'=>$content
		];
		include '../extend/alipay/pagepay/pagepay.php';
	}
	//支付成功get传值的同步
	public function returnurl(){
		require_once("../extend/alipay/config.php");
		require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
		$arr=input('get.');
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);
		if($result) {//验证成功
			
			//请在这里加上商户的业务逻辑程序代码
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

			//商户订单号
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);

			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);
				
			$data=[
				'pay_status'=>1,
				'ali_order_id'=>$trade_no
			];
			$result=Order::where('order_id',$out_trade_no)->update($data);
			if($result){
				$this->success("支付成功",url("home/order/selforder"));
			}else{
				$this->error("支付失败",url("home/order/orderinfo"));
			}
		}
		else {
		    //验证失败
		    echo "验证失败";
		}
	}
	//支付成功post传值异步通知
	public function notifyurl(){
		require_once 'config.php';
		require_once 'pagepay/service/AlipayTradeService.php';

		$arr=input('post.');
		$alipaySevice = new AlipayTradeService($config); 
		$alipaySevice->writeLog(var_export($_POST,true));
		$result = $alipaySevice->check($arr);
		if($result) {//验证成功
			//请在这里加上商户的业务逻辑程序代
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			//商户订单号
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);

			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);
				
			$data=[
				'pay_status'=>1,
				'ali_order_id'=>$trade_no
			];
			$result=Order::where('order_id',$out_trade_no)->update($data);
			if($result){
			echo 'success';
			}
		}else {
		    //验证失败
		    echo "验证失败";
		}
	}
}