<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 18.08.2015
 * Time: 18:15
 */

require_once 'BaseObject.php';

class TemplateLoader extends BaseObject {

    private static $instance = false;
    private $loader;
    private $twig;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new TemplateLoader();
        }
        return self::$instance;
    }
    private function __construct(){
        Twig_Autoloader::register();
        $this->loader = new Twig_Loader_Filesystem('templates');
        $this->twig = new Twig_Environment($this->loader);
    }

    public function loadTemplate($templateName, $arguments){
        $template = $this->twig->loadTemplate($templateName);
        if(isset($arguments)){
            if(is_array($arguments) && SessionController::isLoggedIn()){
                $arguments['loggedIn'] = SessionController::getUserName();
                $arguments['logout'] = Util::action('logout');
                $arguments['channels'] = MainController::getInstance()->getChannels();
                $arguments['activeChannel'] = SessionController::getActiveChannel();
                $arguments['sendNewMessage'] = Util::action('addMessage');
                $arguments['resetMessages'] = Util::action('resetMessage');
                $arguments['editMessage'] = Util::action('editMessage');

            }
        }
        echo $template->render($arguments);
    }
}