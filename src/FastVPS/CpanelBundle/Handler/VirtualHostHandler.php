<?php

namespace FastVPS\CpanelBundle\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

class VirtualHostHandler {

    private $hostPoolDir;

    public function __construct() {

        $this->hostPoolDir = getenv('HOME') . "/www";

    }

    public function getHostPoolDir() {
        return $this->hostPoolDir;
    }

    public function createHostFile() {

        self::reloadNginx();
    }

    public function editHostFile() {

        self::reloadNginx();
    }

    public function removeHostFile() {

        self::reloadNginx();
    }

    public static function reloadNginx() {}

}