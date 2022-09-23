<?php
namespace app\index\controller;//命名空间须小写

use think\Controller;
Class Contactus extends Base{
	public function index(){
		return $this->fetch();
	}
}
?>