<?php

namespace app\core;

class Request {
    public function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position) {
            $path = strsub($path, 0, $position);
        }
        return $path;
    }
    public function getMethod() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function getBody() {
        $body = [];
        switch ($this->getMethod()) {
            case "get":
                foreach ($_GET as $key => $value) {
                    $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
            case "post":
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
        }
        return $body;
    }
}

 ?>
