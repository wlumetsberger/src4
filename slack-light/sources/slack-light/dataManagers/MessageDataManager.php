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
        $res = self::query($con, "SELECT id,user,title,channel,content,creation, favourite FROM message WHERE message.channel=". intval($channelId));
        while($message = self::fetchObject($res)){
            $favouritRes = self::query($con, "SELECT message_id FROM message_favourite WHERE message_id=". intval($message->id));
            $isFavouritMessage = false;
            if($fav = self::fetchObject($favouritRes)){
                $isFavouritMessage = true;
            }
            if($message->id == $maxId && $message->user == SessionController::getUser()){
                $m = new Message($message->id, $message->user, $message->channel, $message->title,$message->content, true, $isFavouritMessage, $message->creation);
            }else {
                $m = new Message($message->id, $message->user, $message->channel, $message->title, $message->content, false, $isFavouritMessage, $message->creation);
            }
            array_push($messages, $m );
        }


       self::close($res);
        self::closeConnection($con);
        return $messages;
    }
    public static function getMessageForId($messageId){
        $con = self::getConnection();
        $res = self::query($con, "SELECT id,user,title,channel,content,creation, favourite FROM message WHERE message.id=". intval($messageId));
        if($message = self::fetchObject($res)){
            $favouritRes = self::query($con, "SELECT message_id FROM message_favourite WHERE message_id=". intval($message->id));
            $isFavouritMessage = false;
            if($fav = self::fetchObject($favouritRes)){
                $isFavouritMessage = true;
            }
            $m = new Message($message->id, $message->user, $message->channel, $message->title, $message->content, false, $isFavouritMessage, $message->creation);
        }
        self::close($res);
        self::closeConnection($con);
        return $m;
    }

    public static function updateMessage($id,$title,$content){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        $content = $con->real_escape_string($content);
        $title = $con->real_escape_string($title);
        self::query($con, "UPDATE message set title='".$title ."', content='".$content."' WHERE id=".intval($id));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function insertMessage($title,$content, $channel, $user ){
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
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "DELETE FROM message WHERE message.id =" . intval($messageId));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function removeFromFavourite($messageId,$userId){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "DELETE FROM message_favourite WHERE message_id=".intval($messageId). " AND user_id=".intval($userId));
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
    public static function addToFavourite($messageId,$userId){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        self::query($con, "INSERT INTO message_favourite (message_id, user_id) Values('".intval($messageId)."','".intval($userId)."')");
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }

}