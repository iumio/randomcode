<?php

    $currentHost = $_SERVER['HTTP_HOST'];
    $removeProtocol = function ($str) {
        return preg_replace('#^https?://#', '', $str);
    };
    $error404 = function () {
        header("Content-Type: application/json");
        http_response_code(404);
        echo json_encode(['code' => 404, 'message' => 'Not Found']);
        die();
    };
    $allowed = [
        $removeProtocol("https://api.url.com") => [
            "/api",
        ],
        $removeProtocol("https://admin.url.com") => [
            "/admin"
        ],
    ];
    $selectedHost = $allowed[$currentHost] ?? null;
    $continue = false;
    if (null != $selectedHost) {
        foreach ($selectedHost as $one) {
            if (substr($_SERVER['REQUEST_URI'], 0, strlen($one)) === $one) {
                $continue = true;
                break;
            }
        }
    }

    if (false == $continue) {
        $error404();
    }
