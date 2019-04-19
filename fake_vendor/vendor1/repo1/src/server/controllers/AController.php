<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/17/2019
 * Time: 9:38 PM
 */

namespace Vendor1\Repo1;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AController extends \CloudsDotEarth\Code\Controller {
    /**
     * Home controller
     *
     * @uri /hello
     * @priority 10
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function someTest(ServerRequestInterface &$request, ResponseInterface &$response, array $matches) : bool {
        var_dump("INSIDE METHOD");
        $status = 200;
        $headers = ['X-Foo' => 'Bar'];
        $body = 'someTest!';
        $protocol = '1.1';
        $response = new \GuzzleHttp\Psr7\Response($status, $headers, $body, $protocol);
        $result = "patate";
        return false;
    }
}

$GLOBALS["STACK_CLASS"] = AController::class;