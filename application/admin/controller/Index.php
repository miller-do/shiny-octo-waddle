<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use Token;
class Index extends Controller
{
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
