<?php
namespace app\core;

class Response {
    public function statusCode($code) {
        http_response_code($code);
    }
    public function redirect($path) {
        $this->statusCode(302);
        header("Location: $path");
        exit;
    }
}

 ?>
