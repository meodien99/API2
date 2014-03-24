<?php

//include constant.php
include_once(dirname(__FILE__)."/constants.php");
//include models
include_once BASE_MODEL_PATH."/AppModel.php";

try{
    $params = $_REQUEST;

    //get controllers
    $controller = ucfirst(strtolower($params['controller']));
    //get the action and format it correctly
    $action = strtolower($params['action'])."Action";

    //check if the controller exists, if not , throw an exception
    if(file_exists(BASE_CONTROLLER_PATH."/{$controller}.php")){
        include_once BASE_CONTROLLER_PATH."/{$controller}.php";
    } else {
        throw new Exception("Controller is invalid.");
    }

    //create a new instance of the controller,and pass it parameters from request
    $controller = new $controller($params);

    //check if action exists in controller
    if(method_exists($controller,$action) === false){
        throw new Exception("Action is invalid");
    }

    //execute the action
    $result['data'] = $controller->$action();
    $result['success'] = true;
} catch(Exception $e){
    $result = array();
    $result['success'] = false;
    $result['errmsg'] = $e->getMessage();
}

echo json_encode($result);
exit();