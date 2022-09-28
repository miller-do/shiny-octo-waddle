<?php
namespace app\index\controller;
use think\Controller;
Class Base extends Controller{
	public function initialize(){
		//tp5.0初始化方法为_initialize
		// $admin = session('admin');
		// if (empty($admin)) {
		// 	$this -> error('请先登录', url('Common/login'));//当前模块之下
		// }
		// dump($admin);
		$this->getTopMenu();//获取后台顶部菜单
		$this->rightData();
		$this->getUserInfo();
	}
	public function getUserInfo(){
		$uid=session('member.id');
		$userInfo=model('user')->find($uid);
		$this->assign('userInfo',$userInfo);
	}
	
	public function rightData(){
		$where = '`is_top`=1';
		$arTop=model('article')->where($where)->limit(6)->select();
		$arHot=model('article')->order('clickcount', 'desc')->limit(6)->select();
		// dump($artop);
		// die;
		$this->assign('artop',$arTop);
		$this->assign('arHot',$arHot);
	}
	
	public function getTopMenu(){
		$cates=Db("cate")->where("is_hide != 1")->order('sort', 'asc')->select();
		$menus=[];
		foreach ($cates as $key => $cate) {
			if($cate['pid']==0){
				$menus[$key]=$cate;
			}
		}
		
		foreach ($menus as $k => $menu) {
			foreach ($cates as $kk => $cate) {
				//一级菜单的id等于菜单数据的pid
				if($menu['id']==$cate['pid']){
					$menus[$k]['subMenu'][$kk]=$cate;
				}
			}
		}
		// dump($menus);die;
		//往模板传递2个变量的参数
		//array('cateList' => $toMenu, 'subMenu' => $subMenu)
		$this->assign('menus',$menus);//把顶部菜单往模板传送，并与循环体中的命名name的值保持一致
	}
}