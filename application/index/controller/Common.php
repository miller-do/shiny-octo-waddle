<?php
	namespace app\index\controller;
	use think\Controller;
	use think\Db;
	use think\Session;

class Common extends Base{
	
	//退出登录
	public function loginOut() {
		Session::set('member', null);
		$this -> success('退出成功！', url('Common/login'), 5);
	}
	
	public function Login(){
		if($_POST){
			// $user_login =input('param.password'); // 获取单个参数
			// input('param.'); // 获取全部参数
			// // 下面是等效的
			// input('password'); 
			// input('');
			// echo "<pre>";
			// print_r($user_login);
			// die;
			$captcha = $_POST['captcha'];
			if($captcha==''){
				$this -> error('验证码不能为空！');
			}
			
			if (!captcha_check($captcha)) {//验证失败////captcha_check是TP框架封装好的检测图片验证码的函数
				$this -> error('验证码错误，请重新输入！');
			}
			
			if(empty($_POST['account'])){
				$this -> error('帐号不能为空，请重新输入！');
				// die('error:帐号不能为空，请重新输入！');
			}else{
				$account = trim($_POST['account']);
				$where = '`account`="' . $account . '"';
				$member = Db::table('member') -> where($where) -> find();
			}
			if($member){
				$password = trim($_POST['password']);
				if ($password == $member['password']) {
					session('member', $member);
					// 数据库连接
					// 	$con = mysql_connect("localhost","root","root");
					// 	mysql_select_db("tpblog", $con);
					// 	// mysql_query("SET NAMES UTF8");
					
					// 	//校验MD5密码
					// 	$md5psw = MD5($psw);
					
					// 	//查询数据库账号密码是否一致
					// 	$exist = mysql_query("SELECT * FROM userlist WHERE username = '$user' AND password = '$md5psw'");
					// 	$exist_result = mysql_num_rows($exist);
					// 	if($exist_result){
					// 		echo "[{\"result\":\"登陆成功\"}]";
					// 	}else{
					// 		echo "[{\"result\":\"fail\"}]";
					// 	}
					$this -> success('登录成功！', url('user/User_center'), 5);
					
				} else {
					$this -> error('密码错误，请重新输入！');
				}
			}else{
				$this -> error('账号不存在！');
			}
			//以下为多账号登录功能
			$username = trim($_POST['username']);
			//is_mobile封装在根目录的common.php文件中
			if (is_mobile($username)) {
				$where = '`mobile`="' . $username . '"';
			} else if (is_email($username)) {
				$where = '`email`="' . $username . '"';
			} else {
				$where = '`account`="' . $username . '"';
			}
			$member = Db::table('member') -> where($where) -> find();
			
			if ($member) {
				$password = trim($_POST['password']);
				if ($password == $member['password']) {
					//Session::set('member_info', $member);
					//TP框架内置Session::set('member_info', $member)机制，需要引进use think\Session
					session('member', $member);
					
					//TP框架内置公共函数助手session()，无需引进use think\Session;
					$this -> success('登录成功！', url('user/User_center'), 10);
				} else {
					$this -> error('密码错误，请重新输入！');
				}
			} else {
				$this -> error('账号不存在，请更换账号！');
			}
			
		} else {
			//小程序请求接口test
			//php5.6版本会响应报错
			// $where = '`account`="miller"';
			// $member = Db::table('member') -> where($where) -> find();
			// return json($member);
			return $this -> fetch();
		}
	}
	
	public function Register(){
		if ($_POST) {
			// print_r($_POST);
			// $captcha = $_POST['captcha'];
			// if (!captcha_check($captcha)) {//验证失败
			// 	$this -> error('验证码错误，请重新输入！');
			// }
			
			if (empty($_POST['account'])) {
				$this -> error('帐号不能为空，请重新输入！');
				//把错误信息弹回前端界面
			} else {
				$data_insert['account'] = trim($_POST['account']);
			}
		
			if (!empty($_POST['mobile'])) {
				$data_insert['mobile'] = trim($_POST['mobile']);
			} else if (!empty($_POST['email'])) {
				$data_insert['email'] = trim($_POST['email']);
			} else {
				$this -> error('作为验证方式，手机和邮箱不能为空，请重新输入！');
			}
		
			if (empty($_POST['password'])) {
				$this -> error('密码不能为空，请重新输入！');
			} else {
				if ($_POST['password'] == $_POST['repassword']) {
					$password = trim($_POST['repassword']);
					// $password = password_encode($password);
					$data_insert['password'] = $password;
					// $data_insert['update_time'] = time();
					$data_insert['register_time'] = date('Y-m-d H:i:s');
					// $data_insert['register_ip'] = get_client_ip();
					$result = Db::table('member') -> insert($data_insert);
					if ($result) {
						$this -> success('恭喜注册成功！', url('common/login'), 5);
					} else {
						$this -> error('注册失败，请重新注册！');
					}
				} else {
					$this -> error('密码与确认密码不一致，请重新输入！');
				}
			}
		} else {
			return $this -> fetch();
		}
	}
}
	
?>