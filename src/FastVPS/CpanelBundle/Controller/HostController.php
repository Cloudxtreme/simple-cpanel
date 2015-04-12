<?php

namespace FastVPS\CpanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use FastVPS\CpanelBundle\Form\NewHostType;
use FastVPS\CpanelBundle\Form\EditHostType;
use FastVPS\CpanelBundle\Entity\Host;

use FastVPS\CpanelBundle\Handler\VirtualHostHandler;

class HostController extends Controller {


    public function hostListAction() {

        $em = $this->getDoctrine()->getManager();
        $hostList = $em->getRepository("FastVPSCpanelBundle:Host")->findAll();

        $hostsDir = $this->get('virtual_host_handler')->getNginxHostsDir();


        return $this->render('FastVPSCpanelBundle:Host:hostlist.html.twig',
            array('hosts' => $hostList, 'nginx_hosts_dir' => $hostsDir));
    }


    public function newHostAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $host = new Host();
        $form = $this->createForm(new NewHostType(), $host);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $host->setCreationDate();

            if($this->get('virtual_host_handler')
                    ->createHostFile($host->getHostName()) == VirtualHostHandler::$SUCCESS) {
                $em->persist($host);
                $em->flush();
            };

            return $this->redirect($this->generateUrl("fast_vps_cpanel_homepage"));

        }

        return $this->render('FastVPSCpanelBundle:Host:newhost.html.twig',
            array('form' => $form->createView()));
    }

    public function editHostAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->get('id');
        $host = $em->getRepository("FastVPSCpanelBundle:Host")->findOneById($id);

        $oldHostName = $host->getHostName();

        $form = $this->createForm(new EditHostType(), $host);

        $form->handleRequest($request);

        if ($form->isValid()) {

            if($this->get('virtual_host_handler')
                    ->editHostFile($host->getHostName(), $oldHostName) == VirtualHostHandler::$SUCCESS) {
                $em->persist($host);
                $em->flush();
            }

            return $this->redirect($this->generateUrl("fast_vps_cpanel_homepage"));

        }

        return $this->render('FastVPSCpanelBundle:Host:edithost.html.twig',
            array('form' => $form->createView()));

    }

    public function removeHostAction() {

        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->get('id');
        $host = $em->getRepository("FastVPSCpanelBundle:Host")->findOneById($id);

        if($this->get('virtual_host_handler')
                ->removeHostFile($host->getHostName()) == VirtualHostHandler::$SUCCESS) {
            $em->remove($host);
            $em->flush();
        };


        return $this->redirect($this->generateUrl("fast_vps_cpanel_homepage"));

    }

}