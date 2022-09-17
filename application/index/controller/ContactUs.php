<?php
namespace app\home\Controller;
use think\Controller;
Class ContactUs extends IndexBase{
	public function index(){
		return $this->fetch();
	}
}
?>