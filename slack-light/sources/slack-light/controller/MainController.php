<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 18.08.2015
 * Time: 19:47
 */

class MainController extends BaseObject{

    private static $instance = false;

    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new MainController();
        }
        return self::$instance;
    }

    private function __construct(){
    }

    public function show(){

        $m = SessionController::getCurrentMessage();
        if($m != null){
            $message = MessageDataManager::getMessageForId($m);

            TemplateLoader::getInstance()->loadTemplate('home.twig',array('fetchDataUrl'=> Util::action('requestMessages'), 'actionPostMessage' => Util::action('addMessage'),
                'messageId' => $message->getID(), 'messageTitle' => $message->getTitle(), 'messageContent' => $message->getContent()));
        }else{
            TemplateLoader::getInstance()->loadTemplate('home.twig',array('fetchDataUrl'=> Util::action('requestMessages'), 'actionPostMessage' => Util::action('addMessage')));
        }

    }

    public function editMessage(){
        SessionController::editMessage($_REQUEST['messageId']);
    }

    public function switchToChannel(){
        if(isset($_REQUEST['messageId'])){
            unset($_REQUEST['messageId']);
        }
        SessionController::setChannel($_REQUEST['ChannelId']);
    }
    public function generateMessages(){
        $messages = MessageDataManager::getMessagesForChannel(SessionController::getActiveChannel());
        $json = "{\"channel\":\"" . MainController::getInstance()->getActiveChannel() . "\"";
        $json .= ", \"messages\":[";
        $first = true;
        for($i = 0; $i < count($messages); $i++){

            //if($messages[$i]->getFavourite() != 1){
                if($first == false){
                    $json .= " , ";
                }
                $json .= "{\"message\":\"" . $messages[$i]->getContent() . "\"";
                $json .= ",\"autor\":\"" . UserDataManager::getNameForId($messages[$i]->getUser()) . "\"";
                $json .= ",\"written\":\"" . $messages[$i]->getCreation() . "\"";
                $json .= ",\"title\":\"" . $messages[$i]->getTitle() . "\"";
                $json .= ",\"isEditable\":\"" . $messages[$i]->getEditable() . "\"";
                $json .= ",\"isRemoveAble\":\"" . $messages[$i]->getEditable() . "\"";
                $json .= ",\"isFavourite\":\"" . $messages[$i]->getFavourite() . "\"";
                $json .= ",\"editLink\":\"" . Util::action('editMessage',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"removeLink\":\"" . Util::action('deleteMessage',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"addToFavourite\":\"" . Util::action('addFavourite',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"removeFromFavourite\":\"" . Util::action('removeFavourite',array('messageId'=>$messages[$i]->getID())) . "\"";
            $json .= ",\"isUnread\":\"" . $messages[$i]->isShowUnread() . "\"";
                $json .= "}";
                $first = false;
            //}
        }
        $json .= "], \"favouriteMessages\":[";
        $first = true;
        for($i = 0; $i < count($messages); $i++){

            if($messages[$i]->getFavourite() == 1){
                if($first == false){
                    $json .= " , ";
                }
                $json .= "{\"message\":\"" . $messages[$i]->getContent() . "\"";
                $json .= ",\"autor\":\"" . UserDataManager::getNameForId($messages[$i]->getUser()) . "\"";
                $json .= ",\"written\":\"" . $messages[$i]->getCreation() . "\"";
                $json .= ",\"isEditable\":\"" . $messages[$i]->getEditable() . "\"";
                $json .= ",\"isRemoveAble\":\"" . $messages[$i]->getEditable() . "\"";
                $json .= ",\"isFavourite\":\"" . $messages[$i]->getFavourite() . "\"";
                $json .= ",\"editLink\":\"" . Util::action('editMessage',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"removeLink\":\"" . Util::action('deleteMessage',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"removeFromFavourite\":\"" . Util::action('removeFavourite',array('messageId'=>$messages[$i]->getID())) . "\"";
                //$json .= ",\"addToFavourite\":\"" . Util::action('addFavourite',array('messageId'=>$messages[$i]->getID())) . "\"";
                $json .= ",\"title\":\"" . $messages[$i]->getTitle() . "\"";
                $json .= "}";
                $first = false;
            }
        }
        $json .= "]}";
        echo $json;

    }
    public function getChannels(){
        $channels = UserDataManager::getChannelsForUser(SessionController::getUser());
        $retval = array();
        foreach($channels as $key => $val) {
            array_push($retval,array('name' => $val->getName(),'id' => $val->getID(), 'link' =>Util::action('switchChannel',array('ChannelId' => $val->getID()))));
        }
        return $retval;

    }
    public function getActiveChannel(){
        $channelId = SessionController::getActiveChannel();
        $channel = UserDataManager::getChannelById($channelId);
        return $channel->getName();
    }
    public function addMessage(){
        $message = $_REQUEST['message'];
        $title = $_REQUEST['title'];
        if(isset($_REQUEST['messageId']) && $_REQUEST['messageId'] != null){
            $messageId = $_REQUEST['messageId'];
            MessageDataManager::updateMessage($messageId,$title,$message);
            unset($_REQUEST['messageId']);
            return;
        }
        if(!isset($title) || $title == null ){
            SessionController::addErrorMessage('Bitte geben Sie mindestens Titel und Nachricht ein');
            return false;
        }
        MessageDataManager::insertMessage($title,$message, SessionController::getActiveChannel(), SessionController::getUser());
    }
    public function resetMessage(){
        if(isset($_REQUEST['messageId'])){
            unset($_REQUEST['messageId']);
        }
    }
    public function deleteMessage(){
        $messageId = $_REQUEST['messageId'];
        MessageDataManager::deleteMessage($messageId);
        unset($_REQUEST['messageId']);
    }

    public function removeFromFavourite(){
        $messageId = $_REQUEST['messageId'];
        MessageDataManager::removeFromFavourite($messageId, SessionController::getUser());
        unset($_REQUEST['messageId']);
    }
    public function addToFavourite(){
        $messageId = $_REQUEST['messageId'];
        MessageDataManager::addToFavourite($messageId,SessionController::getUser());
        unset($_REQUEST['messageId']);
    }


}