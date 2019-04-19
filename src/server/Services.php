<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/18/2019
 * Time: 1:10 PM
 */

namespace CloudsDotEarth\Code;


class Services
{
    /**
     * @var \Swoole\Server
     */
    public $server;
    /**
     * @var \Swoole\Server[] $services
     */
    public $services;
    public function __construct(\Swoole\Server $server)
    {
        $this->server = $server;
    }
    public function register_ws(string $service, \Swoole\Server $instance) {
        $this->services[$service] = $instance;
    }
    public function register_tcp(string $service, \Swoole\Server\Port $instance) {
        $this->services[$service] = $instance;
    }
    public function on(string $service, string $action, \Closure $callback)  {
        ($this->services[$service])->on($action, $callback);
    }
    public function start() {
        echo "started" . PHP_EOL;
        $this->server->start();
    }
}