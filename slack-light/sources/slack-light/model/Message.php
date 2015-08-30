<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 26.08.2015
 * Time: 18:18
 */

class Message extends Entity implements JsonSerializable{

    private $user;
    private $channel;
    private $content;
    private $favourite;
    private $editable;
    private $creation;
    private $title;
    private $showUnread;

    public function __construct($id, $user, $channel,$titel ,$content, $editable, $favourite, $creation, $showUnread){
        parent::__construct($id);
        $this->user = $user;
        $this->channel = $channel;
        $this->content = $content;
        $this->favourite = $favourite;
        $this->editable = $editable;
        $this->creation = $creation;
        $this->title = $titel;
        $this->showUnread = $showUnread;
    }

    public function isShowUnread(){
        return $this->showUnread;
    }

    public function getTitle(){
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getFavourite()
    {
        return $this->favourite;
    }

    /**
     * @return mixed
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * @return mixed
     */
    public function getCreation()
    {
        return $this->creation;
    }
    public function jsonSerialize() {
        return [
            'message' => $this->content,
            'autor' => UserDataManager::getNameForId($this->user),
            'written' => $this->creation,
            'isEditable' => false,
            'isRemoveAble' => false
        ];
    }


}