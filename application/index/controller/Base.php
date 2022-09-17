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
	}
	public function getTopMenu(){
		$where['pid']=array('eq',0);
		$toMenu=Db("cate")->where($where)->limit(10)->order('sort', 'asc')->select();//$toMenu页面中的下拉菜单模板名
		// dump($toMenu);
		$this->assign('cateList',$toMenu);//把顶部菜单往模板传送，并与循环体中的命名name的值保持一致
	}
}