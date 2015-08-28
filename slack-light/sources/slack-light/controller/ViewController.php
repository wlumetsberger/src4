<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 18.08.2015
 * Time: 17:15
 */

require_once('/util/Util.php');
class ViewController extends BaseObject{


    private static $instance = false;
    const ACTION = 'action';
    const ACTION_LOGIN = 'login';
    const METHOD_POST = 'POST';
    const PAGE = 'page';
    /**
     * getInstance of Singleton
     * @return ViewController
     */
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new ViewController();
        }
        return self::$instance;
    }

    /**
     * Private Constructor
     */
    private function __construct(){

    }

    public function invokeAction(){
        SessionController::clearErrorMessagegs();
        if(!isset($_REQUEST[self::ACTION]) ){
            throw new Exception(self::ACTION. ' parameter is not specified');
        }
        $action = $_REQUEST[self::ACTION];
        switch($action){
            case self::ACTION_LOGIN:
                LoginController::getInstance()->handleLogin();
                //if(SessionController::isLoggedIn()){
                Util::redirect('index.php');
                break;
            case 'logout':
                LoginController::getInstance()->handleLogout();
                Util::redirect("index.php?action='logout'");
                break;
            case 'register':
                LoginController::getInstance()->register();
                Util::redirect("index.php");
                break;
            case 'switchChannel':
                MainController::getInstance()->switchToChannel();
                Util::redirect();
                break;
            case 'requestMessages':
                if(SessionController::isLoggedIn()){
                    MainController::getInstance()->generateMessages();
                }
                exit();
                break;
            case 'addMessage':
                MainController::getInstance()->addMessage();
                Util::redirect();
                break;
            case 'resetMessage':
                MainController::getInstance()->resetMessage();
                Util::redirect();
                break;
            case 'deleteMessage':
                MainController::getInstance()->deleteMessage();
                Util::redirect();
                break;
            case 'removeFavourite':
                MainController::getInstance()->removeFromFavourite();
                Util::redirect();
                break;
            case 'addFavourite':
                MainController::getInstance()->addToFavourite();
                Util::redirect();
                break;
            case 'editMessage':
                MainController::getInstance()->editMessage();
                Util::redirect();
                break;
            default:
        }
    }
    protected function forwardRequest(array $errors = null, $target = null) {
        //check for given target and try to fall back to previous page if needed
        if ($target == null) {
            if (!isset($_REQUEST[self::PAGE])) {
                throw new Exception('Missing target for forward.');
            }
            $target = $_REQUEST[self::PAGE];
        }
        //forward request to target
        // optional - add errors to redirect and process them in view
        if (count($errors) > 0)
            $target .= '&errors='.urlencode(serialize($errors));
        header('location: '.$target);
        exit();
    }



}