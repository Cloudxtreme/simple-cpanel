<?php

namespace FastVPS\CpanelBundle\Entity;


class Customer {

    private $userName;

    private $password;

    public function setUserName($userName) {

        $this->userName = $userName;

    }

    public function setPassword($password) {

        $this->password = $password;

    }

    public function getUserName() {

        return $this->userName;

    }

}