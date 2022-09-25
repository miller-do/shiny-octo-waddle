<?php
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;
//注意引入的类名必须是大写，从而说明对象的特征
// use think\Db;

class Article extends Model
{
	use SoftDelete;
	
	protected $deleteTime = 'delete_time';
	protected $defoultSoftDelete = 0;
	
	//关联栏目表
	public function cate(){
		//belongsTo('关联的模型'，'关联的外键','关联的主键');
		//外键：默认的外键规则是当前模型名（不含命名空间，下同）+_id ，例如user_id（当前表中与另一个表中相关联的字段）
		return $this->belongsTo('cate','cate','id');
	}
	
	//添加栏目
    public function articleAdd($data)
    {
		$validate=new \app\common\validate\Article();
		if(!$validate->scene('add')->check($data)){
			return $validate->getError();
		}
		
		// allowField 过滤post数组中的非数据表字段数据
		$result = $this->allowField(true)->save($data);
		// $result = $this->withTrashed()->select(); //包含软删除后的数据
		// $result = $this->onlyTrashed()->select(); //仅查询软删除后的数据
		if($result){
			return 1;
		}else{
			return '文章添加失败';
		}
    }
}
