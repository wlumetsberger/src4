<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 18.08.2015
 * Time: 17:15
 */
class LoginController extends BaseObject{

    private static $instance = false;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new LoginController();
        }
        return self::$instance;
    }

    private function __construct(){
    }

    public function show(){
        $action = Util::action('login', array('view' => 'login'));
        $register = Util::action('register', array('view' => 'login'));
        $channels= UserDataManager::getChannelsAvailable();
        $channelsForView = array();
        foreach($channels as $key => $val) {
            array_push($channelsForView,array('name' => $val->getName(),'id' => $val->getID() ));
        }

            TemplateLoader::getInstance()->loadTemplate('login.twig',array('action' => $action, 'register' => $register, 'channels' => $channelsForView));


    }

    public function handleLogin(){
       (LoginController::authenticate($_REQUEST['username'], $_REQUEST['password']));
        $channelId = UserDataManager::getDefaultChannelForUser(SessionController::getUser());
        SessionController::setChannel($channelId);
    }

    public function handleLogout(){
        SessionController::destroy();
    }
    public static function authenticate($username, $password){
        $user = UserDataManager::getUserForUserName($username);
        if($user != null && $user->getPasswordHash() == hash('sha1',$username . '|' . $password)){
            SessionController::addUser($user->getID());
            return true;
        }
        return false;
    }
    public function register(){
        $username = $_REQUEST['username'];
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'];
        $password = $_REQUEST['password'];

        if(UserDataManager::getUserForUserName($username) != null){
            return false;
        }

        $channels = UserDataManager::getChannelsAvailable();
        $passwordHash = hash('sha1',$username . '|' . $password);
        $user = new User(null,$username, $passwordHash, $firstname,$lastname);
        $user = UserDataManager::persistUser($user);
        foreach($channels as $key => $val){
            if(isset($_REQUEST[$val->getID()]) && $_REQUEST[$val->getID()] == 1){
                UserDataManager::insertChannelForUser($user->getId(),$val->getID());
            }
        }
        return true;
    }


}