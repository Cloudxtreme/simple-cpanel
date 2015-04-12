<?php

namespace FastVPS\CpanelBundle\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class VirtualHostHandler implements VirtualHostHandlerInterface {

    const IO_ERROR = "IO_ERROR";
    const NGINX_RESTART_ERROR = "NGINX_RESTART_ERROR";
    const SUCCESS= "SUCCESS";

    private static $CONF_EXTENSION = ".conf";
    private static $INDEX_FILENAME = "index.html";

    private $nginxConfDir;
    private $nginxHostsDir;

    private $indexTemplatePath;
    private $confTemplatePath;

    private $fs;
    private $reloadNginxCommand;


    public function __construct($confDir, $hostsDir, $reloadNginxCommand, FileLocator $fileLocator) {

        $this->nginxConfDir = $confDir;
        $this->nginxHostsDir = $hostsDir;
        $this->reloadNginxCommand = $reloadNginxCommand;

        $this->fs = new Filesystem();

        $this->indexTemplatePath = $fileLocator
            ->locate('@FastVPSCpanelBundle/Resources/template/index.html.dist');

        $this->confTemplatePath = $fileLocator
            ->locate('@FastVPSCpanelBundle/Resources/template/vhost.conf.dist');

    }

    public function createHost($hostName) {

        try {
            $this->fs->mkdir($this->getNginxHostsDir() . $hostName, 0777);
            $this->createDefaultIndex($hostName);
            $this->createConfiguration($hostName);

        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        $this->reloadNginx();

        return self::SUCCESS;
    }

    public function editHost($newHostName, $oldHostName) {

        try {
            $this->fs->rename($this->getNginxHostsDir() . $oldHostName,
                $this->getNginxHostsDir() . $newHostName, false);

            $this->renameConfiguration($oldHostName, $newHostName);
        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        $this->reloadNginx();

        return self::SUCCESS;
    }

    public function removeHost($hostName) {

        try {
            $this->fs->remove($this->getNginxHostsDir() . $hostName);
            $this->removeConfiguration($hostName);

        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        $this->reloadNginx();

        return self::SUCCESS;
    }

    public function getNginxConfDir() {

        return $this->nginxConfDir;
    }

    public function getNginxHostsDir() {

        return $this->nginxHostsDir;
    }

    /**
     * reloads nginx process via shell
     * (don't forget to insert a correct command into service parameters!)
     */
    private function reloadNginx() {

        $process = new Process($this->reloadNginxCommand);

        try {
            $process->run();

        } catch (ProcessFailedException $e) {

            return self::NGINX_RESTART_ERROR;

        }

        return self::SUCCESS;

    }

    /**
     * generates custom index.html from a template and places it
     * into the host directory
     */
    private function createDefaultIndex($hostName) {

        try {
            $this->fs->copy($this->indexTemplatePath,
                $this->getNginxHostsDir() . $hostName . "/" . self::$INDEX_FILENAME);

        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        return self::SUCCESS;

    }


    /**
     * generates host nginx configuration from a template and places it
     * into the nginx sites-enabled directory
     */
    private function createConfiguration($hostName) {

        $confPath = $this->getNginxConfDir() . $hostName . self::$CONF_EXTENSION;

        $absHostPath = $this->getNginxHostsDir() . $hostName;

        try {

            $file = file_get_contents($this->confTemplatePath);
            $file = str_replace("%relpath%", $hostName, $file);
            file_put_contents($confPath, $file);


        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        return self::SUCCESS;

    }

    private function renameConfiguration($oldHostName, $newHostName) {

        $oldConfPath = $this->getNginxConfDir() . $oldHostName . self::$CONF_EXTENSION;
        $newConfPath = $this->getNginxConfDir() . $newHostName . self::$CONF_EXTENSION;

        try {

            $this->fs->rename($oldConfPath, $newConfPath);

            $file = file_get_contents($newConfPath);
            $file = str_replace($oldHostName, $newHostName, $file);
            file_put_contents($newConfPath, $file);

        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        return self::SUCCESS;

    }

    private function removeConfiguration($hostName) {

        try {
            $this->fs->remove($this->getNginxConfDir() . $hostName . self::$CONF_EXTENSION);

        } catch (IOException $e) {
            return self::IO_ERROR;
        }

        return self::SUCCESS;

    }


}