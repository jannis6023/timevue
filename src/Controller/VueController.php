<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VueController extends AbstractController
{
    /**
     * @Route("/")
     */
    function home(){
        return $this->redirectToRoute('vue_admin');
    }

    /**
     * @Route("/admin", name="vue_admin")
     * @Route("/admin/{route}", name="vue_admin_pages", requirements={"route"="^.+"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    function adminFrontend(){
        return $this->render('vue.html.twig');
    }

    /**
     * @Route("/employee", name="vue_employee")
     * @Route("/employee/{route}", name="vue_employee_pages", requirements={"route"="^.+"})
     */
    function employeeFrontend(){
        return $this->render('vue.html.twig');
    }

    /**
     * @Route("/var/requiredLocation")
     */
    function getLocation(){
        $config = $this->getParameter('geofence');
        $parts = explode(',', $config);
        return $this->json(["lat" => $parts[0], "lon" => $parts[1], "rad" => $parts[2]]);
    }
}