<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 25.08.2015
 * Time: 19:26
 */
class UserDataManager extends DataManager{


    public static function getNameForId($id){
        $user = self::getUserForId($id);
        return $user->getName();
    }



    public static function getUserForUserName($username){
        $user = null;
        $con = self::getConnection();
        $res = self::query($con, "SELECT id, username, password, firstname, lastname FROM user WHERE username = '" . ($username) . "';");

        if ($u = self::fetchObject($res)) {
            $user = New User($u->id, $u->username, $u->password, $u->firstname, $u->lastname);
        }

        self::close($res);
        self::closeConnection($con);
        return $user;
    }

    public static function getUserForId($userId) {
        $user = null;
        $con = self::getConnection();
        $res = self::query($con, "SELECT id, username, password, firstname, lastname FROM user WHERE id = " . intval($userId) . ";");

        if ($u = self::fetchObject($res)) {
            $user = New User($u->id, $u->username, $u->password, $u->firstname, $u->lastname);
        }
        self::close($res);
        self::closeConnection($con);
        return $user;

    }

    public static function getDefaultChannelForUser($userId){
        $channel = null;
        $con = self::getConnection();
        $res = self::query($con, "SELECT channel_id from channel_user WHERE user_id = " . intval($userId) .  " AND default_channel = " .intval(1) .";");
        if ($u = self::fetchObject($res)) {
            $channel =$u->channel_id;
        }
        self::close($res);
        self::closeConnection($con);
        return $channel;

    }

    public static function getChannelById($channelId){
        $channel = null;
        $con = self::getConnection();
        $res = self::query($con, "SELECT id, name , description FROM channel WHERE id = " . intval($channelId) . ";");

        if ($u = self::fetchObject($res)) {
            $channel = New Channel($u->id, $u->name, $u->description);
        }
        self::close($res);
        self::closeConnection($con);
        return $channel;
    }

    public static function getChannelsForUser($userId){
        $channels = array();
        $con = self::getConnection();
        $res = self::query($con, "SELECT id,name,description from channel, channel_user WHERE channel.id = channel_user.channel_id AND channel_user.user_id=". intval($userId));
        while($channel = self::fetchObject($res)){
            $channels[] = new Channel($channel->id, $channel->name, $channel->description);
        }
        self::close($res);
        self::closeConnection($con);
        return $channels;
    }
    public static function getChannelsAvailable(){
        $channels = array();
        $con = self::getConnection();
        $res = self::query($con, "SELECT id,name,description from channel");
        while($channel = self::fetchObject($res)){
            $channels[] = new Channel($channel->id, $channel->name, $channel->description);
        }
        self::close($res);
        self::closeConnection($con);
        return $channels;
    }
    public static function persistUser($user){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        $username = $con->real_escape_string($user->getUserName());
        $firstname = $con->real_escape_string($user->getFirstName());
        $lastname = $con->real_escape_string($user->getLastName());
        $password = $con->real_escape_string($user->getPasswordHash());
        self::query($con, "INSERT INTO user (username,password, firstname, lastname) VALUES ('".$username."','".$password."', '".$firstname."', '".$lastname."')");
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
        return self::getUserForUserName($username);
    }
    public static function insertChannelForUser($userId, $channelId){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        if(self::getDefaultChannelForUser($userId) == null){
            self::query($con, "INSERT INTO channel_user (user_id,channel_id, default_channel) VALUES ('".intval($userId)."','".intval($channelId)."', '1')");
        }else{
            self::query($con, "INSERT INTO channel_user (user_id,channel_id, default_channel) VALUES ('".intval($userId)."','".intval($channelId)."', '0')");
        }
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
}