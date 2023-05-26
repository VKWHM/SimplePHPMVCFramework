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
}

 ?>
