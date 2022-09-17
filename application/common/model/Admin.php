<?php
namespace app\common\model;
use think\Model;
use think\model\concern\SoftDelete;
//注意引入的类名必须是大写，从而说明对象的特征
// use think\Db;

class Admin extends Model
{
	use SoftDelete;
	
	protected $deleteTime = 'delete_time';
	protected $defoultSoftDelete = 0;
	
	//登录校验
    public function login($data)
    {
		$validate=new \app\common\validate\Admin();
		
		if(!$validate->scene('login')->check($data)){
			return $validate->getError();
		}
		
		//软删除的使用
		// $id=$data['id'];
		// return $this->destroy($id);
		
		$result = $this->where($data)->find();
		// $result = $this->withTrashed()->select(); //包含软删除后的数据
		// $result = $this->onlyTrashed()->select(); //仅查询软删除后的数据
		// dump($result);
		if($result){
			if($result['status']!=1){
				return '账号已被禁用';
			}
			//mvc模式下的session
			// $sessionData=[
			// 	'id'=>$result['id'],
			// 	'nickname'=>$result['nickname'],
			// 	'is_super'=>$result['is_super'],
			// ];
			// session('admin',$sessionData);
			return 1;
		}else{
			return '用户名或密码错误';
		}
    }
}
