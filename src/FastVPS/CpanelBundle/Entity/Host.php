<?php

namespace FastVPS\CpanelBundle\Entity;


class Host {

    private $hostName;


    public function __construct() {
    }


    public function setHostName($name) {
        $this->hostName = $name;
    }

    public function getHostName() {
        return $this->hostName;
    }

}