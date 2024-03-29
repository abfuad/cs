<?php

namespace App\Repository;

use App\Entity\Reason;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reason>
 *
 * @method Reason|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reason|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reason[]    findAll()
 * @method Reason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reason::class);
    }

    public function save(Reason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Reason[] Returns an array of Reason objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function getActive(): ?Reason
   {
    $today=new DateTime("today");
       return $this->createQueryBuilder('r')
           ->andWhere('r.endAt >= :val')
           ->setParameter('val', $today)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
