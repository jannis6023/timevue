<?php

namespace App\Controller\api\v1;

use App\Entity\SPWeekTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/shiftplan")
 */
class SPApiController extends AbstractController
{
    /**
     * @Route("/templates/current")
     */
    function getCurrentSPWeekTemplate(EntityManagerInterface $em){
        $currentPlan = $em->getRepository(SPWeekTemplate::class)->createQueryBuilder("t")
            ->andWhere("t.validFrom <= :currentWeekMonday")
            ->set('currentWeekMonday', (new \DateTime('monday of this week'))->format('Y-m-d'))
            ->orderBy('t.validFrom', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $this->json($currentPlan);
    }

    /**
     * @Route("/templates/byDate/{dateString}")
     */
    function getSPWeekTemplateByDate($dateString, EntityManagerInterface $em){
        // YY-MM-DD
        $date = new \DateTime($dateString);
        if($date->format('w') == 0){
            $date->modify('last day');
        }
        $date->modify('monday this week');

        $currentPlan = $em->getRepository(SPWeekTemplate::class)->createQueryBuilder("t")
            ->andWhere("t.validFrom <= :currentWeekMonday")
            ->setParameter('currentWeekMonday', $date->format('Y-m-d'))
            ->orderBy('t.validFrom', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        return $this->json($currentPlan);
    }
}