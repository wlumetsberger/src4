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
        $user = self::getUser();
        return isset($user)&& $user != null;
    }
    public static function destroy(){
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

}
