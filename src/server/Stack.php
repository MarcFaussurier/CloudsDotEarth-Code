<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 04/03/2019
 * Time: 09:33
 */
namespace CloudsDotEarth\Code;
/**
 * Class Stack
 * Stacks allow framework to load faster all its components
 * @package CloudsDotEarth\App\core
 */
class Stack
{
    /**
     * @var string
     */
    public $stackType;
    /**
     * @var \stdClass[]
     */
    public $data = [];

    public $lookup_folder = __DIR__;

    public $actions;

    /**
     * Stack constructor.
     * Will load all class instances in $this->data
     * Used to load controllers ...
     * @param string $lookup_folder
     * @param Actions $actions
     */
    public function __construct(Actions &$actions, $lookup_folder = __DIR__)
    {
        $this->actions = $actions;
        $this->lookup_folder = $lookup_folder;
        // get all stack files
        $files = $this->getStackFiles();
        var_dump($files);
        foreach ($files as $k => $v) {
            require_once $v;
            $class = $GLOBALS["STACK_CLASS"];
            array_push($this->data, new $class());
        }
    }
    /**
     * Will load all stack files and return a stack containing all key : value arguments
     * @return array
     */
    public function getStackFiles(bool $includeFiles = true): array {
        $files = glob($this->lookup_folder . DIRECTORY_SEPARATOR . "*.php");
        var_dump($this->lookup_folder . DIRECTORY_SEPARATOR . "*.php");
        // array merge priority is the higher to the left
        if ($includeFiles) {
            foreach ($files as $v)  {
                require_once  $v;
            }
        }
        return $files;
    }
}