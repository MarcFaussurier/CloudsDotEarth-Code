<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/17/2019
 * Time: 5:08 PM
 */

class SomeBundle extends \CloudsDotEarth\Code\Bundle {
    public function __construct(\CloudsDotEarth\Code\Actions $actions)
    {
        parent::__construct($actions);

        //psuedocode

        $controller_stack = new \CloudsDotEarth\Code\ControllerStack($actions, __DIR__ . "/src/server/controllers");

        var_dump("REGISTRED ON HTTP_REQUEST");

        $this->on("http_request", function(&$result, &$request, &$response) { // arguments may change
            echo "FIRING....".PHP_EOL;
            var_dump("INSIDE BUNDLE ON HTTP_REQUEST");
            var_dump($this->actions);

            $psr_request = (new \Jasny\HttpMessage\ServerRequest())->withGlobalEnvironment();
            $psr_response = new \Jasny\HttpMessage\Response();

            $this->actions->fire("route://", [&$psr_request, &$psr_response]);
            $result = "HELLO WORLD";
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
