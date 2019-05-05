<?php
namespace app\websocket;

use think\swoole\Server;
class WebSocket extends Server
{
        protected $host = '0.0.0.0'; //监听所有地址
        protected $port = 9501; //监听9501端口
        protected $serverType = 'socket';
        protected $option = [
            'worker_num' => 4,  //设置启动的Worker进程数
			'daemonize' => false,  //守护进程化（上线改为true）
			'backlog' => 128,  //Listen队列长度
			'dispatch_mode' => 2, //固定模式，保证同一个连接发来的数据只会被同一个worker处理

			//心跳检测：每60秒遍历所有连接，强制关闭10分钟内没有向服务器发送任何数据的连接
			'heartbeat_check_interval' => 60, 
			'heartbeat_idle_time' => 600
	];
	
	//建立连接时回调函数
	public function onOpen($server ,$req)
	{
		$fd = $req->fd;//客户端标识
		$uid = $req->get['uid'];//客户端传递的用户id
		$token = $req->get['token'];//客户端传递的用户登录token
		//省略token验证逻辑......
		if(!$token){
			$arr = array('status'=>2,'message'=>'token已过期',);
			$server->push($fd, json_encode($arr));
			$server->close($fd);
			return;
		}
		//省略给用户绑定fd逻辑......
		echo "用户{$uid}建立了连接,标识为{$fd}\n";
	}
	
	//接收数据时回调函数
	public function onMessage($server,$frame)
	{
		$fd = $frame->fd;
		$message = $frame->data;
		
		$uid = 666;
		$data['uid'] = $uid;
        $data['message'] = '用户'.$uid.'发送了:'.$message;
        $data['post_time'] = date("m/d H:i",time());
        $arr = array('status'=>1,'message'=>'success','data'=>$data);
		
		//仅推送给当前连接用户
        //$server->push($fd, json_encode($arr));
        
        //推送给全部连接用户
        foreach($server->connections as $fd) {
            $server->push($fd, json_encode($arr));
        } 
	}
	
	//连接关闭时回调函数
    public function onClose($server,$fd)
    {
        echo "标识{$fd}关闭了连接\n";
    }
}
