<?php

namespace App\Repository;

use App\Entity\SPWeekTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SPWeekTemplate>
 *
 * @method SPWeekTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SPWeekTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SPWeekTemplate[]    findAll()
 * @method SPWeekTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SPWeekTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SPWeekTemplate::class);
    }

    public function add(SPWeekTemplate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SPWeekTemplate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SPWeekTemplate[] Returns an array of SPWeekTemplate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SPWeekTemplate
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
