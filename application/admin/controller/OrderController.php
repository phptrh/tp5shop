<?php
namespace app\admin\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Order;
class OrderController extends Controller {
	//订单分配物流
	public function sendWuliu(){
		$order_id=input('order_id');
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Order',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$postData['send_status']=1;
			$orderModel=new Order();
			if($orderModel->where('order_id',$order_id)->update($postData)){
				$this->success("添加成功",url("admin/order/index"));
			}else{
				$this->error("添加失败");
			}
		}
		return $this->fetch('',['order_id'=>$order_id]);
	}
    //ajax查询物流
    public function queryWuliu(){
    	if(request()->isAjax()){
    		$com=input('company');
    		$nu=input('number');
    		$key=config('wuliu_key');
    		$url="http://www.kuaidi100.com/applyurl?key={$key}&com={$com}&nu={$nu}&show=0";
    		echo file_get_contents($url);
    	}
    }	
	//订单列表
	public function index(){
		$keyword=trim(input('keyword'));
		$where='';
		if($keyword){
			$where.="order_id like '%$keyword%' or receiver like '%$keyword%' or tel like '%$keyword%' or address like '%$keyword%'";
		}
		$lists=Order::alias('t1')
						->field('t1.*,t2.username')
						->where($where)
						->join('sh_member t2','t1.member_id=t2.member_id','left')
						->paginate('3');
		if(request()->isAjax()){
			$data=[
				'pagelist'=>$lists->render(),
				'tbody'=>$this->fetch('tbody',['lists'=>$lists])
			];
			return json($data);
		}
		return $this->fetch('',['lists'=>$lists]);
	}
}