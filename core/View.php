<?php

namespace core;

use app\exceptions\NotFoundException;

class View
{
    public static function render($view)
    {
        $file = dirname(__DIR__) . "/View/$view.php";

        require_once $file;
    }

    public static function redirect($dest, $statusCode = 301)
    {
        header('Location: /' . $dest, true, $statusCode);
    }
}