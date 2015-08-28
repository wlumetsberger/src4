<?php
/**
 * Created by PhpStorm.
 * User: Wolfgang
 * Date: 26.08.2015
 * Time: 18:16
 */

class Channel extends Entity {

    private $name;
    private $description;

    public function __construct($id, $name, $description) {
        parent::__construct($id);
        $this->name = $name;
        $this->description = $description;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

}