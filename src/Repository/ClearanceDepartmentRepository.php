<?php

namespace App\Repository;

use App\Entity\ClearanceDepartment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClearanceDepartment>
 *
 * @method ClearanceDepartment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClearanceDepartment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClearanceDepartment[]    findAll()
 * @method ClearanceDepartment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClearanceDepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClearanceDepartment::class);
    }

    public function save(ClearanceDepartment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClearanceDepartment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ClearanceDepartment[] Returns an array of ClearanceDepartment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClearanceDepartment
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
