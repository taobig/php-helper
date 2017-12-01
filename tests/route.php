<?php


$uri = $_SERVER["REQUEST_URI"];
switch ($uri) {
    case "/json":
        header("Content-type:application/json");
        echo file_get_contents("php://input");
        break;
    case "/form":
        header("Content-type:application/json");
        echo file_get_contents("php://input");
        break;
    case "/":
        echo "Hello world!";
        break;
    default:
        exit;
}