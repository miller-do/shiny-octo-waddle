<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\home\Model\User as M;

class User extends IndexBase{
	//如果你的控制器类继承了\think\Controller类的话，可以定义控制器初始化方法_initialize，在该控制器的方法调用之前首先执行。
	// public function _initialize() {
	// 	$member = session('member');
	// 	if (empty($member)) {
	// 		$this -> error('请先去登录', url('Common/login'));
	// 	}
	// }
	
	public function User_center(){
		if ($_POST) {
			$data = $_POST;
			$member_id = session('member.id');
			$member_info = Db::table('member') -> find($member_id);
			// echo "<pre/>";
			// print_r($member_info);
			// die;
			if ($member_info) {
				$result = Db::table('member') -> where('id', $member_id) -> update($data);
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
				$result = Db::table('member') -> insert($data);
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
			$member_info = Db::table('member') -> find($member_id);
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
}

?>