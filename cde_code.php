<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/18/2019
 * Time: 1:15 PM
 */

require_once "vendor/autoload.php";


class DemoProject extends \CloudsDotEarth\Code\Bundle {
    public function __construct(\CloudsDotEarth\Code\Actions $actions)
    {
        parent::__construct($actions);
    }

    public function on(...      $args) {
        parent::on(...  $args);
    }
}

$GLOBALS["BUNDLE_CLASS"] = DemoProject::class;

// we ran this file directly
if(isset($GLOBALS["BUNDLE_CONTEXT"])) {
    echo "Starting " . DemoProject::class . " in bundle mode ..." . PHP_EOL;
}
// we accessed this file in a bundle context
else {
    echo "Stating " . DemoProject::class . " in project mode ..." . PHP_EOL;
    $actions = new \CloudsDotEarth\Code\Actions();

    $bundleLoader = new \CloudsDotEarth\Code\BundleLoader($actions);
    $server = new \CloudsDotEarth\Code\Server($actions);
}