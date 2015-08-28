<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 19.08.2015
 * Time: 18:38
 */
if(isset($_GET['errors'])){
    $errors = unserialize(urldecode($_GET['errors']));
}
LoginController::getInstance()->show();
