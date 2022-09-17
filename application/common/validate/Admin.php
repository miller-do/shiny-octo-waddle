<?php
namespace app\common\validate;
use think\Validate;

class Admin extends Validate
{
	protected $rule=[
		'username|管理员账户'=>'require',
		'password|密码'=>'require'
	];
	//登录校验
    public function sceneLogin()
    {
		return $this->only(['username','password']);
    }
}
