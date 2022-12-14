<?php

namespace App\Repository;

use App\Entity\PeopleRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PeopleRecord>
 *
 * @method PeopleRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeopleRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeopleRecord[]    findAll()
 * @method PeopleRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeopleRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeopleRecord::class);
    }

    public function save(PeopleRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PeopleRecord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findHopitaux(): array
    {
        return $this->createQueryBuilder('p')
                    ->select('attr5')
                    ->andWhere('p.attr5 IS NOT NULL')
                    ->getQuery()
                    ->getResult()
                    ;
    }

   /**
    * @return PeopleRecord[] Returns an array of PeopleRecord objects
    */
   public function findByFirstnameAndLastname($firstname, $lastname): array
   {
        $qb = $this->createQueryBuilder('p','n');
        $qb->select('p.sn', 'p.displayGn', 'p.mainLineNumber', 'p.didNumbers', 'p.mail', 'p.hierarchySv', 'p.attr1', 'p.attr5', 'p.attr6', 'p.attr7', 'n.private')
            ->from('peopleRecord', 'p')
            ->innerJoin('numberRecord', 'n');

        return $qb->getResult();

    //    return $this->createQueryBuilder('e')
    //        ->select('hierarchysv', )
    //        ->andWhere('e.exampleField = :val')
    //        ->setParameter('val', $value)
    //        ->orderBy('e.id', 'ASC')
    //        ->setMaxResults(10)
    //        ->getQuery()
    //        ->getResult()
    //    ;
   }

//    public function findOneBySomeField($value): ?PeopleRecord
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
