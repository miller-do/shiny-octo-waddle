<?php
namespace app\index\controller;
use think\Controller;
Class About extends Base{
	public function index(){
		return $this->fetch();
	}
}
?>