<?php

namespace FastVPS\CpanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FastVPS\CpanelBundle\Form\NewHostType;
use FastVPS\CpanelBundle\Entity\Host;

use FastVPS\CpanelBundle\Handler\VirtualHostHandler;

class HostController extends Controller {


    public function hostListAction() {

        $em = $this->getDoctrine()->getEntityManager();
        $hostList = $em->getRepository("FastVPSCpanelBundle:Host")->findAll();


        return $this->render('FastVPSCpanelBundle:Host:hostlist.html.twig', array('hosts' => $hostList));
    }


    public function newHostAction(Request $request) {

        $em = $this->getDoctrine()->getEntityManager();
        $vHostHandler = new VirtualHostHandler();

        $host = new Host();
        $form = $this->createForm(new NewHostType(), $host);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $host->setCreationDate();
            $host->setHostPoolDir($vHostHandler->getHostPoolDir());

            $em->persist($host);
            $em->flush();

            $vHostHandler->createHostFile();

        }

        return $this->render('FastVPSCpanelBundle:Host:newhost.html.twig',
            array('form' => $form->createView()));
    }

    public function editHostAction() {

    }

    public function removeHostAction() {

        $id = $this->getRequest()->get('id');

        $host = $this->getDoctrine()->getEntityManager()
            ->getRepository("FastVPSCpanelBundle:Host")->findOneById($id);

        die($host);
        return $this->redirect($this->generateUrl("fast_vps_cpanel_homepage"));

    }

}