<?php

namespace App\Controller\api\v1;

use App\Entity\Employee;
use App\Entity\Shift;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminApiController extends AbstractController
{
    /**
     * @Route("/stats")
     */
    function getStats(EntityManagerInterface $em){
        $shiftMonthTotal = (int) $em->getRepository(Shift::class)->createQueryBuilder('s')
            ->where('s.startTime > :currentMonth')
            ->setParameter('currentMonth', (new \DateTime('first day of ' . date('F') . " " . date('Y')))->format('Y-m-d'))
            ->select('SUM(s.totalSeconds)')
            ->getQuery()->getSingleScalarResult();

        return $this->json($shiftMonthTotal);
    }

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

        $employee = $em->getRepository(Employee::class)->findOneBy([
            "name" => $data["name"]
        ]);

        if($employee == null){
            $employee = new Employee();
            $employee->setName($data["name"]);
            $employee->setActive(true);
            $em->persist($employee);
            $em->flush();

            return $this->json($employee, 200, [], ["groups" => "employee"]);
        }

        return $this->json($employee, 400, [], ["groups" => "employee"]);


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
     * @Route("/employees/{employee}/shifts", methods={"PUT"})
     */
    function createShift(Employee $employee, Request $request, EntityManagerInterface $em){
        $data = json_decode($request->getContent(), true);
        $shift = new Shift();
        $shift->setStartLocation(null);
        $shift->setStopLocation(null);
        $shift->setEmployee($employee);
        $shift->setStartTime(new \DateTime($data["startTime"]));
        $shift->setEndTime(new \DateTime($data["endTime"]));
        $shift->setTotalSeconds($shift->getEndTime()->getTimestamp()-$shift->getStartTime()->getTimestamp());

        $em->persist($shift);
        $em->flush();

        return $this->json($employee->getShifts(), 200, [], ["groups" => "employee"]);
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