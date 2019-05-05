<?php
return [
// 扩展自身配置
'host' => '0.0.0.0', // 监听地址
'port' => 9501, // 监听端口
'type' => 'socket', // 服务类型 支持 socket http server
'mode' => SWOOLE_PROCESS,
'socket_type' => SWOOLE_SOCK_TCP,
// 可以支持swoole的所有配置参数
'daemonize' => false,
'worker_num' => 4,
//'swooleclass' => 'app\websocket\WebSocket',
];
