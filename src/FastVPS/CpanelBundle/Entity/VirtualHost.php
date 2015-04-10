<?php

namespace FastVPS\CpanelBundle\Entity;

use FastVPS\CpanelBundle\Entity\Customer as Customer;

class VirtualHost {

    private $hostName;

    private $hostOwner;


    public function __construct() {
    }

    public function setHostOwner(Customer $owner) {
        $this->hostOwner = $owner;
    }

    public function setHostName($name) {
        $this->hostName = $name;
    }

    public function getHostOwner() {
        return $this->hostOwner;
    }

    public function getHostName() {
        return $this->hostName;
    }

}