<?php
namespace app\core;

class Response {
    public function statusCode($code) {
        http_response_code($code);
    }
}

 ?>
