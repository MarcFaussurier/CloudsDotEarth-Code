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
    const USE_FAKE_VENDOR = true;

    public function __construct(Actions &$actions) {
        $GLOBALS["BUNDLE_CONTEXT"] = true;
        $files = glob((self::USE_FAKE_VENDOR ? "vendor" : "fake_vendor") . "/**/*/cde_code.php");
        foreach ($files as $file) {
            $test = require_once $file;
            new $GLOBALS["BUNDLE_CLASS"]($actions);
        }
    }
}