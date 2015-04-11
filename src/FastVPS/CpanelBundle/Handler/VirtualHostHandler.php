<?php

namespace FastVPS\CpanelBundle\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use FastVPS\CpanelBundle\Entity\Host;

class VirtualHostHandler {

    private $nginxConfDir;
    private $nginxHostsDir;

    public function __construct($vconfDir, $hostsDir) {

        $this->nginxConfDir = $vconfDir;
        $this->nginxHostsDir = $hostsDir;

        $this->nonExistentField = "php is so fucked up";


    }

    public function createHostFile(Host $host) {

        self::reloadNginx();
    }

    public function editHostFile(Host $host) {

        self::reloadNginx();
    }

    public function removeHostFile(Host $host) {

        self::reloadNginx();
    }

    public static function reloadNginx() {}

    public function getNginxConfDir() {

        return $this->nginxConfDir;
    }

    public function getNginxHostsDir() {

        return $this->nginxHostsDir;
    }

}