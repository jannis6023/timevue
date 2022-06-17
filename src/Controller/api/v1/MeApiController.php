<?php

namespace App\Controller\api\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/me")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class MeApiController extends AbstractController
{

    /**
     * @Route("/")
     */
    function me(){
        return $this->json($this->getUser());
    }

}