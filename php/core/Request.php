<?php

namespace app\core;

class Request {
    public function path() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position) {
            $path = strsub($path, 0, $position);
        }
        return $path;
    }
    public function method() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    public function isGet() {
        return $this->method() === 'get';
    }
    public function isPost() {
        return $this->method() === 'post';
    }
    public function body() {
        $body = [];
        switch ($this->method()) {
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
