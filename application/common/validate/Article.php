<?php
namespace app\common\validate;
use think\Validate;

class Article extends Validate
{
	protected $rule=[
		'title|文章标题'=>'require|unique:article,title',
		'cate|所属栏目'=>'require',
		'is_open'=>'require',
		'is_top'=>'require',
		'content|内容'=>'require'
	];
	//校验增加场景
    public function sceneAdd()
    {
		//only 场景需要验证的字段
		return $this->only(['title','cate','is_open','is_top','content']);
    }
}
