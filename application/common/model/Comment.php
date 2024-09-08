<?php
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;

class Comment extends Model{
	use SoftDelete;
	
	protected $deleteTime = 'delete_time';
	protected $defoultSoftDelete = 0;
	
	//关联用户表
	public function userInfo(){
		//belongsTo('关联的模型'，'关联的外键','关联的主键');
		//外键：默认的外键规则是当前模型名（不含命名空间，下同）+_id ，例如user_id（当前表中与另一个表中相关联的字段）
		return $this->belongsTo('user');//,'user_id','id' 参数默认，所以可不写第二三参数
	}
	public function article()
	{
		return $this->belongsTo('article');
	}
	
	public function index(){
		echo "111111111";
	}
}