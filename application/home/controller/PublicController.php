<?php
namespace app\home\controller;  //定义控制器的命名空间
use think\Controller;  // 引入基础控制器类
use app\home\model\Member;
class PublicController extends Controller {
    
	public function register(){
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Member.add',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			if(md5($postData['phoneCaptcha'].config('sms_salt'))!==cookie('sms')){
				$this->error("短信验证码错误");
			}
			$memberModel=new Member();
			if($memberModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("home/public/login"));
			}else{
				$this->error("添加失败");
			}
		}
		return $this->fetch();
	}
	//发送短信
	public function sendSms(){
		if(request()->isAjax()){
			$phone=input('phone');
			$result=$this->validate(['phone'=>$phone],'Member.phone',[],true);
			if($result!==true){
				return json(['code'=>'-1','message'=>$request]);
			}
			$rand=rand(0000,9999);
			$sms=md5($rand.config('sms_salt'));
			cookie('sms',$sms,60);
			return sendSms($phone,array($rand,'2'),'1');
		}
	}
	//登录
	public function login(){
		if(request()->isPost()){
			$postData=input('post.');
			$result=$this->validate($postData,'Member.login',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			$memberModel=new Member();
			$result=$memberModel->checkUser($postData['username'],$postData['password']);
			if($result){
				if(input('goods_id')){
					$this->redirect("home/goods/detail?goods_id=".input('goods_id'));
				}
				$this->redirect("home/index/index");
			}else{
				$this->error("用户名或密码错误");
			}
		}
		return $this->fetch();
	}
	//退出登录
	public function logout(){
		session('home_username',null);
		session('member_id',null);
		$this->redirect('home/index/index');
	}
	//发送邮件找回密码
	public function findPwd(){
		if(request()->isAjax()){
			$email=input('email');
			$result=$this->validate(['email'=>$email],'Member.email',[],true);
			if($result!==true){
				return json(['code'=>-1,'message'=>implode(',', $result)]);
			}
			$memberModel=new Member();
			$userinfo=$memberModel->where('email',$email)->find();
			if($userinfo){
				$title='京西商城修改密码';
				$time=time();
				$hash=md5($userinfo['member_id'].$time.config('email_salt'));
				//拼接网址http://域名/home/public/updPwd/member_id/$userinfo['member_id']
				$href=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/home/public/updPwd/member_id/".$userinfo['member_id'].'/time/'.$time.'/hash/'.$hash;
				$content="<a href='{$href}'>点我修改密码</a>";
				if(sendEmail($email,$title,$content)){
					return json(['code'=>200,'message'=>'发送成功']);
				}else{
					return json(['code'=>-3,'message'=>'发送失败,请联系管理员']);
				}
			}else{
				return json(['code'=>-2,'message'=>'邮箱不存在']);
			}
		}
		return $this->fetch();
	}
	//修改密码
	public function updPwd(){
		if(request()->isAjax()){
			$postData=input('post.');
			$memberModel=new Member();
			$result=$memberModel->isUpdate(true)->allowField(true)->save($postData);
			if($result!==false){
				return json(['code'=>200,'message'=>'修改成功,转到登录页面']);
			}else{
				return json(['code'=>-1,'message'=>'修改失败']);
			}
		}
		$time=input('time');
		$hash=input('hash');
		$member_id=input('member_id');
		if(md5($member_id.$time.config('email_salt'))!=$hash){
			die('无效的链接地址');
		}
		if($time+120<time()){
			die('链接地址已失效');
		}
		return $this->fetch('',['member_id'=>$member_id]);
	}

}	