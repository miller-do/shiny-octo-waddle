<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Token;
class Index extends Controller
{
	public function index(){
	    	$cur_namespace=__NAMESPACE__;//获取当前的命名空间
			$cur_controller=__CLASS__;//获取当前的类，包含命名空间
			$cur_method=__METHOD__;//获取当前类的方法，包含命名空间
			$cur_function=__FUNCTION__;//获取当前的方法，不包含命名空间
	//      echo "<pre>";
			// $cur1_controller=str_replace($cur_namespace.'\\', '', $cur_controller);//单个反斜杠表示转义符
	//		var_dump($cur1_controller);
			// $url=$cur1_controller.'/'.$cur_function;
			// $this->getMenus($url);
	        return $this->fetch();
	}
	// private function makeToken(){
	//     $str = md5(uniqid(md5(microtime(true)), true)); //生成一个不会重复的字符串
	//     $str = sha1($str); //加密
	//     return $str;
	// }
	
	// public function verifyToken(){
	// 	$token = input('token');
	// 	$res = Token::verify_token($token);
	// 	var_dump($res);
	// }
	
	public function proFile(){
		// {
		// 	code:200,
		// 	data:{
		// 		icon:'',
		// 		menus:[],
		// 		roles:['订单管理员','商品管理员','咨询部员工','超级管理员'],
		// 		username:'admin',
		// 	},
		// 	msg:''
		// }
		return json(['code'=>200,'data'=>'$result','msg'=>'成功']);
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
		// $data=input('post.');//笼统接收参数
		$data=[
			'username'=>input('post.username'),
			'password'=>input('post.password')
		];
		$token = Token::create_token($data);
		//调用模型的方法（当前模块/其它模块下）
		// new \app\common\model\Admin();
		$result=model('Admin')->login($data);
		// print_r($result);
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
