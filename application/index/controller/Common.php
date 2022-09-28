<?php
	namespace app\index\controller;
	use think\Controller;
	use think\Db;
	use think\Session;

class Common extends Base{
	
	//退出登录
	public function loginOut() {
		session('member', null);
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
				$member = db('user') -> where($where) -> find();
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
					$this -> success('登录成功！', url('common/User_center'), 5);
					
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
			$member = Db::table('user') -> where($where) -> find();
			
			if ($member) {
				$password = trim($_POST['password']);
				if ($password == $member['password']) {
					//Session::set('member_info', $member);
					//TP框架内置Session::set('member_info', $member)机制，需要引进use think\Session
					session('member', $member);
					
					//TP框架内置公共函数助手session()，无需引进use think\Session;
					$this -> success('登录成功！', url('common/User_center'), 10);
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
					$data_insert['create_time'] = date('Y-m-d H:i:s');
					// $data_insert['register_ip'] = get_client_ip();
					$result = Db::table('user') -> insert($data_insert);
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
	
	public function User_center(){
		if ($_POST) {
			$data = $_POST;
			$member_id = session('member.id');
			$member_info = Db::table('user') -> find($member_id);
			// echo "<pre/>";
			// print_r($member_info);
			// die;
			if ($member_info) {
				$result = Db::table('user') -> where('id', $member_id) -> update($data);
				if ($result) {
					$this -> success('个人资料更新成功！');//ajax请求自动转为json数据格式
				} else {
					$this -> error('资料未作任何修改，个人资料更新失败');
				}
	
			} else {
				$data['id'] = session('member.id');
				$data['account'] = session('member.account');//完整性约束
				print_r($data);
				// die;
				$result = Db::table('user') -> insert($data);
				if ($result) {
					$this -> success('个人资料添加成功！');
				} else {
					$this -> error('个人资料添加失败！');
				}
			}
	
		} else {
			//未有提交
			$member_id = session('member.id');
			if(!$member_id){
				$this -> error('请先登录！', url('Common/login'));
			}
			$member_info = Db::table('user') -> find($member_id);
			// echo "<pre>";
			// print_r($member_info);
			if (!empty($member_info)) {
				if (!empty($member_info['borth_addr'])) {
					$member_info['borth_addr_list'] = explode(' ', $member_info['borth_addr']);
				}
	
				if (!empty($member_info['live_addr'])) {
					$member_info['live_addr_list'] = explode(' ', $member_info['live_addr']);
				}
				$member_info = array_merge(session('member'), $member_info);//将一个或多个数组的单元合并起来
			}
			$this -> assign('userinfo', $member_info);
			//userinfo为显示已有信息功能的盒子模型
			// return $this -> fetch();
			return view();
			//助手函数
			// return view('personal_center',['name'=>'thinkphp']);
		}
	}
	
	public function updateAvatar(){
		// echo "接收Img";
		$file = $this->request->file('file');
		// dump($file);
		if($file){
			$path=env('ROOT_PATH')."public/upload/avatar";
			
			$info = $file->move($path);//'../uploads'
			if($info){
			    // 成功上传后 获取上传信息
				$url = $info->getSaveName();
				// http://localhost/tp-yycms/uploads/20220921/93c04acb867db32bdbc4da7756152b7b.jpg
				$result=db('user')->where('id',session('member.id'))->data(['avatar'=>$url])->update();
				if($result){
					return json(['code'=>200,'data'=>$result,'msg'=>'头像修改成功']);
				}else{
					return json(['code'=>500,'data'=>$result,'msg'=>'头像修改失败']);
				}
			}else{
			    // 上传失败获取错误信息
			    return $file->getError();
			}
		}else{
			return $this->error('请上传图片', url('common/user_center'), 5);
		}
	}
	
}
	
?>