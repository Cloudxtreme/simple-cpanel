<?php

namespace FastVPS\CpanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{

    public function indexAction()
    {


        return $this->render('FastVPSCpanelBundle:Base:hostlist.html.twig');
    }


}