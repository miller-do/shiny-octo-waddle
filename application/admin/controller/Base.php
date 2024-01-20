<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Response;
use Token;
use Request;
class Base extends Controller
{
	protected $user_id = NULL,$encryption = null;
	protected $noNeedLogin = ['login'];   //不需要验证的接口
	
	public function initialize(){
		if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
			//  解决预请求OPTIONS
			header('Access-Control-Allow-Origin:*');
			header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Content-Type,Cookie,Token,authorization');
			header('Access-Control-Allow-Credentials:true');
			header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
			header('Access-Control-Max-Age:1728000');
			header('Content-Type:text/plain charset=UTF-8');
			header('Content-Length: 0', true);
			header('status: 200');
			header('HTTP/1.0 204 No Content');
			exit;
		}else{
			//   获取ajax请求header
			header('Access-Control-Allow-Origin:*');   //允许跨域请求的域名
			header('Access-Control-Allow-Credentials: true');
			header("Access-Control-Allow-Methods:GET, POST, PUT,DELETE,POSTIONS");   //  允许跨域请求的方式
			header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie,Token,authorization");    //  将前端自定义的header头名称写入，红色部分
		}
		// header('Content-Type: text/html;charset=utf-8');
		// header('Access-Control-Allow-Origin:*'); // *代表允许任何网址请求
		// header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); // 允许请求的类型
		// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
		// $this->encryption = new Encryption();//加密
		// $token=Request::instance()->header("Authorization");    //从header里获取token
		$token=request()->header("Authorization");    //从header里获取token
		// var_dump(request()->header());
		var_dump($token);
		die;
		// $token='eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJweWciLCJleHAiOjE2NzE5ODQyODAsImF1ZCI6ImFkbWluIiwibmJmIjoxNjcxOTg0MjIwLCJpYXQiOjE2NzE5ODQyMjAsImFkbWluX2lkIjp7InVzZXJuYW1lIjoiYWRtaW4iLCJwYXNzd29yZCI6IjEyMzQ1NiJ9fQ.44esPiIAcaDnsZZsp9Jj0-sZk7_lZidJ7z9NZdeQxOE';
		if ($this->noNeedLogin){     //检查不需要token验证的接口
			$controller=Request::action();
			if (in_array($controller,$this->noNeedLogin)){
				// return false; //return之后 不再执行后续代码
			}
		}
		// $v=Token::verify_token($token);
		// echo $v;
		// die;
		
		//检查token
		// $this->CheckToken($token);
	}
	
	//检查token
	public function CheckToken($token){
		if($token){
			$res = Db::name('user')
				->field('id,expires_time')
				->where(['token'=>$token])
				->where('expires_time','>',time())
				->find();
			
			if ($res){
				$this->user_id = $res['id'];
				//更新token,到期十分钟更新
				if($res['expires_time']-time() <= 10*60){
					$expires_time = $res['expires_time']+7200;
					Db::name('user')->where('id',$res['id'])->update(['expires_time'=>$expires_time]);
				}
				
			}else{
				$rs_arr['code'] = 500;
				$rs_arr['msg']="登录已过期，请重新登录";
				$rs_arr['data']=null;
				Response::create($rs_arr, 'json')->send();
				exit;
			}
			
		}else{
			$rs_arr['code']=500;
			$rs_arr['msg']='请先登录';
			$rs_arr['data']=null;
			Response::create($rs_arr, 'json')->send();exit;
		}
	}
	
}
