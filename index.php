<?php
    spl_autoload_register(function ($class) {
        $class = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
        require_once $class;
    });

    $fileNotFoundFlag = false;
    $controllerName;
    if(isset($_GET['target'])){
        $controllerName = $_GET['target'];
    }
    else{
        $controllerName = "index";
    }
    $methodName;
    if(isset($_GET['action'])){
        $methodName = $_GET['action'];
    }
    else{
        $methodName = "home";
    }

    $controllerClassName = "\\Controller\\" . ucfirst($controllerName) . "Controller";

    if(class_exists($controllerClassName)){
        $controller = new $controllerClassName();
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            $controller = new Controller\IndexController();
            $controller->error(404);
        }
    }
    else{
        $fileNotFoundFlag = true;
    }

    if($fileNotFoundFlag){
        $controller->error(404);
    }
?>