<?php

class View
{
    public static function render($viewName, $args)
    {
        extract($args);

        // 指定したviewの名前と変数を渡したら、HTMLを作って返してくれる
        include(dirname(__FILE__) . '/../view/' . $viewName . '.php');
    }
}
