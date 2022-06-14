<?php

namespace App\Controller\api\v1;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/admin")
 */
class AdminApiController extends AbstractController
{
    /**
     * @Route("/employees", methods={"GET"})
     */
    function getEmployees(EntityManagerInterface $em){
        return $this->json($em->getRepository(Employee::class)->findBy(["active" => true]), 200, [], ["groups" => "employee_list"]);
    }

    /**
     * @Route("/employees", methods={"PUT"})
     */
    function createEmployee(Request $request, EntityManagerInterface $em){
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee->setName($data["name"]);
        $employee->setActive(true);

        $em->persist($employee);
        $em->flush();

        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }

    /**
     * @Route("/employees/{employee}", methods={"GET"})
     */
    function getEmployee(Employee $employee){
        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }

    /**
     * @Route("/employees/{employee}", methods={"POST"})
     */
    function updateEmployee(Employee $employee, Request $request, EntityManagerInterface $em){
        $data = json_decode($request->getContent(), true);
        $employee->setName($data["name"]);
        $employee->setMaxHoursPerMonth($data["maxHoursPerMonth"]);
        $em->persist($employee);
        $em->flush();

        return $this->json($employee, 200, [], ["groups" => "employee"]);
    }

    /**
     * @Route("/employees/{employee}", methods={"DELETE"})
     */
    function deleteEmployee(Employee $employee, EntityManagerInterface $em){
        if($employee->getShifts()->count() > 0){
            $employee->setActive(false);
            $em->flush();
            return $this->json(["success" => false, "error" => "shifts existing"]);
        }else{
            $em->remove($employee);
            $em->flush();
        }

        return $this->json(["success" => true]);
    }
}