<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 26.08.2015
 * Time: 19:58
 */

class MessageDataManager extends DataManager{


    public static function getMessagesForChannel($channelId){
        $messages = array();
        $con = self::getConnection();
        $maxId = 0;
        $maxMessageRes = self::query($con,"SELECT MAX(id) as m FROM message WHERE message.channel = ". intval($channelId));
         if($maxMessage = self::fetchObject($maxMessageRes)){
             $maxId = $maxMessage->m;
         }
        $res = self::query($con, "SELECT id,user,title,channel,content,creation FROM message WHERE message.channel=". intval($channelId). " AND (message.deleted <> '1' OR message.deleted IS NULL)");
        while($message = self::fetchObject($res)){
            $favouritRes = self::query($con, "SELECT message_id , favourite FROM message_user WHERE message_id=". intval($message->id) . " AND user_id=" .intval(SessionController::getUser()));
            $isFavouritMessage = false;
            $isShowUnread = true;
            if($fav = self::fetchObject($favouritRes)){
                $isShowUnread = false;
                $isFavouritMessage = $fav->favourite == '1';
            }
            if($message->id == $maxId && $message->user == SessionController::getUser()){
                $m = new Message($message->id, $message->user, $message->channel, $message->title,$message->content, true, $isFavouritMessage, $message->creation, $isShowUnread);
            }else {
                $m = new Message($message->id, $message->user, $message->channel, $message->title, $message->content, false, $isFavouritMessage, $message->creation, $isShowUnread);
            }
            array_push($messages, $m );
            if($isShowUnread){
                self::setMessagesRead($m);
            }
        }


       self::close($res);
        self::closeConnection($con);
        return $messages;
    }
    public static function getMessageForId($messageId){
        $con = self::getConnection();
        $res = self::query($con, "SELECT id,user,title,channel,content,creation  FROM message WHERE message.id=". intval($messageId));
        if($message = self::fetchObject($res)){
            $favouritRes = self::query($con, "SELECT message_id , favourite FROM message_user WHERE message_id=". intval($message->id) . " AND user_id=" .intval(SessionController::getUser()));
            $isFavouritMessage = false;
            $isShowUnread = true;
            if($fav = self::fetchObject($favouritRes)){
                $isShowUnread = false;
                $isFavouritMessage = $fav->favourite == '1';
            }
            $m = new Message($message->id, $message->user, $message->channel, $message->title, $message->content, false, $isFavouritMessage, $message->creation, $isShowUnread);
            if($isShowUnread){
                self::setMessagesRead($m);
            }
        }
        self::close($res);
        self::closeConnection($con);
        return $m;
    }

    public static function setMessagesRead($message){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "INSERT INTO message_user(message_id,user_id,favourite) VALUES ('".intval($message->getID())."','".intval(SessionController::getUser())."', '".intval($message->getFavourite())."')");
        self::query($con, 'COMMIT;');
        self::closeConnection($con);

    }

    public static function updateMessage($id,$title,$content){
        DataManager::writeLog('edit message ' . $id);
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        $content = $con->real_escape_string($content);
        $title = $con->real_escape_string($title);
        self::query($con, "UPDATE message set title='".$title ."', content='".$content."' WHERE id=".intval($id));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function insertMessage($title,$content, $channel, $user ){
        DataManager::writeLog('insert new message');
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        $user = intval($user);
        $channel = intval($channel);
        $content = $con->real_escape_string($content);
        $title = $con->real_escape_string($title);
        self::query($con, "INSERT INTO message (title,content, user, channel) VALUES ('".$title."','".$content."', '".$user."', '".$channel."')");
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function deleteMessage($messageId){
        DataManager::writeLog('delete message ' . $messageId);
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "UPDATE message set deleted='1' WHERE id=".intval($messageId));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function removeFromFavourite($messageId,$userId){
        DataManager::writeLog('remove message from favourite' . $messageId);
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "UPDATE message_user SET favourite='0' WHERE message_id=".intval($messageId). " AND user_id=".intval($userId));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function addToFavourite($messageId,$userId){
        DataManager::writeLog('add message to favourite' . $messageId);
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "UPDATE message_user SET favourite='1' WHERE message_id=".intval($messageId). " AND user_id=".intval($userId));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }

}