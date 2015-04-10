<?php

namespace FastVPS\CpanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HostController extends Controller {


    public function hostListAction() {


        return $this->render('FastVPSCpanelBundle:Host:hostlist.html.twig');
    }


    public function newHostAction() {

    }

    public function editHostAction() {

    }

    public function removeHostAction() {

    }

}