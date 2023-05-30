<?php
namespace app\controllers;
use app\core\Controller;

class SiteController extends Controller {
    public function contact() {
        print_r($this->requestBody());
        $params = array(
            "name" => "Anythink"
        );

        return $this->render("contact", $params);
    }

}
 ?>
