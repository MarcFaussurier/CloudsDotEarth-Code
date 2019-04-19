<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/17/2019
 * Time: 5:08 PM
 */

use CloudsDotEarth\Code\Adapters\SwoolePSR;

class SomeBundle extends \CloudsDotEarth\Code\Bundle {
    public function __construct(\CloudsDotEarth\Code\Actions $actions)
    {
        parent::__construct($actions);

        //psuedocode

        $controller_stack = new \CloudsDotEarth\Code\ControllerStack($actions, __DIR__ . "/src/server/controllers");

        var_dump("REGISTRED ON HTTP_REQUEST");

        $this->on("http_request", function(&$request, &$response) { // arguments may change
            echo "FIRING....".PHP_EOL;
            var_dump("INSIDE BUNDLE ON HTTP_REQUEST");

            $prefix = \CloudsDotEarth\Code\Controller::ACTION_PREFIX;

            $routesActions = $this->actions->getActionsThatStartWith($prefix);
            $psr_response = new \Jasny\HttpMessage\Response();
            $psr_request = SwoolePSR::swooleRequestToPSR($request);
            $uri = $psr_request->getServerParams()["request_uri"];
            var_dump($uri);
            foreach ($routesActions->actions as $k => $v) {
                $result = [];
                $currentPattern = trim(substr($k, strlen($prefix), strlen($k)));
                $escapedUrl = preg_quote($currentPattern, "/");
                $regex = '/' . $escapedUrl . '/';
                echo($regex);
                if (((int) preg_match_all($regex, $uri, $result)) > 0) {
                    if ($routesActions->fire($k, [&$psr_request, &$psr_response, $result]))
                        break;
                } else {
                    var_dump("NOT FIRED");
                }
            }
            SwoolePSR::swooleResponseFromPSR($response, $psr_response);
            return false;
        });

        $this->on("websocket_message", function(&$result, $server, $frame) { // arguments may change
            $result = "HELLO WORLD";
            return false;
        });

        $this->on("tcp_receive",  function(&$result, $server, $reactor_id, $data) {
            $result = "HELLOW WORLD";
            return false;
        },0);

        $this->on("action", function() { // arguments may change
            var_dump("GOT ACTION 9");
            return false;
        }, 9);
    }
}
$GLOBALS["BUNDLE_CLASS"] = SomeBundle::class;
// we ran this file directly
if(isset($GLOBALS["BUNDLE_CONTEXT"])) {
    echo "Starting " . SomeBundle::class . " in bundle mode ..." . PHP_EOL;
}
// we accessed this file in a bundle context
else {
    echo "Stating " . SomeBundle::class . " in project mode ..." . PHP_EOL;
    $actions = new \CloudsDotEarth\Code\Actions();

    $bundleLoader = new \CloudsDotEarth\Code\BundleLoader($actions);
    $server = new \CloudsDotEarth\Code\Server($actions);
}
