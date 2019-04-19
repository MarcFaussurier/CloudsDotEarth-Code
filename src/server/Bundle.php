<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/18/2019
 * Time: 1:12 PM
 */

namespace CloudsDotEarth\Code;

// a bundle is where hooks are defined
class Bundle
{
    public $actions;

    public function __construct(Actions &$actions)
    {
        $this->actions = $actions;
    }

    public function on(...      $args) {
        $this->actions->on(...  $args);
    }
}