<?php
namespace app\api\controller;
use think\Db;
use think\Controller;
use Token;
use Request;
class User extends Base
{
	public function proFile(){
		// {
		// 	code:200,
		// 	data:{
		// 		icon:'',
		// 		menus:[{}],
		// 		roles:['订单管理员','商品管理员','咨询部员工','超级管理员'],
		// 		username:'admin',
		// 	},
		// 	msg:'操作成功'
		// }
		
		// {roles:[],icon:'',menus:[{id:'',parentid:'',title:''}]}
		
		// $accept= Request::instance()->header('accept');
		// return json(['code'=>200,'data'=>$accept,'msg'=>'成功']);
		$menus=model('Cate')->order('sort', 'asc')->select();
		$data['icon']="";
		$data['menus']=$menus;
		$data['roles']=[];
		$userInfo = Db::name('admin')
			->where(['token'=>$_SERVER["HTTP_AUTHORIZATION"]])
			->find();
		$data['username']=$userInfo['username'];
		
		if($menus){
			return json(['code'=>200,'data'=>$data,'msg'=>'登录成功']);
		}else{
			return json(['code'=>200,'data'=>$menus,'msg'=>'菜单获取失败']);
		}
		
	}
	
	public function login()
	{
		// $database=Db::table('admin') -> find();
		// print_r($database);
		/* if(request()->isAjax()){
			$data=[
				'username'=>input('post.username')
			];
			//调用模型的方法（当前模块/其它模块下）
			// new \app\common\model\Admin();
			$result=model('Admin')->login($data);
			if($result==1){
				$this->success('登录成功','amin/home/index');
				//seccess()自动将数据转为json格式；
			}else{
				$this->error('账号或密码错误');
			}
			
		} */
		$data=input('post.');//笼统接收参数
		$data=[
			'username'=>input('post.username'),
			'password'=>input('post.password')
		];
		
		//调用模型的方法（当前模块/其它模块下）
		// new \app\common\model\Admin();
		$result=model('Admin')->login($data);
		$userInfo = Db::name('admin')->field('id,token')->find();
		// print_r($expires_time);
		// die;
		$token = "";
		if($_SERVER["HTTP_AUTHORIZATION"]!=$userInfo['token']){
			$token = Token::create_token($data);
			$expires_time=time() + 1000*60*30;
			
			Db::name('admin')->where('id',$userInfo['id'])->update(['token'=>$token,'expires_time'=>$expires_time]);
		}else{
			$token =$_SERVER["HTTP_AUTHORIZATION"];
		}
		
		if($result==1){
			return json(['code'=>200,'data'=>$token,'msg'=>'登录成功']);
			// $this->success('登录成功','amin/home/index');
			//seccess()自动将数据转为json格式；
		}else{
			// $this->error($result);
			return json(['code'=>500,'data'=>$result,'msg'=>'登录失败']);
		}
		// return view();
	}
	
	public function register()
	{
		$data=[
			'username'=>input('post.username'),
			'password'=>input('post.password')
		];
		$result=model('Admin')->register($data);
		if($result==1){
			return json(['code'=>200,'data'=>$result,'msg'=>'登录成功']);
		}else{
			return json(['code'=>500,'data'=>$result,'msg'=>'登录失败']);
		}
	}
}
