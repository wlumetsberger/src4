<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 17.08.2015
 * Time: 19:57
 */
error_reporting(E_ALL); ini_set('display_errors', 'On');
require_once '/util/TemplateLoader.php';
require_once '/util/BaseObject.php';
require_once '/libs/Twig-1.20.0/lib/Twig/Autoloader.php';
require_once '/controller/ViewController.php';
require_once '/controller/LoginController.php';
require_once '/controller/SessionController.php';
require_once '/controller/MainController.php';
require_once '/util/Util.php';
require_once 'dataManagers/DataManager.php';
require_once 'dataManagers/UserDataManager.php';
require_once 'dataManagers/MessageDataManager.php';
require_once 'model/Entity.php';
require_once 'model/User.php';
require_once 'model/Channel.php';
require_once 'model/Message.php';

SessionController::create();