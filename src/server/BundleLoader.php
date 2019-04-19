<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/17/2019
 * Time: 5:07 PM
 */

namespace CloudsDotEarth\Code;

// will load all bundles
class BundleLoader
{
    public function __construct(Actions &$actions) {
        $GLOBALS["BUNDLE_CONTEXT"] = true;
        $files = glob("vendor/**/*/cde_code.php");
        foreach ($files as $file) {
            $test = require_once $file;
            new $GLOBALS["BUNDLE_CLASS"]($actions);
        }
    }
}