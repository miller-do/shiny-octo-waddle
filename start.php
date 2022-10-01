<?php
use Workerman\Worker;
use Workerman\Connection\TcpConnection;

// 3. 引入 composer 的自动加载文件
require_once __DIR__."/vendor/autoload.php";
// 4. 设置时区
date_default_timezone_set('PRC');
 
// 5.主要代码如下
$worker = new \Workerman\Worker('websocket://tpyycms.cn/:2346');
$worker->name = 'ws_server';
$worker->count = 2; // 启动两个进程
CONST HEARTBEAT_TIME = 10 * 60; // 10分钟没有操作关闭连接
$worker->onWorkerStart = function($worker) {
    echo "服务开始 server_id:($worker->id):\n";
    $worker->reusePort = true;
    $time_now = time();
    foreach ($worker->connections as $connection){
        if (empty($connection->lastMessageTime)){
            $connection->lastMessageTime = $time_now;
            continue;
        }
        if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME){
            $connection->close();
        }
    }
    // 定时任务
    \Workerman\Timer::add(60,function () use ($worker){
        $time_now = time();
        foreach ($worker->connections as $connection) {
            if (empty($connection->lastMessageTime)){
                $connection->lastMessageTime = $time_now;
                continue;
            }
            if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME){
                $data = [
                    'user_id' => 0,
                    'user_name' => '系统',
                    'msg' => '连接长时间未操作已关闭',
                    'time' => date('Y-m-d H:i:s'),
                ];
                $connection->send(json_encode($data));
                $connection->close();
                echo "定时关闭连接[$connection->id]\n";
            }
        }

    });
};
// 连接事件
$worker->onConnect = function ($connection) use($worker){
    $userId = $connection->id;
    $count = count($worker->connections);
    $connection->lastMessageTime = time();
    foreach ($worker->connections as $connection){
        if ($userId != $connection->id){
            $data = [
                'user_id' => $userId,
                'user_name' => '匿名用户['.$userId.']',
                'msg' => '上线',
                'time' => date('Y-m-d H:i:s'),
            ];
            $connection->send(json_encode($data));
        }

    }
    echo "有新的连接[ID=$userId]\n";
    echo "目前总的连接数[$count]\n";
};
// 发消息
$worker->onMessage = function ($connection, $data) use($worker){
    $userId = $connection->id;
    $connection->lastMessageTime = time();
    $arr = json_decode($data,true);
    foreach ($worker->connections as $connection){
        $data = [
            'user_id' => $userId,
            'user_name' => '',
            'msg' => $arr['msg'],
            'time' => date('Y-m-d H:i:s'),
        ];
        if ($userId != $connection->id){
           $data['user_name'] = '匿名用户['.$userId.']';
        }else{
            $data['user_name'] = '我';
        }
        $connection->send(json_encode($data));
    }
};
// 关闭事件
$worker->onClose = function ($connection) use($worker){
    $userId = $connection->id;
    $count = count($worker->connections) - 1;
    foreach ($worker->connections as $connection){
        if ($userId != $connection->id){
            $data = [
                'user_id' => $userId,
                'user_name' => '匿名用户['.$userId.']',
                'msg' => '离开',
                'time' => date('Y-m-d H:i:s'),
            ];
            $connection->send(json_encode($data));
        }
    }
    echo "[ID=$userId] 退出连接\n";
    echo "目前总的连接数[$count]\n";
};
$worker::runAll();