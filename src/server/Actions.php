<?php
/**
 * Created by PhpStorm.
 * User: Marc
 * Date: 4/18/2019
 * Time: 1:10 PM
 */

namespace CloudsDotEarth\Code;


class Actions
{
    public $actions;
    public function on(string $action, \Closure $callback, int $priority = 0) {
        if (!isset($this->actions[$action])) {
            $this->actions[$action] = [];
        }
        $this->actions[$action][] = [$priority, $callback];
    }
    public function fire(string $action, array $args) {
        if (!isset($this->actions[$action])) {
            return;
        }
        echo "There is " . count($this->actions[$action]) . " actions" . PHP_EOL;
        usort($this->actions[$action], function($a, $b) {
            return $a[0] < $b[0];
        });
        foreach ($this->actions[$action] as $v) {
            if (call_user_func_array($v[1], $args)) {
                break;
            }
        }
    }
}