<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/18/2019
 * Time: 1:10 PM
 */

namespace CloudsDotEarth\Code;


// SERVICE INITIAL REGISTRATION (bundles may register other servers)
class Server
{
    public function __construct(Actions &$actions)
    {
        $tcp_options = [
            'open_length_check' => true,
            'package_length_type' => 'n',
            'package_length_offset' => 0,
            'package_body_offset' => 2
        ];
        $server = new \Swoole\WebSocket\Server('127.0.0.1', 9501, SWOOLE_BASE);
        $server->set(['open_http2_protocol' => true]);
        $tcp_server = $server->listen('127.0.0.1', 9502, SWOOLE_TCP);
        $tcp_server->set($tcp_options);
        $services = new Services($server);
        $services->register_ws("http", $server);
        $services->register_ws("websocket", $server);
        $services->register_tcp("tcp", $tcp_server);
        $services->on("http", "request", function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use($actions) {
            $result = "";
            $actions->fire("http_request", [&$result, &$request, &$response]);
            $response->end($result);
        });
        $services->on("websocket", "message", function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) use($actions) {
            $result = "";
            $actions->fire("websocket_message", [&$result, $server, $frame]);
            $server->push($frame->fd, $result);
        });
        $services->on("tcp", "receive", function (\Swoole\Server $server, int $fd, int $reactor_id, string $data) use ($actions){
            $result = "";
            $actions->fire("tcp_receive", [&$result, $server, $reactor_id, $data]);
            $server->send($fd, $this->tcp_pack('Hello ' . $this->tcp_unpack($data)));
        });

        $actions->fire("on_services_start", [&$services]);
        $services->start();
        $actions->fire("on_services_stop", [&$services]);
    }
    public function tcp_pack(string $data): string
    {
        return pack('n', strlen($data)) . $data;
    }
    public function tcp_unpack(string $data): string
    {
        return substr($data, 2, unpack('n', substr($data, 0, 2))[1]);
    }
}