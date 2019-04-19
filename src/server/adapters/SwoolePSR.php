<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/19/2019
 * Time: 3:31 PM
 */

namespace CloudsDotEarth\Code\Adapters;

class SwoolePSR
{
    /**
     * @param \Swoole\Http\Request $request
     * @return RequestInterface
     */
    public static function swooleRequestToPSR(\Swoole\Http\Request $request) : \Psr\Http\Message\ServerRequestInterface {
        //  return new R
        $_SERVER = $GLOBALS["_SERVER"] = is_null($request->server) ? array() : $request->server;
        $_REQUEST = $GLOBALS["_REQUEST"] = is_null($request->request) ?  array() : $request->request;
        $_COOKIE = $GLOBALS["_COOKIE"] = is_null($request->cookie) ?  array() : $request->cookie;
        $_GET = $GLOBALS["_GET"] = is_null($request->get) ? array() : $request->get;
        $_FILES = $GLOBALS["_FILES"] = is_null($request->files) ?  array() : $request->files;
        $_POST = $GLOBALS["_POST"] = is_null($request->post) ?  array() : $request->post;
        $serverRequest = (new \Jasny\HttpMessage\ServerRequest());
        $request = $serverRequest->withGlobalEnvironment(true);
        return $request;
    }

    public static function swooleResponseFromPSR(\Swoole\Http\Response &$swooleResponse,  \Psr\Http\Message\ResponseInterface $psrResponse) : void {
        /**
         * @param \Swoole\Http\Response $swooleResponse
         * @param ResponseInterface $psrResponse
         * @return void
         */
        foreach ($psrResponse->getHeaders() as $key => $header) {
            $swooleResponse->header($key, join(",", $header));
        }
        $swooleResponse->end($psrResponse->getBody()->getContents());
        return;
    }
}