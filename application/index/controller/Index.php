<?php
namespace app\index\controller;
use Think\Cache\Driver\Redis;
//use think\Controller;
use app\base;
class Index extends base
{
    public function index()
    {
	return $this->fetch();
    }

    public function hello($name = 'ThinkPHP5')
    {
        echo 'hello,' . $name;
    }

    public function redis(){
	$redis = new Redis();
	var_dump(class_exists('\Redis'));

    }
}
