<?php
namespace app\common\validate;
use think\Validate;

class Cate extends Validate
{
	protected $rule=[
		'catename|栏目名称'=>'require|unique:cate',
		'pid|上级菜单'=>'require',
		'url'=>'require',
		'is_hide'=>'require',
		'sort|排序'=>'require'
	];
	//校验增加场景
    public function sceneAdd()
    {
		return $this->only(['catename','parentId','url','is_hide','sort']);
    }
}
