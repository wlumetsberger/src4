<?php

class User extends Entity {

    private $userName;
    private $password;
    private $firstname;
    private $lastname;

    public function __construct($id, $userName, $passwordHash, $firstname, $lastname) {
        parent::__construct($id);
        $this->userName = $userName;
        $this->password = $passwordHash;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPasswordHash() {
        return $this->password;
    }

    public function getFirstName(){
        return $this->firstname;
    }

    public function getLastName(){
        return $this->lastname;
    }

    public function getName(){
        return $this->firstname . " " . $this->lastname;
    }



}