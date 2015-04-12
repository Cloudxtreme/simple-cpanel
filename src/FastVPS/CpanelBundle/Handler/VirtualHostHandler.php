<?php

namespace FastVPS\CpanelBundle\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class VirtualHostHandler {

    public static $IO_ERROR = "IO_ERROR";
    public static $NGINX_RESTART_ERROR = "NGINX_RESTART_ERROR";
    public static $SUCCESS= "SUCCESS";

    private $nginxConfDir;
    private $nginxHostsDir;

    private $fs;

    private $indexTemplateFilePath;
    private $confTemplateFilePath;


    public function __construct($confDir, $hostsDir, FileLocator $fileLocator) {

        $this->nginxConfDir = $confDir;
        $this->nginxHostsDir = $hostsDir;

        $this->fs = new Filesystem();

        $this->indexTemplateFilePath = $fileLocator
            ->locate('@FastVPSCpanelBundle/Resources/template/index.html.dist');

        $this->confTemplateFilePath = $fileLocator
            ->locate('@FastVPSCpanelBundle/Resources/template/vhost.conf.dist');

    }

    public function createHostFile($hostName) {

        try {
            $this->fs->mkdir($this->getNginxHostsDir() . $hostName, 0777);
            $this->generateIndexFile($hostName);
        } catch (IOException $e) {
            return self::$IO_ERROR;
        }

        $this->reloadNginx();

        return self::$SUCCESS;
    }

    public function editHostFile($newHostName, $oldHostName) {

        try {
            $this->fs->rename($this->getNginxHostsDir() . $oldHostName,
                $this->getNginxHostsDir() . $newHostName, false);
        } catch (IOException $e) {
            return self::$IO_ERROR;
        }

        $this->reloadNginx();

        return self::$SUCCESS;
    }

    public function removeHostFile($hostName) {

        try {
            $this->fs->remove($this->getNginxHostsDir() . $hostName);

        } catch (IOException $e) {
            return self::$IO_ERROR;
        }

        $this->reloadNginx();

        return self::$SUCCESS;
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

        $process = new Process('service nginx reload');

        try {
            $process->run();

        } catch (ProcessFailedException $e) {

            return self::$NGINX_RESTART_ERROR;

        }

        return self::$SUCCESS;

    }


    /**
     * generates custom index.html from a template and places it
     * into the host directory
     */
    private function generateIndexFile($hostName) {

        try {
            $this->fs->copy($this->indexTemplateFilePath,
                $this->getNginxHostsDir() . $hostName . "/index.html");
        } catch (IOException $e) {
            return self::$IO_ERROR;
        }

        return self::$SUCCESS;

    }


    /**
     * generates host nginx configuration from a template and places it
     * into the nginx sites-enabled directory
     */
    private function generateConfigurationFile($hostName) {

        try {
            $this->fs->copy($this->indexTemplateFilePath, $this->getNginxHostsDir() . $hostName);
        } catch (IOException $e) {
            return self::$IO_ERROR;
        }

        return self::$SUCCESS;

    }

}