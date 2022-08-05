<?php

namespace App\Controller\api\v1;

use App\Entity\Employee;
use App\Entity\Shift;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/employee")
 */
class EmployeeApiController extends AbstractController
{
    /**
     * @Route("/{employee}")
     */
    function getEmployee(Employee $employee){
        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }

    /**
     * @Route("/{employee}/shifts", methods={"PUT"})
     */
    function createShift(Employee $employee, EntityManagerInterface $em, Request $request){
        $data = json_decode($request->getContent(), true);
        if(!$employee->isActive()){
            return $this->json(["error" => "Inactive."], 400);
        }
        $newShift = new Shift();
        $newShift->setEmployee($employee);
        $newShift->setStartTime(new \DateTime());
        if(array_key_exists("startLocation", $data)){
            $newShift->setStartLocation($data["startLocation"]);
        }

        /*
        if($data["location"] != $this->getParameter('geofence')){
            return $this->json(["error" => "Sie müssen sich am Geofence-Standort befinden!"], 400);
        }
         */

        $em->persist($newShift);
        $em->flush();

        $employee->setCurrentShift($newShift);

        $em->persist($employee);
        $em->flush();

        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }

    /**
     * @Route("/{employee}/shifts/{shift}", methods={"DELETE"})
     */
    function stopShift(Employee $employee, Shift $shift, EntityManagerInterface $em, Request $request){
        $data = json_decode($request->getContent(), true);

        if(!$employee->isActive()){
            return $this->json(["error" => "Inactive."], 400);
        }
        $shift->setEndTime(new \DateTime());
        $shift->setTotalSeconds($shift->getEndTime()->getTimestamp()-$shift->getStartTime()->getTimestamp());
        if(array_key_exists("stopLocation", $data)){
            $shift->setStopLocation($data["stopLocation"]);
        }
        $employee->setCurrentShift(null);
        $em->persist($employee);

        $em->flush();
        $em->persist($shift);

        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }
}