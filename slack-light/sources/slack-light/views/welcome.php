<?php

if(isset($_GET['errors'])){
    $errors = unserialize(urldecode($_GET['errors']));
}
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 19.08.2015
 * Time: 18:36
**/
if(SessionController::isLoggedIn()){
    MainController::getInstance()->show();
}else{
    LoginController::getInstance()->show();
}