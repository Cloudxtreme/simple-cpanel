<?php


namespace FastVPS\CpanelBundle\Handler;


interface VirtualHostHandlerInterface {

    function createHost($hostName);

    function editHost($newHostName, $oldHostName);

    function removeHost($hostName);

    function getNginxConfDir();

    function getNginxHostsDir();

}