<?php

class View
{
    public static function render($viewName, $args)
    {
        extract($args);
        include(dirname(__FILE__) . '/../view/' . $viewName . '.php');
    }
}
