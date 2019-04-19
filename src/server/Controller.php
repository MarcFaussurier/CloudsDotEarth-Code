<?php

/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/02/2019
 * Time: 13:36
 */
namespace CloudsDotEarth\Code;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class Controller
 * @package CloudsDotEarth\Bundles\Core
 */
class Controller {
    /**
     * Format is function name, uri regex, service regex, priority, controller
     * @var array[]
     */
    public $methods = [];
    /**
     * Will set $methods array using reflexion // tokens parsing
     * @throws \ReflectionException
     */
    public function setMetaData() : void {
        $childClass = get_class($this);
        $child = new $childClass();
        $class_info = new \ReflectionClass($child);
        $source = file_get_contents($class_info->getFileName());
        $tokens = token_get_all( $source );
        $output = [];
        $lastComment = "";
        foreach ($tokens as $token) {
            // dont proceed useless tokens
            if (in_array($token[0], [
                T_COMMENT,      // All comments since PHP5
                T_DOC_COMMENT,   // PHPDoc comments
                T_STRING
            ])) {
                if (($lastComment !== "") && ($token[0] === T_STRING)) {
                    $lastFunction = $token[1];
                    if ((strpos($lastComment, "@uri ") !== false)) {
                        $exploded = explode("@uri ", $lastComment)[1];
                        $uri = explode("\n",$exploded)[0];
                        $priority = 0;
                        if ((strpos($lastComment, "@uri ") !== false)) {
                            $exploded = explode("@priority ", $lastComment)[1];
                            $priority = explode("\n", $exploded)[0];
                        }
                        $output[] = ["function" => $lastFunction, "uri" => $uri, "priority" => $priority, "controller" => &$this];
                        var_dump($output);
                    }
                    $lastComment = "";
                } else {
                    if ($token[0] === T_DOC_COMMENT) {
                        $lastComment = $token[1];
                    }
                }
            }
        }
        $this->methods = $output;
    }
}