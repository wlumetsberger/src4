<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 18.08.2015
 * Time: 19:05
 */
class SessionController{

    private  static $exists = false;

    public static function create(){
        if(!self::$exists){
            self::$exists = session_start();
        }
        return self::$exists;
    }

    public static function addUser($user){
        $_SESSION['user'] = $user;
    }
    public static function getUser(){
        if(isset($_SESSION['user'])){
            //return UserDataManager::getNameForId($_SESSION['user']);
            return $_SESSION['user'];
        }
        return null;
    }
    public static function getUserName(){
        if(isset($_SESSION['user'])){
            return UserDataManager::getNameForId($_SESSION['user']);
            //return $_SESSION['user'];
        }
        return null;
    }

    public static function isLoggedIn(){
        $user = SessionController::getUser();
        return isset($user)&& $user != null;
    }
    public static function destroy(){
            unset($_SESSION['user']);
           session_destroy();
    }
    public static function setChannel($channelId){
        $_SESSION['channel'] = $channelId;
    }
    public static function getActiveChannel(){
        if(isset($_SESSION['channel'])){
            return $_SESSION['channel'];
        }
    }
    public static function editMessage($id){
        $_SESSION['message'] = $id;
    }
    public static function getCurrentMessage(){
        if(isset($_SESSION['message'])){
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
            return $m;
        }
        return null;
    }
    public static function addErrorMessage($message){

        if(isset($_SESSION['errors']) && is_array($_SESSION['errors'])){
            array_push($_SESSION['errors'],$message);
        }else{
            $arr = array();
            array_push($arr, $message);
            $_SESSION['errors'] = $arr;
        }

    }
    public static function clearErrorMessagegs(){
        unset($_SESSION['errors']);
    }
    public static function getErrorMessages(){

        if(isset($_SESSION['errors'])&& is_array($_SESSION['errors'])){
            $retVal = $_SESSION['errors'];
            //unset($_SESSION['errors']);
            return $retVal;
        }
        return null;
    }

}
