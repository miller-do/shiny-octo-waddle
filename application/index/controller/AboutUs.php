<?php
namespace app\home\Controller;
use think\Controller;
Class AboutUs extends IndexBase{
	public function index(){
		return $this->fetch();
	}
}
?>