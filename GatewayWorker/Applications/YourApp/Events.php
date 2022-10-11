<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
		$data['type']='init';
		$data['fromid']=$client_id;
		// $data['toid']=$toid;
        // 向当前client_id发送数据 
		//向客户端发消息
        Gateway::sendToClient($client_id,json_encode($data));
        // 向所有人发送
        // Gateway::sendToAll("$client_id login\r\n");
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
		echo($message);
		$data=json_decode($message,true);
		switch ($data['type']){
			case 'init':
				//在init中我们绑定uid，将我们的身份确定，没有向客户端发消息
				Gateway::bindUid($client_id,$data['fromid']);
				break;
			case 'status':
				//在status中我们判断聊天对象是否在线，并将结果返回刚刚连接的客户
				$data['type']='status';
				$res['fromid']=$data['fromid'];
				$res['toid']=$data['toid'];
				$res['status']=Gateway::isUidOnline($data['toid']);
				// Gateway::sendToUid($data['fromid'],json_encode($res));
				break;
			default:
				$res['type']="say";
				$res['fromid'] = $data['fromid'];
				$res['toid'] = $data['toid'];
				$res['content'] = $data['content'];
				// Gateway::sendToUid($data['toid'],json_encode($res));
				Gateway::sendToAll(json_encode($res));
		}
		
        // 向所有人发送 
        // Gateway::sendToAll(json_encode($res));
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");
   }
}
