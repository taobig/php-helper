<?php

declare(strict_types=1);


$uri = $_SERVER["REQUEST_URI"];
switch ($uri) {
    case "/json":
        header("Content-type:application/json");
        $str = file_get_contents("php://input");
        $params = json_decode($str, true);
        $params['headers'] = apache_request_headers();
        echo json_encode($params);
        break;
    case "/form":
        header("Content-type:application/json");
        echo file_get_contents("php://input");
        break;
    case "/file":
        header("Content-type:application/json");
        $result = [
            'files' => $_FILES,
            'post' => $_POST,
        ];
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
    case "/":
        echo "Hello world!";
        break;
    default:
        exit;
}